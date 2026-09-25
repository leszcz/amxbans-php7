<?php
declare(strict_types=1);

/**
 * Queries used by more than one page.
 *
 * All of them use prepared statements through the {@see Database} class.
 * Page-specific queries live in the page controllers.
 *
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 * @see       docs/database.md
 */

/**
 * Loads the web settings (_webconfig) into $config, with types and limits applied.
 *
 * @param stdClass $config Configuration object; receives cookie, bans_per_page, design, banner,
 *                         banner_url, default_lang, start_page, show_*_count, demo_all, comment_all,
 *                         use_capture, max_file_size, file_type, auto_prune, max_offences*, use_demo, use_comment.
 * @return void
 * @throws PDOException On database errors.
 */
function settings_load(stdClass $config): void
{
    $row = Database::one('SELECT * FROM ' . Database::table('webconfig') . ' ORDER BY `id` LIMIT 1') ?? [];

    $config->cookie              = (string)($row['cookie'] ?? 'amxbans');
    $config->bans_per_page       = max(5, min(200, (int)($row['bans_per_page'] ?? 50)));
    $config->design              = (string)($row['design'] ?? 'default');
    $config->banner              = (string)($row['banner'] ?? '');
    $config->banner_url          = (string)($row['banner_url'] ?? '');
    $config->default_lang        = (string)($row['default_lang'] ?? 'english');
    $config->start_page          = (string)($row['start_page'] ?? 'ban_list.php');
    $config->show_kick_count     = (int)($row['show_kick_count'] ?? 1);
    $config->show_comment_count  = (int)($row['show_comment_count'] ?? 1);
    $config->show_demo_count     = (int)($row['show_demo_count'] ?? 1);
    $config->demo_all            = (int)($row['demo_all'] ?? 0);
    $config->comment_all         = (int)($row['comment_all'] ?? 0);
    $config->use_capture         = (int)($row['use_capture'] ?? 1);
    $config->max_file_size       = max(1, (int)($row['max_file_size'] ?? 2));
    $config->file_type           = (string)($row['file_type'] ?? 'dem,zip,rar,jpg,gif,png');
    $config->auto_prune          = (int)($row['auto_prune'] ?? 0);
    $config->max_offences        = (int)($row['max_offences'] ?? 10);
    $config->max_offences_reason = (string)($row['max_offences_reason'] ?? 'max offences reached');
    $config->use_demo            = (int)($row['use_demo'] ?? 1);
    $config->use_comment         = (int)($row['use_comment'] ?? 1);
}

/**
 * Upload extensions allowed by the settings, minus script/HTML types that are always blocked.
 *
 * @param stdClass $config Configuration with file_type ("dem,zip,jpg").
 * @return list<string> Lowercase extensions.
 */
function allowed_file_types(stdClass $config): array
{
    $blocked = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'php8', 'phar', 'pht', 'phps', 'cgi', 'pl', 'py',
                'asp', 'aspx', 'jsp', 'sh', 'exe', 'bat', 'cmd', 'htaccess', 'html', 'htm', 'svg', 'js', 'shtml'];
    $types = array_map(fn($t) => strtolower(trim($t)), explode(',', $config->file_type));
    return array_values(array_filter($types, fn($t) => preg_match('/^[a-z0-9]{1,8}$/', $t) && !in_array($t, $blocked, true)));
}

/**
 * Items of the public navigation (_usermenu).
 *
 * Guests see url/lang_key, logged-in admins url2/lang_key2. Login/logout entries
 * are skipped because the layout renders dedicated buttons.
 *
 * @param bool $loggedIn Whether an admin is logged in.
 * @return list<array{url: string, label: string, file: string}> file = script name for the "active" state.
 */
function menu_items(bool $loggedIn): array
{
    static $rows = null;
    $rows ??= Database::all('SELECT * FROM ' . Database::table('usermenu') . ' WHERE `activ` = 1 ORDER BY `pos`');
    $items = [];
    foreach ($rows as $row) {
        $url = (string)($loggedIn ? $row['url2'] : $row['url']);
        $key = (string)($loggedIn ? $row['lang_key2'] : $row['lang_key']);
        // Login / logout are rendered as dedicated buttons in the header.
        if ($url === '' || $key === '' || in_array(basename($url), ['login.php', 'logout.php'], true)) {
            continue;
        }
        $items[] = ['url' => safe_url($url), 'label' => $key, 'file' => basename((string)parse_url($url, PHP_URL_PATH))];
    }
    return $items;
}

