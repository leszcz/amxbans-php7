<?php
  session_start();
  
  if(!$_SESSION["loggedin"]) {
    header("Location:index.php");
    #exit;
  }
  if ( !has_access("amxadmins_view") ) {
    header("Location:index.php");
  }

  $admin_site="av";
  $title2 ="_TITLEAMXADMINS";
  
  if(isset($_POST["aid"])) {
    $aid=(int)$_POST["aid"];
  } else {
    $aid="";
  }
  
  //amxadmin delete
  if(isset($_POST["del"])) {
    if ( !has_access("amxadmins_view") ) {
      header("Location:index.php");
    }

    $query = mysqli_query($mysql, "DELETE FROM `".$config->db_prefix."_amxadmins` WHERE `id`=".$aid." LIMIT 1") or die (mysqli_error($mysql));
    $query = mysqli_query($mysql, "DELETE FROM `".$config->db_prefix."_admins_servers` WHERE `admin_id`=".$aid) or die (mysqli_error($mysql));
    $user_msg=$row1['_AMXADMINDELETED'];
    log_to_db("AMXXAdmin config","Deleted admin: ".mysqli_real_escape_string($mysql, $_POST["username"]));
  }
  //validate input values
  if(isset($_POST["save"]) || isset($_POST["new"])) {
    if ( !has_access("amxadmins_view") ) {
      header("Location:index.php");
    }

    $username=sql_safe($_POST["username"]);
    $password= $_POST["password"] ? md5($_POST["password"]) : '';
    $access=sql_safe($_POST["access"]);
    $flags=sql_safe($_POST["flags"]);
    $steamid=sql_safe($_POST["steamid"]);
    $nickname=sql_safe($_POST["nickname"]);
    $icq = (int)$_POST["icq"];
    
  /*  if(!validate_value($username,"name",$error,0,31,"USERNAME")) $user_msg[]=$error;
    if(!validate_value($password,"name",$error,0,50,"PASSWORD")) $user_msg[]=$error;
    if(strrpos($flags,"a")===true || strrpos($flags,"e")===false) 
    if(!validate_value($access,"amxxaccess",$error)) $user_msg[]=$error;
    if(!validate_value($flags,"amxxflags",$error)) $user_msg[]=$error;
    //validate steamid depending on flags (steamid, name, ip)
    if(strrpos($flags,"b")!==false) { //clantag
      if(!validate_value($steamid,"name",$error,1,30,"TAG"))
        $user_msg[]=$error;
    } else if(strrpos($flags,"c")!==false) { //steamid
      if(!validate_value($steamid,"steamid",$error))
        $user_msg[]=$error;
    } else if(strrpos($flags,"d")!==false) { //ip
      if(!validate_value($steamid,"ip",$error))
        $user_msg[]=$error;
    }
    if(!validate_value($nickname,"name",$error,0,31,"NICKNAME")) $user_msg[]=$error; */
  }
  //amxadmin edit
  if(isset($_POST["save"])) {
    if ( !has_access("amxadmins_view") ) {
      header("Location:index.php");
    }

    if(isset($_POST["noend"])) {
      $days=0;
      $exp="0";
    } elseif (isset($_POST["moredays"]) && (int)$_POST["moredays"]<>"") {
      $days=(int)$_POST["days"] + (int)$_POST["moredays"];
      $exp="(`created`+(".($days * 86400)."))";
    } else {
      $days=(int)$_POST["days"];
      $exp=($days<=0)?"0":"(`created`+(".($days * 86400)."))";
    }
    $password = $password ? " `password`='".$password."', ": "";
    if(!$user_msg) {
      $query = mysqli_query($mysql, "UPDATE `".$config->db_prefix."_amxadmins` SET 
            `username`='".$username."',
            {$password}
            `access`='".$access."',
            `flags`='".$flags."',
            `steamid`='".$steamid."',
            `nickname`='".$nickname."',
            `icq`='".$icq."',
            `ashow`='".(int)$_POST["ashow"]."',
            `expired`=".$exp.",
            `days`=".$days."
            WHERE `id`=".$aid." LIMIT 1") or die (mysqli_error());
      $user_msg=$row1['_AMXADMINSAVESUCCESS'];
      log_to_db("AMXXAdmin config","Edited admin: ".mysqli_real_escape_string($mysql, $_POST["username"])." (nick: ".mysqli_real_escape_string($mysql, $_POST["nickname"]).")");
    }
  }
  //amxadmin add
  if(isset($_POST["new"])) {
    if ( !has_access("amxadmins_view") ) {
      header("Location:index.php");
    }

    $exp="0,";
    if((int)$_POST["days"]=="" && (!isset($_POST["noend"]))) {
      $user_msg=$row1['_NOVALIDTIME'];
    } elseif (isset($_POST["noend"])) {
      $days=0;
    } else { 
      $days=(int)$_POST["days"];
      $exp="(UNIX_TIMESTAMP()+(".($days * 86400).")),";
    }
    if(!$user_msg) {
      $name=mysqli_real_escape_string($mysql, $_POST["username"]);
      //add new amxxadmin to db
      $query = mysqli_query($mysql, "INSERT INTO `".$config->db_prefix."_amxadmins` 
              (`username`,`password`,`access`,`flags`,`steamid`,`nickname`,`icq`,`ashow`,`created`,`expired`,`days`) 
              VALUES (
              '".$username."',
              '".$password."',
              '".$access."',
              '".$flags."',
              '".$steamid."',
              '".$nickname."',
              '".$icq."',
              ".(int)$_POST["ashow"].",
              UNIX_TIMESTAMP(),
              ".$exp."
              ".$days."
              )") or die (mysqli_error($mysql));
      //add as admin to selected servers
      $adminid=mysqli_insert_id($mysql);
      $addtoserver=$_POST["addtoserver"];
      $sban=mysqli_real_escape_string($mysql, $_POST["staticbantime"]);
      if(is_array($addtoserver)) {
        foreach($addtoserver as $k => $v) {
          $query = mysqli_query($mysql, "INSERT INTO `".$config->db_prefix."_admins_servers` 
              (`admin_id`,`server_id`,`custom_flags`,`use_static_bantime`) 
              VALUES 
              ('".$adminid."','".$v."','','".$sban."')
              ") or die (mysqli_error());
          }
      }
      $user_msg=$row1['_AMXADMINADDED'];
      log_to_db("AMXXAdmin config","Added admin: ".$name);
    } else {
      $input=array(
        "username"=>html_safe($username),
        "password"=>$password,
        "access"=>$access,
        "flags"=>$flags,
        "steamid"=>$steamid,
        "nickname"=>html_safe($nickname),
        "icq"=>$icq,
        "ashow"=>(int)$_POST["ashow"],
        "days"=>$_POST["days"],
        "moredays"=>(int)$_POST["moredays"],
        "noend"=>(isset($_POST["noend"])?1:0)
        );
      $smarty->assign("input",$input);
    }
  }
  //amxadmins holen
  $admins=sql_get_amxadmins();

  //server holen
  $servers=sql_get_server();
  $svalues = array();
  $soutput = array();

  if(is_array($servers)) {
    foreach($servers as $k => $v) {
      $svalues[]=$v["sid"];
      $soutput[]=$v["hostname"];
    }
  }
  
  $smarty->assign("yesno_choose",array("yes","no"));
  $smarty->assign("yesno_output",array("_YES","_NO"));
  $smarty->assign("ashow_output",array('_NO','_YES'));
  $smarty->assign("ashow",array(0,1));
  $smarty->assign("admins",$admins);
  $smarty->assign("svalues",$svalues);
  $smarty->assign("soutput",$soutput);
  
?>