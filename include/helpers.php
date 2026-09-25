<?php
declare(strict_types=1);

/**
 * Helper functions shared by all pages.
 *
 * Sections: request input, redirects/errors, flash messages, logging,
 * validation, Steam, GeoIP, formatting, BBCode/icons, captcha, uploaded
 * files, pagination and AMXBans 6 compatibility.
 *
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 * @see       docs/architecture.md
 */

// ============================================================================
// Request / response
// ============================================================================

/**
 * Reads a trimmed string from POST.
 *
 * @param string $key     Field name.
 * @param string $default Returned when the field is missing or is an array.
 * @return string Trimmed value.
 */
function input(string $key, string $default = ''): string
{
    $v = $_POST[$key] ?? $default;
    return is_string($v) ? trim($v) : $default;
}

/**
 * Reads an integer from POST.
 *
 * @param string $key     Field name.
 * @param int    $default Returned when the field is missing or not an integer.
 * @return int
 */
function input_int(string $key, int $default = 0): int
{
    $v = $_POST[$key] ?? null;
    return is_string($v) && preg_match('/^-?\d+$/', trim($v)) ? (int)$v : $default;
}

/**
 * Reads a checkbox from POST ("1", "on", "yes", "true" count as checked).
 *
 * @param string $key Field name.
 * @return bool
 */
function input_bool(string $key): bool
{
    $v = $_POST[$key] ?? '';
    return is_string($v) && in_array(strtolower($v), ['1', 'on', 'yes', 'true'], true);
}

/**
 * Reads an array field from POST (e.g. name="ids[]"); nested arrays are dropped.
 *
 * @param string $key Field name without "[]".
 * @return array<int|string, scalar>
 */
function input_array(string $key): array
{
    $v = $_POST[$key] ?? [];
    return is_array($v) ? array_filter($v, 'is_scalar') : [];
}

/**
 * Reads a trimmed string from the query string ($_GET).
 *
 * @param string $key     Parameter name.
 * @param string $default Returned when missing or an array.
 * @return string
 */
function query(string $key, string $default = ''): string
{
    $v = $_GET[$key] ?? $default;
    return is_string($v) ? trim($v) : $default;
}

/**
 * Reads a non-negative integer from the query string.
 *
 * @param string $key     Parameter name.
 * @param int    $default Returned when missing or not a number.
 * @return int
 */
function query_int(string $key, int $default = 0): int
{
    $v = $_GET[$key] ?? null;
    return is_string($v) && preg_match('/^\d+$/', $v) ? (int)$v : $default;
}

/**
 * @return bool True for POST requests.
 */
function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

/**
 * Name of the pressed submit button.
 *
 * Forms use `<button name="action" value="save">`; controllers dispatch with
 * `switch (action())`. Always "" for GET requests.
 *
 * @return string Value of the "action" field.
 */
function action(): string
{
    return is_post() ? input('action') : '';
}

/**
 * Redirects (303 See Other) to a local URL and ends the request.
 *
 * Absolute and protocol-relative URLs are replaced with index.php to prevent open redirects.
 *
 * @param string $url Relative URL, e.g. "ban_list.php?bid=5".
 * @return never
 */
function redirect(string $url): never
{
    if ($url === '' || preg_match('#^(?:[a-z][a-z0-9+.-]*:|//|\\\\)#i', $url)) {
        $url = 'index.php';
    }
    header('Location: ' . $url, true, 303);
    exit;
}

/**
 * Redirects to the current script with the current query string (Post/Redirect/Get).
 *
 * @param array<string, scalar|null> $override Query parameters to change; null removes a parameter.
 * @return never
 */
function redirect_back(array $override = []): never
{
    $query = array_merge($_GET, $override);
    $query = array_filter($query, fn($v) => $v !== null && $v !== '');
    redirect(basename($_SERVER['SCRIPT_NAME'] ?? 'index.php') . ($query ? '?' . http_build_query($query) : ''));
}

/**
 * Shows an error page and ends the request.
 *
 * @param int    $code    HTTP status code (403, 404, ...).
 * @param string $message Language key or text; a default key is chosen by status code.
 * @return never
 */
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

/**
 * Queues a message that is shown once on the next page (after a redirect).
 *
 * @param string       $type    success | error | info | warning
 * @param string       $message Language key or text.
 * @param list<string> $details Additional lines (language keys or text), e.g. validation errors.
 * @return void
 */
function flash(string $type, string $message, array $details = []): void
{
    $_SESSION['_flash'][] = ['type' => $type, 'message' => $message, 'details' => $details];
}

/**
 * Returns and removes all queued flash messages.
 *
 * @return list<array{type: string, message: string, details: list<string>}>
 */
function flash_pull(): array
{
    $messages = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return is_array($messages) ? $messages : [];
}

// ============================================================================
// Logging
// ============================================================================

/**
 * Writes an entry to the website log (_logs) with IP and admin name.
 *
 * Errors are only written to the PHP error log, never shown to the user.
 *
 * @param string $action  Short category, e.g. "Ban edit" (max. 64 characters).
 * @param string $remarks Description (max. 256 characters).
 * @return void
 */
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

