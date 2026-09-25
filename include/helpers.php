<?php
declare(strict_types=1);

/*
 * Small helpers shared by all pages: request input, redirects, flash
 * messages, validation, formatting and the few HTML fragments generated
 * in PHP (icons, BBCode).
 */

// ============================================================================
// Request / response
// ============================================================================

/** Trimmed string from POST (or $default). Arrays are rejected. */
function input(string $key, string $default = ''): string
{
    $v = $_POST[$key] ?? $default;
    return is_string($v) ? trim($v) : $default;
}

function input_int(string $key, int $default = 0): int
{
    $v = $_POST[$key] ?? null;
    return is_string($v) && preg_match('/^-?\d+$/', trim($v)) ? (int)$v : $default;
}

function input_bool(string $key): bool
{
    $v = $_POST[$key] ?? '';
    return is_string($v) && in_array(strtolower($v), ['1', 'on', 'yes', 'true'], true);
}

/** Array of strings from POST, e.g. name="ids[]". */
function input_array(string $key): array
{
    $v = $_POST[$key] ?? [];
    return is_array($v) ? array_filter($v, 'is_scalar') : [];
}

function query(string $key, string $default = ''): string
{
    $v = $_GET[$key] ?? $default;
    return is_string($v) ? trim($v) : $default;
}

