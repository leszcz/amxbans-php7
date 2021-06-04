<?php
session_start();
if(!$_SESSION["loggedin"]) {
  header("Location:index.php");
}
if ( !has_access("amxadmins_view") ) {
  header("Location:index.php");
  exit;
}
$admin_site="up";
$title2 ="_TITLEUPDATE";

$update_ip="version.gm-community.net"; 
$update_user="gm_amxbans";
$update_pw="fdT3jyhc";
$update_db="amxbans";

$error = array();


//get versions from update db
@$mysql_upd = mysqli_connect($update_ip,$update_user,$update_pw) or $error[]="_UPD_CONNECT_ERROR";
if($mysql_upd) {
  $resource = mysqli_select_db($update_db,$mysql_upd) or $error[]="_UPD_DB_ERROR";
  if(!$error) {  
    //get newest web versions info
    $query = mysqli_query($mysql_upd, "SELECT * FROM `version` WHERE `for`='web' ORDER BY `release` DESC LIMIT 1") or $error[]="_UPD_SELECT_ERROR";
    while($result = mysqli_fetch_object($query)) {
      $version=array(
        "release"=>$result->release,
        "beta"=>$result->beta,
        "recommended_to"=>$result->recommended_to,
        "changelog"=>$result->changelog,
        "url"=>$result->url
      );
    }
    $smarty->assign("version_db_web",$version);
  }
  mysqli_close($mysql_upd);
}
$smarty->assign("error",$error);
?>