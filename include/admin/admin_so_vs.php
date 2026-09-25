<?php
declare(strict_types=1);

/**
 * Admin page "Version" (admin.php?site=so_vs): web version and plugin versions of all servers.
 *
 * The old version check connected to a third-party MySQL server with credentials
 * stored in the source code; it was removed. Releases are published on GitHub.
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

$view->page('admin/version.tpl', [
    'servers' => Database::all('SELECT `hostname`, `address`, `amxban_version`, `timestamp` FROM ' . Database::table('serverinfo') . ' ORDER BY `hostname`'),
    'releases_url' => 'https://github.com/leszcz/amxbans-php7/releases',
], '_TITLEUPDATE');
