<?php
declare(strict_types=1);

/**
 * Start page: redirects to the page chosen in the web settings (only local pages).
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

require __DIR__ . '/include/bootstrap.php';

$start = basename($config->start_page);
$allowed = ['ban_list.php', 'admin_list.php', 'search.php', 'view.php'];
redirect(in_array($start, $allowed, true) ? $start : 'ban_list.php');
