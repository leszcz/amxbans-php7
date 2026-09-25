<?php
declare(strict_types=1);

/**
 * Creates a fresh TEST database with sample data (including XSS/SQL-injection payloads).
 *
 * WARNING: drops and recreates all AMXBans tables of the configured prefix.
 * Never run it against a production database.
 *
 * Usage: php tests/seed.php            (uses include/db.config.inc.php)
 *
 * Accounts created: admin / admin123 (level 1, all permissions),
 *                   mod / mod123 (level 2, MD5 password to test the upgrade).
 *
 * @package AMXBans\Tests
 */

define('AMXB_ROOT', dirname(__DIR__));
require AMXB_ROOT . '/include/Database.php';
require AMXB_ROOT . '/install/schema.php';

if (PHP_SAPI !== 'cli') {
    exit('CLI only');
}
$config = new stdClass();
require AMXB_ROOT . '/include/db.config.inc.php';
Database::configure($config);
$p = Database::prefix();

foreach (install_schema() as $table => $columns) {
    Database::pdo()->exec('DROP TABLE IF EXISTS ' . Database::table($table));
    Database::pdo()->exec('CREATE TABLE ' . Database::table($table) . " ($columns) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}
foreach (install_default_data('english') as $table => $rows) {
    foreach ($rows as $row) {
        Database::insert($table, $row);
    }
}
Database::run('UPDATE ' . Database::table('webconfig') . " SET `bans_per_page` = 20, `auto_prune` = 0, `use_capture` = 0, `comment_all` = 1, `demo_all` = 1");
Database::insert('levels', ['level' => 2, 'bans_add' => 'yes', 'bans_edit' => 'own', 'bans_delete' => 'own', 'bans_unban' => 'own', 'amxadmins_view' => 'yes']);
Database::insert('webadmins', ['username' => 'admin', 'password' => password_hash('admin123', PASSWORD_DEFAULT), 'level' => 1, 'email' => 'admin@example.com', 'try' => 0]);
Database::insert('webadmins', ['username' => 'mod', 'password' => md5('mod123'), 'level' => 2, 'email' => 'mod@example.com', 'try' => 0]);

$now = time();
foreach ([
    ['Test CS 1.6 #1', '127.0.0.1:27015', 'cstrike', 'secret', 0],
    ['<b>XSS</b> Server', '10.0.0.5:27016', 'czero', 'x', 1],
] as [$host, $addr, $mod, $rcon, $tz]) {
    Database::insert('serverinfo', ['timestamp' => $now, 'hostname' => $host, 'address' => $addr, 'gametype' => $mod,
        'rcon' => $rcon, 'amxban_version' => '6.14.4', 'amxban_motd' => '', 'motd_delay' => 10, 'amxban_menu' => 1, 'timezone_fixx' => $tz]);
}
Database::insert('amxadmins', ['username' => 'STEAM_0:1:111', 'password' => '', 'access' => 'abcdefghijklmnopqrstu', 'flags' => 'ce',
    'steamid' => 'STEAM_0:1:111', 'nickname' => 'HeadAdmin', 'icq' => 0, 'ashow' => 1, 'created' => $now, 'expired' => 0, 'days' => 0]);
Database::insert('amxadmins', ['username' => 'STEAM_0:0:222', 'password' => '', 'access' => 'bcdefij', 'flags' => 'ce',
    'steamid' => 'STEAM_0:0:222', 'nickname' => 'Moderator', 'icq' => 0, 'ashow' => 1, 'created' => $now, 'expired' => $now + 30 * 86400, 'days' => 30]);
Database::insert('admins_servers', ['admin_id' => 1, 'server_id' => 1, 'custom_flags' => '', 'use_static_bantime' => 'yes']);
Database::insert('admins_servers', ['admin_id' => 2, 'server_id' => 1, 'custom_flags' => 'bcd', 'use_static_bantime' => 'no']);
foreach ([['Cheating', 0], ['Spam', 60], ['Insults', 1440]] as [$r, $t]) {
    Database::insert('reasons', ['reason' => $r, 'static_bantime' => $t]);
}

$names = ['xX_Sniper_Xx', '<script>alert(1)</script>', 'Łukasz Żółć', '[ProClan] Headshot', 'noob"onmouseover=alert(1)', 'Player', 'BotKiller', 'Ninja'];
$reasons = ['Cheating', 'Spam', 'Aimbot [url=javascript:alert(1)]x[/url]', 'Insults'];
for ($i = 0; $i < 45; $i++) {
    $len = [0, 60, 1440, 10080, -1][$i % 5];
    Database::insert('bans', [
        'player_ip' => '83.12.' . ($i % 250) . '.' . (10 + $i), 'player_id' => 'STEAM_0:' . ($i % 2) . ':' . (100000 + $i * 37 % 13),
        'player_nick' => $names[$i % count($names)] . ($i > 7 ? " $i" : ''), 'admin_ip' => '10.0.0.1', 'admin_id' => 'STEAM_0:1:111',
        'admin_nick' => 'HeadAdmin', 'ban_type' => $i % 3 ? 'S' : 'SI', 'ban_reason' => $reasons[$i % 4], 'cs_ban_reason' => 'r',
        'ban_created' => $now - $i * 3600 * 7, 'ban_length' => $len, 'server_ip' => $i % 4 ? '127.0.0.1:27015' : '',
        'server_name' => $i % 4 ? 'Test CS 1.6 #1' : 'website', 'ban_kicks' => $i % 3, 'expired' => ($len === -1 || $i % 7 === 0) ? 1 : 0,
    ]);
}
Database::insert('comments', ['name' => 'Guest', 'comment' => 'Nice [b]bold[/b] :) [url=javascript:alert(1)]evil[/url] <img src=x onerror=alert(1)>',
    'email' => 'g@x.com', 'addr' => '1.2.3.4', 'date' => $now, 'bid' => 1]);

echo "Test database '{$config->db_db}' (prefix {$p}_) seeded.\n";
