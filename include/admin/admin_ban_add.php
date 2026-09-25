<?php
declare(strict_types=1);

/**
 * Admin page "Add ban" (admin.php?site=ban_add, permission bans_add).
 *
 * POST action "add": name, steamid, ip, ban_type (S|SI), reason / custom_reason,
 * length (minutes) or permanent. Creates a web ban (server_name = "website").
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

if (action() === 'add') {
    $data = [
        'player_nick' => mb_substr(input('name'), 0, 100),
        'player_id'   => input('steamid'),
        'player_ip'   => input('ip'),
        'ban_type'    => input('ban_type') === 'SI' ? 'SI' : 'S',
        'reason'      => mb_substr(input('custom_reason') !== '' ? input('custom_reason') : input('reason'), 0, 100),
        'length'      => input_bool('permanent') ? 0 : max(0, input_int('length')),
    ];
    $errors = [];
    if ($data['player_nick'] === '') {
        $errors[] = '_NOBANNAME';
    }
    if ($data['reason'] === '') {
        $errors[] = '_NOREASON';
    }
    if ($data['player_id'] !== '' && !valid_steamid($data['player_id'])) {
        $errors[] = '_STEAMIDINVALID';
    }
    if ($data['player_ip'] !== '' && !valid_ip($data['player_ip'])) {
        $errors[] = '_IPINVALID';
    }
    if ($data['ban_type'] === 'S' && $data['player_id'] === '') {
        $errors[] = '_NOBANSTEAMID';
    }
    if ($data['ban_type'] === 'SI' && $data['player_ip'] === '') {
        $errors[] = '_NOIP';
    }
    if (!input_bool('permanent') && $data['length'] === 0) {
        $errors[] = '_NOVALIDTIME';
    }
    if (!$errors) {
        $exists = Database::value(
            'SELECT `bid` FROM ' . Database::table('bans') . ' WHERE `expired` = 0 AND ((`player_id` = :sid AND :sid2 <> \'\') OR (`player_ip` = :ip AND :ip2 <> \'\')) LIMIT 1',
            ['sid' => $data['player_id'], 'sid2' => $data['player_id'], 'ip' => $data['player_ip'], 'ip2' => $data['player_ip']]
        );
        if ($exists) {
            $errors[] = '_ACTIVBANEXISTS';
        }
    }
    if ($errors) {
        $_SESSION['_old'] = $data + ['custom' => input('custom_reason') !== '', 'permanent' => input_bool('permanent')];
        flash('error', '_ERROR', $errors);
        redirect_back();
    }
    $bid = Database::insert('bans', [
        'player_ip' => $data['player_ip'], 'player_id' => $data['player_id'], 'player_nick' => $data['player_nick'],
        'admin_ip' => client_ip(), 'admin_id' => Auth::name(), 'admin_nick' => Auth::name(),
        'ban_type' => $data['ban_type'], 'ban_reason' => $data['reason'], 'cs_ban_reason' => $data['reason'],
        'ban_created' => time(), 'ban_length' => $data['length'], 'server_ip' => '', 'server_name' => 'website',
    ]);
    log_to_db('Add ban', "Ban #$bid: {$data['player_nick']} ({$data['player_id']}) for {$data['length']} min");
    flash('success', '_BANADDSUCCESS');
    redirect('ban_list.php?bid=' . $bid);
}

$old = $_SESSION['_old'] ?? [];
unset($_SESSION['_old']);

$view->page('admin/ban_add.tpl', [
    'reasons' => reasons_all(),
    'lengths' => ban_length_presets(),
    'old'     => $old,
], '_TITLEBANADD');
