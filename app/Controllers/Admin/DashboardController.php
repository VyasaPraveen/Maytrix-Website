<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Models\Booking;
use App\Models\Enrolment;
use App\Models\Batch;
use App\Models\Student;
use App\Models\ContactMessage;

final class DashboardController extends AdminController
{
    public function index(Request $request): void
    {
        $bookingModel = new Booking();
        $enrolModel = new Enrolment();

        $this->renderAdmin('admin/dashboard', [
            'pageTitle'  => 'Dashboard',
            'activeMenu' => 'dashboard',
            'stats' => [
                'newBookings'   => $bookingModel->count(['status' => 'new']),
                'pendingEnrol'  => $enrolModel->count(['status' => 'pending']),
                'confirmedEnrol'=> $enrolModel->count(['status' => 'confirmed']),
                'openBatches'   => (new Batch())->count(['status' => 'open']),
                'students'      => (new Student())->count(),
                'unreadMsgs'    => (new ContactMessage())->unreadCount(),
            ],
            'recentBookings'   => array_slice($bookingModel->recentWithLabels(10), 0, 10),
            'recentEnrolments' => $enrolModel->recentWithDetails(8),
        ]);
    }
}
