<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Mode extends Model
{
    protected string $table = 'modes';
    protected array $fillable = ['code', 'name', 'description', 'sort_order', 'is_active'];

    public function active(): array
    {
        return $this->where(['is_active' => 1], 'sort_order ASC');
    }
}
