<?php
declare(strict_types=1);

/*
 * Import AMX Mod X admins from a users.ini file and assign them to a server.
 * Original module by Portek, PDO version by l3szcz.
 * Format: "auth" "password" "access flags" "account flags"
 */

Auth::require('amxadmins_edit');

if (action() === 'import') {
    $sid = input_int('server');
    $server = server_find($sid) ?? abort(404);
    $static = input('static_bantime') === 'no' ? 'no' : 'yes';
    $upload = $_FILES['file'] ?? null;
    if (!is_array($upload) || ($upload['error'] ?? 1) !== UPLOAD_ERR_OK || !is_uploaded_file((string)$upload['tmp_name'])) {
        flash('error', '_FILENOFILE');
        redirect_back();
    }
    if ((int)$upload['size'] > 2 * 1024 * 1024) {
        flash('error', '_FILETOBIG');
        redirect_back();
    }
    $added = $assigned = $skipped = 0;
    foreach (file((string)$upload['tmp_name'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === ';' || !preg_match_all('/"([^"]*)"/', $line, $m) || count($m[1]) < 4) {
            continue;
        }
        [$auth, $password, $access, $flags] = $m[1];
        $flagError = '';
        if ($auth === '' || !valid_access_flags($access) || !valid_account_flags($flags, $flagError)) {
            $skipped++;
            continue;
        }
        $steamid = valid_steamid($auth) ? $auth : '';
        $id = (int)Database::value('SELECT `id` FROM ' . Database::table('amxadmins') . ' WHERE `username` = :u LIMIT 1', ['u' => $auth]);
        if (!$id) {
            $id = Database::insert('amxadmins', [
                'username' => mb_substr($auth, 0, 32), 'password' => $password !== '' ? md5($password) : '',
                'access' => $access, 'flags' => $flags, 'steamid' => $steamid, 'nickname' => mb_substr($auth, 0, 32),
                'icq' => 0, 'ashow' => 1, 'created' => time(), 'expired' => 0, 'days' => 0,
            ]);
            $added++;
        }
        $exists = Database::value('SELECT 1 FROM ' . Database::table('admins_servers') . ' WHERE `admin_id` = :a AND `server_id` = :s', ['a' => $id, 's' => $sid]);
        if (!$exists) {
            Database::insert('admins_servers', ['admin_id' => $id, 'server_id' => $sid, 'custom_flags' => '', 'use_static_bantime' => $static]);
            $assigned++;
        }
    }
    log_to_db('Import admins', "users.ini → {$server['hostname']}: $added added, $assigned assigned, $skipped skipped");
    flash('success', '_IMPORTSUCCESS', [sprintf(__('_USERSI_RESULT'), $added, $assigned, $skipped)]);
    redirect_back();
}

$view->page('modules/usersi.tpl', ['servers' => servers_all()], '_MENUIMPORTADMINS');
