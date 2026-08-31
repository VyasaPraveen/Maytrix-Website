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
        $today = date('Y-m-d');
        // [loc, lastmod, changefreq, priority]
        $urls = [
            [base_url(),               $today, 'weekly',  '1.0'],
            [base_url('curricula'),    $today, 'monthly', '0.9'],
            [base_url('subjects'),     $today, 'monthly', '0.8'],
            [base_url('programmes'),   $today, 'monthly', '0.8'],
            [base_url('one-to-one'),   $today, 'monthly', '0.8'],
            [base_url('small-group'),  $today, 'weekly',  '0.8'],
            [base_url('about'),        $today, 'yearly',  '0.6'],
            [base_url('resources'),    $today, 'weekly',  '0.6'],
            [base_url('contact'),      $today, 'yearly',  '0.5'],
            [base_url('book'),         $today, 'yearly',  '0.7'],
        ];
        $dateOf = static function (array $row): string {
            $raw = $row['updated_at'] ?? $row['published_at'] ?? $row['created_at'] ?? null;
            $ts = $raw ? strtotime((string) $raw) : false;
            return $ts ? date('Y-m-d', $ts) : date('Y-m-d');
        };
        foreach ((new CurriculumPage())->published() as $p) {
            $urls[] = [base_url('curriculum/' . $p['slug']), $dateOf($p), 'monthly', '0.7'];
        }
        foreach ((new Post())->published() as $post) {
            $urls[] = [base_url('resources/' . $post['slug']), $dateOf($post), 'monthly', '0.6'];
        }

        header('Content-Type: application/xml; charset=UTF-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as [$loc, $lastmod, $freq, $priority]) {
            echo "  <url><loc>" . e($loc) . "</loc>"
                . "<lastmod>" . e($lastmod) . "</lastmod>"
                . "<changefreq>" . e($freq) . "</changefreq>"
                . "<priority>" . e($priority) . "</priority></url>\n";
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
