<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Request;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Validator;
use App\Core\Mailer;
use App\Models\ContactMessage;

final class ContactController extends SiteController
{
    public function show(Request $request): void
    {
        $this->render('site/contact', [
            'activeNav' => 'contact',
            'metaTitle' => 'Contact | Maytrix Education',
            'metaDescription' => 'Talk to us before booking a consultation.',
        ]);
    }

    public function submit(Request $request): void
    {
        Csrf::check($request->post('_token'));
        $input = $request->only(['name', 'email', 'country', 'curriculum', 'message']);

        $v = new Validator($input, [
            'name'    => 'required|max:160',
            'email'   => 'required|email|max:190',
            'message' => 'required|max:2000',
        ]);
        if ($v->fails()) {
            $this->redirectWithErrors(base_url('contact'), $v->errors(), $input);
            return;
        }

        (new ContactMessage())->create([
            'name'       => $input['name'],
            'email'      => $input['email'],
            'country'    => $input['country'] ?? null,
            'curriculum' => $input['curriculum'] ?? null,
            'message'    => $input['message'],
            'is_read'    => 0,
            'created_at' => now(),
        ]);

        Mailer::notifyAdmin('New contact message — ' . $input['name'],
            '<p>New enquiry from <strong>' . e($input['name']) . '</strong> (' . e($input['email']) . ')</p>'
            . '<p>' . nl2br(e($input['message'])) . '</p>');

        Flash::success('Thanks — your message has reached the Maytrix team. We\'ll reply within one business day.');
        $this->redirect(base_url('contact'));
    }
}
