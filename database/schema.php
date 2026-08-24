<?php
/**
 * Single source of truth for the database structure of BOTH databases.
 * Returns table definitions in a compact, driver-agnostic DSL that the
 * migrator compiles to MySQL (Hostinger) or SQLite (local dev).
 *
 * Column DSL: 'name TYPE [modifiers]'
 *   Types:  pk | int | bigint | bool | string(n) | text | longtext |
 *           decimal(p,s) | date | datetime | json
 *   Mods:   null | notnull | default:VALUE | unique
 *
 * 'connection' selects which database the table belongs to: 'web' | 'admin'.
 */

return [
    // ================= ADMIN / AUTH DATABASE (maytrix_admin) =================
    'admins' => [
        'connection' => 'admin',
        'columns' => [
            'id pk',
            'name string(120) notnull',
            'email string(190) notnull unique',
            'password_hash string(255) notnull',
            'role string(30) notnull default:admin',   // admin | editor
            'is_active bool notnull default:1',
            'last_login_at datetime null',
            'created_at datetime null',
        ],
    ],
    'audit_log' => [
        'connection' => 'admin',
        'columns' => [
            'id pk',
            'admin_id int null',
            'action string(60) notnull',      // create | update | delete | login | logout
            'entity string(60) null',
            'entity_id int null',
            'meta text null',
            'ip string(45) null',
            'created_at datetime null',
        ],
        'indexes' => [['admin_id'], ['created_at']],
    ],
    // Brute-force throttling for the admin login.
    'login_attempts' => [
        'connection' => 'admin',
        'columns' => [
            'id pk',
            'identifier string(190) notnull unique', // ip|email
            'attempts int notnull default:0',
            'locked_until datetime null',
            'updated_at datetime null',
        ],
    ],

    // ================= CONTENT / WEBSITE DATABASE (maytrix_web) ===============
    'curricula' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'code string(30) notnull unique',   // ib_dp | ib_myp | igcse | alevel
            'name string(120) notnull',
            'short_name string(60) null',
            'tagline string(190) null',
            'description text null',
            'sort_order int notnull default:0',
            'is_active bool notnull default:1',
            'created_at datetime null',
        ],
    ],
    'subjects' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'code string(30) notnull unique',   // math | physics
            'name string(120) notnull',
            'description text null',
            'sort_order int notnull default:0',
            'is_active bool notnull default:1',
            'created_at datetime null',
        ],
    ],
    'modes' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'code string(30) notnull unique',   // online | offline | hybrid
            'name string(80) notnull',
            'description string(255) null',
            'sort_order int notnull default:0',
            'is_active bool notnull default:1',
        ],
    ],
    'topics' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'subject_id int null',
            'curriculum_id int null',
            'key_label string(60) null',        // e.g. CORE, A LEVEL / IB
            'title string(160) notnull',
            'description text null',
            'sort_order int notnull default:0',
            'is_active bool notnull default:1',
            'created_at datetime null',
        ],
        'indexes' => [['subject_id'], ['curriculum_id']],
    ],
    // The 8 dedicated curriculum-subject landing pages.
    'curriculum_pages' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'curriculum_id int notnull',
            'subject_id int notnull',
            'slug string(160) notnull unique',
            'eyebrow string(160) null',
            'title string(190) notnull',
            'badges json null',                 // ["AA HL","AA SL",...]
            'intro text null',
            'topics json null',                 // [["Title","Desc"],...]
            'body_html longtext null',
            'seo_title string(190) null',
            'seo_description string(300) null',
            'seo_keywords string(255) null',
            'og_image string(255) null',
            'is_published bool notnull default:1',
            'sort_order int notnull default:0',
            'created_at datetime null',
            'updated_at datetime null',
        ],
        'indexes' => [['curriculum_id'], ['subject_id'], ['is_published']],
    ],
    // A "course" = curriculum × subject × class-type template.
    'courses' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'curriculum_id int notnull',
            'subject_id int notnull',
            'class_type string(20) notnull default:small_group', // one_to_one | small_group
            'title string(190) notnull',
            'slug string(190) notnull unique',
            'summary string(300) null',
            'description longtext null',
            'level string(120) null',
            'default_price decimal(10,2) null',
            'currency string(10) notnull default:INR',
            'is_active bool notnull default:1',
            'sort_order int notnull default:0',
            'created_at datetime null',
            'updated_at datetime null',
        ],
        'indexes' => [['curriculum_id'], ['subject_id'], ['is_active']],
    ],
    // A "batch" = a scheduled instance of a course (small-group cohort or 1-to-1 slot).
    'batches' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'course_id int notnull',
            'mode_id int null',                 // online | offline | hybrid
            'name string(190) notnull',
            'tutor_name string(120) null',
            'start_date date null',
            'end_date date null',
            'schedule_text string(190) null',   // "Tue & Thu, 6:00 PM IST"
            'duration_text string(120) null',   // "8 weeks"
            'timezone string(60) null',
            'max_seats int notnull default:6',
            'price decimal(10,2) null',
            'currency string(10) notnull default:INR',
            'mode_detail string(255) null',     // location (offline) / platform note
            'zoom_link string(500) null',
            'meeting_notes text null',
            'status string(20) notnull default:open', // draft | open | full | closed | completed
            'is_published bool notnull default:1',
            'created_at datetime null',
            'updated_at datetime null',
        ],
        'indexes' => [['course_id'], ['mode_id'], ['status'], ['is_published']],
    ],
    'students' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'name string(160) notnull',
            'email string(190) notnull unique',
            'phone string(40) null',
            'country string(80) null',
            'timezone string(60) null',
            'notes text null',
            'created_at datetime null',
        ],
    ],
    'enrolments' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'batch_id int notnull',
            'student_id int notnull',
            'status string(20) notnull default:pending',      // pending | confirmed | cancelled | waitlist
            'payment_status string(20) notnull default:unpaid', // unpaid | paid | refunded | waived
            'payment_ref string(120) null',
            'amount decimal(10,2) null',
            'currency string(10) notnull default:INR',
            'notes text null',
            'enrolled_at datetime null',
            'confirmed_at datetime null',
            'created_at datetime null',
        ],
        'indexes' => [['batch_id'], ['student_id'], ['status']],
    ],
    // 1-to-1 enquiry / consultation requests (request-based booking flow).
    'bookings' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'student_id int null',
            'name string(160) notnull',
            'email string(190) notnull',
            'phone string(40) null',
            'country string(80) null',
            'timezone string(60) null',
            'curriculum_id int null',
            'subject_id int null',
            'class_type string(20) null',       // one_to_one | small_group
            'mode_id int null',
            'preferred_contact string(30) null',// Email | WhatsApp | Phone
            'message text null',
            'status string(20) notnull default:new', // new | contacted | confirmed | closed
            'zoom_link string(500) null',
            'scheduled_at datetime null',
            'admin_notes text null',
            'created_at datetime null',
        ],
        'indexes' => [['student_id'], ['curriculum_id'], ['subject_id'], ['status']],
    ],
    // Blog / Resources.
    'posts' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'slug string(190) notnull unique',
            'title string(190) notnull',
            'kicker string(120) null',
            'excerpt string(400) null',
            'body_html longtext null',
            'cover_image string(255) null',
            'curriculum_id int null',
            'subject_id int null',
            'status string(20) notnull default:draft', // draft | published
            'seo_title string(190) null',
            'seo_description string(300) null',
            'published_at datetime null',
            'created_at datetime null',
            'updated_at datetime null',
        ],
        'indexes' => [['status'], ['curriculum_id'], ['subject_id']],
    ],
    // Generic editable pages (About, and any custom pages) + content blocks.
    'pages' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'slug string(160) notnull unique',
            'title string(190) notnull',
            'body_html longtext null',
            'blocks json null',
            'seo_title string(190) null',
            'seo_description string(300) null',
            'is_published bool notnull default:1',
            'updated_at datetime null',
        ],
    ],
    // Contact form submissions.
    'contact_messages' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'name string(160) notnull',
            'email string(190) notnull',
            'country string(80) null',
            'curriculum string(80) null',
            'message text null',
            'is_read bool notnull default:0',
            'created_at datetime null',
        ],
        'indexes' => [['is_read']],
    ],
    // Key/value global settings (contact info, hero copy, stats, gateway keys...).
    'settings' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'skey string(120) notnull unique',
            'svalue longtext null',
            'updated_at datetime null',
        ],
    ],
    // Payment records (Razorpay / Stripe / PayPal).
    'payments' => [
        'connection' => 'web',
        'columns' => [
            'id pk',
            'context string(20) notnull default:enrolment', // enrolment | booking
            'context_id int null',
            'gateway string(20) notnull',       // razorpay | stripe | paypal
            'amount decimal(10,2) notnull',
            'currency string(10) notnull default:INR',
            'status string(20) notnull default:created', // created | paid | failed | refunded
            'gateway_ref string(190) null',
            'payload text null',
            'created_at datetime null',
        ],
        'indexes' => [['context', 'context_id'], ['status']],
    ],
];