function query_int(string $key, int $default = 0): int
{
    $v = $_GET[$key] ?? null;
    return is_string($v) && preg_match('/^\d+$/', $v) ? (int)$v : $default;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

/** Which submit button was pressed: <button name="action" value="...">. */
function action(): string
{
    return is_post() ? input('action') : '';
}

/** Redirects to a local URL (absolute URLs are refused to prevent open redirects). */
function redirect(string $url): never
{
    if ($url === '' || preg_match('#^(?:[a-z][a-z0-9+.-]*:|//|\\\\)#i', $url)) {
        $url = 'index.php';
    }
    header('Location: ' . $url, true, 303);
    exit;
}

/** Redirects back to the current page (GET), keeping the query string. */
function redirect_back(array $override = []): never
{
    $query = array_merge($_GET, $override);
    $query = array_filter($query, fn($v) => $v !== null && $v !== '');
    redirect(basename($_SERVER['SCRIPT_NAME'] ?? 'index.php') . ($query ? '?' . http_build_query($query) : ''));
}

function abort(int $code, string $message = ''): never
{
    global $view;
    http_response_code($code);
    $titles = [403 => '_ERR_FORBIDDEN', 404 => '_ERR_NOTFOUND', 400 => '_ERR_BADREQUEST'];
    if ($view instanceof View) {
        $view->page('error.tpl', ['code' => $code, 'message' => $message ?: ($titles[$code] ?? '_ERROR')], (string)$code);
    }
    exit((string)$code);
}

// ============================================================================
// Flash messages (shown once after a redirect)
// ============================================================================

/** @param string $type success|error|info|warning ; $message: language key or text */
function flash(string $type, string $message, array $details = []): void
{
    $_SESSION['_flash'][] = ['type' => $type, 'message' => $message, 'details' => $details];
}

function flash_pull(): array
{
    $messages = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return is_array($messages) ? $messages : [];
}

// ============================================================================
// Logging
// ============================================================================

function log_to_db(string $action, string $remarks): void
{
    try {
        Database::insert('logs', [
            'timestamp' => time(),
            'ip'        => client_ip(),
            'username'  => Auth::name(),
            'action'    => mb_substr($action, 0, 64),
            'remarks'   => mb_substr($remarks, 0, 256),
        ]);
    } catch (PDOException $e) {
        error_log('AMXBans: cannot write log: ' . $e->getMessage());
    }
}

function client_ip(): string
{
    $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '');
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

// ============================================================================
// Validation
// ============================================================================

function valid_steamid(string $id): bool
{
    return (bool)preg_match('/^STEAM_[0-5]:[01]:\d{1,10}$/', $id);
}

function valid_ip(string $ip): bool
{
    return filter_var($ip, FILTER_VALIDATE_IP) !== false;
}

function valid_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/** AMX Mod X access flags, e.g. "abcdefghijklmnopqrstu" or "z". */
function valid_access_flags(string $flags): bool
{
    return (bool)preg_match('/^[a-uz]{1,23}$/', $flags);
}

/** AMX Mod X account flags: exactly one of a/b/c/d plus optional e and k. */
function valid_account_flags(string $flags, string &$error = ''): bool
{
    if (!preg_match('/^[a-ek]{1,4}$/', $flags)) {
        $error = '_FLAGSINVALID';
        return false;
    }
    $main = preg_match_all('/[bcd]/', $flags);
    if ($main > 1) {
        $error = '_FLAGSINVALID';
        return false;
    }
    if ($main === 0 && !str_contains($flags, 'a')) {
        $error = '_FLAGSBCDMISSING';
        return false;
    }
    return true;
}

/** Removes characters that could break out of a quoted RCON/console argument. */
function rcon_safe(string $value, int $max = 100): string
{
    $value = preg_replace('/["\';\r\n\x00-\x1f]/', '', $value);
    return mb_substr(trim((string)$value), 0, $max);
}

// ============================================================================
// Steam
// ============================================================================

/** STEAM_0:1:1234 or [U:1:2469] => 64-bit community id (as string) or ''. */
function steam_community_id(?string $steamid): string
{
    $steamid = (string)$steamid;
    if (preg_match('/^STEAM_[0-5]:([01]):(\d{1,10})$/', $steamid, $m)) {
        return (string)(76561197960265728 + (int)$m[2] * 2 + (int)$m[1]);
    }
    if (preg_match('/^\[U:1:(\d{1,10})\]$/', $steamid, $m)) {
        return (string)(76561197960265728 + (int)$m[1]);
    }
    return '';
}

function steam_profile_url(?string $steamid): string
{
    $id = steam_community_id($steamid);
    return $id === '' ? '' : 'https://steamcommunity.com/profiles/' . $id;
}

// ============================================================================
// GeoIP (legacy country database shipped with AMXBans)
// ============================================================================

/** @return array{code:string, name:string} */
function geo_country(?string $ip): array
{
    static $gi = null;
    $empty = ['code' => '', 'name' => ''];
    if (!$ip || !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        return $empty;
    }
    if ($gi === null) {
        $file = __DIR__ . '/GeoIP.dat';
        if (!is_file($file)) {
            $gi = false;
            return $empty;
        }
        require_once __DIR__ . '/geoip.inc';
        $gi = geoip_open($file, GEOIP_STANDARD);
    }
    if ($gi === false) {
        return $empty;
    }
    return [
        'code' => strtolower((string)geoip_country_code_by_addr($gi, $ip)),
        'name' => (string)geoip_country_name_by_addr($gi, $ip),
    ];
}

function country_flag(?string $code): string
{
    $code = strtolower((string)$code);
    if (preg_match('/^[a-z]{2}$/', $code) && is_file(AMXB_ROOT . '/images/flags/' . $code . '.png')) {
        return 'images/flags/' . $code . '.png';
    }
    return 'images/flags/clear.png';
}

function game_icon(?string $mod): string
{
    $mod = preg_replace('/[^A-Za-z0-9_-]/', '', (string)$mod);
    if ($mod === '' || $mod === 'html' || $mod === 'website') {
        return 'images/games/web.png';
    }
    foreach (['png', 'gif'] as $ext) {
        if (is_file(AMXB_ROOT . '/images/games/' . $mod . '.' . $ext)) {
            return 'images/games/' . $mod . '.' . $ext;
        }
    }
    return 'images/games/cstrike.gif';
}

// ============================================================================
// Formatting
// ============================================================================

function format_datetime(mixed $timestamp, string $format = 'datetime'): string
{
    $ts = (int)$timestamp;
    if ($ts <= 0) {
        return '—';
    }
    return date(match ($format) {
        'date'  => 'Y-m-d',
        'time'  => 'H:i',
        'full'  => 'Y-m-d H:i:s',
        default => 'Y-m-d H:i',
    }, $ts);
}

/** Seconds => "2 weeks 3 days" (short: only the largest unit). */
function format_duration(mixed $seconds, bool $short = false): string
{
    $s = max(0, (int)$seconds);
    $units = [
        ['_YEAR', '_YEARS', 31536000], ['_MONTH', '_MONTHS', 2592000], ['_WEEK', '_WEEKS', 604800],
        ['_DAY', '_DAYS', 86400], ['_HOUR', '_HOURS', 3600], ['_MIN', '_MINS', 60], ['_SEC', '_SECS', 1],
    ];
    $parts = [];
    foreach ($units as [$one, $many, $len]) {
        if ($s >= $len) {
            $n = intdiv($s, $len);
            $s -= $n * $len;
            $parts[] = $n . ' ' . Lang::get($n === 1 ? $one : $many);
            if ($short || count($parts) === 2) {
                break;
            }
        }
    }
    return $parts ? implode(' ', $parts) : '0 ' . Lang::get('_MINS');
}

/** Ban length in minutes => text (0 = permanent, -1 = unbanned). */
function format_ban_length(mixed $minutes): string
{
    $m = (int)$minutes;
    if ($m === 0) {
        return Lang::get('_PERMANENT');
    }
    if ($m < 0) {
        return Lang::get('_UNBANNED');
    }
    return format_duration($m * 60);
}

function format_relative(mixed $timestamp): string
{
    $diff = (int)$timestamp - time();
    return $diff >= 0
        ? sprintf(Lang::get('_IN_TIME'), format_duration($diff, true))
        : sprintf(Lang::get('_TIME_AGO'), format_duration(-$diff, true));
}

function format_filesize(mixed $bytes): string
{
    $b = (float)$bytes;
    foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
        if ($b < 1024 || $unit === 'GB') {
            return ($unit === 'B' ? (int)$b : number_format($b, 1)) . ' ' . $unit;
        }
        $b /= 1024;
    }
    return (string)$bytes;
}

