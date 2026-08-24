-- Maytrix Education — maytrix_web schema (MySQL)
-- Generated from database/schema.php. Import via phpMyAdmin.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `curricula` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(30) NOT NULL UNIQUE,
  `name` VARCHAR(120) NOT NULL,
  `short_name` VARCHAR(60) NULL,
  `tagline` VARCHAR(190) NULL,
  `description` TEXT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `subjects` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(30) NOT NULL UNIQUE,
  `name` VARCHAR(120) NOT NULL,
  `description` TEXT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `modes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(30) NOT NULL UNIQUE,
  `name` VARCHAR(80) NOT NULL,
  `description` VARCHAR(255) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `topics` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `subject_id` INT NULL,
  `curriculum_id` INT NULL,
  `key_label` VARCHAR(60) NULL,
  `title` VARCHAR(160) NOT NULL,
  `description` TEXT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE INDEX `idx_topics_subject_id` ON `topics` (`subject_id`);
CREATE INDEX `idx_topics_curriculum_id` ON `topics` (`curriculum_id`);

CREATE TABLE IF NOT EXISTS `curriculum_pages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `curriculum_id` INT NOT NULL,
  `subject_id` INT NOT NULL,
  `slug` VARCHAR(160) NOT NULL UNIQUE,
  `eyebrow` VARCHAR(160) NULL,
  `title` VARCHAR(190) NOT NULL,
  `badges` LONGTEXT NULL,
  `intro` TEXT NULL,
  `topics` LONGTEXT NULL,
  `body_html` LONGTEXT NULL,
  `seo_title` VARCHAR(190) NULL,
  `seo_description` VARCHAR(300) NULL,
  `seo_keywords` VARCHAR(255) NULL,
  `og_image` VARCHAR(255) NULL,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE INDEX `idx_curriculum_pages_curriculum_id` ON `curriculum_pages` (`curriculum_id`);
CREATE INDEX `idx_curriculum_pages_subject_id` ON `curriculum_pages` (`subject_id`);
CREATE INDEX `idx_curriculum_pages_is_published` ON `curriculum_pages` (`is_published`);

CREATE TABLE IF NOT EXISTS `courses` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `curriculum_id` INT NOT NULL,
  `subject_id` INT NOT NULL,
  `class_type` VARCHAR(20) NOT NULL DEFAULT 'small_group',
  `title` VARCHAR(190) NOT NULL,
  `slug` VARCHAR(190) NOT NULL UNIQUE,
  `summary` VARCHAR(300) NULL,
  `description` LONGTEXT NULL,
  `level` VARCHAR(120) NULL,
  `default_price` DECIMAL(10,2) NULL,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'INR',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE INDEX `idx_courses_curriculum_id` ON `courses` (`curriculum_id`);
CREATE INDEX `idx_courses_subject_id` ON `courses` (`subject_id`);
CREATE INDEX `idx_courses_is_active` ON `courses` (`is_active`);

CREATE TABLE IF NOT EXISTS `batches` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `course_id` INT NOT NULL,
  `mode_id` INT NULL,
  `name` VARCHAR(190) NOT NULL,
  `tutor_name` VARCHAR(120) NULL,
  `start_date` DATE NULL,
  `end_date` DATE NULL,
  `schedule_text` VARCHAR(190) NULL,
  `duration_text` VARCHAR(120) NULL,
  `timezone` VARCHAR(60) NULL,
  `max_seats` INT NOT NULL DEFAULT 6,
  `price` DECIMAL(10,2) NULL,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'INR',
  `mode_detail` VARCHAR(255) NULL,
  `zoom_link` VARCHAR(500) NULL,
  `meeting_notes` TEXT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'open',
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE INDEX `idx_batches_course_id` ON `batches` (`course_id`);
CREATE INDEX `idx_batches_mode_id` ON `batches` (`mode_id`);
CREATE INDEX `idx_batches_status` ON `batches` (`status`);
CREATE INDEX `idx_batches_is_published` ON `batches` (`is_published`);

