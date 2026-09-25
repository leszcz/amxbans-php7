<?php
declare(strict_types=1);

require __DIR__ . '/include/bootstrap.php';

Auth::require();

/*
 * Admin area router: admin.php?site=<page> or admin.php?modul=<module>.
 * Only pages listed here can be loaded; each has the permission needed to open it.
 * (Actions inside a page may require additional permissions.)
 */
$pages = [
    'so_in'          => null,
    'ban_add'        => 'bans_add',
    'ban_add_online' => 'bans_add',
    'sm_sv'          => 'servers_edit',
    'sm_bg'          => 'servers_edit',
    'sm_av'          => 'amxadmins_view',
    'sm_sa'          => 'amxadmins_view',
    'wm_wa'          => null, // everybody may change their own password; the list needs webadmins_view
    'wm_ul'          => 'permissions_edit',
    'wm_um'          => 'websettings_view',
    'wm_ms'          => 'websettings_view',
    'so_lg'          => 'websettings_view',
    'so_mo'          => 'websettings_view',
    'so_vs'          => null,
];

$modules = modules_active();
$module = query('modul');
$site = query('site', 'so_in');
if ($site === 'so_up') {
    $site = 'so_vs';
}

$view->assign('admin_nav', admin_navigation($modules));

if ($module !== '') {
    if (!isset($modules[$module])) {
        abort(404);
    }
    $view->assign('admin_site', 'modul_' . $module);
    require __DIR__ . '/include/modules/modul_' . $module . '.php';
    exit;
}

if (!array_key_exists($site, $pages)) {
    abort(404);
}
if ($pages[$site] !== null) {
    Auth::require($pages[$site]);
}
$view->assign('admin_site', $site);
require __DIR__ . '/include/admin/admin_' . $site . '.php';

/** Sidebar of the admin area, filtered by the permissions of the current admin. */
function admin_navigation(array $modules): array
{
    $groups = [
        ['_ADMINAREA', [
            ['so_in', '_MENUINFO', 'squares-2x2', null],
            ['ban_add', '_ADDBAN', 'plus', 'bans_add'],
            ['ban_add_online', '_ADDBANONLINE', 'bolt', 'bans_add'],
        ]],
        ['_SERVER', [
            ['sm_sv', '_MENUSERVER', 'server-stack', 'servers_edit'],
            ['sm_bg', '_MENUREASONS', 'clipboard-document-list', 'servers_edit'],
            ['sm_av', '_MENUAMXADMINS', 'users', 'amxadmins_view'],
            ['sm_sa', '_MENUSERVERADMINS', 'key', 'amxadmins_view'],
        ]],
        ['_WEB', [
            ['wm_wa', '_MENUWEBADMINS', 'user', null],
            ['wm_ul', '_MENUUSERLEVEL', 'shield-check', 'permissions_edit'],
            ['wm_um', '_MENUUSERMENU', 'list-bullet', 'websettings_view'],
            ['wm_ms', '_MENUWEBCONFIG', 'cog-6-tooth', 'websettings_view'],
            ['so_lg', '_MENULOGS', 'document-text', 'websettings_view'],
        ]],
        ['_MODULES', [
            ['so_mo', '_MODULES', 'puzzle-piece', 'websettings_view'],
            ['so_vs', '_MENUUPDATE', 'arrow-path', null],
        ]],
    ];
    foreach ($modules as $name => $m) {
        $groups[3][1][] = ['modul_' . $name, (string)$m['menuname'], $name === 'iexport' ? 'archive-box' : 'arrow-up-tray', null];
    }
    $out = [];
    foreach ($groups as [$label, $items]) {
        $visible = [];
        foreach ($items as [$site, $title, $icon, $perm]) {
            if ($perm === null || Auth::can($perm)) {
                $url = str_starts_with($site, 'modul_') ? 'admin.php?modul=' . substr($site, 6) : 'admin.php?site=' . $site;
                $visible[] = ['site' => $site, 'title' => $title, 'icon' => $icon, 'url' => $url];
            }
        }
        if ($visible) {
            $out[] = ['label' => $label, 'items' => $visible];
        }
    }
    return $out;
}
