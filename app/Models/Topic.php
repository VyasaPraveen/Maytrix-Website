<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Topic extends Model
{
    protected string $table = 'topics';
    protected array $fillable = ['subject_id', 'curriculum_id', 'key_label', 'title', 'description', 'sort_order', 'is_active', 'created_at'];

    public function forSubject(int $subjectId): array
    {
        return $this->where(['subject_id' => $subjectId, 'is_active' => 1], 'sort_order ASC');
    }
}
