<?php

/*   

  AMXBans v6.0
  
  Copyright 2009, 2010 by SeToY & |PJ|ShOrTy

  This file is part of AMXBans.

    AMXBans is free software, but it's licensed under the
  Creative Commons - Attribution-NonCommercial-ShareAlike 2.0

    AMXBans is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

    You should have received a copy of the cc-nC-SA along with AMXBans.  
  If not, see <http://creativecommons.org/licenses/by-nc-sa/2.0/>.

*/

  session_start();
  
  if(!$_SESSION["loggedin"]) {
    header("Location:index.php");
  }
  if ( !has_access("amxadmins_view") )
  {
    header("Location:index.php");
    exit;
  }
  
  $admin_site="bg";
  $title2 ="_TITLEREASONS";
  
  //new set
  if(isset($_POST["newset"])) {
    if ( !has_access("servers_edit") )
    {
      header("Location:index.php");
      exit;
    }
    $setname=sql_safe($_POST["setname"]);
    if(!validate_value($setname,"name",$error,1,31,"REASONSET")) $user_msg=$error;
    if(!$user_msg) {
      $query = mysqli_query($mysql, "INSERT INTO `".$config->db_prefix."_reasons_set` (`setname`)  VALUES ('".$setname."')") or die (mysqli_error($mysql));
      $user_msg='_REASONSETADDED';
      log_to_db("Reasons config","Added Set: ".sql_safe($setname));
    }
  }
  //new reason
  if(isset($_POST["newreason"])) {
    if ( !has_access("servers_edit") )
    {
      header("Location:index.php");
      exit;
    }
    $reason=sql_safe($_POST["reason"]);
    if(!validate_value($reason,"name",$error,1,99,"REASON")) $user_msg=$error;
    $time=(int)$_POST["static_bantime"];
    if(!$user_msg) {
      $query = mysqli_query($mysql, "INSERT INTO `".$config->db_prefix."_reasons` (`reason`,`static_bantime`)  VALUES ('".$reason."',".$time.")") or die (mysqli_error($mysql));
      $user_msg='_REASONADDED';
      log_to_db("Reasons config","Added Reason: ".sql_safe($reason)." (".$time." min)");
    }
  }
  
  $rsid=(int)$_POST["rsid"];
  $rid=(int)$_POST["rid"];
  
  //delete set
  if(isset($_POST["delset"])) {
    if ( !has_access("servers_edit") )
    {
      header("Location:index.php");
      exit;
    }
    $setname=html_safe($_POST["setname"]);
    //delete the set
    $query = mysqli_query($mysql, "DELETE FROM `".$config->db_prefix."_reasons_set` WHERE `id`=".$rsid." LIMIT 1") or die (mysqli_error($mysql));
    //delete all reasons for set
    $query = mysqli_query($mysql, "DELETE FROM `".$config->db_prefix."_reasons_to_set` WHERE `setid`=".$rsid) or die (mysqli_error($mysql));
    $user_msg='_REASONSETDELETED';
    log_to_db("Reasons config","Deleted set: ".sql_safe($setname));  
  }
  
  //save set
  if(isset($_POST["saveset"])) {
    if ( !has_access("servers_edit") )
    {
      header("Location:index.php");
      exit;
    }
    $setname=sql_safe($_POST["setname"]);
    if(!validate_value($setname,"name",$error,1,31,"REASONSET")) $user_msg=$error;
    if(!$user_msg) {
      $query = mysqil_query($mysql, "DELETE FROM `".$config->db_prefix."_reasons_to_set` WHERE `setid`=".$rsid) or die (mysqli_error($mysql));
      if (isset($_POST["aktiv"])) {
        foreach ($_POST["aktiv"] as $k => $v) {
          $query = mysqli_query($mysql, "INSERT INTO `".$config->db_prefix."_reasons_to_set` (`setid`,`reasonid`) VALUES (".$rsid.",".$v.")") or die (mysqli_error($mysql));
        }
      }
      $query = mysqli_query($mysql, "UPDATE `".$config->db_prefix."_reasons_set` SET `setname`='".$setname."' WHERE `id`=".$rsid." LIMIT 1") or die (mysqli_error($mysql));
      $user_msg='_REASONSSETSAVED';
      log_to_db("Reasons config","Edited set: ".sql_safe($setname));
    }
  }
  
  //del reason
  if(isset($_POST["reasondel"])) {
    if ( !has_access("servers_edit") )
    {
      header("Location:index.php");
      exit;
    }
    $reason=html_safe($_POST["reason"]);
    $query = mysqli_query($mysql, "DELETE FROM `".$config->db_prefix."_reasons` WHERE `id`=".$rid." LIMIT 1") or die (mysql_error($mysql));
    $query = mysqli_query($mysql, "DELETE FROM `".$config->db_prefix."_reasons_to_set` WHERE `reasonid`=".$rid) or die (mysql_error($mysql));
    $user_msg='_REASONDELETED';
    log_to_db("Reasons config","Deleted reason: ".sql_safe($reason));
  }
  
  //save reason
  if(isset($_POST["reasonsave"])) {
    if ( !has_access("servers_edit") )
    {
      header("Location:index.php");
      exit;
    }
    $reason=sql_safe($_POST["reason"]);
    if(!validate_value($reason,"name",$error,1,99,"REASON")) $user_msg=$error;
    if(!$user_msg) {
      $time=(int)$_POST["static_bantime"];
      $query = mysqli_query($mysql, "UPDATE `".$config->db_prefix."_reasons` SET `reason`='".$reason."',`static_bantime`=".$time." WHERE `id`=".$rid." LIMIT 1") or die (mysql_error($mysql));
      $user_msg='_REASONSAVED';
      log_to_db("Reasons config","Edited reason: ".sql_safe($reason)." (".$time." min)");
    }
  }
  
  //reason sets holen
  $reasons_set=sql_get_reasons_set();
  $smarty->assign("reasons_set",$reasons_set);
  
  //reason holen
  $reasons=sql_get_reasons();
  
  $check_values=array("1","0");
  $check_output=array("Ja","Nein");
  $smarty->assign("check_values",$check_values);
  $smarty->assign("check_output",$check_output);
  $smarty->assign("reasons",$reasons);
?>
