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
  if ( !has_access("websettings_view") )
  {
    header("Location:index.php");
    exit;
  }
  
  $admin_site="ms";
  $title2 ="_TITLESITE";
  
  //Designs suchen
  $d=opendir($config->path_root."/templates/");
  while($f=readdir($d)) {
    if($f=="." || $f=="..") continue;
    if(is_dir($config->path_root."/templates/".$f)) {
      $designs[$f]=$f;
    }
  }
  closedir($d);
  
  //Banner suchen
  $banners[""]="---";
  $d=opendir($config->path_root."/images/banner/");
  while($f=readdir($d)) {
    if($f=="." || $f==".." || is_dir($config->path_root."/images/banner/".$f)) continue;
    if(is_file($config->path_root."/images/banner/".$f) && $f != "index.php") {
      $banners[$f]=$f;
    }
  }
  closedir($d);
  
  //Startseiten suchen
  //$start_pages[""]="---";
  $vorbidden=array("index.php","login.php","logout.php","admin.php","search.php","setup.php","motd.php");
  $d=opendir($config->path_root."/");
  while($f=readdir($d)) {
    if($f=="." || $f==".." || is_dir($config->path_root."/".$f)) continue;
    if(is_file($f) && !in_array($f,$vorbidden) && substr($f,-3,3)=="php") {
      $start_pages[$f]=$f;
    }
  }
  closedir($d);
  
  //Settings speichern
  if(isset($_POST["save"])) {
    if ( !has_access("websettings_edit") )
    {
      header("Location:index.php");
      exit;
    }
    $update_query="`cookie`='".mysqli_real_escape_string($mysql, $_POST["cookie"])."'";
    $update_query.=",`design`='".(mysqli_real_escape_string($mysql, $_POST["design"])=="---" ? "":mysqli_real_escape_string($mysql, $_POST["design"]))."'";
    $update_query.=",`bans_per_page`=".((is_numeric($_POST["bans_per_page"]) && $_POST["bans_per_page"] > 1)?(int)$_POST["bans_per_page"]:10);
    $update_query.=",`banner`='".(mysqli_real_escape_string($mysql, $_POST["banner"])=="---" ? "":mysqli_real_escape_string($mysql, $_POST["banner"]))."'";
    $update_query.=",`banner_url`='".mysqli_real_escape_string($mysql, trim($_POST["banner_url"]))."'";
    $update_query.=",`default_lang`='".mysqli_real_escape_string($mysql, $_POST["language"])."'";
    $update_query.=",`start_page`='".mysqli_real_escape_string($mysql, $_POST["start_page"])."'";
    $update_query.=",`show_comment_count`=".(int)$_POST["show_comment_count"];
    $update_query.=",`show_demo_count`=".(int)$_POST["show_demo_count"];
    $update_query.=",`show_kick_count`=".(int)$_POST["show_kick_count"];
    $update_query.=",`use_demo`=".(int)$_POST["use_demo"];
    $update_query.=",`use_comment`=".(int)$_POST["use_comment"];
    $update_query.=",`demo_all`=".(int)$_POST["demo_all"];
    $update_query.=",`comment_all`=".(int)$_POST["comment_all"];
    $update_query.=",`use_capture`=".(int)$_POST["use_capture"];
    $update_query.=",`auto_prune`=".(int)$_POST["auto_prune"];
    $update_query.=",`max_offences`=".((is_numeric($_POST["max_offences"]) && $_POST["max_offences"] > 1)?(int)$_POST["max_offences"]:10);
    $update_query.=",`max_offences_reason`='".(mysqli_real_escape_string($mysql, $_POST["max_offences_reason"])=="" ? "max offences reached":mysqli_real_escape_string($mysql, $_POST["max_offences_reason"]))."'";
    $update_query.=",`max_file_size`=".(int)$_POST["max_file_size"];
    $update_query.=",`file_type`='".(mysqli_real_escape_string($mysql, $_POST["file_type"]))."'";
    
    //save it to db
    $query = mysqli_query($mysql, "UPDATE `".$config->db_prefix."_webconfig` SET ".$update_query." WHERE `id`=1 LIMIT 1") or die (mysqli_error($mysql));
    $user_msg="_CONFIGSAVED";
    log_to_db("Websetting config","Changed");
    
    //set language
    $_SESSION["lang"]=mysqli_real_escape_string($mysql, $_POST["language"]);

    // Clearing cache
    $smarty->clearCompiledTemplate();
  }
  
  //get and set websettings
  $vars=sql_set_websettings();

  $smarty->assign("yesno_select",array("_YES","_NO"));
  $smarty->assign("yesno_values",array(1,0));
  $smarty->assign("vars",$vars);
  $smarty->assign("designs",$designs);
  $smarty->assign("banners",$banners);
  $smarty->assign("start_pages",$start_pages);
?>
