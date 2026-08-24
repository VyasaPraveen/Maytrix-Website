<?php
/**
 * Admin dashboard front controller — a SEPARATE application from the public
 * website. Authentication runs against the SEPARATE admin database
 * (maytrix_admin); content is managed in the website database (maytrix_web).
 */

declare(strict_types=1);

define('BASE_PATH', dirname(dirname(__DIR__)));
require BASE_PATH . '/app/bootstrap.php';

use App\Core\Request;
use App\Core\Router;
use App\Core\View;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\CurriculaController;
use App\Controllers\Admin\SubjectsController;
use App\Controllers\Admin\ModesController;
use App\Controllers\Admin\TopicsController;
use App\Controllers\Admin\CoursesController;
use App\Controllers\Admin\CurriculumPagesController;
use App\Controllers\Admin\PostsController;
use App\Controllers\Admin\PagesController;
use App\Controllers\Admin\StudentsController;
use App\Controllers\Admin\BatchesController;
use App\Controllers\Admin\EnrolmentsController;
use App\Controllers\Admin\BookingsController;
use App\Controllers\Admin\MessagesController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\AdminsController;
use App\Controllers\Admin\ReportsController;
use App\Controllers\Admin\BackupsController;

View::root(BASE_PATH . '/app/Views');

\App\Core\Security::applyHeaders('admin');

$router = new Router();

/* ---- Auth ---- */
$router->get('/login',  [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

/* ---- Dashboard ---- */
$router->get('/', [DashboardController::class, 'index']);

/**
 * Register the 6 standard CRUD routes for a config-driven resource controller.
 */
$crud = function (Router $r, string $route, string $controller): void {
    $r->get("/{$route}",              [$controller, 'index']);
    $r->get("/{$route}/create",       [$controller, 'create']);
    $r->post("/{$route}",             [$controller, 'store']);
    $r->get("/{$route}/{id}/edit",    [$controller, 'edit']);
    $r->post("/{$route}/{id}",        [$controller, 'update']);
    $r->post("/{$route}/{id}/delete", [$controller, 'destroy']);
};

/* ---- Catalog & content (generic CRUD) ---- */
$crud($router, 'curricula',        CurriculaController::class);
$crud($router, 'subjects',         SubjectsController::class);
$crud($router, 'modes',            ModesController::class);
$crud($router, 'topics',           TopicsController::class);
$crud($router, 'courses',          CoursesController::class);
$crud($router, 'curriculum-pages', CurriculumPagesController::class);
$crud($router, 'posts',            PostsController::class);
$crud($router, 'pages',            PagesController::class);
$crud($router, 'students',         StudentsController::class);

/* ---- Batches (CRUD + manage enrolments) ---- */
$router->get('/batches',              [BatchesController::class, 'index']);
$router->get('/batches/create',       [BatchesController::class, 'create']);
$router->post('/batches',             [BatchesController::class, 'store']);
$router->get('/batches/{id}/manage',  [BatchesController::class, 'manage']);
$router->get('/batches/{id}/edit',    [BatchesController::class, 'edit']);
$router->post('/batches/{id}',        [BatchesController::class, 'update']);
$router->post('/batches/{id}/delete', [BatchesController::class, 'destroy']);

/* ---- Enrolments ---- */
$router->get('/enrolments',                 [EnrolmentsController::class, 'index']);
$router->post('/enrolments/{id}/confirm',   [EnrolmentsController::class, 'confirm']);
$router->post('/enrolments/{id}/cancel',    [EnrolmentsController::class, 'cancel']);
$router->post('/enrolments/{id}/paid',      [EnrolmentsController::class, 'markPaid']);
$router->post('/enrolments/{id}/delete',    [EnrolmentsController::class, 'destroy']);

/* ---- Bookings (1-to-1) ---- */
$router->get('/bookings',              [BookingsController::class, 'index']);
$router->get('/bookings/{id}',         [BookingsController::class, 'show']);
$router->post('/bookings/{id}',        [BookingsController::class, 'update']);
$router->post('/bookings/{id}/delete', [BookingsController::class, 'destroy']);

/* ---- Contact messages ---- */
$router->get('/messages',              [MessagesController::class, 'index']);
$router->get('/messages/{id}',         [MessagesController::class, 'show']);
$router->post('/messages/{id}/delete', [MessagesController::class, 'destroy']);

/* ---- Reports ---- */
$router->get('/reports', [ReportsController::class, 'index']);

/* ---- Backups ---- */
$router->get('/backups', [BackupsController::class, 'index']);
$router->get('/backups/{conn}/download', [BackupsController::class, 'download']);

/* ---- Settings ---- */
$router->get('/settings',  [SettingsController::class, 'edit']);
$router->post('/settings', [SettingsController::class, 'update']);

/* ---- Admin users ---- */
$crud($router, 'admins', AdminsController::class);

/* ---- 404 ---- */
$router->notFound(function () {
    http_response_code(404);
    echo '<p style="font-family:sans-serif;padding:40px;">Admin page not found. <a href="' . e(admin_url()) . '">Go to dashboard</a></p>';
});

$router->dispatch(new Request());
