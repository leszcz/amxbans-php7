<?php
declare(strict_types=1);

/**
 * Logout. Only a POST with a valid CSRF token logs out (header button); GET just redirects.
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

require __DIR__ . '/include/bootstrap.php';

if (is_post()) {
    Auth::logout();
}
redirect('index.php');
