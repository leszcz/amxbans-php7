<?php
declare(strict_types=1);

/* Admin dashboard: statistics, system information and database maintenance. */

$maintenance = ['optimize', 'prune', 'clear_cache', 'repair_files', 'repair_comments'];
if (in_array(action(), $maintenance, true)) {
    Auth::require('prune_db');
    switch (action()) {
        case 'optimize':
            $tables = Database::column('SHOW TABLES LIKE ' . Database::pdo()->quote(addcslashes(Database::prefix(), '_%') . '\_%'));
            if ($tables) {
                Database::pdo()->query('OPTIMIZE TABLE ' . implode(', ', array_map(fn($t) => '`' . str_replace('`', '', $t) . '`', $tables)))->fetchAll();
            }
            log_to_db('Database', 'Optimized tables');
            flash('success', '_DBOPTIMIZED');
            break;
        case 'prune':
            $count = bans_prune();
            log_to_db('Database', "Pruned $count expired bans");
            flash('success', '_DBPRUNED', [$count . ' ' . __('_BANS')]);
            break;
        case 'clear_cache':
            $view->clearCompiledTemplate();
            flash('success', '_CACHEDELETED');
            break;
        case 'repair_files':
            flash('success', '_REPAIRED', [(string)repair_files(true)]);
            break;
        case 'repair_comments':
            $n = Database::run('DELETE c FROM ' . Database::table('comments') . ' c LEFT JOIN ' . Database::table('bans') . ' b ON b.`bid` = c.`bid` WHERE b.`bid` IS NULL')->rowCount();
            flash('success', '_REPAIRED', [(string)$n]);
            break;
    }
    redirect_back();
}

/** Files without ban / ban-less DB entries / stored files without DB entry. Returns count (repaired count when $repair). */
function repair_files(bool $repair): int
{
    $orphans = Database::all('SELECT f.`id`, f.`demo_file` FROM ' . Database::table('files') . ' f LEFT JOIN ' . Database::table('bans') . ' b ON b.`bid` = f.`bid` WHERE b.`bid` IS NULL');
    $known = array_flip(Database::column('SELECT `demo_file` FROM ' . Database::table('files')));
    $stray = [];
    foreach (glob(files_dir() . '*') ?: [] as $path) {
        $name = preg_replace('/_thumb$/', '', basename($path));
        if (preg_match('/^[a-f0-9]{32}_\d+$/', $name) && !isset($known[$name])) {
            $stray[$name] = true;
        }
    }
    if ($repair) {
        foreach ($orphans as $o) {
            delete_stored_file((string)$o['demo_file']);
            Database::delete('files', ['id' => (int)$o['id']]);
        }
        foreach (array_keys($stray) as $name) {
            delete_stored_file($name);
        }
    }
    return count($orphans) + count($stray);
}

$dbSize = 0;
foreach (Database::all('SHOW TABLE STATUS LIKE ' . Database::pdo()->quote(addcslashes(Database::prefix(), '_%') . '\_%')) as $t) {
    $dbSize += (int)$t['Data_length'] + (int)$t['Index_length'];
}

$bans = Database::table('bans');
$view->page('admin/dashboard.tpl', [
    'stats' => [
        'bans'        => (int)Database::value("SELECT COUNT(*) FROM $bans"),
        'active'      => (int)Database::value("SELECT COUNT(*) FROM $bans WHERE `expired` = 0"),
        'today'       => (int)Database::value("SELECT COUNT(*) FROM $bans WHERE `ban_created` >= :t", ['t' => strtotime('today')]),
        'week'        => (int)Database::value("SELECT COUNT(*) FROM $bans WHERE `ban_created` >= :t", ['t' => time() - 7 * 86400]),
        'comments'    => (int)Database::value('SELECT COUNT(*) FROM ' . Database::table('comments')),
        'files'       => (int)Database::value('SELECT COUNT(*) FROM ' . Database::table('files')),
        'comment_orphans' => (int)Database::value('SELECT COUNT(*) FROM ' . Database::table('comments') . " c LEFT JOIN $bans b ON b.`bid` = c.`bid` WHERE b.`bid` IS NULL"),
        'file_orphans'=> repair_files(false),
        'db_size'     => $dbSize,
    ],
    'recent' => array_map('ban_present', Database::all(ban_select_sql() . ' ORDER BY ba.`ban_created` DESC LIMIT 8')),
    'system' => [
        'PHP'          => PHP_VERSION,
        'MySQL'        => (string)Database::pdo()->getAttribute(PDO::ATTR_SERVER_VERSION),
        'Smarty'       => \Smarty\Smarty::SMARTY_VERSION,
        'GD'           => extension_loaded('gd') ? (gd_info()['GD Version'] ?? 'yes') : '—',
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'HTTPS'        => Security::isHttps() ? 'yes' : 'no',
        'setup.php'    => is_file(AMXB_ROOT . '/setup.php') ? 'present' : 'removed',
    ],
    'auto_prune' => (bool)$config->auto_prune,
], '_TITLEINFO');
