<?php
declare(strict_types=1);

/**
 * Common start-up for every page of AMXBans.
 *
 * ```php
 * require __DIR__ . '/include/bootstrap.php';
 * ```
 *
 * Order of work (see docs/architecture.md#request-lifecycle):
 *  1. constants AMXB_ROOT, AMXB_VERSION; Composer autoloader and project classes
 *  2. include/db.config.inc.php (redirects to setup.php when missing)
 *  3. security headers, hardened session
 *  4. web settings from _webconfig ({@see settings_load()})
 *  5. language ({@see Lang::init()}), logged-in admin ({@see Auth::init()})
 *  6. CSRF check of POST requests (skipped when AMXB_SKIP_CSRF is defined)
 *  7. template engine ({@see View})
 *
 * Globals available afterwards:
 *  - `$config` (stdClass) database settings + web settings
 *  - `$view`   (View)     Smarty 5 instance
 *
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

define('AMXB_ROOT', dirname(__DIR__));
define('AMXB_VERSION', '7.0.0');

if (!is_file(AMXB_ROOT . '/vendor/autoload.php')) {
    http_response_code(500);
    exit('Missing vendor/ directory. Run "composer install --no-dev" or upload the complete release package.');
}

require_once AMXB_ROOT . '/vendor/autoload.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/Lang.php';
require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/View.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/queries.php';

ini_set('display_errors', '0');
error_reporting(E_ALL);

// ----------------------------------------------------------------------------
// Configuration
// ----------------------------------------------------------------------------
$config = new stdClass();
$config->v_web = AMXB_VERSION;

if (!is_file(__DIR__ . '/db.config.inc.php')) {
    if (is_file(AMXB_ROOT . '/setup.php')) {
        header('Location: setup.php');
        exit;
    }
    http_response_code(500);
    exit('AMXBans is not configured (include/db.config.inc.php is missing).');
}

require __DIR__ . '/db.config.inc.php';

$config->path_root = AMXB_ROOT;
Database::configure($config);

// ----------------------------------------------------------------------------
// Request security
// ----------------------------------------------------------------------------
Security::sendHeaders();
Security::startSession();

try {
    settings_load($config);
} catch (PDOException $e) {
    error_log('AMXBans: database error: ' . $e->getMessage());
    http_response_code(503);
    exit('Database connection failed. Please try again later.');
}

Lang::init($config->default_lang);
Auth::init($config);

if (!defined('AMXB_SKIP_CSRF')) {
    Security::verifyCsrfOnPost();
}

$view = new View($config);
