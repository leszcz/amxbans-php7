<?php
declare(strict_types=1);

/**
 * Web admin authentication and permissions.
 *
 * The session only stores the admin id; the account and its permission
 * level are re-read on every request, so deleting an admin, changing a
 * password or editing a permission level takes effect immediately.
 */
final class Auth
{
    public const PERMISSIONS = [
        'bans_add', 'bans_edit', 'bans_delete', 'bans_unban', 'bans_import', 'bans_export',
        'amxadmins_view', 'amxadmins_edit', 'webadmins_view', 'webadmins_edit',
        'websettings_view', 'websettings_edit', 'permissions_edit', 'prune_db',
        'servers_edit', 'ip_view',
    ];
    /** Permissions that may also have the value "own" (only bans created by the admin). */
    public const OWN_PERMISSIONS = ['bans_edit', 'bans_delete', 'bans_unban'];

    public const MAX_TRIES = 5;
    public const BLOCK_MINUTES = 15;
    private const REMEMBER_DAYS = 30;

    private static ?array $user = null;
    private static string $cookieName = 'amxbans';

    public static function init(stdClass $config): void
    {
        $cookie = preg_replace('/[^A-Za-z0-9_]/', '', (string)($config->cookie ?? '')) ?: 'amxbans';
        self::$cookieName = $cookie . '_remember';

        $uid = $_SESSION['_uid'] ?? null;
        if (is_int($uid)) {
            $user = self::loadUser($uid);
            if ($user && hash_equals((string)($_SESSION['_pwfp'] ?? ''), self::fingerprint($user))) {
                self::$user = $user;
            } else {
                self::clearSession();
            }
        } elseif (isset($_COOKIE[self::$cookieName])) {
            self::loginFromCookie((string)$_COOKIE[self::$cookieName]);
        }

        if (self::$user && time() - (int)self::$user['last_action'] > 60) {
            Database::update('webadmins', ['last_action' => time()], ['id' => self::$user['id']]);
        }
    }

    public static function check(): bool
    {
        return self::$user !== null;
    }

    public static function user(): ?array
    {
        return self::$user;
    }

    public static function id(): int
    {
        return (int)(self::$user['id'] ?? 0);
    }

    public static function name(): string
    {
        return (string)(self::$user['username'] ?? '');
    }

    public static function can(string $permission): bool
    {
        return self::$user !== null && (self::$user['perms'][$permission] ?? 'no') === 'yes';
    }

    /** "yes", or "own" and the ban was created by this admin. */
    public static function canOnBan(string $permission, array $ban): bool
    {
        if (self::$user === null) {
            return false;
        }
        $value = self::$user['perms'][$permission] ?? 'no';
        if ($value === 'yes') {
            return true;
        }
        if ($value !== 'own') {
            return false;
        }
        $name = self::name();
        return $name !== '' && in_array($name, [
            (string)($ban['admin_nick'] ?? ''),
            (string)($ban['admin_id'] ?? ''),
            (string)($ban['nickname'] ?? ''),
        ], true);
    }

    /** All permission values of the current admin (for templates). */
    public static function permissions(): array
    {
        $perms = array_fill_keys(self::PERMISSIONS, 'no');
        return self::$user ? array_merge($perms, self::$user['perms']) : $perms;
    }

    /** Redirects guests to the login page and shows 403 when a permission is missing. */
    public static function require(?string $permission = null): void
    {
        if (!self::check()) {
            redirect('login.php');
        }
        if ($permission !== null && !self::can($permission)) {
            abort(403);
        }
    }

    /**
     * @return array{status:string, block_left?:int, tries_left?:int}
     *         status: ok | invalid | blocked
     */
    public static function attempt(string $username, string $password, bool $remember): array
    {
        $row = Database::one(
            'SELECT * FROM ' . Database::table('webadmins') . ' WHERE `username` = :u LIMIT 1',
            ['u' => $username]
        );

        if (!$row) {
            // Same work as for an existing account, so response time does not reveal user names.
            password_verify($password, '$2y$12$YQi6tQFuM8tEipUv9dZkd.qazS4w2aVr/JQk4H2.aVbkD3UJfVA16');
            return ['status' => 'invalid'];
        }

        $tries = (int)$row['try'];
        $blockedUntil = (int)$row['last_action'] + self::BLOCK_MINUTES * 60;
        if ($tries >= self::MAX_TRIES && time() < $blockedUntil) {
            return ['status' => 'blocked', 'block_left' => $blockedUntil - time()];
        }
        if ($tries >= self::MAX_TRIES) {
            $tries = 0;
        }

        if (!self::verifyPassword($password, (string)$row['password'], (int)$row['id'])) {
            $tries++;
            Database::update('webadmins', ['try' => $tries, 'last_action' => time()], ['id' => $row['id']]);
            log_to_db('Login failed', $tries >= self::MAX_TRIES
                ? 'login blocked (' . self::BLOCK_MINUTES . ' minutes) for ' . $username
                : 'login failed for ' . $username . ' (try ' . $tries . '/' . self::MAX_TRIES . ')');
            if ($tries >= self::MAX_TRIES) {
                return ['status' => 'blocked', 'block_left' => self::BLOCK_MINUTES * 60];
            }
            return ['status' => 'invalid', 'tries_left' => self::MAX_TRIES - $tries];
        }

        Database::update('webadmins', ['try' => 0, 'last_action' => time()], ['id' => $row['id']]);
        self::loginAs((int)$row['id']);
        if ($remember) {
            self::issueRememberCookie((int)$row['id']);
        }
        log_to_db('Login', 'successful login');
        return ['status' => 'ok'];
    }

