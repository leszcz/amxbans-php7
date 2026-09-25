<?php
declare(strict_types=1);

/*
 * Ban details: ban_list.php?bid=123
 * Included from ban_list.php after bootstrap.
 */

$bid = query_int('bid');
$ban = ban_find($bid);
if (!$ban) {
    abort(404, '_BANNOTFOUND');
}
$self = 'ban_list.php?bid=' . $bid;
$isGuest = !Auth::check();
$canComment = $config->use_comment && ($config->comment_all || !$isGuest);
$canUpload = $config->use_demo && ($config->demo_all || !$isGuest);
$guestNeedsCaptcha = $isGuest && $config->use_capture;

// ----------------------------------------------------------------------------
// File download / thumbnail (GET)
// ----------------------------------------------------------------------------
if (($did = query_int('download')) > 0 && $config->use_demo) {
    $file = Database::one('SELECT * FROM ' . Database::table('files') . ' WHERE `id` = :id AND `bid` = :bid', ['id' => $did, 'bid' => $bid]);
    $path = $file ? stored_file_path((string)$file['demo_file'], query('thumb') === '1') : null;
    if (!$path || !is_file($path)) {
        abort(404, '_FILENOTAVAILABLE');
    }
    if (query('thumb') === '1') {
        header('Content-Type: image/png');
        header('Cache-Control: private, max-age=86400');
        readfile($path);
        exit;
    }
    Database::run('UPDATE ' . Database::table('files') . ' SET `down_count` = `down_count` + 1 WHERE `id` = :id', ['id' => $did]);
    send_download($path, (string)$file['demo_real']);
}

