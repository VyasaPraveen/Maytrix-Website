<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Lightweight mailer. When MAIL_ENABLED is false (or SMTP not configured),
 * emails are written to storage/logs/mail.log instead of being sent —
 * so booking/enrolment flows work end-to-end in development without SMTP.
 *
 * In production on Hostinger, set MAIL_ENABLED=true with SMTP credentials.
 * Uses PHP's mail() as the transport when enabled (Hostinger supports it);
 * swap in PHPMailer/SMTP later without changing callers.
 */
final class Mailer
{
    public static function send(string $to, string $subject, string $htmlBody, ?string $replyTo = null): bool
    {
        $fromName = (string) Config::get('mail.from_name', 'Maytrix Education');
        $fromAddr = (string) Config::get('mail.from_address', 'no-reply@example.com');

        if (!Config::get('mail.enabled', false)) {
            self::log($to, $subject, $htmlBody);
            return true; // treated as "queued" in dev
        }

        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . self::encodeName($fromName) . ' <' . $fromAddr . '>';
        if ($replyTo) {
            $headers[] = 'Reply-To: ' . $replyTo;
        }

        $ok = @mail($to, $subject, $htmlBody, implode("\r\n", $headers));
        if (!$ok) {
            log_message("Mail send failed to {$to}: {$subject}", 'mail');
        }
        self::log($to, $subject, $htmlBody, $ok ? 'SENT' : 'FAILED');
        return $ok;
    }

    /** Notify the site admin (used on new bookings/enrolments/contact). */
    public static function notifyAdmin(string $subject, string $htmlBody): bool
    {
        $admin = (string) Config::get('mail.admin_notify', '');
        if ($admin === '') {
            return false;
        }
        return self::send($admin, $subject, $htmlBody);
    }

    private static function encodeName(string $name): string
    {
        return '=?UTF-8?B?' . base64_encode($name) . '?=';
    }

    private static function log(string $to, string $subject, string $body, string $status = 'DEV'): void
    {
        $entry = str_repeat('=', 60) . "\n";
        $entry .= "[{$status}] " . now() . "\nTo: {$to}\nSubject: {$subject}\n\n";
        $entry .= strip_tags($body) . "\n";
        @file_put_contents(BASE_PATH . '/storage/logs/mail.log', $entry, FILE_APPEND);
    }
}