    public static function logout(): void
    {
        if (self::$user) {
            Database::update('webadmins', ['logcode' => null], ['id' => self::$user['id']]);
        }
        self::forgetCookie();
        $lang = $_SESSION['lang'] ?? null;
        self::clearSession();
        session_regenerate_id(true);
        if ($lang) {
            $_SESSION['lang'] = $lang;
        }
    }

    /** Keeps the current session valid after the admin changed their own password. */
    public static function refreshSession(): void
    {
        $user = self::loadUser(self::id());
        if ($user) {
            self::$user = $user;
            $_SESSION['_pwfp'] = self::fingerprint($user);
        }
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // ------------------------------------------------------------------------

    private static function loginAs(int $uid): void
    {
        session_regenerate_id(true);
        $_SESSION['_created'] = time();
        $user = self::loadUser($uid);
        if (!$user) {
            return;
        }
        $_SESSION['_uid'] = $uid;
        $_SESSION['_pwfp'] = self::fingerprint($user);
        unset($_SESSION['_csrf']);
        self::$user = $user;
    }

    /**
     * Supports bcrypt/argon hashes and the MD5 hashes of AMXBans 6 installations;
     * old hashes are transparently upgraded on successful login.
     */
    private static function verifyPassword(string $password, string $hash, int $uid): bool
    {
        if (preg_match('/^[a-f0-9]{32}$/i', $hash)) {
            $ok = hash_equals(strtolower($hash), md5($password));
        } else {
            $ok = password_verify($password, $hash);
        }
        if ($ok && (strlen($hash) === 32 || password_needs_rehash($hash, PASSWORD_DEFAULT))) {
            Database::update('webadmins', ['password' => self::hashPassword($password)], ['id' => $uid]);
        }
        return $ok;
    }

    private static function loadUser(int $uid): ?array
    {
        $row = Database::one(
            'SELECT w.`id`, w.`username`, w.`password`, w.`email`, w.`level`, w.`last_action`, l.*
               FROM ' . Database::table('webadmins') . ' w
               LEFT JOIN ' . Database::table('levels') . ' l ON l.`level` = w.`level`
              WHERE w.`id` = :id LIMIT 1',
            ['id' => $uid]
        );
        if (!$row) {
            return null;
        }
        $perms = [];
        foreach (self::PERMISSIONS as $p) {
            $perms[$p] = (string)($row[$p] ?? 'no');
            unset($row[$p]);
        }
        $row['level'] = (int)$row['level'];
        $row['perms'] = $perms;
        return $row;
    }

    private static function fingerprint(array $user): string
    {
        return substr(hash('sha256', (string)$user['password'] . '|' . $user['id']), 0, 32);
    }

    private static function clearSession(): void
    {
        unset($_SESSION['_uid'], $_SESSION['_pwfp']);
        self::$user = null;
    }

    private static function issueRememberCookie(int $uid): void
    {
        $token = bin2hex(random_bytes(32));
        Database::update('webadmins', ['logcode' => hash('sha256', $token)], ['id' => $uid]);
        self::setCookie($uid . ':' . $token, time() + self::REMEMBER_DAYS * 86400);
    }

    private static function loginFromCookie(string $value): void
    {
        if (!preg_match('/^(\d+):([a-f0-9]{64})$/', $value, $m)) {
            self::forgetCookie();
            return;
        }
        $row = Database::one(
            'SELECT `id`, `logcode` FROM ' . Database::table('webadmins') . ' WHERE `id` = :id LIMIT 1',
            ['id' => (int)$m[1]]
        );
        if (!$row || !is_string($row['logcode']) || !hash_equals($row['logcode'], hash('sha256', $m[2]))) {
            self::forgetCookie();
            return;
        }
        self::loginAs((int)$row['id']);
        self::issueRememberCookie((int)$row['id']); // rotate the token on every use
    }

    private static function forgetCookie(): void
    {
        if (isset($_COOKIE[self::$cookieName])) {
            self::setCookie('', time() - 3600);
        }
    }

    private static function setCookie(string $value, int $expires): void
    {
        setcookie(self::$cookieName, $value, [
            'expires'  => $expires,
            'path'     => Security::cookiePath(),
            'secure'   => Security::isHttps(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}
