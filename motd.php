<?php
declare(strict_types=1);

/**
 * Ban information for the in-game MOTD window of a banned player.
 *
 * URL used by the AMXBans plugin: motd.php?sid=<char><bid>&adm=<0|1>&lang=<amxx language code>
 * (configured per server in Admin area → Server). Template: motd.tpl (standalone page).
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

define('AMXB_SKIP_CSRF', true);
require __DIR__ . '/include/bootstrap.php';
require __DIR__ . '/include/amxx_langs.inc.php';

$sid = query('sid');
$bid = (int)preg_replace('/\D/', '', $sid);
$ban = $bid > 0 ? ban_find($bid) : null;
if (!$ban) {
    abort(404, '_BANNOTFOUND');
}

$langCode = query('lang');
if (isset($amxx_langs[$langCode])) {
    Lang::set($amxx_langs[$langCode]);
}

$view->assign('show_admin', query('adm') === '1');
$view->assign('ban', $ban);
$view->assignCommon();
header('Content-Type: text/html; charset=UTF-8');
$view->display('motd.tpl');
