<?php
/**
 * Seed data for Maytrix Education.
 * Idempotent: skips tables that already contain rows.
 * Can be run standalone:  php database/migrate.php --seed
 *
 * Content (curricula, subjects, the 8 curriculum pages, topics, sample
 * batches and blog posts) is derived from the approved MVP prototype.
 */

declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
    require BASE_PATH . '/app/bootstrap.php';
}

use App\Core\Database;

$web = Database::web();
$admin = Database::admin();

/** Insert helper returning last id. */
$insert = function (\PDO $pdo, string $table, array $data): int {
    $cols = array_keys($data);
    $ph = array_fill(0, count($cols), '?');
    $sql = "INSERT INTO {$table} (" . implode(',', $cols) . ') VALUES (' . implode(',', $ph) . ')';
    $pdo->prepare($sql)->execute(array_values($data));
    return (int) $pdo->lastInsertId();
};
$isEmpty = function (\PDO $pdo, string $table): bool {
    return (int) $pdo->query("SELECT COUNT(*) c FROM {$table}")->fetch()['c'] === 0;
};
$ts = now();

/* ----------------------------- ADMIN USER ----------------------------- */
if ($isEmpty($admin, 'admins')) {
    $pwd = getenv('ADMIN_SEED_PASSWORD') ?: 'Maytrix@2026';
    $insert($admin, 'admins', [
        'name' => 'Maytrix Admin',
        'email' => 'admin@maytrixeducation.com',
        'password_hash' => password_hash($pwd, PASSWORD_DEFAULT),
        'role' => 'admin',
        'is_active' => 1,
        'created_at' => $ts,
    ]);
    echo "  ✓ admin user  (admin@maytrixeducation.com / {$pwd})\n";
}

/* ----------------------------- CURRICULA ------------------------------ */
$curriculumIds = [];
if ($isEmpty($web, 'curricula')) {
    // Order is intentional (matches the client's curricula spec):
    // IBDP → IBMYP 4 & 5 → AS & A Level → IGCSE.
    $curricula = [
        ['ib_dp',  'International Baccalaureate',   'IBDP',   'Higher & Standard Level Mathematics — Analysis & Approaches (AA) and Applications & Interpretation (AI) — and Physics, across both years of the Diploma.'],
        ['ib_myp', 'IB Middle Years Programme',     'IBMYP',  'MYP Year 4 & 5 Mathematics (Standard & Extended) and Physics.'],
        ['alevel', 'Cambridge AS & A Level',        'A Level', 'Cambridge International AS & A Level Mathematics (9709) & Further Mathematics (9231), and Physics (9702).'],
        ['igcse',  'Cambridge IGCSE',               'IGCSE',   'Cambridge IGCSE Mathematics (0580 Extended, 0606 Additional, 0607 International) and Physics (0625).'],
    ];
    $i = 0;
    foreach ($curricula as [$code, $name, $short, $desc]) {
        $curriculumIds[$code] = $insert($web, 'curricula', [
            'code' => $code, 'name' => $name, 'short_name' => $short,
            'tagline' => $desc, 'description' => $desc,
            'sort_order' => $i++, 'is_active' => 1, 'created_at' => $ts,
        ]);
    }
    echo "  ✓ curricula\n";
} else {
    foreach ($web->query("SELECT id, code FROM curricula")->fetchAll() as $r) {
        $curriculumIds[$r['code']] = (int) $r['id'];
    }
}

/* ----------------------------- SUBJECTS ------------------------------- */
$subjectIds = [];
if ($isEmpty($web, 'subjects')) {
    $subjects = [
        ['math', 'Mathematics', 'Algebra, Calculus, Statistics, Vectors & Mechanics.'],
        ['physics', 'Physics', 'Mechanics, Waves & Optics, Electricity & Magnetism, Modern Physics.'],
    ];
    $i = 0;
    foreach ($subjects as [$code, $name, $desc]) {
        $subjectIds[$code] = $insert($web, 'subjects', [
            'code' => $code, 'name' => $name, 'description' => $desc,
            'sort_order' => $i++, 'is_active' => 1, 'created_at' => $ts,
        ]);
    }
    echo "  ✓ subjects\n";
} else {
    foreach ($web->query("SELECT id, code FROM subjects")->fetchAll() as $r) {
        $subjectIds[$r['code']] = (int) $r['id'];
    }
}

