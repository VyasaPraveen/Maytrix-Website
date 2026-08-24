<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Plain-PHP template renderer with layout support.
 * Views live in app/Views/{site|admin}/... and receive an escaped-by-you
 * data array (use e() in templates).
 */
final class View
{
    private static string $viewRoot = '';

    public static function root(string $path): void
    {
        self::$viewRoot = rtrim($path, '/');
    }

    /**
     * Render a view optionally wrapped in a layout.
     * @param string $view   e.g. "site/home"
     * @param array  $data   variables extracted into the template
     * @param string|null $layout e.g. "site/layouts/main"
     */
    public static function render(string $view, array $data = [], ?string $layout = null): string
    {
        $content = self::renderPartial($view, $data);
        if ($layout === null) {
            return $content;
        }
        $data['content'] = $content;
        return self::renderPartial($layout, $data);
    }

    public static function renderPartial(string $view, array $data = []): string
    {
        $file = self::$viewRoot . '/' . $view . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException("View not found: {$view} ({$file})");
        }
        extract($data, EXTR_SKIP);
        ob_start();
        include $file;
        return (string) ob_get_clean();
    }

    public static function output(string $view, array $data = [], ?string $layout = null): void
    {
        echo self::render($view, $data, $layout);
    }
}
