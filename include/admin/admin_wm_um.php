<?php
declare(strict_types=1);

/* Public navigation menu (_usermenu). "url" is shown to guests, "url2" to logged in admins. */

$readItem = function (): array {
    $data = [
        'activ'     => input_bool('activ') ? 1 : 0,
        'lang_key'  => mb_substr(input('lang_key'), 0, 64),
        'url'       => mb_substr(input('url'), 0, 64),
        'lang_key2' => mb_substr(input('lang_key2'), 0, 64),
        'url2'      => mb_substr(input('url2'), 0, 64),
    ];
    foreach (['url', 'url2'] as $k) {
        if ($data[$k] !== '' && safe_url($data[$k]) !== $data[$k]) {
            flash('error', '_URLINVALID');
            redirect_back();
        }
    }
    return $data;
};

if (action() !== '') {
    Auth::require('websettings_edit');
}
switch (action()) {
    case 'add':
        $data = $readItem();
        $data['pos'] = (int)Database::value('SELECT COALESCE(MAX(`pos`), 0) + 1 FROM ' . Database::table('usermenu'));
        Database::insert('usermenu', $data);
        log_to_db('Usermenu config', 'Added menu item');
        flash('success', '_USERMENUADDED');
        redirect_back();
    case 'save':
        Database::update('usermenu', $readItem(), ['id' => input_int('mid')]);
        log_to_db('Usermenu config', 'Edited menu item #' . input_int('mid'));
        flash('success', '_USERMENUSAVED');
        redirect_back();
    case 'delete':
        Database::delete('usermenu', ['id' => input_int('mid')]);
        log_to_db('Usermenu config', 'Deleted menu item #' . input_int('mid'));
        flash('success', '_USERMENUDELETED');
        redirect_back();
    case 'up':
    case 'down':
        $items = Database::all('SELECT `id` FROM ' . Database::table('usermenu') . ' ORDER BY `pos`, `id`');
        $ids = array_map('intval', array_column($items, 'id'));
        $i = array_search(input_int('mid'), $ids, true);
        $j = $i === false ? false : (action() === 'up' ? $i - 1 : $i + 1);
        if ($j !== false && isset($ids[$j])) {
            [$ids[$i], $ids[$j]] = [$ids[$j], $ids[$i]];
            Database::transaction(function () use ($ids) {
                foreach ($ids as $pos => $id) {
                    Database::update('usermenu', ['pos' => $pos + 1], ['id' => $id]);
                }
            });
        }
        redirect_back();
}

$view->page('admin/usermenu.tpl', [
    'items' => Database::all('SELECT * FROM ' . Database::table('usermenu') . ' ORDER BY `pos`, `id`'),
], '_TITLEUSERMENU');
