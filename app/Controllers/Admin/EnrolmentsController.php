<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Mailer;
use App\Models\Enrolment;
use App\Models\Batch;
use App\Models\Student;
use App\Models\AuditLog;

final class EnrolmentsController extends AdminController
{
    public function index(Request $request): void
    {
        $status = $request->query('status');
        $result = (new Enrolment())->paginateWithDetails(
            \App\Core\Paginator::currentPage(),
            20,
            $status
        );
        $this->renderAdmin('admin/enrolments/index', [
            'pageTitle'  => 'Enrolments',
            'activeMenu' => 'enrolments',
            'rows'       => $result['rows'],
            'pager'      => $result['pager'],
            'filter'     => $status,
        ]);
    }

    /** Confirm an enrolment: consume a seat (if available) + share the class link. */
    public function confirm(Request $request, string $id): void
    {
        Csrf::check($request->post('_token'));
        $enrolModel = new Enrolment();
        $enrol = $enrolModel->find((int) $id);
        if (!$enrol) {
            Flash::error('Enrolment not found.');
            $this->redirect(admin_url('enrolments'));
            return;
        }

        // Atomic seat guard: re-check confirmed count inside a transaction so
        // two simultaneous confirmations can't overbook the batch.
        $db = $enrolModel->db();
        $db->beginTransaction();
        try {
            $batch = (new Batch())->findWithDetails((int) $enrol['batch_id']);
            if ($enrol['status'] !== 'confirmed' && (int) ($batch['available_seats'] ?? 0) <= 0) {
                $db->rollBack();
                Flash::error('Cannot confirm — this batch is already full. Increase seats or cancel another enrolment first.');
                $this->back();
                return;
            }
            $enrolModel->update((int) $id, ['status' => 'confirmed', 'confirmed_at' => now()]);
            $db->commit();
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            log_message('Enrolment confirm failed: ' . $e->getMessage());
            Flash::error('Could not confirm the enrolment. Please try again.');
            $this->back();
            return;
        }
        AuditLog::record('confirm', 'enrolments', (int) $id);

        // Share the Zoom link / class details with the student.
        $student = (new Student())->find((int) $enrol['student_id']);
        if ($student) {
            $this->sendConfirmation($student, $batch);
        }

        Flash::success('Enrolment confirmed and the class link has been shared with the student.');
        $this->back();
    }

    public function cancel(Request $request, string $id): void
    {
        Csrf::check($request->post('_token'));
        (new Enrolment())->update((int) $id, ['status' => 'cancelled']);
        AuditLog::record('cancel', 'enrolments', (int) $id);
        Flash::success('Enrolment cancelled — the seat is now free.');
        $this->back();
    }

    public function markPaid(Request $request, string $id): void
    {
        Csrf::check($request->post('_token'));
        (new Enrolment())->update((int) $id, ['payment_status' => 'paid']);
        AuditLog::record('mark_paid', 'enrolments', (int) $id);
        Flash::success('Marked as paid.');
        $this->back();
    }

    public function destroy(Request $request, string $id): void
    {
        Csrf::check($request->post('_token'));
        (new Enrolment())->delete((int) $id);
        AuditLog::record('delete', 'enrolments', (int) $id);
        Flash::success('Enrolment removed.');
        $this->redirect(admin_url('enrolments'));
    }

    private function sendConfirmation(array $student, array $batch): void
    {
        $isOnline = ($batch['mode_code'] ?? 'online') === 'online';
        $link = $batch['zoom_link'] ?? '';
        $body = '<p>Hi ' . e($student['name']) . ',</p>'
            . '<p>Your seat in <strong>' . e($batch['name']) . '</strong> is confirmed.</p>'
            . '<p><strong>Schedule:</strong> ' . e($batch['schedule_text'] ?? 'TBC') . '<br>'
            . '<strong>Starts:</strong> ' . e($batch['start_date'] ?? 'TBC') . '</p>';
        if ($isOnline && $link) {
            $body .= '<p><strong>Join on Zoom:</strong><br><a href="' . e($link) . '">' . e($link) . '</a></p>';
        } elseif (!$isOnline && !empty($batch['mode_detail'])) {
            $body .= '<p><strong>Venue:</strong> ' . e($batch['mode_detail']) . '</p>';
        } else {
            $body .= '<p>Your class link / venue details will follow shortly.</p>';
        }
        $body .= '<p>See you in class!<br>— Maytrix Education</p>';
        Mailer::send($student['email'], 'Your seat is confirmed — ' . ($batch['name'] ?? 'Class'), $body);
    }
}
