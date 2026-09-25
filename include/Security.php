<?php
declare(strict_types=1);

/**
 * Session hardening, security headers and CSRF protection.
 */
final class Security
{
    private const SESSION_NAME = 'AMXBSESSID';

    public static function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off')
            || (int)($_SERVER['SERVER_PORT'] ?? 0) === 443
            || strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
    }

    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_trans_sid', '0');
        ini_set('session.cookie_httponly', '1');
        if (PHP_VERSION_ID < 80400) {
            ini_set('session.sid_length', '48');
            ini_set('session.sid_bits_per_character', '6');
        }

        session_name(self::SESSION_NAME);
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => self::cookiePath(),
            'secure'   => self::isHttps(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();

        // Periodically rotate the session id to limit the value of a stolen id.
        $now = time();
        if (!isset($_SESSION['_created'])) {
            $_SESSION['_created'] = $now;
        } elseif ($now - (int)$_SESSION['_created'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['_created'] = $now;
        }
    }

    /** Directory of the application as seen from the browser, e.g. "/bans/". */
    public static function cookiePath(): string
    {
        $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
        return rtrim($dir, '/') . '/';
    }

    public static function sendHeaders(): void
    {
        if (headers_sent()) {
            return;
        }
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: same-origin');
        header('Cross-Origin-Opener-Policy: same-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');
        header(
            "Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; "
            . "img-src 'self' data: https://*.steamstatic.com https://steamcdn-a.akamaihd.net; "
            . "font-src 'self'; connect-src 'self'; object-src 'none'; base-uri 'self'; "
            . "form-action 'self'; frame-ancestors 'self'"
        );
        if (self::isHttps()) {
            header('Strict-Transport-Security: max-age=15552000');
        }
    }

    // ------------------------------------------------------------------------
    // CSRF
    // ------------------------------------------------------------------------

    public static function csrfToken(): string
    {
        if (empty($_SESSION['_csrf']) || !is_string($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function csrfValid(?string $token): bool
    {
        return is_string($token) && $token !== '' && hash_equals(self::csrfToken(), $token);
    }

    /**
     * Every state-changing request in AMXBans is a POST; each one must carry the
     * token of the current session (hidden field "_token" or X-CSRF-Token header).
     */
    public static function verifyCsrfOnPost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            return;
        }
        $token = $_POST['_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
        if (!self::csrfValid(is_string($token) ? $token : null)) {
            http_response_code(419);
            header('Content-Type: text/html; charset=UTF-8');
            $back = htmlspecialchars((string)($_SERVER['REQUEST_URI'] ?? 'index.php'), ENT_QUOTES, 'UTF-8');
            exit('<!DOCTYPE html><meta charset="utf-8"><title>419</title>'
                . '<p>The form has expired or was sent from another site. '
                . '<a href="' . $back . '">Reload the page</a> and try again.</p>');
        }
    }
}
