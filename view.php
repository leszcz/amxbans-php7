<?php
declare(strict_types=1);

require __DIR__ . '/include/bootstrap.php';
require __DIR__ . '/include/GameServer.php';

// JSON endpoint used by the server cards: view.php?server=ID
if (($sid = query_int('server')) > 0) {
    header('Content-Type: application/json; charset=UTF-8');
    header('Cache-Control: no-store');
    $server = server_find($sid);
    if (!$server) {
        http_response_code(404);
        exit(json_encode(['online' => false]));
    }
    $gs = new GameServer((string)$server['address']);
    $info = $gs->info();
    $out = ['online' => $info !== null, 'info' => null, 'players' => []];
    if ($info) {
        $rules = $gs->rules();
        $mod = preg_replace('/[^A-Za-z0-9_-]/', '', (string)$info['mod']);
        $map = preg_replace('/[^A-Za-z0-9_.-]/', '', (string)$info['map']);
        $out['info'] = [
            'name'        => $info['name'],
            'map'         => $info['map'],
            'mod'         => $info['mod'],
            'players'     => $info['players'],
            'max_players' => $info['max_players'],
            'bots'        => $info['bots'],
            'os'          => $info['os'],
            'password'    => (bool)$info['password'],
            'secure'      => (bool)$info['secure'],
            'nextmap'     => $rules['amx_nextmap'] ?? '',
            'timeleft'    => $rules['amx_timeleft'] ?? '',
            'map_image'   => is_file(__DIR__ . "/images/maps/$mod/$map.jpg") ? "images/maps/$mod/$map.jpg" : '',
        ];
        foreach ($gs->players() as $p) {
            $out['players'][] = [
                'name'  => $p['name'] !== '' ? $p['name'] : '…',
                'frags' => $p['frags'],
                'time'  => sprintf('%d:%02d', intdiv($p['time'], 60), $p['time'] % 60),
            ];
        }
    }
    exit(json_encode($out, JSON_INVALID_UTF8_SUBSTITUTE));
}

$t = Database::table('bans');
$stats = [
    'total'     => (int)Database::value("SELECT COUNT(*) FROM $t"),
    'active'    => (int)Database::value("SELECT COUNT(*) FROM $t WHERE `expired` = 0"),
    'permanent' => (int)Database::value("SELECT COUNT(*) FROM $t WHERE `expired` = 0 AND `ban_length` = 0"),
    'admins'    => (int)Database::value('SELECT COUNT(*) FROM ' . Database::table('amxadmins')),
    'servers'   => (int)Database::value('SELECT COUNT(*) FROM ' . Database::table('serverinfo')),
];
$latest = Database::one(ban_select_sql() . ' ORDER BY ba.`ban_created` DESC LIMIT 1');

$servers = array_map(fn($s) => [
    'id' => (int)$s['id'], 'hostname' => $s['hostname'], 'address' => $s['address'], 'gametype' => $s['gametype'],
], servers_all());

$view->page('view.tpl', [
    'servers' => $servers,
    'stats'   => $stats,
    'latest'  => $latest ? ban_present($latest) : null,
], '_TITLEVIEW');
