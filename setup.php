<?php
declare(strict_types=1);

/**
 * Installer.
 *
 * Steps: requirements → database → administrator → install. Creates the tables
 * (keeps existing AMXBans 6 tables), default data, the first web admin and
 * include/db.config.inc.php (written with var_export). Refuses to run once the
 * config file exists and can delete itself afterwards.
 *
 * The wizard state is kept in $_SESSION['setup']. Template: install/setup.tpl.
 * @see install/schema.php
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

define('AMXB_ROOT', __DIR__);
define('AMXB_VERSION', '7.0.0');
$configFile = __DIR__ . '/include/db.config.inc.php';

if (!is_file(__DIR__ . '/vendor/autoload.php')) {
    http_response_code(500);
    exit('Missing vendor/ directory. Upload the complete package or run "composer install --no-dev".');
}
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/include/Database.php';
require __DIR__ . '/include/Security.php';
require __DIR__ . '/include/Lang.php';
require __DIR__ . '/include/Auth.php';
require __DIR__ . '/include/View.php';
require __DIR__ . '/include/helpers.php';
require __DIR__ . '/install/schema.php';

ini_set('display_errors', '0');
Security::sendHeaders();
Security::startSession();
Security::verifyCsrfOnPost();
Lang::init($_SESSION['setup']['lang'] ?? 'english');

$config = (object)['design' => 'modern'];
$view = new View($config);
$view->setTemplateDir(__DIR__ . '/templates/modern/');

$render = function (string $step, array $vars = []) use ($view): never {
    $view->assign($vars + [
        'step'       => $step,
        'steps'      => ['requirements', 'database', 'admin', 'install'],
        'step_index' => (int)array_search($step, ['requirements', 'database', 'admin', 'install'], true),
        'step_labels'=> ['requirements' => '_SETUP_STEP_REQUIREMENTS', 'database' => '_SETUP_STEP_DATABASE',
                         'admin' => '_SETUP_STEP_ADMIN', 'install' => '_SETUP_STEP_INSTALL'],
        'app'        => ['version' => AMXB_VERSION, 'lang' => Lang::current(), 'languages' => Lang::available(),
                         'html_lang' => strtolower(substr(Lang::get('_LOCALE'), 0, 2)) ?: 'en', 'script' => 'setup.php'],
        'flashes'    => flash_pull(),
        'asset_ver'  => asset_version(),
        'setup'      => $_SESSION['setup'] ?? [],
    ]);
    header('Content-Type: text/html; charset=UTF-8');
    $view->display('install/setup.tpl');
    exit;
};

if (is_file($configFile)) {
    if (action() === 'delete_setup') {
        @unlink(__FILE__);
        redirect(is_file(__FILE__) ? 'setup.php' : 'index.php');
    }
    $render('locked');
}

$state = &$_SESSION['setup'];
$state ??= ['lang' => 'english'];

$requirements = [
    ['PHP >= 8.1', PHP_VERSION, version_compare(PHP_VERSION, '8.1.0', '>='), true],
    ['PDO MySQL', extension_loaded('pdo_mysql') ? 'OK' : '—', extension_loaded('pdo_mysql'), true],
    ['mbstring', extension_loaded('mbstring') ? 'OK' : '—', extension_loaded('mbstring'), true],
    ['GD', extension_loaded('gd') ? 'OK' : '—', extension_loaded('gd'), false],
    ['templates_c/ ' . __('_WRITABLE'), '', is_writable(__DIR__ . '/templates_c'), true],
    ['include/ ' . __('_WRITABLE'), '', is_writable(__DIR__ . '/include'), true],
    ['include/files/ ' . __('_WRITABLE'), '', is_writable(__DIR__ . '/include/files'), false],
    ['include/backup/ ' . __('_WRITABLE'), '', is_writable(__DIR__ . '/include/backup'), false],
    ['HTTPS', Security::isHttps() ? 'OK' : '—', Security::isHttps(), false],
];
$requirementsOk = !array_filter($requirements, fn($r) => $r[3] && !$r[2]);

$connect = function (array $db): PDO {
    Database::configure((object)[
        'db_host' => $db['host'], 'db_user' => $db['user'], 'db_pass' => $db['pass'],
        'db_db' => $db['name'], 'db_prefix' => $db['prefix'],
    ]);
    return Database::pdo();
};

switch (action()) {
    case 'language':
        if (in_array(input('lang'), Lang::available(), true)) {
            $state['lang'] = input('lang');
        }
        redirect('setup.php');

    case 'back:requirements':
    case 'back:database':
    case 'back:admin':
        $order = ['requirements', 'database', 'admin', 'install'];
        $target = substr(action(), 5);
        if (in_array($target, $order, true) && array_search($target, $order, true) < array_search($state['step'] ?? 'requirements', $order, true)) {
            $state['step'] = $target;
        }
        redirect('setup.php');

    case 'requirements':
        if ($requirementsOk) {
            $state['step'] = 'database';
        }
        redirect('setup.php');

    case 'database':
        $db = [
            'host' => input('host', 'localhost'), 'user' => input('user'), 'pass' => (string)($_POST['pass'] ?? ''),
            'name' => input('name'), 'prefix' => input('prefix', 'amx'),
        ];
        $state['db'] = array_diff_key($db, ['pass' => 1]);
        if ($db['user'] === '' || $db['name'] === '' || !preg_match('/^[A-Za-z0-9_]{1,20}$/', $db['prefix'])) {
            flash('error', '_NOREQUIREDFIELDS');
            redirect('setup.php');
        }
        try {
            $connect($db);
            $existing = Database::column('SHOW TABLES LIKE ' . Database::pdo()->quote(addcslashes($db['prefix'], '_%') . '\_%'));
        } catch (PDOException $e) {
            flash('error', '_SETUP_DB_FAILED', [$e->getMessage()]);
            redirect('setup.php');
        }
        $state['db_pass'] = $db['pass'];
        $state['existing'] = in_array($db['prefix'] . '_bans', $existing, true);
        $state['step'] = 'admin';
        flash($state['existing'] ? 'info' : 'success', $state['existing'] ? '_SETUP_EXISTING' : '_DBOK');
        redirect('setup.php');

    case 'admin':
        $admin = ['user' => mb_substr(input('user'), 0, 32), 'email' => input('email'), 'pass' => (string)($_POST['pass'] ?? '')];
        $state['admin'] = ['user' => $admin['user'], 'email' => $admin['email']];
        $errors = [];
        if (!($state['existing'] ?? false) || $admin['user'] !== '') {
            if (mb_strlen($admin['user']) < 2) {
                $errors[] = '_USERNAMETOSHORT';
            }
            if (strlen($admin['pass']) < 8) {
                $errors[] = '_PASSWORDTOSHORT';
            }
            if (!hash_equals($admin['pass'], (string)($_POST['pass2'] ?? ''))) {
                $errors[] = '_PASSWORDNOTMATCH';
            }
            if ($admin['email'] !== '' && !valid_email($admin['email'])) {
                $errors[] = '_EMAILINVALID';
            }
        }
        if ($errors) {
            flash('error', '_ERROR', $errors);
            redirect('setup.php');
        }
        $state['admin_hash'] = $admin['pass'] !== '' ? Auth::hashPassword($admin['pass']) : null;
        $state['step'] = 'install';
        redirect('setup.php');

    case 'install':
        if (($state['step'] ?? '') !== 'install') {
            redirect('setup.php');
        }
        $db = $state['db'] + ['pass' => $state['db_pass']];
        $log = [];
        try {
            $connect($db);
            foreach (install_schema() as $table => $columns) {
                Database::pdo()->exec('CREATE TABLE IF NOT EXISTS ' . Database::table($table) . " ($columns) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
                $log[] = $table;
            }
            foreach (install_default_data($state['lang']) as $table => $rows) {
                if ((int)Database::value('SELECT COUNT(*) FROM ' . Database::table($table)) === 0) {
                    foreach ($rows as $row) {
                        Database::insert($table, $row);
                    }
                }
            }
            if (!empty($state['admin_hash'])) {
                $level = (int)Database::value('SELECT MIN(`level`) FROM ' . Database::table('levels'));
                Database::insert('webadmins', [
                    'username' => $state['admin']['user'], 'password' => $state['admin_hash'],
                    'level' => $level ?: 1, 'email' => $state['admin']['email'], 'try' => 0,
                ]);
            }
            Database::insert('logs', ['timestamp' => time(), 'ip' => client_ip(), 'username' => (string)($state['admin']['user'] ?? ''),
                'action' => 'Install', 'remarks' => 'Installation AMXBans ' . AMXB_VERSION]);
        } catch (PDOException $e) {
            flash('error', '_INSTALLFAILED', [$e->getMessage()]);
            redirect('setup.php');
        }

        // Values are written with var_export(), so quotes or "$" in the password cannot break the file.
        $php = "<?php\n// Generated by setup.php on " . date('Y-m-d H:i:s') . "\n";
        foreach (['db_host' => $db['host'], 'db_user' => $db['user'], 'db_pass' => $db['pass'], 'db_db' => $db['name'], 'db_prefix' => $db['prefix']] as $k => $v) {
            $php .= '$config->' . $k . ' = ' . var_export((string)$v, true) . ";\n";
        }
        $written = @file_put_contents($configFile, $php, LOCK_EX) !== false;
        if ($written) {
            @chmod($configFile, 0640);
        }
        $_SESSION['setup'] = ['lang' => $state['lang'], 'step' => 'done'];
        $render('done', ['written' => $written, 'config_php' => $written ? '' : $php, 'tables' => $log]);
}

$step = $state['step'] ?? 'requirements';
$render($step, ['requirements' => $requirements, 'requirements_ok' => $requirementsOk]);