/* ------------------------------- MODES -------------------------------- */
$modeIds = [];
if ($isEmpty($web, 'modes')) {
    $modes = [
        ['online',  'Online (Zoom)',   'Live classes on Zoom — link shared with enrolled students.'],
        ['offline', 'Offline (In-person)', 'In-person classes at the scheduled location.'],
        ['hybrid',  'Hybrid',          'A mix of online and in-person sessions.'],
    ];
    $i = 0;
    foreach ($modes as [$code, $name, $desc]) {
        $modeIds[$code] = $insert($web, 'modes', [
            'code' => $code, 'name' => $name, 'description' => $desc,
            'sort_order' => $i++, 'is_active' => 1,
        ]);
    }
    echo "  ✓ modes\n";
} else {
    foreach ($web->query("SELECT id, code FROM modes")->fetchAll() as $r) {
        $modeIds[$r['code']] = (int) $r['id'];
    }
}

/* -------------------------- CURRICULUM PAGES -------------------------- */
if ($isEmpty($web, 'curriculum_pages')) {
    $pages = [
        ['curric-ibdp-math', 'ib_dp', 'math', 'IB Diploma · Mathematics', 'IB / IBDP Mathematics',
            ['AA HL', 'AA SL', 'AI HL', 'AI SL'],
            "Both IB Diploma Mathematics routes, at both levels — Analysis & Approaches (AA) for students headed toward pure maths, engineering or physical sciences, and Applications & Interpretation (AI) for students who want maths grounded in modelling and real-world data.",
            [
                ['Functions & Algebra', 'Core techniques through to AA-specific proof and AI-specific modelling approaches.'],
                ['Calculus', 'Differentiation and integration, taught to the depth each route and level actually requires.'],
                ['Statistics & Probability', "Distributions and inference, with AI's heavier data-analysis emphasis covered in full."],
                ['Internal Assessment (IA)', 'One-to-one guidance on topic selection, exploration structure and the analytical write-up IB examiners expect.'],
            ]],
        ['curric-ibdp-physics', 'ib_dp', 'physics', 'IB Diploma · Physics', 'IB / IBDP Physics',
            ['HL', 'SL'],
            "IB Diploma Physics at Higher and Standard Level, built around the IB's data-based and theory papers, practical scheme of work, and the analytical command terms examiners look for in every answer.",
            [
                ['Mechanics & Thermal Physics', "Core HL/SL content, with HL's extended mechanics depth covered separately."],
                ['Waves & Electricity', 'Wave behaviour, circuits and fields, mapped to Paper 1 and Paper 2 style questions.'],
                ['Quantum & Nuclear Physics', 'HL-only content taught as its own strand, not bolted onto SL material.'],
                ['Internal Assessment (IA)', 'Support with experiment design, data processing and the evaluation section specifically.'],
            ]],
        ['curric-ibmyp-math', 'ib_myp', 'math', 'IBMYP · Mathematics', 'IBMYP Mathematics',
            ['Standard', 'Extended'],
            "MYP Year 4 & 5 Mathematics at both Standard and Extended levels, focused on the criterion-based assessment style (Criteria A–D) and the conceptual, statement-of-inquiry thinking that sets students up for a smooth transition into the Diploma Programme.",
            [
                ['Number & Algebra', 'Building fluency and the reasoning habits MYP criteria specifically reward.'],
                ['Geometry & Trigonometry', 'Spatial reasoning and proof, scaffolded toward DP-level rigour.'],
                ['Statistics & Probability', "Investigation-style tasks matching MYP's criterion-based structure."],
                ['Criteria-Based Assessment', 'Direct coaching on Criteria A–D so students know exactly what each mark reflects.'],
            ]],
        ['curric-ibmyp-physics', 'ib_myp', 'physics', 'IBMYP · Physics', 'IBMYP Physics',
            ['MYP 4 & 5'],
            "MYP Physics (Sciences), building the inquiry-based scientific thinking and criterion-based assessment fluency that MYP demands, while laying the conceptual groundwork the DP Physics course will build directly on.",
            [
                ['Forces & Energy', "Core mechanics concepts introduced with MYP's inquiry-cycle approach."],
                ['Matter & Interactions', 'Particle models and interactions, building toward DP-level detail.'],
                ['Scientific Investigation', 'Criterion-based lab write-ups — hypothesis, method, analysis, evaluation.'],
                ['DP Readiness', 'A light bridge into IBDP Physics command terms and paper structure.'],
            ]],
        ['curric-igcse-math', 'igcse', 'math', 'Cambridge IGCSE · Mathematics', 'Cambridge IGCSE Mathematics',
            ['Extended (0580)', 'Additional (0606)', 'International (0607)'],
            "Cambridge IGCSE Mathematics across all three routes we teach — Extended (0580), Additional (0606) and International (0607) — with steady Paper 1/Paper 2 style practice built in from Year 10 onward.",
            [
                ['Number & Algebra', "Core techniques built to Cambridge's specific question style, not a generic syllabus."],
                ['Geometry & Mensuration', 'Shape, space and measures, with calculator and non-calculator technique both covered.'],
                ['Statistics & Probability', "Data handling mapped directly to Cambridge's past-paper patterns."],
                ['Exam Technique', 'Regular past-paper drilling with board-specific mark-scheme feedback.'],
            ]],
        ['curric-igcse-physics', 'igcse', 'physics', 'Cambridge IGCSE · Physics', 'Cambridge IGCSE Physics',
            ['Physics (0625)'],
            "Cambridge IGCSE Physics (0625) across the full syllabus, building strong practical and conceptual foundations that carry directly into AS & A Level or the IB Diploma.",
            [
                ['Forces & Motion', "Core mechanics, built for Cambridge's specific practical-paper expectations."],
                ['Waves, Light & Sound', 'Core wave behaviour topics with plenty of diagram-based practice.'],
                ['Electricity & Magnetism', "Circuits and fields, taught to Cambridge's exact command-word style."],
                ['Practical & Exam Technique', "Past-paper drilling plus guidance on Cambridge's practical-skills paper."],
            ]],
        ['curric-alevel-math', 'alevel', 'math', 'Cambridge AS & A Level · Mathematics', 'Cambridge AS & A Level Mathematics',
            ['Mathematics (9709)', 'Further Mathematics (9231)'],
            "Cambridge International AS & A Level Mathematics (9709), taught unit by unit — Pure Mathematics, Mechanics, and Probability & Statistics — plus Further Mathematics (9231) for students taking the full further route, to the exact paper structure the Cambridge board sets.",
            [
                ['Pure Mathematics 1 & 2', 'Algebra, calculus and trigonometry, paced toward AS milestones first.'],
                ['Mechanics', 'Kinematics, forces and momentum, built for the Mechanics paper specifically.'],
                ['Probability & Statistics', 'Distributions and hypothesis testing, mapped to the S1/S2 paper style.'],
                ['Full A Level Drilling', 'Past-paper practice once all AS units are secure, building toward the full A Level.'],
            ]],
        ['curric-alevel-physics', 'alevel', 'physics', 'Cambridge AS & A Level · Physics', 'Cambridge AS & A Level Physics',
            ['Physics (9702)'],
            "The full Cambridge International AS & A Level Physics (9702) paper set, with unit-by-unit pacing toward AS milestones, then complete past-paper drilling for the full A Level.",
            [
                ['Mechanics & Matter', 'Foundational AS-level content, paced to build toward full syllabus coverage.'],
                ['Waves & Electricity', "Core AS/A Level content mapped to Cambridge's specific paper structure."],
                ['Fields & Modern Physics', 'A Level-only content, covered once AS foundations are secure.'],
                ['Practical & Exam Technique', "Past-paper drilling plus Cambridge's practical-skills paper guidance."],
            ]],
    ];
    $i = 0;
    foreach ($pages as [$slug, $cCode, $sCode, $eyebrow, $title, $badges, $intro, $topics]) {
        $insert($web, 'curriculum_pages', [
            'curriculum_id' => $curriculumIds[$cCode],
            'subject_id' => $subjectIds[$sCode],
            'slug' => $slug,
            'eyebrow' => $eyebrow,
            'title' => $title,
            'badges' => json_encode($badges, JSON_UNESCAPED_UNICODE),
            'intro' => $intro,
            'topics' => json_encode($topics, JSON_UNESCAPED_UNICODE),
            'seo_title' => $title . ' Tutoring | Maytrix Education',
            'seo_description' => seo_excerpt($intro, 160),
            'is_published' => 1,
            'sort_order' => $i++,
            'created_at' => $ts,
            'updated_at' => $ts,
        ]);
    }
    echo "  ✓ curriculum pages (8)\n";
}

