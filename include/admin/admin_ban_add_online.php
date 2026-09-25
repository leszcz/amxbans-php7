<?php
declare(strict_types=1);

/* Ban or kick players that are currently online (via RCON "amx_list" of the AMXBans plugin). */

require_once AMXB_ROOT . '/include/GameServer.php';

$servers = servers_all();
$sid = query_int('server', (int)($servers[0]['id'] ?? 0));
$server = $sid ? server_find($sid) : null;

if (in_array(action(), ['ban', 'kick'], true)) {
    if (!$server) {
        abort(404);
    }
    $player = [
        'userid'  => input_int('userid'),
        'name'    => mb_substr(input('name'), 0, 100),
        'steamid' => input('steamid'),
        'ip'      => input('ip'),
    ];
    $reason = mb_substr(input('custom_reason') !== '' ? input('custom_reason') : input('reason'), 0, 100);
    $errors = [];
    if ($player['userid'] <= 0) {
        $errors[] = '_NOREQUIREDFIELDS';
    }
    if ($reason === '') {
        $errors[] = '_NOREASON';
    }
    if ($errors) {
        flash('error', '_ERROR', $errors);
        redirect_back();
    }
    $gs = new GameServer((string)$server['address'], (string)$server['rcon']);

    if (action() === 'ban') {
        $type = input('ban_type') === 'SI' ? 'SI' : 'S';
        if ($type === 'S' && !valid_steamid($player['steamid'])) {
            flash('error', '_STEAMIDINVALID');
            redirect_back();
        }
        if ($type === 'SI' && !valid_ip($player['ip'])) {
            flash('error', '_IPINVALID');
            redirect_back();
        }
        $length = input_bool('permanent') ? 0 : max(1, input_int('length', 60));
        Database::insert('bans', [
            'player_ip' => valid_ip($player['ip']) ? $player['ip'] : '', 'player_id' => $player['steamid'],
            'player_nick' => $player['name'], 'admin_ip' => client_ip(), 'admin_id' => Auth::name(),
            'admin_nick' => Auth::name(), 'ban_type' => $type, 'ban_reason' => $reason, 'cs_ban_reason' => $reason,
            'ban_created' => time(), 'ban_length' => $length,
            'server_ip' => (string)$server['address'], 'server_name' => (string)$server['hostname'],
        ]);
        log_to_db('Add ban online', "{$player['name']} <{$player['steamid']}> banned for $length min on {$server['hostname']}");
    }
    $response = $gs->rcon('kick #' . $player['userid'] . ' "' . rcon_safe($reason) . '"');
    if (action() === 'kick') {
        log_to_db('Kick online', "{$player['name']} <{$player['steamid']}> kicked from {$server['hostname']}");
    }
    flash($response === null ? 'warning' : 'success', action() === 'ban' ? '_ADDBANSUCCESSKICK' : '_PLAYERKICKED',
        $response === null ? ['_RCON_TIMEDOUT'] : []);
    redirect_back();
}

$players = null;
$error = '';
if ($server) {
    $gs = new GameServer((string)$server['address'], (string)$server['rcon']);
    if (($server['rcon'] ?? '') === '') {
        $error = '_NORCON';
    } else {
        $players = $gs->amxList();
        if ($players === null) {
            $error = '_SERVEROFFLINE';
        } elseif (GameServer::isBadPassword($gs->rcon('echo'))) {
            $error = '_WRONGRCON';
            $players = null;
        }
    }
    foreach ($players ?? [] as &$p) {
        $p['country'] = Auth::can('ip_view') ? geo_country($p['ip']) : ['code' => '', 'name' => ''];
        $p['status_name'] = [0 => '_PLAYER', 1 => '_BOT', 2 => '_HLTV'][$p['status']] ?? '_UNKNOWN';
    }
    unset($p);
}

$view->page('admin/ban_add_online.tpl', [
    'servers' => $servers,
    'server'  => $server,
    'players' => $players,
    'error'   => $error,
    'reasons' => reasons_all(),
    'lengths' => ban_length_presets(),
    'old'     => [],
], '_TITLEBANADDONLINE');
