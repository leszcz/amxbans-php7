<?php
if(file_exists("setup.php") && !file_exists("include/db.config.inc.php")) {
  header("Location: setup.php");
  exit;
}
$config = new \stdClass();
$config->v_web = "1.6";

$page_starttime = explode(" ", microtime());

require_once("sql.inc.php");

// If magic quotes are enabled, strip slashes from all user data
function stripslashes_recursive($var) {
  return (is_array($var) ? array_map('stripslashes_recursive', $var) : stripslashes($var));
}

function htmlsafe_recursive($var) {
  return (is_array($var) ? array_map('htmlsafe_recursive', $var) : htmlspecialchars($var, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
}

// Magic quotes were removed in PHP 7.4+, so this check is no longer necessary
// if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
//   $_GET = stripslashes_recursive($_GET);
//   $_POST = stripslashes_recursive($_POST);
//   $_COOKIE = stripslashes_recursive($_COOKIE);
// }

// Apply htmlsafe_recursive to superglobals
$_GET = htmlsafe_recursive($_GET);
$_POST = htmlsafe_recursive($_POST);
$_COOKIE = htmlsafe_recursive($_COOKIE);

require_once("db.config.inc.php");

//get ip from hostname
function getipbyhost($ip_host = "") {
  $value = $ip_host;

  $pattern['ipv4'] = '/^((\d|[1-9]\d|2[0-4]\d|25[0-5]|1\d\d)(?:\.(\d|[1-9]\d|2[0-4]\d|25[0-5]|1\d\d)){3})$/';
  $pattern['ipv6'] = '/^( (([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})| (([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})| (([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})| (([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})| (([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})| (([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})| ( ([0-9A-Fa-f]{1,4}:){6} ((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3} (\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b) )| ( ([0-9A-Fa-f]{1,4}:){1,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))| (([0-9A-Fa-f]{1,4}:){1}:([0-9A-Fa-f]{1,4}:){0,4}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))| (([0-9A-Fa-f]{1,4}:){0,2}:([0-9A-Fa-f]{1,4}:){0,3}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))| (([0-9A-Fa-f]{1,4}:){0,3}:([0-9A-Fa-f]{1,4}:){0,2}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))| (([0-9A-Fa-f]{1,4}:){0,4}:([0-9A-Fa-f]{1,4}:){1}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))| (::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d) |(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))| ([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})| (::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})| (([0-9A-Fa-f]{1,4}:){1,7}:) )$/';

  if( ($value != "") && ($value != "127.0.0.1") && ($value != "localhost") && !preg_match($pattern['ipv4'], $value) && !preg_match($pattern['ipv6'], $value) ) {
    return gethostbyname($value);
  } else {
    return $value;
  }
}

//connect to db
$config->db_host = getipbyhost($config->db_host);
$mysql = mysqli_connect($config->db_host, $config->db_user, $config->db_pass, $config->db_db) or die (mysqli_connect_error());
mysqli_set_charset($mysql, 'utf8mb4');
mysqli_query($mysql, "SET NAMES utf8mb4");
mysqli_query($mysql, "SET character_set_client='utf8mb4'");
mysqli_query($mysql, "SET character_set_results='utf8mb4'");
mysqli_query($mysql, "SET collation_connection='utf8mb4_unicode_ci'");

//get websettings
$vars = sql_set_websettings();
$config->importdir = $config->path_root . "/tmp";
$config->templatedir = $config->path_root . "/templates/" . $config->design . "/";
$config->langfilesdir = $config->path_root . "/language/";

//save current language to session
if(!isset($_SESSION["lang"]))
  $_SESSION["lang"] = $config->default_lang;

//load smilies to global array
if(empty($smilies)) {
  $sql = mysqli_query($mysql, "SELECT code, url, name FROM " . $config->db_prefix . "_smilies ORDER BY id");
  while ($row = mysqli_fetch_assoc($sql)) {
    $name = htmlentities(stripslashes($row['name']), ENT_QUOTES, 'UTF-8');
    $smilies[] = array($row['code'], $row['url'], $name);
  }
}

//load bbcode tags to global array
if(empty($bbcodes)) {
  $sql = mysqli_query($mysql, "SELECT open_tag, close_tag, url, name FROM " . $config->db_prefix . "_bbcode ORDER BY id");
  while ($row = mysqli_fetch_assoc($sql)) {
    $name = htmlentities(stripslashes($row['name']), ENT_QUOTES, 'UTF-8');
    $bbcodes[] = array($row['open_tag'], $row['close_tag'], $row['url'], $name);
  }
}

/* Smarty settings */
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
    
    //for editing templates it's better "true", but slow down site load
    $this->force_compile = false;
    
    $this->assign("app_name", "dynamicPage");
  }
}
?>