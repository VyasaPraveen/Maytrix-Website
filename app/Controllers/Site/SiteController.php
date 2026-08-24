<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\Curriculum;
use App\Models\Setting;

/** Base for all public-site controllers: injects shared nav + brand data. */
abstract class SiteController extends Controller
{
    protected function render(string $view, array $data = []): void
    {
        // Only what the shared layout actually needs (settings + footer curricula).
        $shared = [
            'settings'        => (new Setting())->map(),
            'navCurricula'    => (new Curriculum())->active(),
            'flash'           => Flash::pull(),
            'old'             => Flash::oldInput(),
            'errors'          => Flash::errors(),
            'currentPath'     => (new \App\Core\Request())->path(),
        ];
        // Sensible SEO defaults; individual pages override.
        $data += [
            'metaTitle'       => ($shared['settings']['brand_name'] ?? 'Maytrix Education') . ' — IB · IGCSE · A Level Maths & Physics',
            'metaDescription' => $shared['settings']['footer_tagline'] ?? '',
            'activeNav'       => '',
        ];
        $this->view($view, array_merge($shared, $data), 'site/layouts/main');
    }
}
