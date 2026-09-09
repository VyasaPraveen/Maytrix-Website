<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Request;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Validator;
use App\Core\Mailer;
use App\Models\Curriculum;
use App\Models\Subject;
use App\Models\Mode;
use App\Models\Booking;
use App\Models\Student;
use App\Models\CurriculumPage;

/**
 * 1-to-1 enquiry / consultation booking (request-based flow, per MOU Clause 1).
 */
final class BookingController extends SiteController
{
    public function show(Request $request): void
    {
        $this->render('site/book', [
            'activeNav'  => '',
            'curricula'  => (new Curriculum())->active(),
            'subjects'   => (new Subject())->active(),
            'modes'      => (new Mode())->active(),
            'pages'      => (new CurriculumPage())->published(),
            'prefill'    => $request->only(['curriculum_id', 'subject_id', 'class_type']),
            'metaTitle'  => 'Book a Consultation | Maytrix Education',
            'metaDescription' => 'Book a free consultation — choose your subject and level, then leave your details.',
        ]);
    }

    public function submit(Request $request): void
    {
        Csrf::check($request->post('_token'));
        $input = $request->only([
            'name', 'email', 'phone', 'country', 'timezone',
            'curriculum_id', 'subject_id', 'level', 'class_type', 'mode_id', 'preferred_contact', 'message',
        ]);

        $v = new Validator($input, [
            'name'          => 'required|max:160',
            'email'         => 'required|email|max:190',
            'curriculum_id' => 'required|int',
            'subject_id'    => 'required|int',
        ], [
            'curriculum_id' => 'Level',   // curriculum is chosen via the level step
            'subject_id'    => 'Subject',
        ]);
        if ($v->fails()) {
            $this->redirectWithErrors(base_url('book'), $v->errors(), $input);
            return;
        }

        // Class type is decided during the free consultation; default to 1-to-1.
        $classType = in_array($input['class_type'] ?? '', ['one_to_one', 'small_group'], true)
            ? $input['class_type'] : 'one_to_one';
        // The chosen level has no dedicated column — capture it in the message.
        $level = trim((string) ($input['level'] ?? ''));
        $note  = trim((string) ($input['message'] ?? ''));
        $message = ($level !== '' ? 'Requested level: ' . $level . ($note !== '' ? "\n\n" : '') : '') . $note;
        $message = $message !== '' ? $message : null;

        // Student + booking created together in one transaction (no orphans).
        $bookingModel = new Booking();
        $db = $bookingModel->db();
        $db->beginTransaction();
        try {
            $studentId = (new Student())->findOrCreate([
                'name'     => $input['name'],
                'email'    => $input['email'],
                'phone'    => $input['phone'] ?? null,
                'country'  => $input['country'] ?? null,
                'timezone' => $input['timezone'] ?? null,
                'created_at' => now(),
            ]);

            $bookingId = $bookingModel->create([
                'student_id'        => $studentId,
                'name'              => $input['name'],
                'email'             => $input['email'],
                'phone'             => $input['phone'] ?? null,
                'country'           => $input['country'] ?? null,
                'timezone'          => $input['timezone'] ?? null,
                'curriculum_id'     => (int) $input['curriculum_id'],
                'subject_id'        => (int) $input['subject_id'],
                'class_type'        => $classType,
                'mode_id'           => !empty($input['mode_id']) ? (int) $input['mode_id'] : null,
                'preferred_contact' => $input['preferred_contact'] ?? 'Email',
                'message'           => $message,
                'status'            => 'new',
                'created_at'        => now(),
            ]);
            $db->commit();
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            log_message('Booking store failed: ' . $e->getMessage());
            Flash::error('Sorry, we could not submit your request. Please try again.');
            $this->redirect(base_url('book'));
            return;
        }

        // Confirmation email to the student + notify admin.
        Mailer::send($input['email'], 'We\'ve received your consultation request — Maytrix Education',
            '<p>Hi ' . e($input['name']) . ',</p>'
            . '<p>Thanks for your request. Our team will confirm your consultation slot by email within one business day.</p>'
            . '<p>— Maytrix Education</p>');
        Mailer::notifyAdmin('New consultation request #' . $bookingId,
            '<p>New booking from <strong>' . e($input['name']) . '</strong> (' . e($input['email']) . ')</p>');

        Flash::success('Request received! Our team will confirm your consultation slot by email within one business day.');
        $this->redirect(base_url('book?submitted=1'));
    }
}