/**
 * @return list<array{code: string, url: string, name: string}> Rows of _smilies (cached per request).
 */
function smilies(): array
{
    static $rows = null;
    try {
        $rows ??= Database::all('SELECT `code`, `url`, `name` FROM ' . Database::table('smilies') . ' ORDER BY `id`');
    } catch (PDOException) {
        $rows = [];
    }
    return $rows;
}

/**
 * Enabled modules whose file include/modules/modul_<name>.php exists.
 *
 * @return array<string, array<string, mixed>> name => _modulconfig row.
 */
function modules_active(): array
{
    static $rows = null;
    if ($rows === null) {
        $rows = [];
        foreach (Database::all('SELECT * FROM ' . Database::table('modulconfig') . ' WHERE `activ` = 1 ORDER BY `name`') as $m) {
            if (preg_match('/^[a-z0-9_]+$/i', (string)$m['name']) && is_file(AMXB_ROOT . '/include/modules/modul_' . $m['name'] . '.php')) {
                $rows[$m['name']] = $m;
            }
        }
    }
    return $rows;
}

/**
 * @return list<array<string, mixed>> All rows of _serverinfo ordered by hostname (including the RCON password - never pass it to templates).
 */
function servers_all(): array
{
    return Database::all('SELECT * FROM ' . Database::table('serverinfo') . ' ORDER BY `hostname`');
}

/**
 * @param int $id Server id.
 * @return array<string, mixed>|null _serverinfo row.
 */
function server_find(int $id): ?array
{
    return Database::one('SELECT * FROM ' . Database::table('serverinfo') . ' WHERE `id` = :id', ['id' => $id]);
}

/**
 * @return list<array<string, mixed>> All ban reasons (_reasons) ordered by name.
 */
function reasons_all(): array
{
    return Database::all('SELECT * FROM ' . Database::table('reasons') . ' ORDER BY `reason`');
}

// ----------------------------------------------------------------------------
// Bans
// ----------------------------------------------------------------------------

/**
 * SELECT used by every ban listing.
 *
 * Joins the server (timezone_fixx, gametype) and the AMX Mod X admin (nickname)
 * without duplicating rows. Append WHERE / ORDER BY / LIMIT.
 *
 * @return string SQL fragment.
 */
function ban_select_sql(): string
{
    return 'SELECT ba.*, se.`gametype`, se.`timezone_fixx`, aa.`nickname`
              FROM ' . Database::table('bans') . ' ba
              LEFT JOIN ' . Database::table('serverinfo') . ' se ON se.`address` = ba.`server_ip`
              LEFT JOIN ' . Database::table('amxadmins') . ' aa ON aa.`id` = (
                    SELECT MIN(a2.`id`) FROM ' . Database::table('amxadmins') . ' a2
                     WHERE a2.`steamid` IN (ba.`admin_id`, ba.`admin_ip`, ba.`admin_nick`))';
}

/**
 * @param int $bid Ban id.
 * @return array<string, mixed>|null Ban row processed by {@see ban_present()}.
 */
function ban_find(int $bid): ?array
{
    $row = Database::one(ban_select_sql() . ' WHERE ba.`bid` = :bid', ['bid' => $bid]);
    return $row ? ban_present($row) : null;
}

/**
 * Adds computed fields to a ban row for templates.
 *
 * Added keys: created (with time zone fix), ban_end, permanent, unbanned, active,
 * website, mod, steam_url, has_steamid, admin_name, cc (country code), cn (country name).
 *
 * @param array<string, mixed> $row Row from {@see ban_select_sql()}.
 * @return array<string, mixed>
 */
