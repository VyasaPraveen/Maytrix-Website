<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class AuditLog extends Model
{
    protected string $table = 'audit_log';
    protected string $connection = 'admin';
    protected array $fillable = ['admin_id', 'action', 'entity', 'entity_id', 'meta', 'ip', 'created_at'];

    public static function record(string $action, ?string $entity = null, $entityId = null, array $meta = []): void
    {
        try {
            (new self())->create([
                'admin_id'  => \App\Core\Auth::id(),
                'action'    => $action,
                'entity'    => $entity,
                'entity_id' => $entityId,
                'meta'      => $meta ? json_encode($meta, JSON_UNESCAPED_UNICODE) : null,
                'ip'        => $_SERVER['REMOTE_ADDR'] ?? null,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            log_message('Audit log failed: ' . $e->getMessage());
        }
    }

    public function recent(int $limit = 30): array
    {
        return $this->all('id DESC');
    }
}
