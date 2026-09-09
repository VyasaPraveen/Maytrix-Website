<?php
/**
 * One-off, idempotent content update: applies the client's approved curricula
 * order and course-structure (syllabus variants + Cambridge codes) to an
 * EXISTING database. seed.php only runs on empty tables, so this script exists
 * to bring already-seeded local/production databases up to date.
 *
 * Safe to run multiple times. CLI usage:
 *   php database/update_curricula.php
 */

declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
    require BASE_PATH . '/app/bootstrap.php';
}

use App\Core\Database;

$web = Database::web();

/* -------- Curricula: order + syllabus-accurate descriptions -------- */
// code => [sort_order, tagline/description]
$curricula = [
    'ib_dp'  => [0, 'Higher & Standard Level Mathematics — Analysis & Approaches (AA) and Applications & Interpretation (AI) — and Physics, across both years of the Diploma.'],
    'ib_myp' => [1, 'MYP Year 4 & 5 Mathematics (Standard & Extended) and Physics.'],
    'alevel' => [2, 'Cambridge International AS & A Level Mathematics (9709) & Further Mathematics (9231), and Physics (9702).'],
    'igcse'  => [3, 'Cambridge IGCSE Mathematics (0580 Extended, 0606 Additional, 0607 International) and Physics (0625).'],
];
$curStmt = $web->prepare(
    'UPDATE curricula SET sort_order = ?, tagline = ?, description = ? WHERE code = ?'
);
foreach ($curricula as $code => [$sort, $desc]) {
    $curStmt->execute([$sort, $desc, $desc, $code]);
    echo "  ✓ curriculum {$code} (order {$sort})\n";
}

/* -------- Curriculum pages: badges (+ intro where it changed) -------- */
// slug => ['badges' => [...], 'intro' => ?string]
$pages = [
    'curric-ibmyp-math' => [
        'badges' => ['Standard', 'Extended'],
        'intro'  => "MYP Year 4 & 5 Mathematics at both Standard and Extended levels, focused on the criterion-based assessment style (Criteria A–D) and the conceptual, statement-of-inquiry thinking that sets students up for a smooth transition into the Diploma Programme.",
    ],
    'curric-ibmyp-physics' => [
        'badges' => ['MYP 4 & 5'],
        'intro'  => null,
    ],
    'curric-igcse-math' => [
        'badges' => ['Extended (0580)', 'Additional (0606)', 'International (0607)'],
        'intro'  => "Cambridge IGCSE Mathematics across all three routes we teach — Extended (0580), Additional (0606) and International (0607) — with steady Paper 1/Paper 2 style practice built in from Year 10 onward.",
    ],
    'curric-igcse-physics' => [
        'badges' => ['Physics (0625)'],
        'intro'  => "Cambridge IGCSE Physics (0625) across the full syllabus, building strong practical and conceptual foundations that carry directly into AS & A Level or the IBDP.",
    ],
    'curric-alevel-math' => [
        'badges' => ['Mathematics (9709)', 'Further Mathematics (9231)'],
        'intro'  => "Cambridge International AS & A Level Mathematics (9709), taught unit by unit — Pure Mathematics, Mechanics, and Probability & Statistics — plus Further Mathematics (9231) for students taking the full further route, to the exact paper structure the Cambridge board sets.",
    ],
    'curric-alevel-physics' => [
        'badges' => ['Physics (9702)'],
        'intro'  => "The full Cambridge International AS & A Level Physics (9702) paper set, with unit-by-unit pacing toward AS milestones, then complete past-paper drilling for the full A Level.",
    ],
];
$ts = now();
$badgeOnly = $web->prepare('UPDATE curriculum_pages SET badges = ?, updated_at = ? WHERE slug = ?');
$full      = $web->prepare('UPDATE curriculum_pages SET badges = ?, intro = ?, seo_description = ?, updated_at = ? WHERE slug = ?');
foreach ($pages as $slug => $p) {
    $badges = json_encode($p['badges'], JSON_UNESCAPED_UNICODE);
    if ($p['intro'] === null) {
        $badgeOnly->execute([$badges, $ts, $slug]);
    } else {
        $full->execute([$badges, $p['intro'], seo_excerpt($p['intro'], 160), $ts, $slug]);
    }
    echo "  ✓ page {$slug}\n";
}

echo "Curricula content update complete.\n";
