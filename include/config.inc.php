<?php
declare(strict_types=1);

if (file_exists("setup.php") && !file_exists("include/db.config.inc.php")) {
    header("Location: setup.php");
    exit;
}

$config = new stdClass();
$config->v_web = "1.6";

$page_starttime = explode(" ", microtime());

require_once("sql.inc.php");
require_once("db.config.inc.php");

function stripslashes_recursive(array|string $var): array|string {
    return is_array($var) ? array_map('stripslashes_recursive', $var) : stripslashes($var);
}

function htmlsafe_recursive(array|string $var): array|string {
    return is_array($var) ? array_map('htmlsafe_recursive', $var) : htmlspecialchars($var, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$_GET = htmlsafe_recursive($_GET);
$_POST = htmlsafe_recursive($_POST);
$_COOKIE = htmlsafe_recursive($_COOKIE);

function getipbyhost(string $ip_host = ""): string {
    $value = $ip_host;

    $pattern = [
        'ipv4' => '/^((\d|[1-9]\d|2[0-4]\d|25[0-5]|1\d\d)(?:\.(\d|[1-9]\d|2[0-4]\d|25[0-5]|1\d\d)){3})$/',
        'ipv6' => '/^( (([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})| (([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})| (([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})| (([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})| (([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})| (([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})| ( ([0-9A-Fa-f]{1,4}:){6} ((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3} (\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b) )| ( ([0-9A-Fa-f]{1,4}:){1,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))| (([0-9A-Fa-f]{1,4}:){1}:([0-9A-Fa-f]{1,4}:){0,4}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))| (([0-9A-Fa-f]{1,4}:){0,2}:([0-9A-Fa-f]{1,4}:){0,3}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))| (([0-9A-Fa-f]{1,4}:){0,3}:([0-9A-Fa-f]{1,4}:){0,2}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))| (([0-9A-Fa-f]{1,4}:){0,4}:([0-9A-Fa-f]{1,4}:){1}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))| (::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d) |(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))| ([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})| (::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})| (([0-9A-Fa-f]{1,4}:){1,7}:) )$/'
    ];

    if ($value !== "" && $value !== "127.0.0.1" && $value !== "localhost" && !preg_match($pattern['ipv4'], $value) && !preg_match($pattern['ipv6'], $value)) {
        return gethostbyname($value);
    } 
    return $value;
}

try {
    $config->db_host = getipbyhost($config->db_host);
    $pdo = getPDO();

    $pdo->exec("SET NAMES utf8mb4");
    $pdo->exec("SET character_set_client='utf8mb4'");
    $pdo->exec("SET character_set_results='utf8mb4'");
    $pdo->exec("SET collation_connection='utf8mb4_unicode_ci'");

    $vars = sql_set_websettings();
    $config->importdir = $config->path_root . "/tmp";
    $config->templatedir = $config->path_root . "/templates/" . $config->design . "/";
    $config->langfilesdir = $config->path_root . "/language/";

    if(!isset($_SESSION["lang"])) {
        $_SESSION["lang"] = $config->default_lang;
    }

    if(empty($smilies)) {
        $stmt = $pdo->query("SELECT code, url, name FROM {$config->db_prefix}_smilies ORDER BY id");
        $smilies = [];
        while ($row = $stmt->fetch()) {
            $name = htmlentities(stripslashes($row['name']), ENT_QUOTES, 'UTF-8');
            $smilies[] = [$row['code'], $row['url'], $name];
        }
    }

    if(empty($bbcodes)) {
        $stmt = $pdo->query("SELECT open_tag, close_tag, url, name FROM {$config->db_prefix}_bbcode ORDER BY id");
        $bbcodes = [];
        while ($row = $stmt->fetch()) {
            $name = htmlentities(stripslashes($row['name']), ENT_QUOTES, 'UTF-8');
            $bbcodes[] = [$row['open_tag'], $row['close_tag'], $row['url'], $name];
        }
    }

} catch (PDOException $e) {
    error_log("Database error in config.inc.php: " . $e->getMessage());
    die("A database error occurred. Please try again later.");
}

require_once("smarty/Smarty.class.php");

class dynamicPage extends Smarty {
    public function __construct() {
        parent::__construct();
        global $config;

        $this->setTemplateDir($config->templatedir);
        $this->setCompileDir(SMARTY_DIR . "templates_c/");
        $this->setConfigDir(SMARTY_DIR . "configs/");
        $this->setCacheDir(SMARTY_DIR . "cache/");
        $this->caching = false;
        
        $this->force_compile = false;
        
        $this->assign("app_name", "dynamicPage");
    }
}
?>