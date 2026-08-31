<?php
/**
 * Public website front controller (maytrix_web content database).
 * All public traffic routes through here.
 */

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/app/bootstrap.php';

use App\Core\Request;
use App\Core\Router;
use App\Core\View;
use App\Controllers\Site\HomeController;
use App\Controllers\Site\CurriculumController;
use App\Controllers\Site\ClassController;
use App\Controllers\Site\ContentController;
use App\Controllers\Site\ContactController;
use App\Controllers\Site\BookingController;
use App\Controllers\Site\EnrolmentController;
use App\Controllers\Site\SitemapController;

View::root(BASE_PATH . '/app/Views');

\App\Core\Security::applyHeaders('site');

$router = new Router();

/* ---- Public pages ---- */
$router->get('/',                       [HomeController::class, 'index']);
$router->get('/about',                  [ContentController::class, 'about']);

$router->get('/curricula',              [CurriculumController::class, 'curricula']);
$router->get('/subjects',               [CurriculumController::class, 'subjects']);
$router->get('/programmes',             [CurriculumController::class, 'matrix']);
$router->get('/courses', function () {  // legacy alias → 301 to canonical /programmes
    header('Location: ' . base_url('programmes'), true, 301);
    exit;
});
$router->get('/curriculum/{slug}',      [CurriculumController::class, 'show']);

$router->get('/one-to-one',             [ClassController::class, 'oneToOne']);
$router->get('/small-group',            [ClassController::class, 'smallGroup']);
$router->get('/batch/{id}',             [ClassController::class, 'batch']);

$router->get('/resources',              [ContentController::class, 'resources']);
$router->get('/resources/{slug}',       [ContentController::class, 'resourceShow']);

$router->get('/contact',                [ContactController::class, 'show']);
$router->post('/contact',               [ContactController::class, 'submit']);

$router->get('/book',                   [BookingController::class, 'show']);
$router->post('/book',                  [BookingController::class, 'submit']);

$router->post('/enrol',                 [EnrolmentController::class, 'store']);
$router->get('/enrol/success',          [EnrolmentController::class, 'success']);

/* ---- SEO ---- */
$router->get('/sitemap.xml',            [SitemapController::class, 'xml']);
$router->get('/robots.txt',             [SitemapController::class, 'robots']);

/* ---- 404 ---- */
$router->notFound(function (Request $request) {
    (new class extends \App\Controllers\Site\SiteController {
        public function run(): void { $this->render('site/404', ['activeNav' => '', 'metaTitle' => 'Page not found | Maytrix Education']); }
    })->run();
});

$router->dispatch(new Request());
