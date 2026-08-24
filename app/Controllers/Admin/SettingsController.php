<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\Setting;
use App\Models\AuditLog;

final class SettingsController extends AdminController
{
    /** Editable site settings, grouped for the form. */
    private function groups(): array
    {
        return [
            'Brand & Contact' => [
                'brand_name'       => ['label' => 'Brand name', 'type' => 'text'],
                'contact_email'    => ['label' => 'Contact email', 'type' => 'text'],
                'contact_whatsapp' => ['label' => 'WhatsApp number', 'type' => 'text'],
                'footer_tagline'   => ['label' => 'Footer tagline', 'type' => 'textarea'],
            ],
            'Homepage Hero' => [
                'hero_eyebrow' => ['label' => 'Hero eyebrow', 'type' => 'text'],
                'hero_title'   => ['label' => 'Hero title', 'type' => 'textarea'],
                'hero_lede'    => ['label' => 'Hero lede', 'type' => 'textarea'],
            ],
            'Homepage Stats' => [
                'stat_students'  => ['label' => 'Students taught', 'type' => 'text'],
                'stat_countries' => ['label' => 'Countries reached', 'type' => 'text'],
                'stat_grades'    => ['label' => 'Avg. grade bands', 'type' => 'text'],
                'stat_curricula' => ['label' => 'Curricula covered', 'type' => 'text'],
            ],
            'Analytics & Payments' => [
                'ga_measurement_id' => ['label' => 'Google Analytics ID', 'type' => 'text', 'hint' => 'e.g. G-XXXXXXXXXX (overrides .env)'],
                'razorpay_key_id'   => ['label' => 'Razorpay Key ID', 'type' => 'text', 'hint' => 'Public key. Keep the secret key in .env.'],
                'stripe_public_key' => ['label' => 'Stripe Publishable Key', 'type' => 'text', 'hint' => 'Public key. Keep the secret key in .env.'],
                'paypal_client_id'  => ['label' => 'PayPal Client ID', 'type' => 'text'],
            ],
        ];
    }

    public function edit(Request $request): void
    {
        $this->renderAdmin('admin/settings/edit', [
            'pageTitle'  => 'Site Settings',
            'activeMenu' => 'settings',
            'groups'     => $this->groups(),
            'values'     => (new Setting())->map(),
        ]);
    }

    public function update(Request $request): void
    {
        Csrf::check($request->post('_token'));
        $model = new Setting();
        foreach ($this->groups() as $fields) {
            foreach ($fields as $key => $meta) {
                $model->put($key, (string) $request->post($key, ''));
            }
        }
        AuditLog::record('update', 'settings');
        Flash::success('Settings saved.');
        $this->redirect(admin_url('settings'));
    }
}