// ----------------------------------------------------------------------------
// Actions (POST)
// ----------------------------------------------------------------------------
switch (action()) {
    case 'edit_ban':
        if (!Auth::canOnBan('bans_edit', $ban)) {
            abort(403);
        }
        $editReason = mb_substr(input('edit_reason'), 0, 255);
        $unban = input_bool('unban');
        $errors = [];
        if ($editReason === '') {
            $errors[] = '_NOEDITREASON';
        }
        if ($unban) {
            if (!Auth::canOnBan('bans_unban', $ban)) {
                abort(403);
            }
            if (!$errors) {
                Database::update('bans', ['ban_length' => -1, 'expired' => 1], ['bid' => $bid]);
            }
        } else {
            $data = [
                'player_nick'   => mb_substr(input('player_nick'), 0, 100),
                'player_id'     => input('player_id'),
                'ban_type'      => input('ban_type') === 'SI' ? 'SI' : 'S',
                'ban_reason'    => mb_substr(input('ban_reason'), 0, 100),
                'cs_ban_reason' => mb_substr(input('ban_reason'), 0, 100),
                'ban_length'    => max(0, input_int('ban_length', (int)$ban['ban_length'])),
            ];
            if (Auth::can('ip_view')) {
                $data['player_ip'] = input('player_ip');
            }
            if ($data['player_nick'] === '') {
                $errors[] = '_NONICKNAME';
            }
            if ($data['ban_reason'] === '') {
                $errors[] = '_NOREASON';
            }
            if ($data['player_id'] !== '' && !valid_steamid($data['player_id'])) {
                $errors[] = '_STEAMIDINVALID';
            }
            $ip = $data['player_ip'] ?? (string)$ban['player_ip'];
            if ($ip !== '' && !valid_ip($ip)) {
                $errors[] = '_IPINVALID';
            }
            if ($data['ban_type'] === 'S' && $data['player_id'] === '') {
                $errors[] = '_NOBANSTEAMID';
            }
            if ($data['ban_type'] === 'SI' && $ip === '') {
                $errors[] = '_NOIP';
            }
            $end = (int)$ban['ban_created'] + $data['ban_length'] * 60;
            $data['expired'] = ($data['ban_length'] > 0 && $end < time()) ? 1 : 0;
            if (!$errors) {
                Database::update('bans', $data, ['bid' => $bid]);
            }
        }
        if ($errors) {
            flash('error', '_ERROR', $errors);
            redirect($self);
        }
        Database::insert('bans_edit', [
            'bid' => $bid, 'edit_time' => time(), 'admin_nick' => Auth::name(),
            'edit_reason' => ($unban ? 'Unban: ' : '') . $editReason,
        ]);
        log_to_db('Ban edit', ($unban ? 'Unban' : 'Edited ban') . ": ID $bid ({$ban['player_nick']} / {$ban['player_id']})");
        flash('success', $unban ? '_UNBANNED' : '_BANEDITED');
        redirect($self);

    case 'delete_ban':
        if (!Auth::canOnBan('bans_delete', $ban)) {
            abort(403);
        }
        ban_delete($bid);
        log_to_db('Ban edit', "Deleted ban: ID $bid ({$ban['player_nick']} / {$ban['player_id']})");
        flash('success', '_BANDELETED');
        redirect('ban_list.php');

    case 'add_comment':
        if (!$canComment) {
            abort(403);
        }
        $errors = [];
        $comment = mb_substr(input('comment'), 0, 2000);
        $name = $isGuest ? mb_substr(input('name'), 0, 35) : Auth::name();
        $email = $isGuest ? mb_substr(input('email'), 0, 100) : (string)(Auth::user()['email'] ?? '');
        if ($guestNeedsCaptcha && !captcha_check(input('captcha'))) {
            $errors[] = '_WRONGCAPTCHA';
        }
        if ($isGuest && time() - (int)($_SESSION['_last_post'] ?? 0) < 30) {
            $errors[] = '_TOOFAST';
        }
        if (mb_strlen($comment) < 2) {
            $errors[] = '_NOCOMMENT';
        }
        if ($name === '') {
            $errors[] = '_NONAME';
        }
        if ($email !== '' && !valid_email($email)) {
            $errors[] = '_EMAILINVALID';
        }
        if ($errors) {
            $_SESSION['_old'] = ['name' => $name, 'email' => $email, 'comment' => $comment];
            flash('error', '_ERROR', $errors);
            redirect($self . '#comments');
        }
        Database::insert('comments', [
            'name' => $name, 'comment' => $comment, 'email' => $email,
            'addr' => client_ip(), 'date' => time(), 'bid' => $bid,
        ]);
        $_SESSION['_last_post'] = time();
        flash('success', '_COMADDED');
        redirect($self . '#comments');

    case 'edit_comment':
    case 'delete_comment':
        $cid = input_int('cid');
        $perm = action() === 'edit_comment' ? 'bans_edit' : 'bans_delete';
        if (!Auth::canOnBan($perm, $ban)) {
            abort(403);
        }
        if (action() === 'delete_comment') {
            Database::delete('comments', ['id' => $cid, 'bid' => $bid]);
            flash('success', '_COMDELETED');
        } else {
            Database::update('comments', ['comment' => mb_substr(input('comment'), 0, 2000)], ['id' => $cid, 'bid' => $bid]);
            flash('success', '_COMEDITED');
        }
        redirect($self . '#comments');

    case 'upload_file':
        if (!$canUpload) {
            abort(403);
        }
        $errors = [];
        $upload = $_FILES['file'] ?? null;
        $types = allowed_file_types($config);
        if ($guestNeedsCaptcha && !captcha_check(input('captcha'))) {
            $errors[] = '_WRONGCAPTCHA';
        }
        if ($isGuest && time() - (int)($_SESSION['_last_post'] ?? 0) < 30) {
            $errors[] = '_TOOFAST';
        }
        if (!is_array($upload) || !is_string($upload['tmp_name'] ?? null) || ($upload['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            $errors[] = '_FILENOFILE';
        } elseif ($upload['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($upload['tmp_name'])) {
            $errors[] = $upload['error'] === UPLOAD_ERR_INI_SIZE || $upload['error'] === UPLOAD_ERR_FORM_SIZE ? '_FILETOBIG' : '_FILEUPLOADFAIL';
        } else {
            $ext = strtolower(pathinfo((string)$upload['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $types, true)) {
                $errors[] = '_FILETYPENOTALLOWED';
            }
            if ((int)$upload['size'] > $config->max_file_size * 1024 * 1024) {
                $errors[] = '_FILETOBIG';
            }
        }
        if (!$errors) {
            $stored = bin2hex(random_bytes(16)) . '_' . $bid;
            $target = files_dir() . $stored;
            if (!move_uploaded_file($upload['tmp_name'], $target)) {
                $errors[] = '_FILEUPLOADFAIL';
            } else {
                @chmod($target, 0644);
                make_thumbnail($target);
                $realName = mb_substr(preg_replace('/[^\w.\- ]/u', '_', basename((string)$upload['name'])), 0, 100);
                Database::insert('files', [
                    'upload_time' => time(), 'down_count' => 0, 'bid' => $bid, 'demo_file' => $stored,
                    'demo_real' => $realName, 'file_size' => (int)$upload['size'],
                    'comment' => mb_substr(input('comment'), 0, 2000),
                    'name' => $isGuest ? mb_substr(input('name'), 0, 64) : Auth::name(),
                    'email' => $isGuest ? mb_substr(input('email'), 0, 64) : (string)(Auth::user()['email'] ?? ''),
                    'addr' => client_ip(),
                ]);
                $_SESSION['_last_post'] = time();
                flash('success', '_FILEUPLOADSUCCESS');
                redirect($self . '#files');
            }
        }
        flash('error', '_ERROR', $errors);
        redirect($self . '#files');

    case 'edit_file':
    case 'delete_file':
        $did = input_int('did');
        $perm = action() === 'edit_file' ? 'bans_edit' : 'bans_delete';
        if (!Auth::canOnBan($perm, $ban)) {
            abort(403);
        }
        $file = Database::one('SELECT * FROM ' . Database::table('files') . ' WHERE `id` = :id AND `bid` = :bid', ['id' => $did, 'bid' => $bid]);
        if (!$file) {
            abort(404, '_FILENOTFOUND');
        }
        if (action() === 'delete_file') {
            delete_stored_file((string)$file['demo_file']);
            Database::delete('files', ['id' => $did]);
            flash('success', '_FILEDELSUCCESS');
        } else {
            Database::update('files', ['comment' => mb_substr(input('comment'), 0, 2000)], ['id' => $did]);
            flash('success', '_FILEEDITED');
        }
        redirect($self . '#files');
}

// ----------------------------------------------------------------------------
// Display
// ----------------------------------------------------------------------------
$history = [];
if ($ban['player_id'] !== '' || $ban['player_ip'] !== '') {
    $history = array_map('ban_present', Database::all(
        ban_select_sql() . ' WHERE ba.`bid` <> :bid
            AND ((ba.`player_id` = :pid AND ba.`player_id` <> \'\') OR (ba.`player_ip` = :ip AND ba.`player_ip` <> \'\'))
          ORDER BY ba.`ban_created` DESC LIMIT 50',
        ['bid' => $bid, 'pid' => (string)$ban['player_id'], 'ip' => (string)$ban['player_ip']]
    ));
}

$files = Database::all('SELECT * FROM ' . Database::table('files') . ' WHERE `bid` = :bid ORDER BY `upload_time`', ['bid' => $bid]);
foreach ($files as &$f) {
    $f['thumb'] = ($p = stored_file_path((string)$f['demo_file'], true)) !== null && is_file($p);
}
unset($f);

if ($guestNeedsCaptcha && ($canComment || $canUpload)) {
    captcha_new();
}
$old = $_SESSION['_old'] ?? [];
unset($_SESSION['_old']);

$view->page('ban_detail.tpl', [
    'ban'        => $ban,
    'history'    => $history,
    'edits'      => Database::all('SELECT * FROM ' . Database::table('bans_edit') . ' WHERE `bid` = :bid ORDER BY `edit_time` DESC', ['bid' => $bid]),
    'comments'   => $config->use_comment ? Database::all('SELECT * FROM ' . Database::table('comments') . ' WHERE `bid` = :bid ORDER BY `date`', ['bid' => $bid]) : [],
    'files'      => $files,
    'can'        => [
        'edit'    => Auth::canOnBan('bans_edit', $ban),
        'delete'  => Auth::canOnBan('bans_delete', $ban),
        'unban'   => Auth::canOnBan('bans_unban', $ban),
        'comment' => $canComment,
        'upload'  => $canUpload,
        'captcha' => $guestNeedsCaptcha,
    ],
    'upload'     => ['types' => implode(', ', allowed_file_types($config)), 'max' => $config->max_file_size],
    'old'        => $old,
    'show_comments' => (bool)$config->use_comment,
    'show_files' => (bool)$config->use_demo,
], '_TITLEBANDETAIL');
