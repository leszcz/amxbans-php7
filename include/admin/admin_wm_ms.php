<?php
declare(strict_types=1);

/**
 * Admin page "Settings" (admin.php?site=wm_ms, permission websettings_view).
 *
 * POST action "save" (websettings_edit) updates _webconfig. Design, banner,
 * language and start page are only accepted from the lists offered in the form.
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

$designs = [];
foreach (glob(AMXB_ROOT . '/templates/*/layout.tpl') ?: [] as $file) {
    $designs[] = basename(dirname($file));
}
$banners = [];
foreach (glob(AMXB_ROOT . '/images/banner/*.{png,jpg,jpeg,gif,webp}', GLOB_BRACE) ?: [] as $file) {
    $banners[] = basename($file);
}
$startPages = ['ban_list.php', 'admin_list.php', 'search.php', 'view.php'];

if (action() === 'save') {
    Auth::require('websettings_edit');
    $pick = fn(string $v, array $allowed, string $default) => in_array($v, $allowed, true) ? $v : $default;
    $types = array_filter(array_map(fn($t) => strtolower(trim($t)), explode(',', input('file_type'))), fn($t) => preg_match('/^[a-z0-9]{1,8}$/', $t));
    $data = [
        'cookie'              => preg_replace('/[^A-Za-z0-9_]/', '', input('cookie')) ?: 'amxbans',
        'design'              => $pick(input('design'), $designs, 'modern'),
        'bans_per_page'       => max(5, min(200, input_int('bans_per_page', 50))),
        'banner'              => $pick(input('banner'), $banners, ''),
        'banner_url'          => mb_substr(safe_url(input('banner_url')) === '#' ? '' : input('banner_url'), 0, 128),
        'default_lang'        => $pick(input('default_lang'), Lang::available(), 'english'),
        'start_page'          => $pick(input('start_page'), $startPages, 'ban_list.php'),
        'show_comment_count'  => input_bool('show_comment_count') ? 1 : 0,
        'show_demo_count'     => input_bool('show_demo_count') ? 1 : 0,
        'show_kick_count'     => input_bool('show_kick_count') ? 1 : 0,
        'use_demo'            => input_bool('use_demo') ? 1 : 0,
        'use_comment'         => input_bool('use_comment') ? 1 : 0,
        'demo_all'            => input_bool('demo_all') ? 1 : 0,
        'comment_all'         => input_bool('comment_all') ? 1 : 0,
        'use_capture'         => input_bool('use_capture') ? 1 : 0,
        'auto_prune'          => input_bool('auto_prune') ? 1 : 0,
        'max_offences'        => max(0, min(1000, input_int('max_offences', 10))),
        'max_offences_reason' => mb_substr(input('max_offences_reason') ?: 'max offences reached', 0, 128),
        'max_file_size'       => max(1, min(512, input_int('max_file_size', 2))),
        'file_type'           => mb_substr(implode(',', $types), 0, 64),
    ];
    $id = (int)Database::value('SELECT `id` FROM ' . Database::table('webconfig') . ' ORDER BY `id` LIMIT 1');
    Database::update('webconfig', $data, ['id' => $id]);
    $view->clearCompiledTemplate();
    log_to_db('Websetting config', 'Changed');
    flash('success', '_CONFIGSAVED');
    redirect_back();
}

$view->page('admin/settings.tpl', [
    'vars'        => Database::one('SELECT * FROM ' . Database::table('webconfig') . ' ORDER BY `id` LIMIT 1') ?? [],
    'designs'     => $designs,
    'banners'     => $banners,
    'start_pages' => $startPages,
    'upload_limit'=> ini_get('upload_max_filesize'),
], '_TITLESITE');
