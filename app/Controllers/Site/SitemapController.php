<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Core\Request;
use App\Models\CurriculumPage;
use App\Models\Post;

final class SitemapController extends Controller
{
    public function xml(Request $request): void
    {
        $urls = [
            base_url(),
            base_url('about'),
            base_url('curricula'),
            base_url('subjects'),
            base_url('programmes'),
            base_url('one-to-one'),
            base_url('small-group'),
            base_url('resources'),
            base_url('contact'),
            base_url('book'),
        ];
        foreach ((new CurriculumPage())->published() as $p) {
            $urls[] = base_url('curriculum/' . $p['slug']);
        }
        foreach ((new Post())->published() as $post) {
            $urls[] = base_url('resources/' . $post['slug']);
        }

        header('Content-Type: application/xml; charset=UTF-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            echo "  <url><loc>" . e($u) . "</loc></url>\n";
        }
        echo '</urlset>';
    }

    public function robots(Request $request): void
    {
        header('Content-Type: text/plain; charset=UTF-8');
        echo "User-agent: *\n";
        echo "Disallow: /admin/\n";
        echo "Allow: /\n\n";
        echo 'Sitemap: ' . base_url('sitemap.xml') . "\n";
    }
}
