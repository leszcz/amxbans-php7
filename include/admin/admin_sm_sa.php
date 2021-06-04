<?php
  session_start();
  if(!$_SESSION["loggedin"]) {
    header("Location:index.php");
  }
  if ( !has_access("amxadmins_view") ) {
    header("Location:index.php");
    exit;
  }

  $admin_site="sa";
  $title2 ="_TITLESERVERADMINS";
  
  if(isset($_POST["sid"])) {
    $sid=(int)$_POST["sid"];
  } else {
    $sid="";
  }
  
  $reasons_choose = "";
  $reasons_values = "";
  
  if(isset($_POST["save"])) {
    $aktiv=$_POST["aktiv_new"];
    $custom_flags=$_POST["custom_flags"];
    $use_static_bantime=$_POST["use_static_bantime"];
    $user_id=$_POST["hid_uid"];
    //delete all admins for this server
    $query = mysqli_query($mysql, "DELETE FROM `".$config->db_prefix."_admins_servers` WHERE `server_id`=".$sid) or die (mysqli_error($mysql));
    //search for the new settings
    if(is_array($aktiv)) {
      foreach($aktiv as $k => $aid) {
        if((int)$aid) {
          $cflags=sql_safe(trim($custom_flags[$k]));
          $sban=sql_safe(trim($use_static_bantime[$k]));
          $uid=sql_safe(trim($user_id[$k]));
          //safe the admin to the db
          $query = mysqli_query($mysql, "INSERT INTO `".$config->db_prefix."_admins_servers` 
                (`admin_id`,`server_id`,`custom_flags`,`use_static_bantime`) 
                VALUES 
                ('".(int)$aid."','".$sid."','".trim($cflags)."','".$sban."')
                ") or die (mysqli_error($mysql));
        }
      }
    }
    $user_msg='_SADMINSAVED';
    $smarty->assign("msg",$user_msg);
    log_to_db("Server Admin config","Edited admins on server: ".sql_safe($_POST["sidname"]));
  }
  if(isset($_POST["admins_edit"])) {
    $editadmins=array("sidname"=>html_safe($_POST["sidname"]),"sid"=>$sid);
    $smarty->assign("editadmins",$editadmins);
    
    $admins=sql_get_amxadmins_server($sid);
    $smarty->assign("admins",$admins);
  }
  //Servers holen
  $servers=sql_get_server();
  
  $delay_choose=array(1,2,5,10);
  $yesno_choose=array("yes","no");
  $yesno_output=array("_YES","_NO");
  $onetwo_choose=array(1,0);
  $smarty->assign("onetwo_choose",$onetwo_choose);
  $smarty->assign("delay_choose",$delay_choose);
  $smarty->assign("yesno_choose",$yesno_choose);
  $smarty->assign("yesno_output",$yesno_output);
  $smarty->assign("reasons_choose",$reasons_choose);
  $smarty->assign("reasons_values",$reasons_values);
  $smarty->assign("servers",$servers);
?>