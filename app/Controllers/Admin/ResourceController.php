<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Validator;
use App\Core\Model;
use App\Core\Paginator;
use App\Models\AuditLog;

/**
 * Config-driven CRUD base. Concrete controllers define resource() (metadata +
 * fields + list columns) and optionally options()/lookups() for FK selects.
 * Provides index / create / store / edit / update / destroy.
 */
abstract class ResourceController extends AdminController
{
    /** @return array resource config */
    abstract protected function resource(): array;

    protected function model(): Model
    {
        $class = $this->resource()['model'];
        return new $class();
    }

    /** Select option maps: ['curriculum' => [id => label], ...] */
    protected function options(): array
    {
        return [];
    }

    /** FK id→label lookups for list display: ['curriculum_id' => [id=>label]] */
    protected function lookups(): array
    {
        return [];
    }

    /** Build an id=>label map from a model's rows. */
    protected function optionMap(Model $model, string $labelCol = 'name', ?string $order = null): array
    {
        $out = [];
        foreach ($model->all($order) as $row) {
            $out[$row['id']] = $row[$labelCol];
        }
        return $out;
    }

    /* --------------------------------- List --------------------------------- */
    public function index(Request $request): void
    {
        $cfg = $this->resource();
        $result = $this->model()->paginate(
            Paginator::currentPage(),
            (int) ($cfg['perPage'] ?? 20),
            $cfg['order'] ?? 'id DESC'
        );
        $this->renderAdmin('admin/crud/index', [
            'pageTitle'  => $cfg['title'],
            'activeMenu' => $cfg['activeMenu'] ?? $cfg['route'],
            'cfg'        => $cfg,
            'rows'       => $result['rows'],
            'pager'      => $result['pager'],
            'lookups'    => $this->lookups(),
        ]);
    }

    /* -------------------------------- Create -------------------------------- */
    public function create(Request $request): void
    {
        $this->renderForm(null);
    }

    public function store(Request $request): void
    {
        Csrf::check($request->post('_token'));
        $cfg = $this->resource();
        $data = $this->collect($request, $cfg['fields']);
        $errors = $this->validate($data, $cfg['fields']);
        if ($errors) {
            $this->redirectWithErrors(admin_url($cfg['route'] . '/create'), $errors, $data);
            return;
        }
        $data = $this->prepare($data, $cfg['fields'], true);
        $id = $this->model()->create($data);
        AuditLog::record('create', $cfg['route'], $id);
        Flash::success($cfg['singular'] . ' created.');
        $this->redirect(admin_url($cfg['route']));
    }

    /* --------------------------------- Edit --------------------------------- */
    public function edit(Request $request, string $id): void
    {
        $item = $this->model()->find((int) $id);
        if (!$item) {
            Flash::error('Record not found.');
            $this->redirect(admin_url($this->resource()['route']));
            return;
        }
        $this->renderForm($item);
    }

    public function update(Request $request, string $id): void
    {
        Csrf::check($request->post('_token'));
        $cfg = $this->resource();
        $item = $this->model()->find((int) $id);
        if (!$item) {
            Flash::error('Record not found.');
            $this->redirect(admin_url($cfg['route']));
            return;
        }
        $data = $this->collect($request, $cfg['fields']);
        $errors = $this->validate($data, $cfg['fields'], (int) $id);
        if ($errors) {
            $this->redirectWithErrors(admin_url($cfg['route'] . '/' . $id . '/edit'), $errors, $data);
            return;
        }
        $data = $this->prepare($data, $cfg['fields'], false);
        $this->model()->update((int) $id, $data);
        AuditLog::record('update', $cfg['route'], (int) $id);
        Flash::success($cfg['singular'] . ' updated.');
        $this->redirect(admin_url($cfg['route']));
    }

    /* ------------------------------- Destroy -------------------------------- */
    public function destroy(Request $request, string $id): void
    {
        Csrf::check($request->post('_token'));
        $cfg = $this->resource();

        // Referential guard: refuse to delete a record still referenced by
        // dependent rows, so we never silently orphan pages/courses/batches.
        $blockers = [];
        foreach ($cfg['references'] ?? [] as $ref) {
            $stmt = $this->model()->db()->prepare(
                "SELECT COUNT(*) c FROM {$ref['table']} WHERE {$ref['column']} = ?"
            );
            $stmt->execute([(int) $id]);
            $count = (int) ($stmt->fetch()['c'] ?? 0);
            if ($count > 0) {
                $blockers[] = "{$count} {$ref['label']}";
            }
        }
        if ($blockers) {
            Flash::error(
                'Cannot delete this ' . strtolower($cfg['singular']) . ' — it is still used by ' .
                implode(', ', $blockers) . '. Remove or reassign those first.'
            );
            $this->redirect(admin_url($cfg['route']));
            return;
        }

        $this->model()->delete((int) $id);
        AuditLog::record('delete', $cfg['route'], (int) $id);
        Flash::success($cfg['singular'] . ' deleted.');
        $this->redirect(admin_url($cfg['route']));
    }

