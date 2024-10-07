<?php

ini_set("display_errors", 0);

session_start();

require_once("install/functions.inc");
require_once("include/functions.inc.php");

$config = new stdClass();
$config->v_web = "Gm 1.6";

// Number of installation steps
$sitenrall = 6;

$sitenr = filter_input(INPUT_POST, 'site', FILTER_VALIDATE_INT) ?? 1;
$sitenr = ($sitenr < 1 || $sitenr > $sitenrall) ? 1 : $sitenr;

if ($sitenr == 7 && isset($_POST["check7"])) {
    $sitenrall = 7;
}
if (isset($_POST["check6"])) {
    $sitenrall = 7;
    $sitenr++;
}
if (isset($_POST["back"])) $sitenr--;
if (isset($_POST["next"])) $sitenr++;

// Setup paths
$config->path_root = str_replace("/".basename(str_replace("\\", "/", $_SERVER["SCRIPT_FILENAME"])), "", str_replace("\\", "/", $_SERVER["SCRIPT_FILENAME"]));
$config->document_root = str_replace("/".basename($_SERVER["PHP_SELF"]), "", $_SERVER["PHP_SELF"]);
$config->templatedir = $config->path_root."/install";
$config->langfilesdir = $config->path_root."/install/language/";
$config->default_lang = "english";
$_SESSION["lang"] = $_SESSION["lang"] ?? "english";

// Check write permissions
if (!is__writable($config->path_root."/include/smarty/templates_c/")) {
    echo '<br /><table border="0" align="center"><tr><td align="center" style="color: #c04040;font-width=bold;font-size=18px;"><img src="images/warning.gif" /> <u>Directory include/smarty/templates_c is not writable !!</u></td></tr></table>';
    exit;
}

// Smarty settings
define("SMARTY_DIR", $config->path_root."/include/smarty/");

require_once(SMARTY_DIR."Smarty.class.php");

class dynamicPage extends Smarty {
    public function __construct() {
        parent::__construct();
        global $config;
        $this->template_dir = $config->templatedir;
        $this->compile_dir = SMARTY_DIR."templates_c/";
        $this->config_dir = SMARTY_DIR."configs/";
        $this->cache_dir = SMARTY_DIR."cache/";
        $this->force_compile = true;
        $this->caching = false;
        $this->assign("app_name", "dynamicPage");
    }
}

$smarty = new dynamicPage();
$smarty->assign("next", false);

/////////////// Step 2: Server Settings /////////////////
if ($sitenr == 2) {
    $php_settings = array(
        "display_errors" => (ini_get('display_errors') == "") ? "off" : ini_get('display_errors'),
        "register_globals" => (ini_get('register_globals') == 1 || ini_get('register_globals') == "on") ? "_ON" : "_OFF",
        "magic_quotes_gpc" => ( function_exists('get_magic_quotes_gpc')) ? "_ON" : "_OFF",
        "safe_mode" => (ini_get('safe_mode') == 1 || ini_get('safe_mode') == "on") ? "_ON" : "_OFF",
        "post_max_size" => ini_get('post_max_size')." (".return_bytes(ini_get('post_max_size'))." bytes)",
        "upload_max_filesize" => ini_get('upload_max_filesize')." (".return_bytes(ini_get('upload_max_filesize'))." bytes)",
        "max_execution_time" => ini_get('max_execution_time'),
        "version_php" => phpversion(),
        "version_amxbans_web" => $config->v_web,
        "server_software" => $_SERVER["SERVER_SOFTWARE"],
        "mysql_version" => mysqli_get_client_info(),
        "bcmath" => extension_loaded('bcmath') ? "_YES" : "_NO",
        "gmp" => extension_loaded('gmp') ? "_YES" : "_NO"
    );

    $smarty->assign("next", true);
    $smarty->assign("checkvalue", "_REFRESH");
    $smarty->assign("php_settings", $php_settings);
}

