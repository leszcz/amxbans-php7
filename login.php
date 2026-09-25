<?php
declare(strict_types=1);

/**
 * Web admin login form.
 *
 * POST fields: user, pass, remember (+ _token). Uses Auth::attempt(); failures
 * and lockouts are reported through flash messages (Post/Redirect/Get).
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

require __DIR__ . '/include/bootstrap.php';

if (Auth::check()) {
    redirect('admin.php');
}

$username = '';
if (is_post()) {
    $username = mb_substr(input('user'), 0, 32);
    $password = (string)($_POST['pass'] ?? '');
    if ($username === '' || $password === '') {
        flash('error', '_LOGINFAILED');
    } else {
        $result = Auth::attempt($username, $password, input_bool('remember'));
        if ($result['status'] === 'ok') {
            redirect('admin.php');
        }
        if ($result['status'] === 'blocked') {
            flash('error', '_LOGINBLOCKED', [sprintf(__('_LOGINBLOCKED_FOR'), (int)ceil($result['block_left'] / 60))]);
        } else {
            flash('error', '_LOGINFAILED', isset($result['tries_left']) ? [sprintf(__('_LOGIN_TRIES_LEFT'), $result['tries_left'])] : []);
        }
    }
    $_SESSION['_old_login'] = $username;
    redirect('login.php');
}

$username = (string)($_SESSION['_old_login'] ?? '');
unset($_SESSION['_old_login']);
$view->page('login.tpl', ['username' => $username], '_TITLELOGIN');
