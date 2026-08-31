<?php
/**
 * Idempotent label normalisation: rewrite the abbreviations "IB DP" -> "IBDP"
 * and "IB MYP" -> "IBMYP" (single words) in every stored text column that can
 * surface them on the website. seed.php / view fallbacks are updated in code;
 * this brings already-seeded local/production databases in line.
 *
 * Safe to run repeatedly (the spaced forms simply no longer exist after the
 * first run). CLI: php database/update_single_word_labels.php
 */

declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
    require BASE_PATH . '/app/bootstrap.php';
}

use App\Core\Database;

$web = Database::web();

// table => [text columns that may contain the abbreviations]
$targets = [
    'curricula'        => ['short_name', 'tagline', 'description'],
    'curriculum_pages' => ['eyebrow', 'title', 'intro', 'seo_title', 'seo_description', 'body_html'],
    'settings'         => ['svalue'],
    'pages'            => ['title', 'body_html', 'seo_description'],
    'content_blocks'   => ['value'],
    'posts'            => ['title', 'kicker', 'excerpt', 'body_html', 'seo_title', 'seo_description'],
];

$total = 0;
foreach ($targets as $table => $cols) {
    foreach ($cols as $col) {
        // REPLACE nested so both abbreviations are handled in one pass.
        $sql = "UPDATE {$table}
                   SET {$col} = REPLACE(REPLACE({$col}, 'IB DP', 'IBDP'), 'IB MYP', 'IBMYP')
                 WHERE {$col} LIKE '%IB DP%' OR {$col} LIKE '%IB MYP%'";
        $affected = $web->exec($sql);
        if ($affected) {
            echo "  ✓ {$table}.{$col}: {$affected} row(s)\n";
            $total += $affected;
        }
    }
}

echo $total === 0
    ? "No spaced labels found — already normalised.\n"
    : "Label normalisation complete ({$total} column updates).\n";
