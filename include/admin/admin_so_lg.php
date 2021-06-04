<?php
session_start();
if(!$_SESSION["loggedin"]) {
  header("Location:index.php");
}

if ( !has_access("websettings_view") ) {
  header("Location:index.php");
  exit;
}

$admin_site="lg";
$title2 ="_TITLELOGS";

//delete logs
if(isset($_POST["delall"])) {
  if ( !has_access("websettings_view") ) {
    header("Location:index.php");
    exit;
  }
  $query = mysqli_query($mysql, "DELETE FROM `".$config->db_prefix."_logs`") or die (mysqli_error($mysql));
  $user_msg="_LOGDELETED";
  log_to_db("Logs del","deleted all logs");
}
if(isset($_POST["delolder"])) {
  if ( !has_access("websettings_view") ) {
    header("Location:index.php");
    exit;
  }
  $days=(int)$_POST["days"];
    $query = mysqli_query($mysql, "DELETE FROM `".$config->db_prefix."_logs` WHERE UNIX_TIMESTAMP(now()) - timestamp > ".($days*84600)) or die (mysqli_error($mysql));
  $user_msg="_LOGDELETED";
  log_to_db("Logs del","deleted logs older than ".$days." days");
}
//get all logs
if(isset($_POST["username"]) && $_POST["username"]!="---") {
  if ( !has_access("websettings_view") ) {
    header("Location:index.php");
    exit;
  }
  $username=mysqli_real_escape_string($mysql, $_POST["username"]);
  $filter="`username`='".$username."'";
  $smarty->assign("username_checked",$username);
}
if(isset($_POST["action"]) && $_POST["action"]!="---") {
  if ( !has_access("websettings_view") ) {
    header("Location:index.php");
    exit;
  }
  $action=mysqli_real_escape_string($mysql, $_POST["action"]);
  $filter.=($filter)?" AND ":"";
  $filter.="`action`='".$action."'";
  $smarty->assign("action_checked",$action);
}
$logs=sql_get_logs($filter);
$smarty->assign("logs",$logs);

//get all usernames
  $query = mysqli_query($mysql, "SELECT * FROM `".$config->db_prefix."_logs` GROUP BY `username` ORDER BY `id`") or die (mysqli_error($mysql));
  $usernames["---"]="---";
  while($result = mysqli_fetch_object($query)) {
    if($result->username <> "") $usernames[html_safe($result->username)]=html_safe($result->username);
  }
  $smarty->assign("usernames",$usernames);
//get all actions
  $query = mysqli_query($mysql, "SELECT * FROM `".$config->db_prefix."_logs` GROUP BY `action` ORDER BY `id`") or die (mysqli_error($mysql));
  $actions["---"]="---";
  while($result = mysqli_fetch_object($query)) {
    if($result->action <> "") $actions[html_safe($result->action)]=html_safe($result->action);
  }
  $smarty->assign("actions",$actions);





?>