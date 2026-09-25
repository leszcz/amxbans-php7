<?php
declare(strict_types=1);

/**
 * Admin page "User level" (admin.php?site=wm_ul, permission permissions_edit).
 *
 * POST actions: add (next level number), save (level + one radio per permission),
 * delete (only levels without admins). Admins cannot remove permissions_edit from their own level.
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

switch (action()) {
    case 'add':
        $next = (int)Database::value('SELECT COALESCE(MAX(`level`), 0) + 1 FROM ' . Database::table('levels'));
        Database::insert('levels', ['level' => $next]);
        log_to_db('User Level config', "Added level $next");
        flash('success', '_LEVELADDED');
        redirect_back();

    case 'save':
        $level = input_int('level');
        $data = [];
        foreach (Auth::PERMISSIONS as $perm) {
            $value = input($perm, 'no');
            $allowed = in_array($perm, Auth::OWN_PERMISSIONS, true) ? ['yes', 'no', 'own'] : ['yes', 'no'];
            $data[$perm] = in_array($value, $allowed, true) ? $value : 'no';
        }
        if ($level === (int)Auth::user()['level'] && $data['permissions_edit'] !== 'yes') {
            flash('error', '_CANNOT_REMOVE_OWN_PERMISSION');
            redirect_back();
        }
        Database::update('levels', $data, ['level' => $level]);
        log_to_db('User Level config', "Edited level $level");
        flash('success', '_LEVELSAVED');
        redirect_back();

    case 'delete':
        $level = input_int('level');
        $users = (int)Database::value('SELECT COUNT(*) FROM ' . Database::table('webadmins') . ' WHERE `level` = :l', ['l' => $level]);
        if ($users > 0) {
            flash('error', '_LEVELDELFAILED');
            redirect_back();
        }
        Database::delete('levels', ['level' => $level]);
        log_to_db('User Level config', "Deleted level $level");
        flash('success', '_LEVELDELETED');
        redirect_back();
}

$levels = Database::all(
    'SELECT l.*, (SELECT COUNT(*) FROM ' . Database::table('webadmins') . ' w WHERE w.`level` = l.`level`) AS users
       FROM ' . Database::table('levels') . ' l ORDER BY l.`level`'
);
$labels = [
    'bans_add' => ['_BANS', '_ADD'], 'bans_edit' => ['_BANS', '_EDIT'], 'bans_delete' => ['_BANS', '_DELETE'],
    'bans_unban' => ['_BANS', '_LEVELUNBAN'], 'bans_import' => ['_BANS', '_LEVELIMPORT'], 'bans_export' => ['_BANS', '_LEVELEXPORT'],
    'amxadmins_view' => ['_AMXADMINS', '_LEVELVIEW'], 'amxadmins_edit' => ['_AMXADMINS', '_EDIT'],
    'webadmins_view' => ['_WEBADMINS', '_LEVELVIEW'], 'webadmins_edit' => ['_WEBADMINS', '_EDIT'],
    'websettings_view' => ['_WEBSETTINGS', '_LEVELVIEW'], 'websettings_edit' => ['_WEBSETTINGS', '_EDIT'],
    'permissions_edit' => ['_PERM', '_EDIT'], 'prune_db' => ['_OTHER', '_DBPRUNE'],
    'servers_edit' => ['_SERVER', '_EDIT'], 'ip_view' => ['_OTHER', '_VIEWIP'],
];

foreach ($labels as $perm => &$label) {
    $label[] = in_array($perm, Auth::OWN_PERMISSIONS, true) ? ['yes', 'own', 'no'] : ['yes', 'no'];
}
unset($label);

$view->page('admin/levels.tpl', [
    'levels' => $levels,
    'labels' => $labels,
], '_TITLEUSERLEVEL');