/* ------------------------------- TOPICS ------------------------------- */
if ($isEmpty($web, 'topics')) {
    $topics = [
        ['math', 'CORE', 'Algebra & Functions', 'Equations, sequences, polynomials, transformations of graphs.'],
        ['math', 'CORE', 'Calculus', 'Differentiation, integration, and their applications — from first principles through to optimisation problems.'],
        ['math', 'CORE', 'Statistics & Probability', "Distributions, hypothesis testing and data handling, mapped to each board's data-response style."],
        ['math', 'A LEVEL / IB', 'Vectors & Mechanics', 'Kinematics, forces and vectors — as required by A Level Mechanics units and IB Applications & Interpretation.'],
        ['physics', 'CORE', 'Mechanics', 'Forces, motion, energy and momentum — the foundation paper across all four curricula.'],
        ['physics', 'CORE', 'Waves & Optics', 'Wave behaviour, sound and light, interference and diffraction.'],
        ['physics', 'CORE', 'Electricity & Magnetism', 'Circuits, fields and electromagnetic induction, with board-specific practical technique.'],
        ['physics', 'A LEVEL / IB HL', 'Modern Physics', 'Quantum, nuclear and particle physics — as required at A Level and IB Higher Level.'],
    ];
    $i = 0;
    foreach ($topics as [$sCode, $key, $title, $desc]) {
        $insert($web, 'topics', [
            'subject_id' => $subjectIds[$sCode],
            'key_label' => $key, 'title' => $title, 'description' => $desc,
            'sort_order' => $i++, 'is_active' => 1, 'created_at' => $ts,
        ]);
    }
    echo "  ✓ topics\n";
}

