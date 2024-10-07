<?php
session_start();
if (!$_SESSION["loggedin"]) {
    header("Location:index.php");
    exit;
}
if (!has_access("amxadmins_view")) {
    header("Location:index.php");
    exit;
}

$admin_site = "vs";
$title2 = "_TITLEUPDATE";

$update_ip = "version.gm-community.net"; 
$update_user = "gm_amxbans";
$update_pw = "fdT3jyhc";
$update_db = "amxbans";

$error = array();

// get versions from servers
try {
    $pdo = getPDO();
    $stmt = $pdo->query("SELECT `hostname`, `address`, `amxban_version` FROM `".$config->db_prefix."_serverinfo` ORDER BY `hostname`");
    $version_server = [];
    $server_count = 0;
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $version = array(
            "hostname" => $row['hostname'],
            "address" => $row['address'],
            "version" => $row['amxban_version']
        );
        $version_server[] = $version;
        $server_count++;
    }
    
    $smarty->assign("server_count", $server_count);
    $smarty->assign("version_server", $version_server);
    
} catch (PDOException $e) {
    $error[] = "_DB_CONNECT_ERROR: " . $e->getMessage();
}

// get version from origin database
try {
    $dsn_upd = "mysql:host=$update_ip;dbname=$update_db;charset=utf8mb4";
    $pdo_upd = new PDO($dsn_upd, $update_user, $update_pw);
    $pdo_upd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // get last version for AMXBANS
    $stmt = $pdo_upd->query("SELECT * FROM `version` WHERE `for`='web' ORDER BY `release` DESC LIMIT 1");
    $version_web = $stmt->fetch(PDO::FETCH_ASSOC);
    $smarty->assign("version_db_web", $version_web);
    
    // get last version for plugins
    $stmt = $pdo_upd->query("SELECT * FROM `version` WHERE `for`='plugin' ORDER BY `release` DESC LIMIT 1");
    $version_plugin = $stmt->fetch(PDO::FETCH_ASSOC);
    $smarty->assign("version_db_plugin", $version_plugin);
    
} catch (PDOException $e) {
    $error[] = "_UPD_CONNECT_ERROR: " . $e->getMessage();
}

$smarty->assign("error", $error);
?>
