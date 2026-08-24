<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Validator;
use App\Core\LoginThrottle;
use App\Models\AuditLog;

/** Admin login/logout — NOT extending AdminController (no auth guard here). */
final class AuthController extends Controller
{
    public function showLogin(Request $request): void
    {
        if (Auth::check()) {
            $this->redirect(admin_url());
            return;
        }
        $this->view('admin/login', [
            'flash'  => Flash::pull(),
            'old'    => Flash::oldInput(),
            'errors' => Flash::errors(),
        ], 'admin/layouts/auth');
    }

    public function login(Request $request): void
    {
        Csrf::check($request->post('_token'));
        $input = $request->only(['email', 'password']);
        $v = new Validator($input, ['email' => 'required|email', 'password' => 'required']);
        if ($v->fails()) {
            $this->redirectWithErrors(admin_url('login'), $v->errors(), ['email' => $input['email'] ?? '']);
            return;
        }

        $email = (string) $input['email'];
        $throttle = new LoginThrottle();

        // Brute-force lockout.
        $locked = $throttle->lockedFor($email);
        if ($locked > 0) {
            $mins = (int) ceil($locked / 60);
            Flash::withInput(['email' => $email], ['email' => 'Account temporarily locked.']);
            Flash::error("Too many failed attempts. Please try again in {$mins} minute(s).");
            $this->redirect(admin_url('login'));
            return;
        }

        if (!Auth::attempt($email, (string) $input['password'])) {
            $throttle->hit($email);
            AuditLog::record('login_failed', 'admin', null, ['email' => $email]);
            Flash::withInput(['email' => $email], ['email' => 'Invalid email or password.']);
            Flash::error('Invalid email or password.');
            $this->redirect(admin_url('login'));
            return;
        }

        $throttle->clear($email);
        AuditLog::record('login', 'admin', Auth::id());
        Flash::success('Welcome back, ' . (Auth::user()['name'] ?? 'Admin') . '.');
        $this->redirect(admin_url());
    }

    public function logout(Request $request): void
    {
        Csrf::check($request->post('_token'));
        AuditLog::record('logout', 'admin', Auth::id());
        Auth::logout();
        Flash::success('You have been signed out.');
        $this->redirect(admin_url('login'));
    }
}
