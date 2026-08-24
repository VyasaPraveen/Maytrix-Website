<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Curriculum extends Model
{
    protected string $table = 'curricula';
    protected array $fillable = ['code', 'name', 'short_name', 'tagline', 'description', 'sort_order', 'is_active', 'created_at'];

    public function active(): array
    {
        return $this->where(['is_active' => 1], 'sort_order ASC, name ASC');
    }
}
