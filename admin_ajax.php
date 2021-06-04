<?php
session_start();

require_once("include/config.inc.php");
require_once("include/steam.inc.php");
require_once("include/sql.inc.php");
require_once("include/functions.inc.php");

$smarty = new dynamicPage;
$smarty->setTemplateDir($config->templatedir);
$admin_id = (INT)$_GET['id'];
global $mysql;

$servers_query = "SELECT hostname FROM `".$config->db_prefix."_admins_servers`
LEFT JOIN `".$config->db_prefix."_serverinfo` ON `".$config->db_prefix."_admins_servers`.server_id=`".$config->db_prefix."_serverinfo`.id WHERE admin_id=${admin_id}";

$servers_query = mysqli_query($mysql, $servers_query);

$servers = '';
while($res = mysqli_fetch_row($servers_query))
  $servers[] = $res[0];

$admin_data = "SELECT * FROM `".$config->db_prefix."_amxadmins` WHERE `id`={$admin_id} LIMIT 1";
$admin_data = mysqli_query($mysql, $admin_data);
$admin_data = mysqli_fetch_assoc($admin_data);

// Template generieren
$title = "_TITLEADMINLIST";
$smarty->assign("servers",$servers);
$smarty->assign("admin",$admin_data);
$smarty->assign("design",$config->design);

$smarty->display('admin_ajax.tpl');

?>