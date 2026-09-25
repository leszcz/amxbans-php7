<?php
declare(strict_types=1);

/**
 * Public ban list and ban details.
 *
 * GET parameters:
 * - bid   show the details page of one ban (handled by include/user/user_bd.php)
 * - show  "expired" lists expired bans instead of active ones
 * - q     quick search in nickname, SteamID and reason
 * - page  page number
 *
 * Template: ban_list.tpl (details: ban_detail.tpl).
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

require __DIR__ . '/include/bootstrap.php';

if (query_int('bid') > 0) {
    require __DIR__ . '/include/user/user_bd.php';
    exit;
}

if ($config->auto_prune) {
    bans_prune();
}

$show = query('show') === 'expired' ? 'expired' : 'active';
$q = mb_substr(query('q'), 0, 64);

$where = 'ba.`expired` = :expired';
$params = ['expired' => $show === 'expired' ? 1 : 0];
if ($q !== '') {
    $where .= ' AND (ba.`player_nick` LIKE :q1 OR ba.`player_id` LIKE :q2 OR ba.`ban_reason` LIKE :q3)';
    $like = '%' . addcslashes($q, '%_\\') . '%';
    $params += ['q1' => $like, 'q2' => $like, 'q3' => $like];
}

$total = (int)Database::value('SELECT COUNT(*) FROM ' . Database::table('bans') . ' ba WHERE ' . $where, $params);
$qs = http_build_query(array_filter(['show' => $show === 'expired' ? 'expired' : null, 'q' => $q !== '' ? $q : null]));
$pager = paginate($total, $config->bans_per_page, query_int('page', 1), 'ban_list.php?' . ($qs !== '' ? $qs . '&' : ''));

$rows = Database::all(
    ban_select_sql() . ' WHERE ' . $where . ' ORDER BY ba.`ban_created` DESC LIMIT :offset, :limit',
    $params + ['offset' => $pager['offset'], 'limit' => $pager['limit']]
);
$bans = array_map('ban_present', $rows);

// Counters shown in the list (one grouped query each instead of one per ban)
$ids = array_column($bans, 'bid');
$counts = ['comments' => [], 'files' => [], 'previous' => []];
if ($ids) {
    $in = implode(',', array_map('intval', $ids));
    foreach (['comments', 'files'] as $t) {
        foreach (Database::all('SELECT `bid`, COUNT(*) c FROM ' . Database::table($t) . " WHERE `bid` IN ($in) GROUP BY `bid`") as $r) {
            $counts[$t][(int)$r['bid']] = (int)$r['c'];
        }
    }
    $steamIds = array_values(array_unique(array_filter(array_column($bans, 'player_id'))));
    if ($steamIds) {
        $ph = implode(',', array_fill(0, count($steamIds), '?'));
        foreach (Database::all('SELECT `player_id`, COUNT(*) c FROM ' . Database::table('bans') . " WHERE `expired` = 1 AND `player_id` IN ($ph) GROUP BY `player_id`", $steamIds) as $r) {
            $counts['previous'][$r['player_id']] = (int)$r['c'];
        }
    }
}
foreach ($bans as &$ban) {
    $ban['comment_count'] = $counts['comments'][$ban['bid']] ?? 0;
    $ban['file_count'] = $counts['files'][$ban['bid']] ?? 0;
    $ban['previous'] = $counts['previous'][$ban['player_id']] ?? 0;
}
unset($ban);

// Is the visitor banned?
$visitorBan = Database::value(
    'SELECT `bid` FROM ' . Database::table('bans') . ' WHERE `expired` = 0 AND `player_ip` = :ip ORDER BY `ban_created` DESC LIMIT 1',
    ['ip' => client_ip()]
);

$view->page('ban_list.tpl', [
    'bans'        => $bans,
    'pager'       => $pager,
    'show'        => $show,
    'q'           => $q,
    'stats'       => [
        'active' => (int)Database::value('SELECT COUNT(*) FROM ' . Database::table('bans') . ' WHERE `expired` = 0'),
        'total'  => (int)Database::value('SELECT COUNT(*) FROM ' . Database::table('bans')),
    ],
    'visitor_ban' => $visitorBan ? (int)$visitorBan : 0,
    'visitor_ip'  => client_ip(),
    'cols'        => [
        'comments' => $config->use_comment && $config->show_comment_count,
        'files'    => $config->use_demo && $config->show_demo_count,
        'kicks'    => (bool)$config->show_kick_count,
    ],
], '_TITLEBANLIST');
