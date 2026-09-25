<?php
declare(strict_types=1);

require __DIR__ . '/include/bootstrap.php';

// Visible AMX Mod X admins, grouped by the servers they are assigned to.
$admins = [];
foreach (Database::all(
    'SELECT * FROM ' . Database::table('amxadmins') . '
      WHERE `ashow` = 1 AND (`expired` = 0 OR `expired` > :now)
      ORDER BY `access` DESC, `nickname`',
    ['now' => time()]
) as $a) {
    $a['steam_url'] = steam_profile_url($a['steamid']);
    $a['display'] = $a['nickname'] !== '' && $a['nickname'] !== null ? $a['nickname'] : $a['username'];
    $admins[(int)$a['id']] = $a;
}

$servers = [];
foreach (servers_all() as $s) {
    $servers[(int)$s['id']] = ['id' => (int)$s['id'], 'hostname' => $s['hostname'], 'gametype' => $s['gametype'], 'address' => $s['address'], 'admins' => []];
}
foreach (Database::all('SELECT * FROM ' . Database::table('admins_servers')) as $link) {
    $sid = (int)$link['server_id'];
    $aid = (int)$link['admin_id'];
    if (isset($servers[$sid], $admins[$aid])) {
        $servers[$sid]['admins'][] = $admins[$aid] + ['custom_flags' => $link['custom_flags']];
    }
}

$view->page('admin_list.tpl', [
    'servers' => array_values(array_filter($servers, fn($s) => $s['admins'])),
], '_TITLEADMINLIST');
