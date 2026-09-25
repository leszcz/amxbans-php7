<?php
declare(strict_types=1);

/**
 * Admin page "Ban reasons" (admin.php?site=sm_bg, permission servers_edit).
 *
 * Reasons (_reasons) and reason sets (_reasons_set + _reasons_to_set). A set is
 * assigned to a server in the server settings; the plugin shows it in its ban menu.
 * POST actions: add_reason, save_reason, delete_reason (rid), add_set, save_set,
 * delete_set (rsid, setname, reasons[]).
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

$reasonInput = function (): array {
    $reason = mb_substr(input('reason'), 0, 100);
    if ($reason === '') {
        flash('error', '_NOREASON');
        redirect_back();
    }
    return ['reason' => $reason, 'static_bantime' => max(0, input_int('static_bantime'))];
};
$setName = function (): string {
    $name = mb_substr(input('setname'), 0, 32);
    if ($name === '') {
        flash('error', '_NOREASONSET');
        redirect_back();
    }
    return $name;
};
$saveSetReasons = function (int $setId): void {
    Database::delete('reasons_to_set', ['setid' => $setId]);
    $valid = array_map('intval', Database::column('SELECT `id` FROM ' . Database::table('reasons')));
    foreach (array_unique(array_map('intval', input_array('reasons'))) as $rid) {
        if (in_array($rid, $valid, true)) {
            Database::insert('reasons_to_set', ['setid' => $setId, 'reasonid' => $rid]);
        }
    }
};

switch (action()) {
    case 'add_reason':
        $data = $reasonInput();
        Database::insert('reasons', $data);
        log_to_db('Reasons config', "Added reason: {$data['reason']} ({$data['static_bantime']} min)");
        flash('success', '_REASONADDED');
        redirect_back();
    case 'save_reason':
        $data = $reasonInput();
        Database::update('reasons', $data, ['id' => input_int('rid')]);
        log_to_db('Reasons config', "Edited reason: {$data['reason']}");
        flash('success', '_REASONSAVED');
        redirect_back();
    case 'delete_reason':
        $rid = input_int('rid');
        Database::delete('reasons', ['id' => $rid]);
        Database::delete('reasons_to_set', ['reasonid' => $rid]);
        log_to_db('Reasons config', "Deleted reason #$rid");
        flash('success', '_REASONDELETED');
        redirect_back();
    case 'add_set':
        $id = Database::insert('reasons_set', ['setname' => $setName()]);
        $saveSetReasons($id);
        log_to_db('Reasons config', "Added set #$id");
        flash('success', '_REASONSETADDED');
        redirect_back();
    case 'save_set':
        $id = input_int('rsid');
        Database::update('reasons_set', ['setname' => $setName()], ['id' => $id]);
        $saveSetReasons($id);
        log_to_db('Reasons config', "Edited set #$id");
        flash('success', '_REASONSSETSAVED');
        redirect_back();
    case 'delete_set':
        $id = input_int('rsid');
        Database::delete('reasons_set', ['id' => $id]);
        Database::delete('reasons_to_set', ['setid' => $id]);
        log_to_db('Reasons config', "Deleted set #$id");
        flash('success', '_REASONSETDELETED');
        redirect_back();
}

$sets = Database::all('SELECT * FROM ' . Database::table('reasons_set') . ' ORDER BY `setname`');
foreach ($sets as &$set) {
    $set['reasons'] = array_map('intval', Database::column('SELECT `reasonid` FROM ' . Database::table('reasons_to_set') . ' WHERE `setid` = :id', ['id' => $set['id']]));
}
unset($set);

$view->page('admin/reasons.tpl', ['reasons' => reasons_all(), 'sets' => $sets], '_TITLEREASONS');
