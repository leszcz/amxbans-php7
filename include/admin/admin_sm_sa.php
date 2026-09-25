<?php
declare(strict_types=1);

/**
 * Admin page "Assign AMX admins" (admin.php?site=sm_sa).
 *
 * GET server=<id>. Opening needs amxadmins_view; POST action "save" (amxadmins_edit)
 * replaces the assignments of the server: active[], custom_flags[aid], static_bantime[aid].
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

$servers = servers_all();
$sid = query_int('server', (int)($servers[0]['id'] ?? 0));
$server = $sid ? server_find($sid) : null;

if (action() === 'save' && $server) {
    Auth::require('amxadmins_edit');
    $active = array_map('intval', input_array('active'));
    $flags = input_array('custom_flags');
    $static = input_array('static_bantime');
    $validAdmins = array_map('intval', Database::column('SELECT `id` FROM ' . Database::table('amxadmins')));
    Database::transaction(function () use ($sid, $active, $flags, $static, $validAdmins) {
        Database::delete('admins_servers', ['server_id' => $sid]);
        foreach (array_unique($active) as $aid) {
            if (!in_array($aid, $validAdmins, true)) {
                continue;
            }
            $custom = preg_replace('/[^a-uz]/', '', (string)($flags[$aid] ?? ''));
            Database::insert('admins_servers', [
                'admin_id' => $aid, 'server_id' => $sid, 'custom_flags' => mb_substr($custom, 0, 32),
                'use_static_bantime' => ($static[$aid] ?? 'yes') === 'no' ? 'no' : 'yes',
            ]);
        }
    });
    log_to_db('Server Admin config', 'Edited admins on server: ' . $server['hostname']);
    flash('success', '_SADMINSAVED');
    redirect_back();
}

$admins = [];
if ($server) {
    $admins = Database::all(
        'SELECT a.`id`, a.`username`, a.`nickname`, a.`access`, a.`steamid`, s.`custom_flags`, s.`use_static_bantime`, s.`admin_id` IS NOT NULL AS active
           FROM ' . Database::table('amxadmins') . ' a
           LEFT JOIN ' . Database::table('admins_servers') . ' s ON s.`admin_id` = a.`id` AND s.`server_id` = :sid
          ORDER BY active DESC, a.`nickname`',
        ['sid' => $sid]
    );
}

$view->page('admin/server_admins.tpl', ['servers' => $servers, 'server' => $server, 'admins' => $admins], '_TITLESERVERADMINS');
