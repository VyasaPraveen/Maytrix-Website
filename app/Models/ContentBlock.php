<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/**
 * Overrides for editable front-end copy. Only blocks the client has changed are
 * stored here; defaults live in app/Support/content_blocks.php. See the block()
 * helper for how these are merged at render time.
 */
final class ContentBlock extends Model
{
    protected string $table = 'content_blocks';
    protected array $fillable = ['block_key', 'value', 'updated_at'];

    private static ?array $cache = null;

    /** All overrides as a key => value map (cached per request). */
    public function overrides(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }
        $out = [];
        foreach ($this->all() as $row) {
            $out[$row['block_key']] = $row['value'];
        }
        return self::$cache = $out;
    }

    /**
     * Upsert an override. An empty/whitespace value deletes the override so the
     * block reverts to its registry default ("clear to reset").
     */
    public function put(string $key, ?string $value): void
    {
        $existing = $this->findBy('block_key', $key);
        if ($value === null || trim((string) $value) === '') {
            if ($existing) {
                $this->delete($existing['id']);
            }
        } elseif ($existing) {
            $this->update($existing['id'], ['value' => $value, 'updated_at' => now()]);
        } else {
            $this->create(['block_key' => $key, 'value' => $value, 'updated_at' => now()]);
        }
        self::$cache = null;
    }
}