/**
 * @return string Validated REMOTE_ADDR, "0.0.0.0" when invalid. Proxy headers are ignored on purpose.
 */
function client_ip(): string
{
    $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '');
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

// ============================================================================
// Validation
// ============================================================================

/**
 * @param string $id Value to check.
 * @return bool True for STEAM_X:Y:Z (legacy GoldSrc format).
 */
function valid_steamid(string $id): bool
{
    return (bool)preg_match('/^STEAM_[0-5]:[01]:\d{1,10}$/', $id);
}

/**
 * @param string $ip Value to check.
 * @return bool True for a valid IPv4 or IPv6 address.
 */
function valid_ip(string $ip): bool
{
    return filter_var($ip, FILTER_VALIDATE_IP) !== false;
}

/**
 * @param string $email Value to check.
 * @return bool True for a syntactically valid e-mail address.
 */
function valid_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validates AMX Mod X access flags (users.ini 3rd column).
 *
 * @param string $flags E.g. "abcdefghijklmnopqrstu" or "z".
 * @return bool
 */
function valid_access_flags(string $flags): bool
{
    return (bool)preg_match('/^[a-uz]{1,23}$/', $flags);
}

/**
 * Validates AMX Mod X account flags (users.ini 4th column).
 *
 * Exactly one of a/b/c/d (or only "a") plus optional "e" and "k".
 *
 * @param string $flags E.g. "ce".
 * @param string $error Receives the language key of the problem.
 * @return bool
 */
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

/**
 * Makes a value safe to put into an RCON command.
 *
 * Removes quotes, semicolons and control characters, which could otherwise
 * end the quoted argument and append another console command.
 *
 * @param string $value Untrusted value (e.g. a ban reason).
 * @param int    $max   Maximum length.
 * @return string
 */
function rcon_safe(string $value, int $max = 100): string
{
    $value = preg_replace('/["\';\r\n\x00-\x1f]/', '', $value);
    return mb_substr(trim((string)$value), 0, $max);
}

// ============================================================================
// Steam
// ============================================================================

/**
 * Converts a SteamID to the 64-bit Steam Community id.
 *
 * @param string|null $steamid STEAM_0:1:1234 or [U:1:2469].
 * @return string Community id as a string, "" for unsupported values (LAN, BOT, …).
 */
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

/**
 * @param string|null $steamid SteamID.
 * @return string https://steamcommunity.com/profiles/<id> or "".
 */
function steam_profile_url(?string $steamid): string
{
    $id = steam_community_id($steamid);
    return $id === '' ? '' : 'https://steamcommunity.com/profiles/' . $id;
}

// ============================================================================
// GeoIP (legacy country database shipped with AMXBans)
// ============================================================================

/**
 * Looks up the country of an IPv4 address in include/GeoIP.dat (legacy GeoLite database).
 *
 * @param string|null $ip IP address.
 * @return array{code: string, name: string} Lowercase ISO code and English name ("" when unknown).
 */
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

/**
 * @param string|null $code Two-letter country code.
 * @return string Path of the flag image (images/flags/clear.png when unknown).
 */
function country_flag(?string $code): string
{
    $code = strtolower((string)$code);
    if (preg_match('/^[a-z]{2}$/', $code) && is_file(AMXB_ROOT . '/images/flags/' . $code . '.png')) {
        return 'images/flags/' . $code . '.png';
    }
    return 'images/flags/clear.png';
}

/**
 * @param string|null $mod Game directory (cstrike, czero, …) or "html"/"website" for web bans.
 * @return string Path of the game icon.
 */
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

/**
 * Formats a Unix timestamp (server time zone).
 *
 * @param mixed  $timestamp Unix timestamp.
 * @param string $format    datetime (Y-m-d H:i) | date | time | full (with seconds).
 * @return string "—" for empty values.
 */
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

/**
 * Formats a duration as words, e.g. "2 Weeks 3 Days".
 *
 * @param mixed $seconds Duration in seconds.
 * @param bool  $short   Only the largest unit.
 * @return string At most two units, translated.
 */
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

/**
 * Formats a ban length.
 *
 * @param mixed $minutes Ban length in minutes; 0 = permanent, -1 = unbanned.
 * @return string Translated text.
 */
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

/**
 * Formats a timestamp relative to now, e.g. "3 Days ago" / "in 2 Hours".
 *
 * @param mixed $timestamp Unix timestamp.
 * @return string
 */
function format_relative(mixed $timestamp): string
{
    $diff = (int)$timestamp - time();
    return $diff >= 0
        ? sprintf(Lang::get('_IN_TIME'), format_duration($diff, true))
        : sprintf(Lang::get('_TIME_AGO'), format_duration(-$diff, true));
}

/**
 * @param mixed $bytes Size in bytes.
 * @return string E.g. "1.5 MB".
 */
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

