<?php
declare(strict_types=1);

/*
 * AMX Mod X admins (_amxadmins). The game plugin loads these accounts.
 * Passwords are stored as MD5 because that is what the AMXBans plugin compares.
 */

$readAdmin = function (bool $isNew): array {
    $data = [
        'username' => mb_substr(input('username'), 0, 32),
        'access'   => input('access'),
        'flags'    => input('flags'),
        'steamid'  => mb_substr(input('steamid'), 0, 32),
        'nickname' => mb_substr(input('nickname'), 0, 32),
        'ashow'    => input_bool('ashow') ? 1 : 0,
    ];
    $errors = [];
    if ($data['username'] === '') {
        $errors[] = '_NOUSERNAME';
    }
    if (!valid_access_flags($data['access'])) {
        $errors[] = '_ACCESSINVALID';
    }
    $flagError = '';
    if (!valid_account_flags($data['flags'], $flagError)) {
        $errors[] = $flagError;
    }
    if ($data['steamid'] !== '' && !valid_steamid($data['steamid'])) {
        $errors[] = '_STEAMIDINVALID';
    }
    $password = (string)($_POST['password'] ?? '');
    if ($password !== '') {
        $data['password'] = md5($password);
    } elseif ($isNew) {
        $data['password'] = '';
    }
    $unlimited = input_bool('unlimited');
    $days = max(0, input_int('days'));
    if (!$unlimited && $days === 0 && $isNew) {
        $errors[] = '_NOVALIDTIME';
    }
    if ($errors) {
        flash('error', '_ERROR', $errors);
        redirect_back();
    }
    $data['days'] = $unlimited ? 0 : $days;
    return $data;
};

switch (action()) {
    case 'add':
        Auth::require('amxadmins_edit');
        $data = $readAdmin(true);
        $data['icq'] = 0;
        $data['created'] = time();
        $data['expired'] = $data['days'] > 0 ? time() + $data['days'] * 86400 : 0;
        $id = Database::insert('amxadmins', $data);
        $static = input('static_bantime') === 'no' ? 'no' : 'yes';
        $validServers = array_map('intval', Database::column('SELECT `id` FROM ' . Database::table('serverinfo')));
        foreach (array_unique(array_map('intval', input_array('servers'))) as $srv) {
            if (in_array($srv, $validServers, true)) {
                Database::insert('admins_servers', ['admin_id' => $id, 'server_id' => $srv, 'custom_flags' => '', 'use_static_bantime' => $static]);
            }
        }
        log_to_db('AMXXAdmin config', "Added admin: {$data['username']}");
        flash('success', '_AMXADMINADDED');
        redirect_back();

    case 'save':
        Auth::require('amxadmins_edit');
        $aid = input_int('aid');
        $admin = Database::one('SELECT * FROM ' . Database::table('amxadmins') . ' WHERE `id` = :id', ['id' => $aid]) ?? abort(404);
        $data = $readAdmin(false);
        $extend = max(0, input_int('extend'));
        if ($data['days'] === 0) {
            $data['expired'] = 0;
        } else {
            $data['days'] += $extend;
            $data['expired'] = (int)$admin['created'] + $data['days'] * 86400;
        }
        Database::update('amxadmins', $data, ['id' => $aid]);
        log_to_db('AMXXAdmin config', "Edited admin: {$data['username']} ({$data['nickname']})");
        flash('success', '_AMXADMINSAVESUCCESS');
        redirect_back();

    case 'delete':
        Auth::require('amxadmins_edit');
        $aid = input_int('aid');
        Database::transaction(function () use ($aid) {
            Database::delete('amxadmins', ['id' => $aid]);
            Database::delete('admins_servers', ['admin_id' => $aid]);
        });
        log_to_db('AMXXAdmin config', "Deleted admin #$aid");
        flash('success', '_AMXADMINDELETED');
        redirect_back();
}

$admins = Database::all('SELECT * FROM ' . Database::table('amxadmins') . ' ORDER BY `nickname`, `username`');
foreach ($admins as &$a) {
    $a['is_expired'] = (int)$a['expired'] > 0 && (int)$a['expired'] < time();
    $a['servers'] = (int)Database::value('SELECT COUNT(*) FROM ' . Database::table('admins_servers') . ' WHERE `admin_id` = :id', ['id' => $a['id']]);
    unset($a['password']);
}
unset($a);

$view->page('admin/amxadmins.tpl', [
    'admins'  => $admins,
    'servers' => servers_all(),
    'access_letters' => 'abcdefghijklmnopqrstuz',
    'flag_letters'   => 'abcdek',
], '_TITLEAMXADMINS');
