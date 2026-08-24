<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Request;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Validator;
use App\Core\Mailer;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Enrolment;

final class EnrolmentController extends SiteController
{
    public function store(Request $request): void
    {
        Csrf::check($request->post('_token'));
        $batchId = (int) $request->post('batch_id');
        $batch = (new Batch())->findWithDetails($batchId);

        if (!$batch || (int) ($batch['is_published'] ?? 0) !== 1 || ($batch['status'] ?? '') !== 'open') {
            Flash::error('Sorry, this class is not open for enrolment.');
            $this->redirect(base_url('small-group'));
            return;
        }

        // Seat check — available = max_seats − confirmed enrolments (MOU Clause 2).
        if ((int) $batch['available_seats'] <= 0) {
            Flash::error('Sorry, this class is now full. Please choose another batch or book a consultation.');
            $this->redirect(base_url('batch/' . $batchId));
            return;
        }

        $input = $request->only(['name', 'email', 'phone', 'country', 'timezone']);
        $v = new Validator($input, [
            'name'  => 'required|max:160',
            'email' => 'required|email|max:190',
        ]);
        if ($v->fails()) {
            $this->redirectWithErrors(base_url('batch/' . $batchId), $v->errors(), $input);
            return;
        }

        $studentId = (new Student())->findOrCreate([
            'name'       => $input['name'],
            'email'      => $input['email'],
            'phone'      => $input['phone'] ?? null,
            'country'    => $input['country'] ?? null,
            'timezone'   => $input['timezone'] ?? null,
            'created_at' => now(),
        ]);

        // Prevent duplicate active enrolment by the same student in this batch.
        $existing = (new Enrolment())->where(['batch_id' => $batchId, 'student_id' => $studentId]);
        $active = array_filter($existing, fn($e) => in_array($e['status'], ['pending', 'confirmed'], true));
        if ($active) {
            Flash::error('You already have an enrolment request for this class.');
            $this->redirect(base_url('batch/' . $batchId));
            return;
        }

        $enrolId = (new Enrolment())->create([
            'batch_id'       => $batchId,
            'student_id'     => $studentId,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
            'amount'         => $batch['price'] ?? null,
            'currency'       => $batch['currency'] ?? 'INR',
            'enrolled_at'    => now(),
            'created_at'     => now(),
        ]);

        Mailer::send($input['email'], 'Enrolment request received — ' . ($batch['name'] ?? 'Class'),
            '<p>Hi ' . e($input['name']) . ',</p>'
            . '<p>We\'ve received your enrolment request for <strong>' . e($batch['name']) . '</strong>.</p>'
            . '<p>Our team will confirm your seat and share the class' . ($batch['mode_code'] === 'online' ? ' Zoom link' : ' details') . ' once payment is completed.</p>'
            . '<p>— Maytrix Education</p>');
        Mailer::notifyAdmin('New enrolment request #' . $enrolId,
            '<p><strong>' . e($input['name']) . '</strong> requested a seat in ' . e($batch['name']) . '.</p>');

        $this->redirect(base_url('enrol/success?e=' . $enrolId));
    }

    public function success(Request $request): void
    {
        $enrolId = (int) $request->query('e');
        $enrol = (new Enrolment())->find($enrolId);
        $batch = $enrol ? (new Batch())->findWithDetails((int) $enrol['batch_id']) : null;
        $this->render('site/enrol-success', [
            'activeNav' => 'classes',
            'enrol'     => $enrol,
            'batch'     => $batch,
            'metaTitle' => 'Enrolment received | Maytrix Education',
        ]);
    }
}
