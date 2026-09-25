<?php
declare(strict_types=1);

/**
 * Admin page "Modules" (admin.php?site=so_mo, permission websettings_view).
 *
 * POST action "save" (websettings_edit): activ, menuname for module mid.
 * The module name is not editable because it selects the PHP file that is loaded.
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

if (action() === 'save') {
    Auth::require('websettings_edit');
    Database::update('modulconfig', [
        'activ'    => input_bool('activ') ? 1 : 0,
        'menuname' => mb_substr(input('menuname'), 0, 32),
    ], ['id' => input_int('mid')]);
    log_to_db('Modules config', 'Edited module #' . input_int('mid'));
    flash('success', '_MODULSAVED');
    redirect_back();
}

$modules = Database::all('SELECT * FROM ' . Database::table('modulconfig') . ' ORDER BY `name`');
foreach ($modules as &$m) {
    $name = preg_replace('/[^a-z0-9_]/i', '', (string)$m['name']);
    $m['installed'] = is_file(AMXB_ROOT . '/include/modules/modul_' . $name . '.php');
}
unset($m);

$view->page('admin/modules.tpl', ['modules' => $modules], '_TITLEMODULE');