/* --------------------------- COURSES + BATCHES ------------------------ */
if ($isEmpty($web, 'courses')) {
    // A few representative courses (curriculum × subject × type).
    $courseDefs = [
        ['ib_dp', 'math', 'small_group', 'IB HL Mathematics AA — Calculus Focus'],
        ['igcse', 'physics', 'small_group', 'Cambridge IGCSE Physics — Foundations'],
        ['alevel', 'math', 'small_group', 'A Level Mathematics — Mechanics Unit'],
        ['ib_dp', 'physics', 'one_to_one', 'IB Physics HL — 1-to-1'],
    ];
    $courseIds = [];
    foreach ($courseDefs as $idx => [$cCode, $sCode, $type, $title]) {
        $courseIds[$idx] = $insert($web, 'courses', [
            'curriculum_id' => $curriculumIds[$cCode],
            'subject_id' => $subjectIds[$sCode],
            'class_type' => $type,
            'title' => $title,
            'slug' => str_slug($title) . '-' . ($idx + 1),
            'summary' => $title,
            'currency' => 'INR',
            'is_active' => 1, 'sort_order' => $idx,
            'created_at' => $ts, 'updated_at' => $ts,
        ]);
    }

    // Sample small-group batches (mirror the prototype listings).
    $batchDefs = [
        [0, 'online', 'IB HL Mathematics AA — Calculus Focus', 'Priya M.', '2026-09-02', 'Tue & Thu, 6:00 PM IST', '8 weeks', 6, 25000, 3],
        [1, 'online', 'Cambridge IGCSE Physics — Foundations', 'Daniel K.', '2026-09-09', 'Mon & Wed, 5:00 PM GST', '10 weeks', 6, 22000, 1],
        [2, 'offline', 'A Level Mathematics — Mechanics Unit', 'Aisha R.', '2026-09-16', 'Sat, 11:00 AM GMT', '6 weeks', 6, 18000, 5],
    ];
    $batchIds = [];
    foreach ($batchDefs as [$ci, $mode, $name, $tutor, $start, $sched, $dur, $seats, $price, $confirmedCount]) {
        $batchIds[] = [
            'id' => $insert($web, 'batches', [
                'course_id' => $courseIds[$ci],
                'mode_id' => $modeIds[$mode],
                'name' => $name, 'tutor_name' => $tutor,
                'start_date' => $start, 'schedule_text' => $sched, 'duration_text' => $dur,
                'max_seats' => $seats, 'price' => $price, 'currency' => 'INR',
                'status' => 'open', 'is_published' => 1,
                'created_at' => $ts, 'updated_at' => $ts,
            ]),
            'confirmed' => $confirmedCount,
        ];
    }
    echo "  ✓ courses + batches\n";

    /* --------- STUDENTS + ENROLMENTS (drive live seat counts) --------- */
    if ($isEmpty($web, 'students')) {
        $sampleStudents = [
            ['Aarav Sharma', 'aarav@example.com', 'India', 'IST'],
            ['Layla Ahmed', 'layla@example.com', 'UAE', 'GST'],
            ['Oliver Smith', 'oliver@example.com', 'UK', 'GMT'],
            ['Sofia Rossi', 'sofia@example.com', 'Italy', 'CET'],
            ['Meera Nair', 'meera@example.com', 'India', 'IST'],
            ['Hassan Ali', 'hassan@example.com', 'UAE', 'GST'],
            ['Emma Brown', 'emma@example.com', 'UK', 'GMT'],
            ['Noah Wilson', 'noah@example.com', 'UK', 'GMT'],
            ['Zara Khan', 'zara@example.com', 'UAE', 'GST'],
        ];
        $studentIds = [];
        foreach ($sampleStudents as [$n, $em, $ct, $tz]) {
            $studentIds[] = $insert($web, 'students', [
                'name' => $n, 'email' => $em, 'country' => $ct, 'timezone' => $tz, 'created_at' => $ts,
            ]);
        }
        // Confirm N enrolments per batch to match the seat bars.
        $sIdx = 0;
        foreach ($batchIds as $b) {
            for ($k = 0; $k < $b['confirmed']; $k++) {
                $studentId = $studentIds[$sIdx % count($studentIds)];
                $sIdx++;
                $insert($web, 'enrolments', [
                    'batch_id' => $b['id'], 'student_id' => $studentId,
                    'status' => 'confirmed', 'payment_status' => 'paid',
                    'currency' => 'INR', 'enrolled_at' => $ts, 'confirmed_at' => $ts, 'created_at' => $ts,
                ]);
            }
        }
        echo "  ✓ students + enrolments\n";
    }
}

