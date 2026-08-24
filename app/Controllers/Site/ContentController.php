<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Request;
use App\Models\Page;
use App\Models\Post;

final class ContentController extends SiteController
{
    public function about(Request $request): void
    {
        $page = (new Page())->bySlug('about');
        $this->render('site/about', [
            'activeNav' => 'about',
            'page'      => $page,
            'metaTitle' => $page['seo_title'] ?? 'About Maytrix Education',
            'metaDescription' => $page['seo_description'] ?? '',
        ]);
    }

    public function resources(Request $request): void
    {
        $this->render('site/resources', [
            'activeNav' => 'resources',
            'posts'     => (new Post())->published(),
            'metaTitle' => 'Resources / Blog | Maytrix Education',
            'metaDescription' => 'Short, syllabus-specific guidance from our tutors.',
        ]);
    }

    public function resourceShow(Request $request, string $slug): void
    {
        $post = (new Post())->bySlug($slug);
        if (!$post || $post['status'] !== 'published') {
            http_response_code(404);
            $this->render('site/404', ['activeNav' => 'resources', 'metaTitle' => 'Article not found']);
            return;
        }
        $this->render('site/resource-detail', [
            'activeNav' => 'resources',
            'post'      => $post,
            'metaTitle' => $post['seo_title'] ?: ($post['title'] . ' | Maytrix Education'),
            'metaDescription' => $post['seo_description'] ?: $post['excerpt'],
        ]);
    }
}
