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
            // Graphs (visual concept section)
            'home.worked.eyebrow'   => ['label' => 'Graphs — eyebrow', 'type' => 'text', 'default' => 'Concepts, visualised'],
            'home.worked.title'     => ['label' => 'Graphs — heading', 'type' => 'text', 'default' => 'Beautiful Maths & Physics, made visual'],
            'home.worked.intro'     => ['label' => 'Graphs — intro', 'type' => 'textarea', 'default' => 'From quadratic curves to wave motion, we teach the intuition behind the graphs — not just the formulas. Switch between a Maths and a Physics example.'],
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
            // Meet your tutor
            'about.tutor.eyebrow' => ['label' => 'Meet-your-tutor — eyebrow', 'type' => 'text', 'default' => 'Meet your tutor'],
            'about.tutor.title'   => ['label' => 'Meet-your-tutor — heading', 'type' => 'text', 'default' => 'Taught by specialists, not generalists'],
            'about.tutor.name'    => ['label' => 'Tutor — name', 'type' => 'text', 'default' => 'Maytrix Lead Tutor'],
            'about.tutor.role'    => ['label' => 'Tutor — role / credentials', 'type' => 'text', 'default' => 'Mathematics & Physics · IBDP · IBMYP · IGCSE · AS & A Level'],
            'about.tutor.bio'     => ['label' => 'Tutor — bio', 'type' => 'textarea', 'default' => 'Our lead tutors are subject specialists who teach Mathematics and Physics to international exam boards every week — with years of experience guiding students through IBDP, IBMYP, Cambridge IGCSE and AS & A Level to top grades. Every lesson is built around the exact mark scheme a student is sitting.'],
        ],
    ],

    'faq' => [
        'label' => 'Home FAQ',
        'blocks' => [
            'faq.eyebrow' => ['label' => 'FAQ — eyebrow', 'type' => 'text', 'default' => 'Questions & answers'],
            'faq.title'   => ['label' => 'FAQ — heading', 'type' => 'text', 'default' => 'Frequently asked questions'],
            'faq.q1' => ['label' => 'Q1 — question', 'type' => 'text', 'default' => 'How do payments and fees work?'],
            'faq.a1' => ['label' => 'Q1 — answer', 'type' => 'textarea', 'default' => 'Fees are quoted per course or per month depending on the plan, and confirmed before your first paid class. We accept secure online payment and share a clear invoice for every payment — there are no hidden charges.'],
            'faq.q2' => ['label' => 'Q2 — question', 'type' => 'text', 'default' => 'Is the first consultation really free?'],
            'faq.a2' => ['label' => 'Q2 — answer', 'type' => 'textarea', 'default' => 'Yes. Your first consultation is completely free. We assess the syllabus, the current gap and the target grade, then recommend the right 1-to-1 or small-group plan — with no obligation to continue.'],
            'faq.q3' => ['label' => 'Q3 — question', 'type' => 'text', 'default' => 'Which subjects and levels do you teach?'],
            'faq.a3' => ['label' => 'Q3 — answer', 'type' => 'textarea', 'default' => 'We specialise in Mathematics and Physics only, across four boards — IBDP (HL & SL), IBMYP Year 4 & 5, Cambridge IGCSE and Cambridge AS & A Level — taught to each board’s exact syllabus and mark scheme.'],
            'faq.q4' => ['label' => 'Q4 — question', 'type' => 'text', 'default' => 'Are classes 1-to-1 or in groups?'],
            'faq.a4' => ['label' => 'Q4 — answer', 'type' => 'textarea', 'default' => 'Both. Choose focused 1-to-1 lessons at your own pace, or a small-group class capped at 6 students matched by curriculum and level. You can switch formats as your needs change.'],
            'faq.q5' => ['label' => 'Q5 — question', 'type' => 'text', 'default' => 'How and where are classes held?'],
            'faq.a5' => ['label' => 'Q5 — answer', 'type' => 'textarea', 'default' => 'Classes run live on Zoom with a shared whiteboard, and worked solutions, notes and recordings are shared after each session. Timings are scheduled around your own time zone — India, the Gulf, the UK and Europe.'],
            'faq.q6' => ['label' => 'Q6 — question', 'type' => 'text', 'default' => 'What makes Maytrix Education different?'],
            'faq.a6' => ['label' => 'Q6 — answer', 'type' => 'textarea', 'default' => 'We do two subjects, deeply. Every tutor teaches inside their own exam board every week, so lessons focus on the command words, technique and past-paper practice that actually earn marks — not generic tutoring.'],
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
            'pages.contact.intro'     => ['label' => 'Contact — intro', 'type' => 'textarea', 'default' => "Send us a message and we'll reply within one business day."],

            'pages.book.eyebrow'      => ['label' => 'Book — eyebrow', 'type' => 'text', 'default' => 'Book a Consultation'],
            'pages.book.title'        => ['label' => 'Book — heading', 'type' => 'text', 'default' => "Let's find the right class for you"],
            'pages.book.intro'        => ['label' => 'Book — intro', 'type' => 'textarea', 'default' => 'A quick 3-step form — subject, level, then your details. Takes under a minute.'],
        ],
    ],
];
