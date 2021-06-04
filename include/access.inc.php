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
require_once("config.inc.php");
require_once("include/functions.inc.php");

function has_access($value) {
  if($_SESSION["loggedin"]) {
    return $_SESSION[$value]=="yes";
  }
  return 0;
}

if(isset($_COOKIE[$config->cookie]) && $_SESSION["loggedin"]==false) {
    $cook = explode(":", sql_safe($_COOKIE[$config->cookie]));
  
    $sid = $cook[0];
    if(!$_SESSION["lang"]) $_SESSION["lang"]=$cook[1];
    
    $mysql = mysqli_connect($config->db_host,$config->db_user,$config->db_pass) or die (mysqli_error($mysql));
    $resource = mysqli_select_db($mysql,$config->db_db) or die (mysqli_error($mysql));

    if ( strlen( $sid ) >= 16  )
    {
      $query = mysqli_query($mysql, "SELECT id,username,level,email FROM `".$config->db_prefix."_webadmins` WHERE logcode='".$sid."' LIMIT 1") or die (mysqli_error($mysql));
      if(mysqli_num_rows($query)) {
        while($result = mysqli_fetch_object($query)) {
          $_SESSION["uid"]=$result->id;
          $_SESSION["uname"]=$result->username;
          $_SESSION["email"]=$result->email;
          $_SESSION["level"]=$result->level;
          $_SESSION["sid"]=session_id();
          $_SESSION["loggedin"]=true;
        }
        $query = mysqli_query($mysql, "SELECT * FROM `".$config->db_prefix."_levels` WHERE level=".$_SESSION["level"]." LIMIT 1") or die (mysqli_error($mysql));
        while($result = mysqli_fetch_object($query)) {
          $_SESSION['bans_add'] = $result->bans_add;
          $_SESSION['bans_edit'] = $result->bans_edit;
          $_SESSION['bans_delete'] = $result->bans_delete;
          $_SESSION['bans_unban'] = $result->bans_unban;
          $_SESSION['bans_import'] = $result->bans_import;
          $_SESSION['bans_export'] = $result->bans_export;
          $_SESSION['amxadmins_view'] = $result->amxadmins_view;
          $_SESSION['amxadmins_edit'] = $result->amxadmins_edit;
          $_SESSION['webadmins_view'] = $result->webadmins_view;
          $_SESSION['webadmins_edit'] = $result->webadmins_edit;
          $_SESSION['websettings_view'] = $result->websettings_view;
          $_SESSION['websettings_edit'] = $result->websettings_edit;
          $_SESSION['permissions_edit'] = $result->permissions_edit;
          $_SESSION['prune_db'] = $result->prune_db;
          $_SESSION['servers_edit'] = $result->servers_edit;
          $_SESSION['ip_view'] = $result->ip_view;
        }
      }
    }
}
if(isset($_COOKIE[$config->cookie]) && $_SESSION["loggedin"]==true) {
  $query = mysqli_query($mysql, "UPDATE `".$config->db_prefix."_webadmins` SET `last_action`=UNIX_TIMESTAMP() WHERE `id`=".$_SESSION["uid"]);
}
/*        
if($_SESSION["sid"] != session_id()) {
  unset($_SESSION["uid"]);
  unset($_SESSION["uname"]);
  unset($_SESSION["email"]);
  unset($_SESSION["level"]);
  unset($_SESSION["sid"]);
  unset($_SESSION["loggedin"]);
}
*/
?>
