<?php
declare(strict_types=1);

/**
 * Request-level security: session, HTTP headers and CSRF.
 *
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 * @see       docs/security.md
 */

/**
 * Session hardening, security headers and CSRF protection.
 *
 * Called by include/bootstrap.php for every page (and directly by setup.php
 * and captcha.php, which run without the full bootstrap):
 *
 * ```php
 * Security::sendHeaders();
 * Security::startSession();
 * Security::verifyCsrfOnPost();   // every POST must carry the "_token" field
 * ```
 *
 * In templates the token field is printed with `{csrf}`.
 */
final class Security
{
    /** Name of the session cookie. */
    private const SESSION_NAME = 'AMXBSESSID';

    /**
     * Tells whether the current request came over HTTPS (also behind a reverse proxy).
     *
     * @return bool True for HTTPS, port 443 or X-Forwarded-Proto: https.
     */
    public static function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off')
            || (int)($_SERVER['SERVER_PORT'] ?? 0) === 443
            || strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
    }

    /**
     * Starts a hardened session.
     *
     * Strict mode and cookie-only ids, HttpOnly + SameSite=Lax cookie (Secure on
     * HTTPS) limited to the application directory. The session id is rotated
     * every 30 minutes; Auth rotates it additionally on login and logout.
     *
     * @return void
     */
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

    /**
     * Directory of the application as seen from the browser - used as cookie path.
     *
     * @return string Path with trailing slash, e.g. "/bans/".
     */
    public static function cookiePath(): string
    {
        $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
        return rtrim($dir, '/') . '/';
    }

    /**
     * Sends security headers.
     *
     * The Content-Security-Policy allows scripts and styles only from this
     * site: no inline `<script>`, no `eval` (that is why the Alpine.js CSP
     * build is used). Steam CDNs are allowed for avatars. HSTS is sent on HTTPS.
     *
     * @return void
     */
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

    /**
     * Returns the CSRF token of the current session, creating it when needed.
     *
     * The token is replaced after login (see Auth::loginAs()).
     *
     * @return string 64 hexadecimal characters.
     */
    public static function csrfToken(): string
    {
        if (empty($_SESSION['_csrf']) || !is_string($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    /**
     * Compares a submitted token with the session token in constant time.
     *
     * @param string|null $token Value of the "_token" field or X-CSRF-Token header.
     * @return bool True when the token matches.
     */
    public static function csrfValid(?string $token): bool
    {
        return is_string($token) && $token !== '' && hash_equals(self::csrfToken(), $token);
    }

    /**
     * Rejects POST requests without a valid CSRF token (HTTP 419).
     *
     * Every state-changing request in AMXBans is a POST; each one must carry the
     * token of the current session (hidden field "_token" or X-CSRF-Token header).
     * GET requests must never change data.
     *
     * @return void Ends the request with status 419 when the token is invalid.
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
