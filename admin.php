<?php

ini_set("display_errors", 0);

session_start();
if( $_SESSION['level'] == NULL || !$_SESSION['loggedin']==true) {
  header("Location: index.php"); 
  exit();
}

require_once("include/config.inc.php");
require_once("include/access.inc.php");
require_once("include/menu.inc.php");
require_once("include/logfunc.inc.php");
require_once("include/functions.inc.php");
require_once("include/sql.inc.php");

if(!$_SESSION["loggedin"]) {
  header("Location:index.php");
}

$site_start    = "so_in"; //Admin Start Page

$admin_site  = "default";
$user_msg = "";

$smarty = new dynamicPage;
$smarty->setTemplateDir($config->templatedir);  
  
//modul page loader
if(isset($_GET["modul"])) {
  $modul=basename($_GET["modul"]);
}else{
  $modul=basename("");
}

$modul_exists = "";
if(isset($_GET["modul"]) && file_exists("include/modules/modul_".$modul.".php")) {
  include("include/modules/modul_".$modul.".php");
  $modul_exists=1;
  
}
//admin page loader
if(isset($_GET["site"])) {
  $site=basename($_GET["site"]);
}else{
  $site=basename("");
}
if(!$modul_exists) {
  if(isset($_GET["site"]) && file_exists("include/admin/admin_".$site.".php")) {
    include("include/admin/admin_".$site.".php");
    $smarty->assign("menu_pos",$site);
  } else {
    include("include/admin/admin_".$site_start.".php");
    $smarty->assign("menu_pos",$site_start);
  }
}

//get module menu (only active)
$modules_menu_count=0;
$modules_menu=sql_get_modules(1, $modules_menu_count);

  
// Template generieren
$smarty->assign("meta","");
$smarty->assign("title",$title2);
$smarty->assign("version_web",$config->v_web);
$smarty->assign("banner",$config->banner);
$smarty->assign("banner_url",$config->banner_url);
if(file_exists("templates/".$config->design."/main_header.tpl")) {
  $smarty->assign("design",$config->design);
}
$smarty->assign("dir",$config->document_root);
$smarty->assign("current_lang",$config->default_lang);
$smarty->assign("this",$_SERVER['PHP_SELF']);
$smarty->assign("menu",$menu);
$smarty->assign("modules_menu",$modules_menu);
$smarty->assign("modules_menu_count",$modules_menu_count);
$smarty->assign("msg",$user_msg);
if($modul_exists==1) {
  $smarty->assign("site",$modul_site);
  $smarty->assign("menu_pos",$modul);
} else {
  $smarty->assign("site",$admin_site);
}

$smarty->display('main_header.tpl');
$smarty->display('admin_index.tpl');
if($modul_exists==1) {
  $smarty->display('modul_'.$modul_site.'.tpl');
} else {
  $smarty->display('admin_'.$admin_site.'.tpl');
}
$smarty->display('main_footer.tpl');

?>
