<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\ContentBlock;
use App\Models\AuditLog;
use App\Support\HtmlSanitizer;

/**
 * "Page Content" — lets the client edit the section copy that is otherwise
 * hard-coded in the site templates (headings, intros, the About cards, etc.).
 * Defaults live in app/Support/content_blocks.php; only changes are stored.
 */
final class SiteContentController extends AdminController
{
    public function index(Request $request): void
    {
        $registry = content_blocks_registry();
        $overrides = (new ContentBlock())->overrides();

        $groups = [];
        foreach ($registry as $key => $group) {
            $edited = 0;
            foreach ($group['blocks'] as $bk => $meta) {
                if (isset($overrides[$bk]) && trim((string) $overrides[$bk]) !== '') {
                    $edited++;
                }
            }
            $groups[$key] = [
                'label' => $group['label'],
                'count' => count($group['blocks']),
                'edited' => $edited,
            ];
        }

        $this->renderAdmin('admin/content/index', [
            'pageTitle'  => 'Page Content',
            'activeMenu' => 'content',
            'groups'     => $groups,
        ]);
    }

    public function edit(Request $request, string $group): void
    {
        $registry = content_blocks_registry();
        if (!isset($registry[$group])) {
            Flash::error('Unknown content group.');
            $this->redirect(admin_url('content'));
            return;
        }
        $overrides = (new ContentBlock())->overrides();

        $this->renderAdmin('admin/content/edit', [
            'pageTitle'  => $registry[$group]['label'] . ' — Content',
            'activeMenu' => 'content',
            'groupKey'   => $group,
            'group'      => $registry[$group],
            'values'     => $overrides,
        ]);
    }

    public function update(Request $request, string $group): void
    {
        Csrf::check($request->post('_token'));
        $registry = content_blocks_registry();
        if (!isset($registry[$group])) {
            Flash::error('Unknown content group.');
            $this->redirect(admin_url('content'));
            return;
        }

        $submitted = $request->post('blocks', []);
        if (!is_array($submitted)) {
            $submitted = [];
        }
        $model = new ContentBlock();
        foreach ($registry[$group]['blocks'] as $key => $meta) {
            $value = (string) ($submitted[$key] ?? '');
            if (($meta['type'] ?? 'text') === 'richtext') {
                $value = HtmlSanitizer::clean($value);
            } else {
                $value = trim($value);
            }
            // Store only genuine customisations; a value matching the default is
            // saved as an override-cleared reset so registry defaults stay live.
            if ($value === (string) ($meta['default'] ?? '')) {
                $value = '';
            }
            $model->put($key, $value);
        }

        AuditLog::record('update', 'content:' . $group);
        Flash::success($registry[$group]['label'] . ' content saved.');
        $this->redirect(admin_url('content/' . $group . '/edit'));
    }
}