CREATE TABLE IF NOT EXISTS `students` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(160) NOT NULL,
  `email` VARCHAR(190) NOT NULL UNIQUE,
  `phone` VARCHAR(40) NULL,
  `country` VARCHAR(80) NULL,
  `timezone` VARCHAR(60) NULL,
  `notes` TEXT NULL,
  `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `enrolments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `batch_id` INT NOT NULL,
  `student_id` INT NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `payment_status` VARCHAR(20) NOT NULL DEFAULT 'unpaid',
  `payment_ref` VARCHAR(120) NULL,
  `amount` DECIMAL(10,2) NULL,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'INR',
  `notes` TEXT NULL,
  `enrolled_at` DATETIME NULL,
  `confirmed_at` DATETIME NULL,
  `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE INDEX `idx_enrolments_batch_id` ON `enrolments` (`batch_id`);
CREATE INDEX `idx_enrolments_student_id` ON `enrolments` (`student_id`);
CREATE INDEX `idx_enrolments_status` ON `enrolments` (`status`);

CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NULL,
  `name` VARCHAR(160) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `phone` VARCHAR(40) NULL,
  `country` VARCHAR(80) NULL,
  `timezone` VARCHAR(60) NULL,
  `curriculum_id` INT NULL,
  `subject_id` INT NULL,
  `class_type` VARCHAR(20) NULL,
  `mode_id` INT NULL,
  `preferred_contact` VARCHAR(30) NULL,
  `message` TEXT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'new',
  `zoom_link` VARCHAR(500) NULL,
  `scheduled_at` DATETIME NULL,
  `admin_notes` TEXT NULL,
  `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE INDEX `idx_bookings_student_id` ON `bookings` (`student_id`);
CREATE INDEX `idx_bookings_curriculum_id` ON `bookings` (`curriculum_id`);
CREATE INDEX `idx_bookings_subject_id` ON `bookings` (`subject_id`);
CREATE INDEX `idx_bookings_status` ON `bookings` (`status`);

CREATE TABLE IF NOT EXISTS `posts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(190) NOT NULL UNIQUE,
  `title` VARCHAR(190) NOT NULL,
  `kicker` VARCHAR(120) NULL,
  `excerpt` VARCHAR(400) NULL,
  `body_html` LONGTEXT NULL,
  `cover_image` VARCHAR(255) NULL,
  `curriculum_id` INT NULL,
  `subject_id` INT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'draft',
  `seo_title` VARCHAR(190) NULL,
  `seo_description` VARCHAR(300) NULL,
  `published_at` DATETIME NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE INDEX `idx_posts_status` ON `posts` (`status`);
CREATE INDEX `idx_posts_curriculum_id` ON `posts` (`curriculum_id`);
CREATE INDEX `idx_posts_subject_id` ON `posts` (`subject_id`);

CREATE TABLE IF NOT EXISTS `pages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(160) NOT NULL UNIQUE,
  `title` VARCHAR(190) NOT NULL,
  `body_html` LONGTEXT NULL,
  `blocks` LONGTEXT NULL,
  `seo_title` VARCHAR(190) NULL,
  `seo_description` VARCHAR(300) NULL,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(160) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `country` VARCHAR(80) NULL,
  `curriculum` VARCHAR(80) NULL,
  `message` TEXT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE INDEX `idx_contact_messages_is_read` ON `contact_messages` (`is_read`);

CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `skey` VARCHAR(120) NOT NULL UNIQUE,
  `svalue` LONGTEXT NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `context` VARCHAR(20) NOT NULL DEFAULT 'enrolment',
  `context_id` INT NULL,
  `gateway` VARCHAR(20) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'INR',
  `status` VARCHAR(20) NOT NULL DEFAULT 'created',
  `gateway_ref` VARCHAR(190) NULL,
  `payload` TEXT NULL,
  `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE INDEX `idx_payments_context_context_id` ON `payments` (`context`, `context_id`);
CREATE INDEX `idx_payments_status` ON `payments` (`status`);

SET FOREIGN_KEY_CHECKS = 1;
