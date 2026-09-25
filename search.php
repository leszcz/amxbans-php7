<?php
declare(strict_types=1);

/**
 * Ban search.
 *
 * Uses GET (results can be bookmarked). Parameters: nick, steamid, ip (only with
 * the ip_view permission), reason, date (Y-m-d), admin, server ("website" for web
 * bans), times (players with at least N bans). Every criterion is bound as a
 * parameter. Template: search.tpl.
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

require __DIR__ . '/include/bootstrap.php';

$criteria = [
    'nick'    => mb_substr(query('nick'), 0, 64),
    'steamid' => mb_substr(query('steamid'), 0, 35),
    'ip'      => mb_substr(query('ip'), 0, 45),
    'reason'  => mb_substr(query('reason'), 0, 64),
    'date'    => query('date'),
    'admin'   => mb_substr(query('admin'), 0, 64),
    'server'  => mb_substr(query('server'), 0, 100),
    'times'   => query_int('times'),
];

$where = [];
$params = [];
$like = fn(string $v) => '%' . addcslashes($v, '%_\\') . '%';

if ($criteria['nick'] !== '') {
    $where[] = 'ba.`player_nick` LIKE :nick';
    $params['nick'] = $like($criteria['nick']);
}
if ($criteria['steamid'] !== '') {
    $where[] = 'ba.`player_id` LIKE :steamid';
    $params['steamid'] = $like($criteria['steamid']);
}
if ($criteria['ip'] !== '' && Auth::can('ip_view')) {
    $where[] = 'ba.`player_ip` LIKE :ip';
    $params['ip'] = $like($criteria['ip']);
}
if ($criteria['reason'] !== '') {
    $where[] = 'ba.`ban_reason` LIKE :reason';
    $params['reason'] = $like($criteria['reason']);
}
if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $criteria['date'])) {
    $start = strtotime($criteria['date'] . ' 00:00:00');
    if ($start !== false) {
        $where[] = 'ba.`ban_created` BETWEEN :d1 AND :d2';
        $params['d1'] = $start;
        $params['d2'] = $start + 86399;
    }
} else {
    $criteria['date'] = '';
}
if ($criteria['admin'] !== '') {
    $where[] = '(ba.`admin_id` = :admin1 OR ba.`admin_nick` = :admin2)';
    $params['admin1'] = $params['admin2'] = $criteria['admin'];
}
if ($criteria['server'] !== '') {
    if ($criteria['server'] === 'website') {
        $where[] = 'ba.`server_name` = \'website\'';
    } else {
        $where[] = 'ba.`server_ip` = :server';
        $params['server'] = $criteria['server'];
    }
}
if ($criteria['times'] > 1) {
    $where[] = 'ba.`player_id` IN (SELECT `player_id` FROM ' . Database::table('bans') . ' WHERE `player_id` <> \'\' GROUP BY `player_id` HAVING COUNT(*) >= :times)';
    $params['times'] = $criteria['times'];
}

$searched = $where !== [];
$results = ['active' => [], 'expired' => []];
if ($searched) {
    $rows = Database::all(
        ban_select_sql() . ' WHERE ' . implode(' AND ', $where) . ' ORDER BY ba.`ban_created` DESC LIMIT 500',
        $params
    );
    foreach ($rows as $row) {
        $ban = ban_present($row);
        $results[(int)$row['expired'] === 0 ? 'active' : 'expired'][] = $ban;
    }
}

// Options for the select boxes
$admins = Database::all(
    'SELECT ba.`admin_id`, MAX(ba.`admin_nick`) AS admin_nick, MAX(aa.`nickname`) AS nickname
       FROM ' . Database::table('bans') . ' ba
       LEFT JOIN ' . Database::table('amxadmins') . ' aa ON aa.`steamid` = ba.`admin_id`
      WHERE ba.`admin_id` <> \'\'
      GROUP BY ba.`admin_id` ORDER BY admin_nick'
);
$servers = Database::all(
    'SELECT `server_ip`, MAX(`server_name`) AS server_name FROM ' . Database::table('bans') . '
      WHERE `server_name` <> \'website\' AND `server_ip` <> \'\' GROUP BY `server_ip` ORDER BY server_name'
);

$view->page('search.tpl', [
    'criteria' => $criteria,
    'searched' => $searched,
    'results'  => $results,
    'admins'   => $admins,
    'servers'  => $servers,
], '_TITLESEARCH');