/* -------------------------------- POSTS ------------------------------- */
if ($isEmpty($web, 'posts')) {
    $posts = [
        ['5 common mistakes in IB Math AA Paper 1', 'IB · Mathematics AA',
            'Where students lose easy marks on the non-calculator paper — and how to fix each one before your next mock.'],
        ['How Cambridge A Level Physics grade boundaries actually work', 'A Level · Physics',
            'A practical look at raw marks vs. UMS, and what that means for revision priorities.'],
        ['IGCSE to A Level: what actually changes in Mathematics', 'IGCSE → A Level',
            'The jump students underestimate most, and how to prepare for it over the summer.'],
    ];
    foreach ($posts as $idx => [$title, $kicker, $excerpt]) {
        $insert($web, 'posts', [
            'slug' => str_slug($title),
            'title' => $title, 'kicker' => $kicker, 'excerpt' => $excerpt,
            'body_html' => '<p>' . e($excerpt) . '</p><p>Full article coming soon.</p>',
            'status' => 'published', 'published_at' => $ts,
            'seo_title' => $title . ' | Maytrix Education', 'seo_description' => $excerpt,
            'created_at' => $ts, 'updated_at' => $ts,
        ]);
    }
    echo "  ✓ posts\n";
}

/* -------------------------------- PAGES ------------------------------- */
if ($isEmpty($web, 'pages')) {
    $about = <<<HTML
<p>Maytrix Education was founded to solve one specific problem: students following IB, IBMYP, Cambridge IGCSE or Cambridge AS & A Level, living outside the countries those syllabuses were written for, struggling to find tutors who actually teach to the exact mark scheme they'll sit.</p>
<p>We specialise deliberately narrow — Mathematics and Physics only, across four curricula — so every tutor on our panel teaches inside their own syllabus, every week, not "maths in general."</p>
<p>Today we work with students across India, the Gulf, the UK and Europe, over 1-to-1 sessions and small-group classes run live on Zoom, with every class timed around the student's own time zone.</p>
HTML;
    $insert($web, 'pages', [
        'slug' => 'about', 'title' => 'About Maytrix', 'body_html' => $about,
        'seo_title' => 'About Maytrix Education',
        'seo_description' => 'Tutoring built for students learning IB, IBMYP, Cambridge IGCSE and A Level Mathematics & Physics across borders.',
        'is_published' => 1, 'updated_at' => $ts,
    ]);
    echo "  ✓ pages\n";
}

/* ------------------------------ SETTINGS ------------------------------ */
if ($isEmpty($web, 'settings')) {
    $settings = [
        'brand_name' => 'Maytrix Education',
        'contact_email' => 'hello@maytrixeducation.com',
        'contact_whatsapp' => '+91 00000 00000',
        'hero_eyebrow' => 'International Tutoring · IB · IBMYP · IGCSE · A Level',
        'hero_title' => 'Mathematics and Physics, taught the way top scorers actually learn them.',
        'hero_lede' => '1-to-1 and small-group online classes for IB, IBMYP, Cambridge IGCSE and Cambridge AS & A Level students across India, the Gulf, the UK and Europe — every concept broken down, worked step by step, until it clicks.',
        'stat_students' => '500+',
        'stat_countries' => '12',
        'stat_grades' => '+1.8',
        'stat_curricula' => '4',
        'footer_tagline' => 'International 1-to-1 and small-group online tutoring for IB, IBMYP, Cambridge IGCSE and Cambridge AS & A Level — Mathematics & Physics.',
    ];
    foreach ($settings as $k => $v) {
        $insert($web, 'settings', ['skey' => $k, 'svalue' => $v, 'updated_at' => $ts]);
    }
    echo "  ✓ settings\n";
}

echo "Seeding complete.\n";
