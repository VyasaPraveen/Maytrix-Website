<?php

/**
 * Registry of editable front-end copy ("content blocks").
 *
 * Each block has a stable key, a human label, an input type and the DEFAULT
 * wording (the single source of truth for defaults). The admin "Page Content"
 * screen renders one form per group; only values the client changes are stored
 * in the `content_blocks` table as overrides. Views read a block via block('key')
 * which returns the override when set, otherwise the default below.
 *
 * To make a new piece of copy editable: add a block here, then swap the hard-coded
 * text in the view for <?= e(block('your.key')) ?> (or block('your.key') for HTML).
 */

return [
    'home' => [
        'label' => 'Home Page',
        'blocks' => [
            // Features
            'home.features.eyebrow' => ['label' => 'Features — eyebrow', 'type' => 'text', 'default' => 'Why families choose us'],
            'home.features.title'   => ['label' => 'Features — heading', 'type' => 'text', 'default' => 'Tutoring built around your syllabus'],
            'home.features.intro'   => ['label' => 'Features — intro', 'type' => 'textarea', 'default' => "Not general help — subject specialists who teach to the exact board, level and mark scheme you're sitting."],
            'home.feature1.title'   => ['label' => 'Feature card 1 — title', 'type' => 'text', 'default' => 'Exam-board aligned'],
            'home.feature1.body'    => ['label' => 'Feature card 1 — text', 'type' => 'textarea', 'default' => 'Every lesson maps to IB, IBMYP, IGCSE or A Level specifications — the command words, mark schemes and past-paper technique that actually earn marks.'],
            'home.feature2.title'   => ['label' => 'Feature card 2 — title', 'type' => 'text', 'default' => '1-to-1 or small group'],
            'home.feature2.body'    => ['label' => 'Feature card 2 — text', 'type' => 'textarea', 'default' => 'Choose focused private lessons at your own pace, or a small cohort of up to six students at a similar level and target grade.'],
            'home.feature3.title'   => ['label' => 'Feature card 3 — title', 'type' => 'text', 'default' => 'Live & online'],
            'home.feature3.body'    => ['label' => 'Feature card 3 — text', 'type' => 'textarea', 'default' => 'Interactive classes on Zoom with a shared whiteboard, worked past papers and recordings — join from India, the Gulf, the UK or Europe.'],
            // Results split
            'home.results.eyebrow'  => ['label' => 'Results — eyebrow', 'type' => 'text', 'default' => 'Real, measurable progress'],
            'home.results.title'    => ['label' => 'Results — heading', 'type' => 'text', 'default' => 'Grades that move — because the teaching is targeted'],
            'home.results.intro'    => ['label' => 'Results — intro', 'type' => 'textarea', 'default' => "We start by finding the exact gap between where a student is and the grade they're aiming for, then build a plan around it. No filler, no generic worksheets."],
            // Curricula
            'home.curricula.eyebrow' => ['label' => 'Curricula — eyebrow', 'type' => 'text', 'default' => 'Curricula'],
            'home.curricula.title'   => ['label' => 'Curricula — heading', 'type' => 'text', 'default' => 'Specialists in four exam boards'],
            'home.curricula.intro'   => ['label' => 'Curricula — intro', 'type' => 'textarea', 'default' => 'Dedicated Mathematics & Physics pathways for every board and level we teach.'],
            // Why
            'home.why.eyebrow'      => ['label' => 'Why-us — eyebrow', 'type' => 'text', 'default' => 'The Maytrix difference'],
            'home.why.title'        => ['label' => 'Why-us — heading', 'type' => 'text', 'default' => 'Everything set up for serious progress'],
            // Classes
            'home.classes.eyebrow'  => ['label' => 'Classes — eyebrow', 'type' => 'text', 'default' => 'Enrolling now'],
            'home.classes.title'    => ['label' => 'Classes — heading', 'type' => 'text', 'default' => 'Popular small-group classes'],
            'home.classes.intro'    => ['label' => 'Classes — intro', 'type' => 'textarea', 'default' => 'Live cohorts with limited seats — reserve a place or ask about a 1-to-1 alternative.'],
            // Steps
            'home.steps.eyebrow'    => ['label' => 'How-it-works — eyebrow', 'type' => 'text', 'default' => 'How it works'],
            'home.steps.title'      => ['label' => 'How-it-works — heading', 'type' => 'text', 'default' => 'From enquiry to your first class'],
            // Worked example
            'home.worked.eyebrow'   => ['label' => 'Worked example — eyebrow', 'type' => 'text', 'default' => 'See how we teach'],
            'home.worked.title'     => ['label' => 'Worked example — heading', 'type' => 'text', 'default' => 'Every concept, broken down step by step'],
            'home.worked.intro'     => ['label' => 'Worked example — intro', 'type' => 'textarea', 'default' => "We don't just give the answer — we show the method examiners want, one clear line at a time. Switch between a Maths and a Physics example."],
            // Testimonials
            'home.testi.eyebrow'    => ['label' => 'Testimonials — eyebrow', 'type' => 'text', 'default' => 'Parents & students'],
            'home.testi.title'      => ['label' => 'Testimonials — heading', 'type' => 'text', 'default' => 'Trusted by families around the world'],
            // Resources
            'home.resources.eyebrow' => ['label' => 'Resources — eyebrow', 'type' => 'text', 'default' => 'From our tutors'],
            'home.resources.title'   => ['label' => 'Resources — heading', 'type' => 'text', 'default' => 'Resources & exam guidance'],
            // CTA banner
            'home.cta.title'        => ['label' => 'CTA banner — heading', 'type' => 'text', 'default' => 'Ready to see where your child stands?'],
            'home.cta.body'         => ['label' => 'CTA banner — text', 'type' => 'textarea', 'default' => "Book a free 20-minute consultation. We'll assess the syllabus, pinpoint the gap, and recommend the right 1-to-1 or small-group plan."],
        ],
    ],

    'about' => [
        'label' => 'About Page',
        'blocks' => [
            'about.card1.title' => ['label' => 'Card 1 — title', 'type' => 'text', 'default' => 'Syllabus-first'],
            'about.card1.body'  => ['label' => 'Card 1 — text', 'type' => 'textarea', 'default' => 'Every tutor teaches to the specific board and paper structure a student is sitting — not a generic curriculum.'],
            'about.card2.title' => ['label' => 'Card 2 — title', 'type' => 'text', 'default' => 'Small by design'],
            'about.card2.body'  => ['label' => 'Card 2 — text', 'type' => 'textarea', 'default' => 'Group classes are capped at 6 students so every question still gets answered live.'],
            'about.card3.title' => ['label' => 'Card 3 — title', 'type' => 'text', 'default' => 'Built to grow with you'],
            'about.card3.body'  => ['label' => 'Card 3 — text', 'type' => 'textarea', 'default' => 'Starting as a focused tutoring brand, with a student portal and progress tracking planned as the next step.'],
        ],
    ],

    'pages' => [
        'label' => 'Other Page Headers',
        'blocks' => [
            'pages.curricula.eyebrow' => ['label' => 'Curricula — eyebrow', 'type' => 'text', 'default' => 'Curricula'],
            'pages.curricula.title'   => ['label' => 'Curricula — heading', 'type' => 'text', 'default' => 'IB · IBMYP · Cambridge IGCSE · Cambridge AS & A Level'],
            'pages.curricula.intro'   => ['label' => 'Curricula — intro', 'type' => 'textarea', 'default' => 'Four curricula, taught by specialists who work inside them every week. Select a curriculum to see how classes are structured, or open the dedicated page for each subject.'],

            'pages.subjects.eyebrow'  => ['label' => 'Subjects — eyebrow', 'type' => 'text', 'default' => 'Subjects'],
            'pages.subjects.title'    => ['label' => 'Subjects — heading', 'type' => 'text', 'default' => 'Mathematics & Physics'],
            'pages.subjects.intro'    => ['label' => 'Subjects — intro', 'type' => 'textarea', 'default' => 'We teach two subjects, deeply, across four curricula — rather than many subjects shallowly.'],

            'pages.oneToOne.eyebrow'  => ['label' => '1-to-1 — eyebrow', 'type' => 'text', 'default' => '1-to-1 Classes'],
            'pages.oneToOne.title'    => ['label' => '1-to-1 — heading', 'type' => 'text', 'default' => 'One tutor. One student. Your exact syllabus.'],
            'pages.oneToOne.intro'    => ['label' => '1-to-1 — intro', 'type' => 'textarea', 'default' => 'Fully personalised pacing, scheduled around your time zone — built for students who need focused, flexible support on a specific curriculum and subject. Available online (Zoom) or offline.'],

            'pages.smallGroup.eyebrow' => ['label' => 'Small-group — eyebrow', 'type' => 'text', 'default' => 'Small-Group Classes'],
            'pages.smallGroup.title'   => ['label' => 'Small-group — heading', 'type' => 'text', 'default' => 'Learn alongside students at your level'],
            'pages.smallGroup.intro'   => ['label' => 'Small-group — intro', 'type' => 'textarea', 'default' => 'Cohorts capped at 6 students, matched by curriculum and level, so pacing stays personal even in a group. Seats update live as students enrol.'],

            'pages.resources.eyebrow' => ['label' => 'Resources — eyebrow', 'type' => 'text', 'default' => 'Resources / Blog'],
            'pages.resources.title'   => ['label' => 'Resources — heading', 'type' => 'text', 'default' => 'Notes from the tutoring room'],
            'pages.resources.intro'   => ['label' => 'Resources — intro', 'type' => 'textarea', 'default' => 'Short, syllabus-specific guidance from our tutors. New articles added regularly.'],

            'pages.contact.eyebrow'   => ['label' => 'Contact — eyebrow', 'type' => 'text', 'default' => 'Contact'],
            'pages.contact.title'     => ['label' => 'Contact — heading', 'type' => 'text', 'default' => 'Talk to us'],
            'pages.contact.intro'     => ['label' => 'Contact — intro', 'type' => 'textarea', 'default' => "Have a question before booking a consultation? Send us a note and we'll reply within one business day."],

            'pages.book.eyebrow'      => ['label' => 'Book — eyebrow', 'type' => 'text', 'default' => 'Book a Consultation'],
            'pages.book.title'        => ['label' => 'Book — heading', 'type' => 'text', 'default' => "Let's find the right class for you"],
            'pages.book.intro'        => ['label' => 'Book — intro', 'type' => 'textarea', 'default' => 'A quick 3-step form — curriculum, subject & class type, then your details. Takes under a minute.'],
        ],
    ],
];
