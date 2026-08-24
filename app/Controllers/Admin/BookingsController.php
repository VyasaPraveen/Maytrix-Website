<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Mailer;
use App\Models\Booking;
use App\Models\AuditLog;

final class BookingsController extends AdminController
{
    public function index(Request $request): void
    {
        $result = (new Booking())->paginateWithLabels(\App\Core\Paginator::currentPage(), 20);
        $this->renderAdmin('admin/bookings/index', [
            'pageTitle'  => '1-to-1 Bookings',
            'activeMenu' => 'bookings',
            'rows'       => $result['rows'],
            'pager'      => $result['pager'],
        ]);
    }

    public function show(Request $request, string $id): void
    {
        $booking = (new Booking())->findWithLabels((int) $id);
        if (!$booking) {
            Flash::error('Booking not found.');
            $this->redirect(admin_url('bookings'));
            return;
        }
        $this->renderAdmin('admin/bookings/show', [
            'pageTitle'  => 'Booking #' . $id,
            'activeMenu' => 'bookings',
            'booking'    => $booking,
        ]);
    }

    /** Update status / schedule / Zoom link — shares the link when confirmed. */
    public function update(Request $request, string $id): void
    {
        Csrf::check($request->post('_token'));
        $booking = (new Booking())->find((int) $id);
        if (!$booking) {
            Flash::error('Booking not found.');
            $this->redirect(admin_url('bookings'));
            return;
        }

        $status    = $request->post('status', $booking['status']);
        $zoomLink  = trim((string) $request->post('zoom_link', ''));
        $scheduled = $request->post('scheduled_at') ?: null;
        $notes     = $request->post('admin_notes');

        (new Booking())->update((int) $id, [
            'status'       => $status,
            'zoom_link'    => $zoomLink ?: null,
            'scheduled_at' => $scheduled,
            'admin_notes'  => $notes,
        ]);
        AuditLog::record('update', 'bookings', (int) $id);

        // When confirmed with a link, notify the student.
        if ($status === 'confirmed' && $request->post('notify') && $booking['email']) {
            $body = '<p>Hi ' . e($booking['name']) . ',</p>'
                . '<p>Your consultation / class is confirmed.</p>';
            if ($scheduled) {
                $body .= '<p><strong>When:</strong> ' . e(date('j M Y, H:i', strtotime($scheduled))) . '</p>';
            }
            if ($zoomLink) {
                $body .= '<p><strong>Join on Zoom:</strong><br><a href="' . e($zoomLink) . '">' . e($zoomLink) . '</a></p>';
            }
            $body .= '<p>— Maytrix Education</p>';
            Mailer::send($booking['email'], 'Your Maytrix session is confirmed', $body);
            Flash::success('Booking updated and confirmation emailed to the student.');
        } else {
            Flash::success('Booking updated.');
        }
        $this->redirect(admin_url('bookings/' . $id));
    }

    public function destroy(Request $request, string $id): void
    {
        Csrf::check($request->post('_token'));
        (new Booking())->delete((int) $id);
        AuditLog::record('delete', 'bookings', (int) $id);
        Flash::success('Booking deleted.');
        $this->redirect(admin_url('bookings'));
    }
}
