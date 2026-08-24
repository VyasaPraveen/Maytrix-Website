<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Small, dependency-free pagination helper.
 * Computes page metadata (offset, from/to, page window) and builds page URLs
 * that preserve the current query string (filters, search, etc.).
 */
final class Paginator
{
    public int $page;
    public int $perPage;
    public int $total;
    public int $pages;
    public int $offset;
    public int $from;
    public int $to;

    public function __construct(int $total, int $page = 1, int $perPage = 20)
    {
        $this->perPage = max(1, $perPage);
        $this->total   = max(0, $total);
        $this->pages   = (int) max(1, (int) ceil($this->total / $this->perPage));
        $this->page    = min(max(1, $page), $this->pages);
        $this->offset  = ($this->page - 1) * $this->perPage;
        $this->from    = $this->total === 0 ? 0 : $this->offset + 1;
        $this->to      = min($this->offset + $this->perPage, $this->total);
    }

    public function hasPrev(): bool
    {
        return $this->page > 1;
    }

    public function hasNext(): bool
    {
        return $this->page < $this->pages;
    }

    /** Read the requested page number from the query string (?page=). */
    public static function currentPage(): int
    {
        $p = (int) ($_GET['page'] ?? 1);
        return $p < 1 ? 1 : $p;
    }

    /** Slice an in-memory array to the current page (for custom join lists). */
    public function slice(array $rows): array
    {
        return array_slice($rows, $this->offset, $this->perPage);
    }

    /** Build a URL for a page number, preserving other query parameters. */
    public function url(int $page): string
    {
        $q = $_GET;
        $q['page'] = max(1, $page);
        return '?' . http_build_query($q);
    }

    /** Compact window of page numbers around the current page. */
    public function window(int $span = 2): array
    {
        $start = max(1, $this->page - $span);
        $end   = min($this->pages, $this->page + $span);
        return range($start, $end);
    }
}
