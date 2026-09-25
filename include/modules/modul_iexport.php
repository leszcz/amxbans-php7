<?php
declare(strict_types=1);

/**
 * Module "Import/Export" (admin.php?modul=iexport, permission bans_export or bans_import).
 *
 * - GET download=<file>   download a stored backup (bans_export)
 * - POST backup           SQL dump of the AMXBans tables, downloaded or stored in include/backup/
 * - POST delete_backup    delete a stored backup
 * - POST export_cfg       banned.cfg / listip.cfg lines for the game server
 * - POST import_cfg       import banid/addip lines from an uploaded file (bans_import)
 * - POST delete_imported  delete bans with imported = 1
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

if (!Auth::can('bans_export') && !Auth::can('bans_import')) {
    abort(403);
}
require_once __DIR__ . '/iexport_func/modul_iexport_dbbackup.php';

$backupDir = AMXB_ROOT . '/include/backup/';
$backupFile = function (string $name) use ($backupDir): ?string {
    return preg_match('/^[\w-]+\.sql$/', $name) && is_file($backupDir . $name) ? $backupDir . $name : null;
};

// Downloads (GET)
if (($file = query('download')) !== '') {
    Auth::require('bans_export');
    $path = $backupFile($file) ?? abort(404);
    send_download($path, $file, 'application/sql');
}

switch (action()) {
    case 'backup':
        Auth::require('bans_export');
        $sql = db_backup(input_bool('structure_only'), input_bool('drop_table'), input('scope') === 'bans');
        $name = date('Y-m-d_H-i-s') . (input('scope') === 'bans' ? '_bans' : '') . '_' . bin2hex(random_bytes(4)) . '.sql';
        if (input_bool('download')) {
            log_to_db('Backup', 'Downloaded backup');
            while (ob_get_level()) {
                ob_end_clean();
            }
            header('Content-Type: application/sql; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . $name . '"');
            header('Cache-Control: private, no-store');
            exit($sql);
        }
        if (@file_put_contents($backupDir . $name, $sql) === false) {
            flash('error', '_BACKUPFAILNOFILE');
        } else {
            log_to_db('Backup', "Created backup $name");
            flash('success', '_BACKUPSUCCESS');
        }
        redirect_back();

    case 'delete_backup':
        Auth::require('bans_export');
        $path = $backupFile(input('file'));
        if ($path && @unlink($path)) {
            flash('success', '_FILEDELSUCCESS');
        } else {
            flash('error', '_FILEDELFAILED');
        }
        redirect_back();

    case 'export_cfg':
        Auth::require('bans_export');
        $where = input_bool('only_permanent') ? ' WHERE `expired` = 0 AND `ban_length` = 0' : ' WHERE `expired` = 0';
        $lines = [];
        foreach (Database::all('SELECT `player_id`, `player_ip`, `ban_type`, `ban_reason` FROM ' . Database::table('bans') . $where) as $b) {
            $comment = input_bool('with_reason') ? ' // ' . preg_replace('/[\r\n]/', ' ', (string)$b['ban_reason']) : '';
            if ($b['ban_type'] === 'SI' && valid_ip((string)$b['player_ip'])) {
                $lines[] = 'addip 0.0 ' . $b['player_ip'] . $comment;
            } elseif (valid_steamid((string)$b['player_id'])) {
                $lines[] = 'banid 0.0 ' . $b['player_id'] . $comment;
            }
        }
        log_to_db('Export', count($lines) . ' bans exported to banned.cfg');
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: text/plain; charset=UTF-8');
        header('Content-Disposition: attachment; filename="banned.cfg"');
        exit(implode("\n", $lines) . "\n");

    case 'import_cfg':
        Auth::require('bans_import');
        $upload = $_FILES['file'] ?? null;
        $reason = mb_substr(input('reason'), 0, 100);
        $nick = mb_substr(input('player_nick'), 0, 100) ?: 'Unknown';
        $serverName = mb_substr(input('server_name'), 0, 100) ?: 'Import';
        $created = strtotime(input('ban_created')) ?: time();
        if ($reason === '' || !is_array($upload) || ($upload['error'] ?? 1) !== UPLOAD_ERR_OK || !is_uploaded_file((string)$upload['tmp_name'])) {
            flash('error', '_NOREQUIREDFIELDS');
            redirect_back();
        }
        if ((int)$upload['size'] > 5 * 1024 * 1024) {
            flash('error', '_FILETOBIG');
            redirect_back();
        }
        $imported = $skipped = 0;
        foreach (file((string)$upload['tmp_name'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
            [$cmd, $time, $id] = array_pad(preg_split('/\s+/', trim($line), 4), 3, '');
            $lineReason = str_contains($line, '//') ? trim(substr($line, strpos($line, '//') + 2)) : '';
            $isSteam = $cmd === 'banid' && valid_steamid($id);
            $isIp = $cmd === 'addip' && valid_ip($id);
            if ((!$isSteam && !$isIp) || (float)$time !== 0.0) {
                $skipped++;
                continue;
            }
            $col = $isSteam ? 'player_id' : 'player_ip';
            if (Database::value('SELECT 1 FROM ' . Database::table('bans') . " WHERE `$col` = :id AND `expired` = 0 LIMIT 1", ['id' => $id])) {
                $skipped++;
                continue;
            }
            Database::insert('bans', [
                'player_id' => $isSteam ? $id : '', 'player_ip' => $isIp ? $id : '', 'player_nick' => $nick,
                'admin_nick' => Auth::name(), 'admin_id' => Auth::name(), 'ban_type' => $isSteam ? 'S' : 'SI',
                'ban_reason' => mb_substr($lineReason ?: $reason, 0, 100), 'cs_ban_reason' => mb_substr($lineReason ?: $reason, 0, 100),
                'ban_created' => $created, 'ban_length' => 0, 'server_name' => $serverName, 'imported' => 1,
            ]);
            $imported++;
        }
        log_to_db('Import', "banned.cfg: $imported imported, $skipped skipped");
        flash('success', '_IMPORTSUCCESS', [sprintf(__('_IMPORT_RESULT'), $imported, $skipped)]);
        redirect_back();

    case 'delete_imported':
        Auth::require('bans_import');
        $n = Database::run('DELETE FROM ' . Database::table('bans') . ' WHERE `imported` = 1')->rowCount();
        log_to_db('Import', "Deleted $n imported bans");
        flash('success', '_BANDELETED', [(string)$n]);
        redirect_back();
}

$backups = [];
foreach (glob($backupDir . '*.sql') ?: [] as $path) {
    $backups[] = ['name' => basename($path), 'size' => filesize($path), 'time' => filemtime($path)];
}
usort($backups, fn($a, $b) => $b['time'] <=> $a['time']);

$view->page('modules/iexport.tpl', [
    'backups'  => $backups,
    'imported' => (int)Database::value('SELECT COUNT(*) FROM ' . Database::table('bans') . ' WHERE `imported` = 1'),
    'writable' => is_writable($backupDir),
], '_TITLEIEXPORT');
