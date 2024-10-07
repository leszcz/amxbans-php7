<?php

declare(strict_types=1);

session_start();

require_once("include/config.inc.php");
require_once("include/access.inc.php");
require_once("include/menu.inc.php");
require_once("include/steam.inc.php");
require_once("include/sql.inc.php");
require_once("include/logfunc.inc.php");
require_once("include/functions.inc.php");

// Template generieren
$title = "_TITLEADMINLIST";
$smarty = new dynamicPage();
$smarty->setTemplateDir($config->templatedir);  

// get all amxadmins
$admins = sql_get_amxadmins_list();

$ReworkedAdmins = [];
$ReworkedServers = [];
$ReworkedAssigned = [];

foreach ($admins as $Admin) {
    $ReworkedAdmins[$Admin['aid']] = $Admin;
}

try {
    $pdo = getPDO();

    // get servers
    $stmt = $pdo->query("SELECT * FROM `{$config->db_prefix}_serverinfo`");
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ReworkedServers[] = [
            'id' => $result['id'],
            'hostname' => $result['hostname'],
            'gametype' => $result['gametype']
        ];
    }

    // get assigned admins
    $stmt = $pdo->query("SELECT * FROM `{$config->db_prefix}_admins_servers`");
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ReworkedAssigned[$result['server_id']][$result['admin_id']] = $result; 
    }

    $serverAdmins = [];
    foreach ($ReworkedServers as $Server) {
        $s = [
            'hostname' => $Server['hostname'],
            'id' => $Server['id'],
            'gametype' => $Server['gametype']
        ];
        
        $ServerId = $ReworkedAssigned[$Server['id']] ?? null;
        
        if (!$ServerId) {
            continue;
        }
        
        $aSd = [];
        foreach ($ServerId as $Id => $Admin) {
            $Admin = $ReworkedAdmins[$Id] ?? null;
            
            if (!$Admin) continue;
            
            $aSd[] = $Admin;
        }
        
        $s['admins'] = $aSd;
        
        $serverAdmins[] = $s;
    }

    $smarty->assign("admin_list", $serverAdmins);

    $e = var_export($serverAdmins, true);

    $smarty->assign("admin_list_structure", $e);
    $smarty->assign("admins", $admins);
    $smarty->assign("meta", "");
    $smarty->assign("title", $title);
    $smarty->assign("version_web", $config->v_web);
    if (file_exists("templates/{$config->design}/main_header.tpl")) {
        $smarty->assign("design", $config->design);
    }
    $smarty->assign("dir", $config->document_root);
    $smarty->assign("this", $_SERVER['PHP_SELF']);
    $smarty->assign("menu", $menu);
    $smarty->assign("banner", $config->banner);
    $smarty->assign("banner_url", $config->banner_url);

    $smarty->display('main_header.tpl');
    $smarty->display('admin_list.tpl');
    $smarty->display('main_footer.tpl');

} catch (PDOException $e) {
    // Log error and display user-friendly message
    error_log("Database error in admin_list.php: " . $e->getMessage());
    die("An error occurred while fetching admin list. Please try again later.");
}
?>