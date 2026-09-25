<?php
declare(strict_types=1);

require __DIR__ . '/include/bootstrap.php';

// Start page chosen in the web settings (only local .php pages of this installation).
$start = basename($config->start_page);
$allowed = ['ban_list.php', 'admin_list.php', 'search.php', 'view.php'];
redirect(in_array($start, $allowed, true) ? $start : 'ban_list.php');
