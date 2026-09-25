<?php
declare(strict_types=1);

/**
 * Admin page "Web admins" (admin.php?site=wm_wa).
 *
 * Every admin can open it to change their own password; the list of other
 * accounts needs webadmins_view and the actions add/save/delete need webadmins_edit.
 * POST actions: add, save, password (uid, password, password2), delete.
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

const MIN_PASSWORD = 8;
$levels = array_map('intval', Database::column('SELECT `level` FROM ' . Database::table('levels') . ' ORDER BY `level`'));

$checkUnique = function (string $name, string $email, int $exceptId = 0): void {
    $dup = Database::value(
        'SELECT COUNT(*) FROM ' . Database::table('webadmins') . ' WHERE (`username` = :u OR (`email` = :e AND `email` <> \'\')) AND `id` <> :id',
        ['u' => $name, 'e' => $email, 'id' => $exceptId]
    );
    if ($dup) {
        flash('error', '_WADMINADDEDFAILED');
        redirect_back();
    }
};
$readAccount = function () use ($levels): array {
    $data = [
        'username' => mb_substr(input('username'), 0, 32),
        'email'    => mb_substr(input('email'), 0, 64),
        'level'    => input_int('level'),
    ];
    $errors = [];
    if (mb_strlen($data['username']) < 2) {
        $errors[] = '_USERNAMETOSHORT';
    }
    if ($data['email'] !== '' && !valid_email($data['email'])) {
        $errors[] = '_EMAILINVALID';
    }
    if (!in_array($data['level'], $levels, true)) {
        $errors[] = '_ACCESSINVALID';
    }
    if ($errors) {
        flash('error', '_ERROR', $errors);
        redirect_back();
    }
    return $data;
};
$readPassword = function (): string {
    $pw = (string)($_POST['password'] ?? '');
    if (strlen($pw) < MIN_PASSWORD) {
        flash('error', '_PASSWORDTOSHORT', [sprintf(__('_MIN_CHARS'), MIN_PASSWORD)]);
        redirect_back();
    }
    if (!hash_equals($pw, (string)($_POST['password2'] ?? ''))) {
        flash('error', '_PASSWORDNOTMATCH');
        redirect_back();
    }
    return Auth::hashPassword($pw);
};

switch (action()) {
    case 'add':
        Auth::require('webadmins_edit');
        $data = $readAccount();
        $checkUnique($data['username'], $data['email']);
        $data['password'] = $readPassword();
        $data['try'] = 0;
        Database::insert('webadmins', $data);
        log_to_db('Webadmin config', "Added user: {$data['username']} (level {$data['level']})");
        flash('success', '_WADMINADDED');
        redirect_back();

    case 'save':
        Auth::require('webadmins_edit');
        $uid = input_int('uid');
        $data = $readAccount();
        $checkUnique($data['username'], $data['email'], $uid);
        if ($uid === Auth::id() && $data['level'] !== (int)Auth::user()['level']) {
            flash('error', '_CANNOT_CHANGE_OWN_LEVEL');
            redirect_back();
        }
        Database::update('webadmins', $data, ['id' => $uid]);
        log_to_db('Webadmin config', "Edited user: {$data['username']} (id $uid)");
        flash('success', '_WADMINSAVED');
        redirect_back();

    case 'password':
        $uid = input_int('uid');
        if ($uid !== Auth::id()) {
            Auth::require('webadmins_edit');
        }
        $hash = $readPassword();
        // Changing the password also logs out "remember me" sessions of that account.
        Database::update('webadmins', ['password' => $hash, 'logcode' => null, 'try' => 0], ['id' => $uid]);
        log_to_db('Webadmin config', "Changed password of user id $uid");
        if ($uid === Auth::id()) {
            Auth::refreshSession();
        }
        flash('success', '_PASSWORDCHANGED');
        redirect_back();

    case 'delete':
        Auth::require('webadmins_edit');
        $uid = input_int('uid');
        if ($uid === Auth::id()) {
            flash('error', '_CANNOT_DELETE_SELF');
            redirect_back();
        }
        Database::delete('webadmins', ['id' => $uid]);
        log_to_db('Webadmin config', "Deleted user id $uid");
        flash('success', '_WADMINDELETED');
        redirect_back();
}

$view->page('admin/webadmins.tpl', [
    'users'  => Database::all(
        'SELECT `id`, `username`, `email`, `level`, `last_action`, `try` FROM ' . Database::table('webadmins')
        . (Auth::can('webadmins_view') ? '' : ' WHERE `id` = ' . Auth::id()) . ' ORDER BY `level`, `username`'
    ),
    'levels' => $levels,
    'min_pw' => MIN_PASSWORD,
], '_TITLEWEBADMIN');