/**
 * Neutralizes dangerous link targets (javascript:, data:, …).
 *
 * @param string|null $url URL from the database or user input.
 * @return string The URL when it is http(s), mailto or relative; "#" otherwise.
 */
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
 * Converts BBCode in comments to safe HTML.
 *
 * The text is HTML-escaped first, then a small set of tags is converted:
 * [b] [i] [u] [center] [quote] [url] [url=…]. Links are only created for
 * http(s) URLs (rel="nofollow noopener"). Smilies from _smilies are replaced.
 *
 * Template usage: `{$comment.text|bbcode nofilter}`
 *
 * @param string|null $text Raw comment.
 * @return string HTML.
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

/**
 * Renders a Heroicons outline icon as inline SVG (Smarty function {icon}).
 *
 * Template usage: `{icon name="trash" class="size-4"}`. Available names are in include/icons.php
 * (generated by `npm run icons` from build/icons.mjs).
 *
 * @param array{name?: string, class?: string} $params Smarty parameters.
 * @return string SVG markup, "" for unknown names.
 */
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

/**
 * Cache-busting value appended to CSS/JS URLs.
 *
 * @return string Modification time of assets/dist/app.css.
 */
function asset_version(): string
{
    $file = AMXB_ROOT . '/assets/dist/app.css';
    return is_file($file) ? (string)filemtime($file) : AMXB_VERSION;
}

// ============================================================================
// Captcha for guests (comments / file uploads)
// ============================================================================

/**
 * Creates a new 6-character captcha code in the session (image: captcha.php).
 *
 * @return void
 */
function captcha_new(): void
{
    $alphabet = 'ABCDEFGHKMNPRSTUVWXYZ23456789';
    $code = '';
    for ($i = 0; $i < 6; $i++) {
        $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
    }
    $_SESSION['_captcha'] = $code;
}

/**
 * Checks and consumes the captcha code (one attempt per code).
 *
 * @param string $answer User input (case-insensitive).
 * @return bool
 */
function captcha_check(string $answer): bool
{
    $expected = $_SESSION['_captcha'] ?? '';
    unset($_SESSION['_captcha']); // one attempt per code
    return is_string($expected) && $expected !== '' && hash_equals($expected, strtoupper(trim($answer)));
}

// ============================================================================
// Uploaded files (demos / screenshots)
// ============================================================================

/**
 * @return string Absolute directory of uploaded demos/screenshots (with trailing slash).
 */
function files_dir(): string
{
    return AMXB_ROOT . '/include/files/';
}

/**
 * Absolute path of an uploaded file.
 *
 * Only names generated by the upload code (32 hex characters + "_" + ban id)
 * are accepted, so database values can never point outside include/files/.
 *
 * @param string $name  Value of _files.demo_file.
 * @param bool   $thumb Path of the thumbnail instead.
 * @return string|null Null for invalid names.
 */
function stored_file_path(string $name, bool $thumb = false): ?string
{
    if (!preg_match('/^[a-f0-9]{32}_\d+$/', $name)) {
        return null;
    }
    return files_dir() . $name . ($thumb ? '_thumb' : '');
}

/**
 * Deletes an uploaded file and its thumbnail from disk.
 *
 * @param string $name Value of _files.demo_file.
 * @return void
 */
function delete_stored_file(string $name): void
{
    foreach ([false, true] as $thumb) {
        $path = stored_file_path($name, $thumb);
        if ($path !== null && is_file($path)) {
            @unlink($path);
        }
    }
}

/**
 * Streams a file as an attachment and ends the request.
 *
 * @param string $path         Absolute path.
 * @param string $downloadName File name offered to the browser (sanitized).
 * @param string $contentType  MIME type.
 * @return never Responds 404 when the file does not exist.
 */
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

/**
 * Creates "<file>_thumb" (PNG, max. $max px) for uploaded JPEG/PNG/GIF images.
 *
 * Re-encoding with GD strips anything that is not image data.
 *
 * @param string $path Absolute path of the uploaded file.
 * @param int    $max  Maximum width/height.
 * @return bool False for non-images or when GD is missing.
 */
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
 * Calculates pagination.
 *
 * @param int    $total   Number of items.
 * @param int    $perPage Items per page.
 * @param int    $page    Requested page (clamped to 1..pages).
 * @param string $base    URL prefix the page number is appended to, e.g. "ban_list.php?show=expired&".
 * @return array{page: int, pages: int, offset: int, limit: int, base: string, links: list<int>, total: int}
 *         links contains page numbers and 0 for a gap ("…"). Used by partials/pagination.tpl.
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

/**
 * sprintf() that never throws (translations may have a different number of placeholders).
 *
 * @param mixed $format  Format string.
 * @param mixed ...$args Values.
 * @return string The formatted text, or $format on error.
 */
function format_sprintf(mixed $format, mixed ...$args): string
{
    try {
        return sprintf((string)$format, ...$args);
    } catch (\ValueError|\ArgumentCountError) {
        return (string)$format;
    }
}

/**
 * AMXBans 6 compatibility for third-party modules.
 *
 * @deprecated Use {@see Auth::can()}.
 * @param string $permission Permission name.
 * @return bool
 */
function has_access(string $permission): bool
{
    return Auth::can($permission);
}

/**
 * Escapes a value for HTML output (for PHP code that builds HTML itself).
 *
 * @param string|null $value Raw text.
 * @return string
 */
function html_safe(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
