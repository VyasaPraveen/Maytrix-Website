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
        $uploads = $request->file('upload');          // ['name'=>[key=>..], 'tmp_name'=>[...], ...]
        $removals = $request->post('remove', []);
        $overrides = (new ContentBlock())->overrides();

        $model = new ContentBlock();
        foreach ($registry[$group]['blocks'] as $key => $meta) {
            $type = $meta['type'] ?? 'text';

            // Image blocks: upload a new file, remove the current one, or keep as-is.
            if ($type === 'image') {
                $current = trim((string) ($overrides[$key] ?? ''));
                if (is_array($uploads) && !empty($uploads['name'][$key]) && ($uploads['tmp_name'][$key] ?? '') !== '') {
                    $file = [
                        'name'     => $uploads['name'][$key],
                        'type'     => $uploads['type'][$key] ?? '',
                        'tmp_name' => $uploads['tmp_name'][$key],
                        'error'    => $uploads['error'][$key] ?? UPLOAD_ERR_NO_FILE,
                        'size'     => $uploads['size'][$key] ?? 0,
                    ];
                    $stored = $this->storeImage($file, 'tutor');
                    if ($stored !== null) {
                        $this->deleteUpload($current);            // clean up the previous file
                        $model->put($key, $stored);
                    }
                    // On failure storeImage() has already flashed a warning; keep the old value.
                } elseif (!empty($removals[$key])) {
                    $this->deleteUpload($current);
                    $model->put($key, '');
                }
                continue;
            }

            $value = (string) ($submitted[$key] ?? '');
            if ($type === 'richtext') {
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

    /**
     * Validate and store an uploaded image in public_html/assets/uploads/.
     * Returns the asset-relative path (e.g. "uploads/tutor-ab12.webp") or null
     * on failure (a Flash warning is set). Security: real-image check via
     * getimagesize(), extension whitelist, size cap, randomised filename.
     */
    private function storeImage(array $file, string $prefix): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            Flash::error('Photo upload failed — please try again.');
            return null;
        }
        if (($file['size'] ?? 0) > 4 * 1024 * 1024) {
            Flash::error('Photo is too large (max 4 MB).');
            return null;
        }
        $info = @getimagesize($file['tmp_name']);
        $allowed = [
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG  => 'png',
            IMAGETYPE_WEBP => 'webp',
        ];
        if ($info === false || !isset($allowed[$info[2]])) {
            Flash::error('Photo must be a JPG, PNG or WebP image.');
            return null;
        }
        $ext = $allowed[$info[2]];
        $name = $prefix . '-' . bin2hex(random_bytes(8)) . '.' . $ext;
        $dir  = BASE_PATH . '/public_html/assets/uploads';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
            Flash::error('Could not save the uploaded photo.');
            return null;
        }
        @chmod($dir . '/' . $name, 0644);
        return 'uploads/' . $name;
    }

    /**
     * Delete a previously-stored upload (only within assets/uploads/, guards
     * against path traversal). No-op for empty values or non-upload paths.
     */
    private function deleteUpload(string $assetPath): void
    {
        $assetPath = trim($assetPath);
        if ($assetPath === '' || strpos($assetPath, 'uploads/') !== 0 || strpos($assetPath, '..') !== false) {
            return;
        }
        $full = BASE_PATH . '/public_html/assets/' . $assetPath;
        if (is_file($full)) {
            @unlink($full);
        }
    }
}
