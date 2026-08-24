<?php
declare(strict_types=1);

namespace App\Core;

/** Base controller with view + redirect helpers shared by site & admin. */
abstract class Controller
{
    protected function view(string $view, array $data = [], ?string $layout = null): void
    {
        View::output($view, $data, $layout);
    }

    protected function redirect(string $url): void
    {
        redirect($url);
    }

    protected function back(): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($ref);
    }

    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /** Redirect back with validation errors + old input. */
    protected function redirectWithErrors(string $url, array $errors, array $input): void
    {
        Flash::withInput($input, $errors);
        Flash::error('Please correct the highlighted fields.');
        redirect($url);
    }
}
