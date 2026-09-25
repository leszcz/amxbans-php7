<?php
declare(strict_types=1);

/*
 * Version information. The old version check connected to a third-party MySQL
 * server with credentials stored in this file; it was removed. Releases are
 * published on GitHub instead.
 */

$view->page('admin/version.tpl', [
    'servers' => Database::all('SELECT `hostname`, `address`, `amxban_version`, `timestamp` FROM ' . Database::table('serverinfo') . ' ORDER BY `hostname`'),
    'releases_url' => 'https://github.com/leszcz/amxbans-php7/releases',
], '_TITLEUPDATE');
