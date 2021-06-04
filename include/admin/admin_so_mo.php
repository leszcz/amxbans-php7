<?php
session_start();
if(!$_SESSION["loggedin"]) {
  header("Location:index.php");
}

if ( !has_access("websettings_view") ) {
  header("Location:index.php");
  exit;
}

$admin_site="mo";
$title2 ="_TITLEMODULE";

if(isset($_POST["mid"])) {
  $mid=(int)$_POST["mid"];
} else {
  $mid="";
}

$modules_menu_count = 0;

//save module
if(isset($_POST["save"])) {
  if ( !has_access("websettings_edit") ) {
    header("Location:index.php");
    exit;
  }
  $query = mysql_query("UPDATE `".$config->db_prefix."_modulconfig` SET 
        `activ`=".(isset($_POST["activ"])?1:0).",
        `menuname`='".mysqli_real_escape_string($mysql, $_POST["menuname"])."',
        `name`='".mysqli_real_escape_string($mysql, $_POST["name"])."',
        `index`='".mysqli_real_escape_string($mysql, $_POST["index"])."'
        WHERE `id`=".$mid." LIMIT 1") or die (mysql_error());
  $user_msg='_MODULSAVED';
  log_to_db("Modules config","Edited module: ID ".$mid);
}

//get all modules
$modules2=sql_get_modules(0,$tmp);

$smarty->assign("modules_menu_count",$modules_menu_count);
$smarty->assign("modules2",$modules2);
  
?>