<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Setting extends Model
{
    protected string $table = 'settings';
    protected array $fillable = ['skey', 'svalue', 'updated_at'];

    private static ?array $cache = null;

    /** All settings as an associative key => value map (cached per request). */
    public function map(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }
        $out = [];
        foreach ($this->all() as $row) {
            $out[$row['skey']] = $row['svalue'];
        }
        return self::$cache = $out;
    }

    public function get(string $key, $default = null)
    {
        return $this->map()[$key] ?? $default;
    }

    public function put(string $key, $value): void
    {
        $existing = $this->findBy('skey', $key);
        if ($existing) {
            $this->update($existing['id'], ['svalue' => $value, 'updated_at' => now()]);
        } else {
            $this->create(['skey' => $key, 'svalue' => $value, 'updated_at' => now()]);
        }
        self::$cache = null; // bust cache
    }
}