/////////////// Step 3: Directory Settings /////////////////
if ($sitenr == 3) {
    $config->path_root = filter_input(INPUT_POST, 'path_root', FILTER_SANITIZE_STRING) ?? $config->path_root;
    $config->document_root = filter_input(INPUT_POST, 'document_root', FILTER_SANITIZE_STRING) ?? $config->document_root;
    
    $dirs = [
        "document_root" => $config->document_root,
        "path_root" => $config->path_root,
        "include" => is__writable($config->path_root."/include/"),
        "files" => is__writable($config->path_root."/include/files/"),
        "backup" => is__writable($config->path_root."/include/backup/"),
        "temp" => is__writable($config->path_root."/temp/"),
        "templates_c" => is__writable($config->path_root."/include/smarty/templates_c/"),
        "setupphp" => is__writable($config->path_root."/")
    ];

    $smarty->assign("next", array_reduce($dirs, function($carry, $item) { return $carry && $item; }, true));
    $smarty->assign("checkvalue", "_RECHECK");
    $smarty->assign("dirs", $dirs);
}

/////////////// Step 4 /////////////////
if ($sitenr == 4 && isset($_POST["check4"])) {
    // Reset database check session variable
    $_SESSION["dbcheck"] = false;

    // Get and sanitize user input
    $dbhost = trim($_POST["dbhost"]);
    $dbuser = trim($_POST["dbuser"]);
    $dbpass = trim($_POST["dbpass"]);
    $dbdb = trim($_POST["dbdb"]);
    $dbprefix = trim($_POST["dbprefix"]);

    // Store values in session
    $_SESSION["dbhost"] = $dbhost;
    $_SESSION["dbuser"] = $dbuser;
    $_SESSION["dbpass"] = $dbpass;
    $_SESSION["dbdb"] = $dbdb;
    $_SESSION["dbprefix"] = $dbprefix;

    // Assign variables to Smarty for template display
    $smarty->assign("db", [$dbhost, $dbuser, $dbpass, $dbdb, $dbprefix]);

    // Validation: Check if any required fields are empty
    if (empty($dbhost) || empty($dbuser) || empty($dbdb) || empty($dbprefix)) {
        $msg = "_NOREQUIREDFIELDS"; // Error message for missing required fields
    } else {
        // Attempt to connect to the database using PDO
        try {
            $dsn = "mysql:host=$dbhost;dbname=$dbdb;charset=utf8";
            $pdo = new PDO($dsn, $dbuser, $dbpass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            // Check user privileges by querying the database
            $privileges = sql_get_privilege($pdo);
			// Required privileges for this operation
			$requiredPrivileges = ["SELECT", "INSERT", "UPDATE", "DELETE", "CREATE"];

			$missingPrivileges = array_diff($requiredPrivileges, $privileges);

			if (!empty($missingPrivileges)) {
			// User does not have all required privileges
				$msg = "_NOTALLPREVILEGES";
			}

            // Check for existing tables with the given prefix
            if (!isset($msg)) {
                $stmt = $pdo->query("SHOW TABLES LIKE '{$dbprefix}_%'");
                if ($stmt->rowCount() > 0) {
                    $prefix_exists = true;

                    // Check if the 'imported' field exists in the 'bans' table (version 6.0+)
                    $stmt = $pdo->query("SHOW COLUMNS FROM `{$dbprefix}_bans` WHERE Field = 'imported'");
                    if ($stmt->rowCount() > 0) {
                        $prefix_isnew = true;
                    }
                }

                $smarty->assign("prevs", $prev);

                if ($prefix_exists) {
                    if ($prefix_isnew) {
                        $msg = "_PREFIXEXISTSV6";
                        $_SESSION["dbcheck"] = true;
                        $smarty->assign("next", true);
                    } else {
                        $msg = "_PREFIXEXISTSV5";
                    }
                } else {
                    $msg = "_DBOK";
                    $_SESSION["dbcheck"] = true;
                    $smarty->assign("next", true);
                }
            }
        } catch (PDOException $e) {
            // Handle connection error
            $msg = "_CANTCONNECT ".$e;
        }
    }
}
if ($sitenr == 4) {
    $smarty->assign("checkvalue", "_DBCHECK");
}

/////////////// Step 5: Admin Account /////////////////
if ($sitenr == 5 && isset($_POST["check5"])) {
    $_SESSION["admincheck"] = false;
    $adminuser = trim($_POST["adminuser"]);
    $adminpass = trim($_POST["adminpass"]);
    $adminpass2 = trim($_POST["adminpass2"]);
    $adminemail = trim($_POST["adminemail"]);

    $_SESSION["adminuser"] = $adminuser;
    $_SESSION["adminemail"] = $adminemail;

    $validate = [];
    if (strlen($adminuser) < 2) $validate[] = "_USERTOSHORT";
    if (strlen($adminpass) < 2) $validate[] = "_PWTOSHORT";
    if ($adminpass != $adminpass2) $validate[] = "_PWNOCONFIRM";
    if (!filter_var($adminemail, FILTER_VALIDATE_EMAIL)) $validate[] = "_NOVALIDEMAIL";

    if (empty($validate)) {
        $_SESSION["adminpass"] = $adminpass;
        $_SESSION["admincheck"] = true;
        $msg = "_ADMINOK";
        $smarty->assign("next", true);
    }

    $smarty->assign("validate", $validate);
}
if($sitenr==5) $smarty->assign("checkvalue","_ADMINCHECK");
if($sitenr==6) $smarty->assign("checkvalue","_STEP7");
/////////////// Step 6: Installation /////////////////
if ($sitenr == 7 && $_SESSION["dbcheck"] == true && $_SESSION["admincheck"] == true && !isset($_POST["check7"])) {
    // Install tables and default data
    try {
		//get tables structure
		include("install/tables.inc");
		//create db structure
		foreach($table_create as $k => $v) {
			$table = ["table"=>$k,"success"=>sql_create_table($k,$v)];
			$tables[]=$table;
		}
		//get default data
		include("install/datas.inc");
		//create default data
		foreach($data_create as $k => $v) {
			$data = ["data"=>$k,"success"=>sql_insert_data($k,$v)];
			$datas[]=$data;
		}
		
		//create default websettings
		$websettings_create = ["data"=>"_CREATEWEBSETTINGS","success"=>sql_insert_setting($websettings_query)];
		//create default usermenu
		$usermenu_create = ["data"=>"_CREATEUSERMENU","success"=>sql_insert_setting($usermenu_query)];
		//create webadmin userlevel
		$webadmin_create[] = ["data"=>"_CREATEUSERLEVEL","success"=>sql_insert_setting($userlevel_query)];
		//create webadmin
		$webadmin_create[] = ["data"=>"_CREATEWEBADMIN","success"=>sql_insert_setting($webadmin_query)];
		//install default modules
		foreach($modules_install as $k => $v) {
			$modul = ["name"=>$k,"success"=>sql_insert_setting($v)];
			$modules[] = $modul;
		}

        // Write config
        $content = "<?php\n\n"
            . "\$config->document_root = \"{$_SESSION['document_root']}\";\n"
            . "\$config->path_root = \"{$_SESSION['path_root']}\";\n"
            . "\$config->db_host = \"{$_SESSION['dbhost']}\";\n"
            . "\$config->db_user = \"{$_SESSION['dbuser']}\";\n"
            . "\$config->db_pass = \"{$_SESSION['dbpass']}\";\n"
            . "\$config->db_db = \"{$_SESSION['dbdb']}\";\n"
            . "\$config->db_prefix = \"{$_SESSION['dbprefix']}\";\n?>";

		$msg = write_cfg_file($config->path_root."/include/db.config.inc.php",$content);
		$smarty->assign("content",$content);
		//create first log ;-)
		sql_insert_setting($log_query);
    } catch (Exception $e) {
        $msg = "_INSTALLFAILED ".$e;
    }
	$smarty->assign("tables",$tables);
	$smarty->assign("datas",$datas);
	$smarty->assign("modules",$modules);
	$smarty->assign("usermenu_create",$usermenu_create);
	$smarty->assign("websettings_create",$websettings_create);
	$smarty->assign("webadmin_create",$webadmin_create);
	$smarty->assign("checkvalue","_SETUPEND");
}

if($sitenr==7 && isset($_POST["check7"])) {
	//clear smarty cache
	$smarty->clear_compiled_tpl();
	//delete setup.php
	@unlink("setup.php");
	header("Location: index.php");
	exit;
}

// Assign session paths
$_SESSION["path_root"] = $config->path_root;
$_SESSION["document_root"] = $config->document_root;

// Generate template
$smarty->assign("msg", $msg ?? "");
$smarty->assign("sitenr", $sitenr);
$smarty->assign("sitenrall", $sitenrall);
$smarty->assign("current_lang", $config->default_lang);
$smarty->assign("v_web", $config->v_web);
$smarty->template_dir = $config->path_root.'/install';
$smarty->compile_dir = $config->path_root."/include/smarty/templates_c";
$smarty->display('setup.tpl');
