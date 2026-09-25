<?php
declare(strict_types=1);

/* Activity log of the website (_logs). */

switch (action()) {
    case 'delete_all':
        Auth::require('websettings_edit');
        Database::run('DELETE FROM ' . Database::table('logs'));
        log_to_db('Logs del', 'Deleted all logs');
        flash('success', '_LOGDELETED');
        redirect_back();
    case 'delete_older':
        Auth::require('websettings_edit');
        $days = max(1, input_int('days', 30));
        Database::run('DELETE FROM ' . Database::table('logs') . ' WHERE `timestamp` < :t', ['t' => time() - $days * 86400]);
        log_to_db('Logs del', "Deleted logs older than $days days");
        flash('success', '_LOGDELETED');
        redirect_back();
}

$filters = ['username' => query('username'), 'action' => query('action')];
$where = [];
$params = [];
foreach ($filters as $col => $value) {
    if ($value !== '') {
        $where[] = "`$col` = :$col";
        $params[$col] = $value;
    }
}
$sqlWhere = $where ? ' WHERE ' . implode(' AND ', $where) : '';
$total = (int)Database::value('SELECT COUNT(*) FROM ' . Database::table('logs') . $sqlWhere, $params);
$qs = http_build_query(array_filter(['site' => 'so_lg'] + $filters));
$pager = paginate($total, 50, query_int('page', 1), 'admin.php?' . $qs . '&');

$view->page('admin/logs.tpl', [
    'logs'      => Database::all('SELECT * FROM ' . Database::table('logs') . $sqlWhere . ' ORDER BY `id` DESC LIMIT :offset, :limit', $params + ['offset' => $pager['offset'], 'limit' => $pager['limit']]),
    'pager'     => $pager,
    'filters'   => $filters,
    'usernames' => Database::column('SELECT DISTINCT `username` FROM ' . Database::table('logs') . " WHERE `username` <> '' ORDER BY `username`"),
    'actions'   => Database::column('SELECT DISTINCT `action` FROM ' . Database::table('logs') . ' ORDER BY `action`'),
], '_TITLELOGS');
