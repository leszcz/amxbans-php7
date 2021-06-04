<?php
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
$smarty = new dynamicPage;
$smarty->setTemplateDir($config->templatedir);  

// get all amxadmins
$admins=sql_get_amxadmins_list();

$ReworkedAdmins = Array( );
$ReworkedServers = Array( );
$ReworkedAssigned = Array( );
Foreach( $admins AS $Admin )
  $ReworkedAdmins[ $Admin[ 'aid' ] ] = $Admin;

// get servers
$query = mysqli_query($mysql, "SELECT * FROM `".$config->db_prefix."_serverinfo`") or die (mysqli_error());
while( $result = mysqli_fetch_assoc( $query ) ) {
  $ReworkedServers[ ] = Array(
    'id' => $result[ 'id' ],
    'hostname' => $result[ 'hostname' ],
    'gametype' => $result[ 'gametype' ] );
}
mysqli_free_result( $query );

// get assigned admins
$query = mysqli_query($mysql, "SELECT * FROM `".$config->db_prefix."_admins_servers`") or die (mysqli_error());
while( $result = mysqli_fetch_assoc( $query ) ) {
  $ReworkedAssigned[ $result[ 'server_id' ] ][ $result[ 'admin_id' ] ] = $result; 
}
mysqli_free_result( $query );



$serverAdmins = array();
ForEach( $ReworkedServers AS $Server ) {
  
  $s['hostname'] = $Server[ 'hostname' ];
  $s['id'] = $Server[ 'id' ];
  $s['gametype'] = $Server[ 'gametype' ];
  
  $ServerId = $ReworkedAssigned[ $Server[ 'id' ] ];
  
  if( !$ServerId )
    continue;
  
  $aSd = array();
  ForEach( $ServerId AS $Id => $Admin ) {
    $Admin = $ReworkedAdmins[ $Id ];
    
    if( !$Admin ) continue;
    
    $aSd[] = $Admin;
    
  }
  
  $s['admins'] = $aSd;
  
  $serverAdmins[] = $s;
}


$smarty->assign("admin_list",$serverAdmins);

$e = var_export($serverAdmins, TRUE);

$smarty->assign("admin_list_structure",$e);
$smarty->assign("admins",$admins);
$smarty->assign("meta","");
$smarty->assign("title",$title);
$smarty->assign("version_web",$config->v_web);
if(file_exists("templates/".$config->design."/main_header.tpl")) {
  $smarty->assign("design",$config->design);
}
$smarty->assign("dir",$config->document_root);
$smarty->assign("this",$_SERVER['PHP_SELF']);
$smarty->assign("menu",$menu);
$smarty->assign("banner",$config->banner);
$smarty->assign("banner_url",$config->banner_url);

$smarty->display('main_header.tpl');
$smarty->display('admin_list.tpl');
$smarty->display('main_footer.tpl');
?>