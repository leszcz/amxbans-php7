<?php
  session_start();


  include("include/config.inc.php");

  setcookie($config->cookie,"delete",time()-(60*60*24*7),"",$_SERVER["HTTP_HOST"]);
  global $config;
  
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  if(isset($_SESSION["uid"])) @$query = mysqli_query($sqlcon , "UPDATE `".$config->db_prefix."_webadmins` SET `logcode`=NULL WHERE `id`=".(int)$_SESSION["uid"]) or die (mysql_error());
  
  unset($_SESSION["uid"]);
  unset($_SESSION["uname"]);
  unset($_SESSION["email"]);
  unset($_SESSION["level"]);
  unset($_SESSION["sid"]);
  unset($_SESSION["loggedin"]);
  unset($_SESSION["logg3din"]);
  
  $temp=$_SESSION["lang"];
  session_destroy();
  @session_start();
  $_SESSION["lang"]=$temp;
  
  header("Location:index.php");

?>