/** Only http(s), mailto and relative URLs are allowed in links. */
function safe_url(?string $url): string
{
    $url = trim((string)$url);
    if ($url === '') {
        return '';
    }
    if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $url) && !preg_match('#^(https?|mailto):#i', $url)) {
        return '#';
    }
    return $url;
}

/**
 * Safe BBCode: the text is HTML-escaped first, then a small set of tags is
 * converted. Links are only created for http(s) URLs. Output is HTML -
 * print it with {$text|bbcode nofilter}.
 */
function bbcode_to_html(?string $text): string
{
    $html = htmlspecialchars((string)$text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    $simple = [
        'b' => '<strong>$1</strong>',
        'i' => '<em>$1</em>',
        'u' => '<span class="underline">$1</span>',
        'center' => '<div class="text-center">$1</div>',
        'quote' => '<blockquote class="bb-quote">$1</blockquote>',
    ];
    foreach ($simple as $tag => $replacement) {
        for ($i = 0; $i < 5; $i++) { // allow limited nesting
            $html = preg_replace('#\[' . $tag . '\](.*?)\[/' . $tag . '\]#si', $replacement, $html, -1, $count);
            if (!$count) {
                break;
            }
        }
    }
    $link = fn(string $url, string $label) => '<a href="' . $url . '" target="_blank" rel="nofollow noopener noreferrer" class="link">' . $label . '</a>';
    $html = preg_replace_callback('#\[url\](https?://[^\s\[\]"<>]+)\[/url\]#i', fn($m) => $link($m[1], $m[1]), $html);
    $html = preg_replace_callback('#\[url=(https?://[^\s\[\]"<>]+)\](.*?)\[/url\]#si', fn($m) => $link($m[1], $m[2]), $html);

    foreach (smilies() as $s) {
        $code = htmlspecialchars($s['code'], ENT_QUOTES, 'UTF-8');
        if ($code === '' || !preg_match('/^[A-Za-z0-9_.-]+$/', $s['url'])) {
            continue;
        }
        $img = '<img src="images/icons/' . $s['url'] . '" alt="' . $code . '" title="'
            . htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8') . '" class="inline h-5 w-5">';
        $html = str_replace($code, $img, $html);
    }
    return nl2br($html, false);
}

/** Inline SVG icon: {icon name="trash" class="size-4"} */
function svg_icon(array $params): string
{
    static $icons = null;
    $icons ??= require __DIR__ . '/icons.php';
    $name = (string)($params['name'] ?? '');
    $class = preg_replace('/[^A-Za-z0-9 _:\/.\-\[\]]/', '', (string)($params['class'] ?? 'size-5'));
    if (!isset($icons[$name])) {
        return '';
    }
    return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="'
        . $class . '">' . $icons[$name] . '</svg>';
}

/** Changes whenever the compiled CSS changes - used to bust browser caches. */
function asset_version(): string
{
    $file = AMXB_ROOT . '/assets/dist/app.css';
    return is_file($file) ? (string)filemtime($file) : AMXB_VERSION;
}

// ============================================================================
// Captcha for guests (comments / file uploads)
// ============================================================================

function captcha_new(): void
{
    $alphabet = 'ABCDEFGHKMNPRSTUVWXYZ23456789';
    $code = '';
    for ($i = 0; $i < 6; $i++) {
        $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
    }
    $_SESSION['_captcha'] = $code;
}

function captcha_check(string $answer): bool
{
    $expected = $_SESSION['_captcha'] ?? '';
    unset($_SESSION['_captcha']); // one attempt per code
    return is_string($expected) && $expected !== '' && hash_equals($expected, strtoupper(trim($answer)));
}

// ============================================================================
// Uploaded files (demos / screenshots)
// ============================================================================

function files_dir(): string
{
    return AMXB_ROOT . '/include/files/';
}

/** Stored file names are generated by us: 32 hex chars + "_" + ban id. */
function stored_file_path(string $name, bool $thumb = false): ?string
{
    if (!preg_match('/^[a-f0-9]{32}_\d+$/', $name)) {
        return null;
    }
    return files_dir() . $name . ($thumb ? '_thumb' : '');
}

function delete_stored_file(string $name): void
{
    foreach ([false, true] as $thumb) {
        $path = stored_file_path($name, $thumb);
        if ($path !== null && is_file($path)) {
            @unlink($path);
        }
    }
}

/** Sends a file as a download and ends the request. */
function send_download(string $path, string $downloadName, string $contentType = 'application/octet-stream'): never
{
    if (!is_file($path)) {
        abort(404);
    }
    $downloadName = preg_replace('/[^\w.\- ]/u', '_', $downloadName) ?: 'download';
    while (ob_get_level()) {
        ob_end_clean();
    }
    header('Content-Type: ' . $contentType);
    header('X-Content-Type-Options: nosniff');
    header('Content-Disposition: attachment; filename="' . $downloadName . '"; filename*=UTF-8\'\'' . rawurlencode($downloadName));
    header('Content-Length: ' . filesize($path));
    header('Cache-Control: private, no-store');
    readfile($path);
    exit;
}

/** Creates a small JPEG/PNG/GIF thumbnail next to an uploaded image. */
function make_thumbnail(string $path, int $max = 160): bool
{
    if (!extension_loaded('gd')) {
        return false;
    }
    $info = @getimagesize($path);
    if ($info === false) {
        return false;
    }
    [$w, $h, $type] = $info;
    $create = [IMAGETYPE_GIF => 'imagecreatefromgif', IMAGETYPE_JPEG => 'imagecreatefromjpeg', IMAGETYPE_PNG => 'imagecreatefrompng'][$type] ?? null;
    if ($create === null || $w < 1 || $h < 1 || $w * $h > 40_000_000) {
        return false;
    }
    $src = @$create($path);
    if (!$src) {
        return false;
    }
    $ratio = min($max / $w, $max / $h, 1);
    $nw = max(1, (int)round($w * $ratio));
    $nh = max(1, (int)round($h * $ratio));
    $dst = imagecreatetruecolor($nw, $nh);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
    // Always re-encode as PNG: strips anything that is not image data.
    return imagepng($dst, $path . '_thumb');
}

// ============================================================================
// Pagination
// ============================================================================

/**
 * @param string $base URL prefix the page number is appended to, e.g. "ban_list.php?show=active&"
 * @return array{page:int, pages:int, offset:int, limit:int, base:string, links:int[]}
 */
function paginate(int $total, int $perPage, int $page, string $base): array
{
    $perPage = max(1, $perPage);
    $pages = max(1, (int)ceil($total / $perPage));
    $page = min(max(1, $page), $pages);
    $links = [];
    foreach (range(1, $pages) as $p) {
        if ($p === 1 || $p === $pages || abs($p - $page) <= 2) {
            $links[] = $p;
        } elseif (end($links) !== 0) {
            $links[] = 0; // gap
        }
    }
    return [
        'page' => $page, 'pages' => $pages, 'offset' => ($page - 1) * $perPage, 'limit' => $perPage,
        'base' => $base, 'links' => $links, 'total' => $total,
    ];
}

/** sprintf that never throws (translations may have a different number of placeholders). */
function format_sprintf(mixed $format, mixed ...$args): string
{
    try {
        return sprintf((string)$format, ...$args);
    } catch (\ValueError|\ArgumentCountError) {
        return (string)$format;
    }
}

/** AMXBans 6 compatibility for third-party modules. */
function has_access(string $permission): bool
{
    return Auth::can($permission);
}

function html_safe(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
