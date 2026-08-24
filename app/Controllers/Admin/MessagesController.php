<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\ContactMessage;

final class MessagesController extends AdminController
{
    public function index(Request $request): void
    {
        $result = (new ContactMessage())->paginate(\App\Core\Paginator::currentPage(), 20, 'id DESC');
        $this->renderAdmin('admin/messages/index', [
            'pageTitle'  => 'Contact Messages',
            'activeMenu' => 'messages',
            'rows'       => $result['rows'],
            'pager'      => $result['pager'],
        ]);
    }

    public function show(Request $request, string $id): void
    {
        $model = new ContactMessage();
        $msg = $model->find((int) $id);
        if (!$msg) {
            Flash::error('Message not found.');
            $this->redirect(admin_url('messages'));
            return;
        }
        if ((int) $msg['is_read'] === 0) {
            $model->update((int) $id, ['is_read' => 1]);
            $msg['is_read'] = 1;
        }
        $this->renderAdmin('admin/messages/show', [
            'pageTitle'  => 'Message from ' . $msg['name'],
            'activeMenu' => 'messages',
            'msg'        => $msg,
        ]);
    }

    public function destroy(Request $request, string $id): void
    {
        Csrf::check($request->post('_token'));
        (new ContactMessage())->delete((int) $id);
        Flash::success('Message deleted.');
        $this->redirect(admin_url('messages'));
    }
}
