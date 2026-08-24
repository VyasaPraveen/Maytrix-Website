<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Flash;
use App\Models\Booking;
use App\Models\ContactMessage;

/** Base for all admin controllers: enforces auth + injects dashboard chrome. */
abstract class AdminController extends Controller
{
    public function __construct()
    {
        Auth::require(admin_url('login'));
    }

    protected function renderAdmin(string $view, array $data = []): void
    {
        $shared = [
            'authUser'     => Auth::user(),
            'flash'        => Flash::pull(),
            'old'          => Flash::oldInput(),
            'errors'       => Flash::errors(),
            'currentPath'  => (new \App\Core\Request())->path(),
            'pendingBookings' => (new Booking())->count(['status' => 'new']),
            'unreadMessages'  => (new ContactMessage())->unreadCount(),
        ];
        $data += ['pageTitle' => 'Dashboard', 'activeMenu' => ''];
        $this->view($view, array_merge($shared, $data), 'admin/layouts/main');
    }
}
