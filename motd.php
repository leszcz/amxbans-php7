<?php
declare(strict_types=1);

/*
 * Ban information shown to a banned player in the in-game MOTD window.
 * URL format used by the AMXBans plugin: motd.php?sid=<char><bid>&adm=<0|1>&lang=<amxx language code>
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
