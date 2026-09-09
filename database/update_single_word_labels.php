<?php
/**
 * Idempotent label normalisation: rewrite "IB Diploma" -> "IBDP", "IB DP" ->
 * "IBDP" and "IB MYP" -> "IBMYP" (single words) in every stored text column that
 * can surface them on the website. seed.php / view fallbacks are updated in code;
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
// Includes JSON-stored columns (badges, topics, blocks): the abbreviations can
// live inside those text payloads too, and a substring REPLACE keeps the JSON
// valid (labels only ever get shorter, quotes/structure untouched).
$targets = [
    'curricula'        => ['short_name', 'tagline', 'description'],
    'curriculum_pages' => ['eyebrow', 'title', 'intro', 'seo_title', 'seo_description', 'body_html', 'badges', 'topics'],
    'settings'         => ['svalue'],
    'pages'            => ['title', 'body_html', 'seo_description', 'blocks'],
    'content_blocks'   => ['value'],
    'posts'            => ['title', 'kicker', 'excerpt', 'body_html', 'seo_title', 'seo_description'],
];

$total = 0;
foreach ($targets as $table => $cols) {
    foreach ($cols as $col) {
        // REPLACE nested so all label variants are handled in one pass.
        // "IB Diploma" is normalised first so the later "IB DP" pass can't touch it.
        $sql = "UPDATE {$table}
                   SET {$col} = REPLACE(REPLACE(REPLACE({$col}, 'IB Diploma', 'IBDP'), 'IB DP', 'IBDP'), 'IB MYP', 'IBMYP')
                 WHERE {$col} LIKE '%IB DP%' OR {$col} LIKE '%IB MYP%' OR {$col} LIKE '%IB Diploma%'";
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