    /* ------------------------------- Helpers -------------------------------- */
    protected function renderForm(?array $item): void
    {
        $cfg = $this->resource();
        $this->renderAdmin('admin/crud/form', [
            'pageTitle'  => ($item ? 'Edit ' : 'New ') . $cfg['singular'],
            'activeMenu' => $cfg['activeMenu'] ?? $cfg['route'],
            'cfg'        => $cfg,
            'item'       => $item,
            'options'    => $this->options(),
        ]);
    }

    /** Pull only defined fields from the request. */
    protected function collect(Request $request, array $fields): array
    {
        $data = [];
        foreach ($fields as $f) {
            if (($f['type'] ?? '') === 'checkbox') {
                $data[$f['name']] = $request->post($f['name']) ? 1 : 0;
            } else {
                $data[$f['name']] = $request->post($f['name']);
            }
        }
        return $data;
    }

    protected function validate(array $data, array $fields, ?int $ignoreId = null): array
    {
        $rules = [];
        $labels = [];
        foreach ($fields as $f) {
            if (!empty($f['rules'])) {
                $rules[$f['name']] = $f['rules'];
                $labels[$f['name']] = $f['label'] ?? $f['name'];
            }
        }
        $v = new Validator($data, $rules, $labels);
        $errors = $v->errors();

        // Unique checks (field 'unique' => true against the table column).
        foreach ($fields as $f) {
            if (!empty($f['unique']) && !empty($data[$f['name']]) && !isset($errors[$f['name']])) {
                $existing = $this->model()->findBy($f['name'], $data[$f['name']]);
                if ($existing && (int) $existing['id'] !== (int) $ignoreId) {
                    $errors[$f['name']] = ($f['label'] ?? $f['name']) . ' must be unique — that value is already used.';
                }
            }
        }
        return $errors;
    }

    /** Transform submitted data before persisting (slugs, timestamps, JSON). */
    protected function prepare(array $data, array $fields, bool $creating): array
    {
        foreach ($fields as $f) {
            $name = $f['name'];
            $type = $f['type'] ?? 'text';

            // Auto-slug from source if empty.
            if ($type === 'slug' && empty($data[$name]) && !empty($f['from']) && !empty($data[$f['from']])) {
                $data[$name] = str_slug((string) $data[$f['from']]);
            }
            // Rich-text fields are rendered unescaped on the public site — clean
            // them through an allowlist sanitizer before persisting.
            if ($type === 'richtext') {
                $data[$name] = \App\Support\HtmlSanitizer::clean($data[$name] ?? '');
            }
            // JSON fields: accept a JSON string; store as-is (model encodes if array).
            if ($type === 'json') {
                $decoded = json_decode((string) $data[$name], true);
                $data[$name] = is_array($decoded) ? json_encode($decoded, JSON_UNESCAPED_UNICODE) : ($data[$name] ?: null);
            }
            // Empty numbers/FKs → the field's configured default (e.g. sort_order
            // defaults to 0), or null when the field is genuinely nullable.
            // This prevents NOT NULL violations when a numeric field is left blank.
            if (in_array($type, ['number', 'select-int'], true) && ($data[$name] === '' || $data[$name] === null)) {
                $data[$name] = array_key_exists('default', $f) ? $f['default'] : null;
            }
        }
        // Timestamps if the model supports them.
        if ($creating && $this->hasColumn('created_at')) {
            $data['created_at'] = now();
        }
        if ($this->hasColumn('updated_at')) {
            $data['updated_at'] = now();
        }
        return $data;
    }

    protected function hasColumn(string $col): bool
    {
        static $cache = [];
        $cfg = $this->resource();
        $key = $cfg['route'];
        if (!isset($cache[$key])) {
            // Derive from schema.php definition.
            $schema = require BASE_PATH . '/database/schema.php';
            $table = $cfg['table'] ?? null;
            $cache[$key] = [];
            if ($table && isset($schema[$table])) {
                foreach ($schema[$table]['columns'] as $c) {
                    $cache[$key][] = explode(' ', trim($c))[0];
                }
            }
        }
        return in_array($col, $cache[$key], true);
    }
}