function ban_present(array $row): array
{
    $tz = (int)($row['timezone_fixx'] ?? 0) * 3600;
    $created = (int)$row['ban_created'] + $tz;
    $length = (int)$row['ban_length'];

    $row['bid']          = (int)$row['bid'];
    $row['created']      = $created;
    $row['ban_end']      = $length > 0 ? $created + $length * 60 : 0;
    $row['permanent']    = $length === 0;
    $row['unbanned']     = $length < 0;
    $row['active']       = (int)$row['expired'] === 0 && ($length === 0 || $row['ban_end'] > time());
    $row['website']      = ($row['server_name'] ?? '') === 'website';
    $row['mod']          = $row['website'] ? 'html' : (string)($row['gametype'] ?? '');
    $row['steam_url']    = steam_profile_url($row['player_id'] ?? '');
    $row['has_steamid']  = !in_array((string)($row['player_id'] ?? ''), ['', '0', 'STEAM_ID_LAN', 'VALVE_ID_LAN', 'STEAM_ID_PENDING', 'HLTV', 'BOT'], true);
    $row['admin_name']   = (string)($row['nickname'] ?? '') !== '' ? (string)$row['nickname'] : (string)$row['admin_nick'];
    $country = geo_country($row['player_ip'] ?? null);
    $row['cc'] = $country['code'];
    $row['cn'] = $country['name'];
    return $row;
}

/**
 * Counts expired bans of the same player (by SteamID or IP).
 *
 * @param array<string, mixed> $ban Ban row (bid, player_id, player_ip).
 * @return int
 */
function ban_previous_count(array $ban): int
{
    $pid = (string)($ban['player_id'] ?? '');
    $ip = (string)($ban['player_ip'] ?? '');
    if ($pid === '' && $ip === '') {
        return 0;
    }
    return (int)Database::value(
        'SELECT COUNT(*) FROM ' . Database::table('bans') . '
          WHERE `expired` = 1 AND `bid` <> :bid
            AND ((`player_id` = :pid AND `player_id` <> \'\') OR (`player_ip` = :ip AND `player_ip` <> \'\'))',
        ['bid' => (int)$ban['bid'], 'pid' => $pid, 'ip' => $ip]
    );
}

/**
 * Deletes a ban with its comments, files (also on disk) and edit history.
 *
 * @param int $bid Ban id.
 * @return void
 * @throws PDOException On database errors (the DB part runs in a transaction).
 */
function ban_delete(int $bid): void
{
    foreach (Database::column('SELECT `demo_file` FROM ' . Database::table('files') . ' WHERE `bid` = :bid', ['bid' => $bid]) as $file) {
        delete_stored_file((string)$file);
    }
    Database::transaction(function () use ($bid) {
        Database::delete('files', ['bid' => $bid]);
        Database::delete('comments', ['bid' => $bid]);
        Database::delete('bans_edit', ['bid' => $bid]);
        Database::delete('bans', ['bid' => $bid]);
    });
}

/**
 * Marks bans whose time has passed as expired and records "Bantime expired" in _bans_edit.
 *
 * Called on every ban list request when auto_prune is enabled, and from the dashboard.
 *
 * @return int Number of bans marked as expired.
 */
function bans_prune(): int
{
    $rows = Database::all(
        'SELECT ba.`bid`, ba.`ban_created`, ba.`ban_length`, se.`timezone_fixx`
           FROM ' . Database::table('bans') . ' ba
           LEFT JOIN ' . Database::table('serverinfo') . ' se ON se.`address` = ba.`server_ip`
          WHERE ba.`expired` = 0 AND ba.`ban_length` > 0'
    );
    $count = 0;
    foreach ($rows as $r) {
        $end = (int)$r['ban_created'] + (int)$r['timezone_fixx'] * 3600 + (int)$r['ban_length'] * 60;
        if ($end < time()) {
            Database::update('bans', ['expired' => 1], ['bid' => (int)$r['bid']]);
            Database::insert('bans_edit', [
                'bid' => (int)$r['bid'], 'edit_time' => $end, 'admin_nick' => 'amxbans', 'edit_reason' => 'Bantime expired',
            ]);
            $count++;
        }
    }
    return $count;
}

/**
 * Ban length choices for select boxes.
 *
 * @return array<int, string> minutes => translated label.
 */
function ban_length_presets(): array
{
    $out = [];
    foreach ([5, 30, 60, 180, 720, 1440, 4320, 10080, 20160, 43200, 129600, 259200, 525600] as $m) {
        $out[$m] = format_duration($m * 60);
    }
    return $out;
}
