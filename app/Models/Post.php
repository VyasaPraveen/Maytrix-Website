<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Post extends Model
{
    protected string $table = 'posts';
    protected array $fillable = [
        'slug', 'title', 'kicker', 'excerpt', 'body_html', 'cover_image',
        'curriculum_id', 'subject_id', 'status', 'seo_title', 'seo_description',
        'published_at', 'created_at', 'updated_at',
    ];

    public function published(): array
    {
        return $this->where(['status' => 'published'], 'published_at DESC, id DESC');
    }

    public function bySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }
}
