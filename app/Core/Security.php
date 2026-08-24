<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Application-level security headers (defence-in-depth — works even if the
 * web server's mod_headers is unavailable). Includes a strict Content-Security
 * -Policy with a per-request nonce for the one inline (analytics) script.
 */
final class Security
{
    private static ?string $nonce = null;

    /** Per-request CSP nonce for whitelisted inline scripts. */
    public static function nonce(): string
    {
        if (self::$nonce === null) {
            self::$nonce = base64_encode(random_bytes(16));
        }
        return self::$nonce;
    }

    /**
     * Emit security headers. $context = 'site' (public) | 'admin' (dashboard).
     * Must be called before any output.
     */
    public static function applyHeaders(string $context = 'site'): void
    {
        if (headers_sent()) {
            return;
        }
        $nonce = self::nonce();
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
              || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

        // ---- Content-Security-Policy ----
        $fontCss = 'https://fonts.googleapis.com';
        $fontFiles = 'https://fonts.gstatic.com';

        $policy = [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'none'",           // clickjacking protection
            "form-action 'self'",
            "img-src 'self' data:",
            "style-src 'self' 'unsafe-inline' {$fontCss}", // inline style="" attrs
            "font-src 'self' {$fontFiles}",
        ];

        if ($context === 'admin') {
            // Dashboard: no external scripts at all.
            $policy[] = "script-src 'self'";
            $policy[] = "connect-src 'self'";
        } else {
            // Public site: allow Google Analytics + nonce'd inline GA config.
            $ga = 'https://www.googletagmanager.com https://www.google-analytics.com';
            $policy[] = "script-src 'self' 'nonce-{$nonce}' {$ga}";
            $policy[] = "connect-src 'self' {$ga}";
        }
        if ($https) {
            $policy[] = 'upgrade-insecure-requests';
        }
        header('Content-Security-Policy: ' . implode('; ', $policy));

        // ---- Other hardening headers ----
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: ' . ($context === 'admin' ? 'DENY' : 'SAMEORIGIN'));
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('X-XSS-Protection: 0'); // modern guidance: rely on CSP, disable legacy auditor
        header('Cross-Origin-Opener-Policy: same-origin');
        header('Permissions-Policy: geolocation=(), camera=(), microphone=(), payment=(), usb=(), interest-cohort=()');
        header_remove('X-Powered-By');

        if ($https) {
            // 1 year HSTS. Only sent over HTTPS so http visitors are unaffected.
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
        if ($context === 'admin') {
            header('X-Robots-Tag: noindex, nofollow');
        }
    }
}
