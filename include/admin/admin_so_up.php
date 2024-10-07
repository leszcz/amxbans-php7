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

$admin_site = "up";
$title2 = "_TITLEUPDATE";

$update_ip = "version.gm-community.net"; 
$update_user = "gm_amxbans";
$update_pw = "fdT3jyhc";
$update_db = "amxbans";

$error = [];

try {
    // Connect to remote database
    $dsn = "mysql:host=$update_ip;dbname=$update_db;charset=utf8mb4";
    $pdo_upd = new PDO($dsn, $update_user, $update_pw);
    $pdo_upd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // download data from remote database
    $stmt = $pdo_upd->query("SELECT * FROM `version` WHERE `for`='web' ORDER BY `release` DESC LIMIT 1");
    $version = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($version) {
        $version_info = array(
            "release" => $version['release'],
            "beta" => $version['beta'],
            "recommended_to" => $version['recommended_to'],
            "changelog" => $version['changelog'],
            "url" => $version['url']
        );
        $smarty->assign("version_db_web", $version_info);
    }
} catch (PDOException $e) {
    $error[] = "_UPD_CONNECT_ERROR: " . $e->getMessage();
}

$smarty->assign("error", $error);
?>
