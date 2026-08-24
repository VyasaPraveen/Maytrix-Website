<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Auth;
use App\Core\Validator;
use App\Models\Admin;
use App\Models\AuditLog;

final class AdminsController extends AdminController
{
    public function index(Request $request): void
    {
        $result = (new Admin())->paginate(\App\Core\Paginator::currentPage(), 20, 'id ASC');
        $this->renderAdmin('admin/admins/index', [
            'pageTitle'  => 'Admin Users',
            'activeMenu' => 'admins',
            'rows'       => $result['rows'],
            'pager'      => $result['pager'],
        ]);
    }

    public function create(Request $request): void
    {
        $this->renderAdmin('admin/admins/form', [
            'pageTitle' => 'New Admin User', 'activeMenu' => 'admins', 'item' => null,
        ]);
    }

    public function store(Request $request): void
    {
        Csrf::check($request->post('_token'));
        $input = $request->only(['name', 'email', 'password', 'role', 'is_active']);
        $v = new Validator($input, [
            'name'     => 'required|max:120',
            'email'    => 'required|email|max:190',
            'password' => 'required|min:8',
            'role'     => 'required|in:admin,editor',
        ]);
        $errors = $v->errors();
        if (!$errors && (new Admin())->findBy('email', strtolower(trim($input['email'])))) {
            $errors['email'] = 'That email is already registered.';
        }
        if ($errors) {
            $this->redirectWithErrors(admin_url('admins/create'), $errors, ['name' => $input['name'], 'email' => $input['email'], 'role' => $input['role']]);
            return;
        }
        $id = (new Admin())->create([
            'name'          => $input['name'],
            'email'         => strtolower(trim($input['email'])),
            'password_hash' => password_hash($input['password'], PASSWORD_DEFAULT),
            'role'          => $input['role'],
            'is_active'     => $request->post('is_active') ? 1 : 0,
            'created_at'    => now(),
        ]);
        AuditLog::record('create', 'admins', $id);
        Flash::success('Admin user created.');
        $this->redirect(admin_url('admins'));
    }

    public function edit(Request $request, string $id): void
    {
        $item = (new Admin())->find((int) $id);
        if (!$item) {
            Flash::error('Admin not found.');
            $this->redirect(admin_url('admins'));
            return;
        }
        $this->renderAdmin('admin/admins/form', [
            'pageTitle' => 'Edit Admin User', 'activeMenu' => 'admins', 'item' => $item,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        Csrf::check($request->post('_token'));
        $item = (new Admin())->find((int) $id);
        if (!$item) {
            Flash::error('Admin not found.');
            $this->redirect(admin_url('admins'));
            return;
        }
        $input = $request->only(['name', 'email', 'password', 'role', 'is_active']);
        $rules = ['name' => 'required|max:120', 'email' => 'required|email|max:190', 'role' => 'required|in:admin,editor'];
        if (!empty($input['password'])) {
            $rules['password'] = 'min:8';
        }
        $v = new Validator($input, $rules);
        $errors = $v->errors();
        $dupe = (new Admin())->findBy('email', strtolower(trim($input['email'])));
        if (!isset($errors['email']) && $dupe && (int) $dupe['id'] !== (int) $id) {
            $errors['email'] = 'That email is already registered.';
        }
        if ($errors) {
            $this->redirectWithErrors(admin_url('admins/' . $id . '/edit'), $errors, ['name' => $input['name'], 'email' => $input['email'], 'role' => $input['role']]);
            return;
        }
        $data = [
            'name'      => $input['name'],
            'email'     => strtolower(trim($input['email'])),
            'role'      => $input['role'],
            'is_active' => $request->post('is_active') ? 1 : 0,
        ];
        if (!empty($input['password'])) {
            $data['password_hash'] = password_hash($input['password'], PASSWORD_DEFAULT);
        }
        (new Admin())->update((int) $id, $data);
        AuditLog::record('update', 'admins', (int) $id);
        Flash::success('Admin user updated.');
        $this->redirect(admin_url('admins'));
    }

    public function destroy(Request $request, string $id): void
    {
        Csrf::check($request->post('_token'));
        if ((int) $id === Auth::id()) {
            Flash::error('You cannot delete your own account while signed in.');
            $this->redirect(admin_url('admins'));
            return;
        }
        if ((new Admin())->count() <= 1) {
            Flash::error('Cannot delete the last remaining admin.');
            $this->redirect(admin_url('admins'));
            return;
        }
        (new Admin())->delete((int) $id);
        AuditLog::record('delete', 'admins', (int) $id);
        Flash::success('Admin user deleted.');
        $this->redirect(admin_url('admins'));
    }
}
