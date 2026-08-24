<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Page extends Model
{
    protected string $table = 'pages';
    protected array $json = ['blocks'];
    protected array $fillable = [
        'slug', 'title', 'body_html', 'blocks', 'seo_title', 'seo_description',
        'is_published', 'updated_at',
    ];

    public function bySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }
}
