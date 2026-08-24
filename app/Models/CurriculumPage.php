<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class CurriculumPage extends Model
{
    protected string $table = 'curriculum_pages';
    protected array $json = ['badges', 'topics'];
    protected array $fillable = [
        'curriculum_id', 'subject_id', 'slug', 'eyebrow', 'title', 'badges', 'intro',
        'topics', 'body_html', 'seo_title', 'seo_description', 'seo_keywords', 'og_image',
        'is_published', 'sort_order', 'created_at', 'updated_at',
    ];

    public function published(): array
    {
        return $this->where(['is_published' => 1], 'sort_order ASC, id ASC');
    }

    public function bySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    /** Pages joined with curriculum + subject names, for the course matrix. */
    public function matrix(): array
    {
        $sql = "SELECT p.*, c.name AS curriculum_name, c.code AS curriculum_code,
                       s.name AS subject_name, s.code AS subject_code
                FROM curriculum_pages p
                JOIN curricula c ON c.id = p.curriculum_id
                JOIN subjects  s ON s.id = p.subject_id
                WHERE p.is_published = 1
                ORDER BY c.sort_order ASC, s.sort_order ASC";
        $rows = $this->db()->query($sql)->fetchAll();
        return array_map([$this, 'decodeRow'], $rows);
    }
}
