<?php
session_start();

// Require basic site files
require_once("include/config.inc.php");
require_once("include/access.inc.php");
require_once("include/menu.inc.php");
require_once("include/functions.inc.php");
//require_once("include/logfunc.inc.php");
//require_once("include/sql.inc.php");

require_once("include/rcon_hl_net.inc");

//fetch server_information
$resource2  = mysqli_query($mysql, "SELECT * FROM ".$config->db_prefix."_serverinfo ORDER BY hostname ASC") or die (mysqli_error($mysql));

while($result2 = mysqli_fetch_object($resource2)) {

  $split_address = explode (":", $result2->address);
  $ip  = $split_address['0'];
  $port    = $split_address['1'];
    
  if($ip && $port) {
    $server = new Rcon();
    $ip=gethostbyname($ip);
    $server->Connect($ip, $port, $result2->rcon);

    $infos = $server->Info();
    if($infos) { //yes, infos ok
      $players = $server->Players();
      $rules = $server->ServerRules();
      
      //copy rules to rules array for template
      if(is_array($rules)) {
        foreach($rules as $k => $v){
          $rules_array[] =array("name"=>$k,"value"=>$v);
        }
      }
      //check if mappic exists
      if(file_exists("images/maps/".$infos[mod]."/".$infos[map].".jpg")) {
        $mappic = $infos[map];
      } else {
        $mappic = "noimage";
      }
      
      //create addons array
      if(is_array($rules)) {
        //create anticheat array
        if($infos[secure]) $anticheat_array[]=array("name"=>"VAC","version"=>"2","url"=>"");
        if($rules[sbsrv_version]) $anticheat_array[]=array("name"=>"Steambans","version"=>$rules[sbsrv_version],"url"=>"http://www.steambans.com");
        if($rules[hlg_version]) $anticheat_array[]=array("name"=>"HLGuard","version"=>$rules[hlg_version],"url"=>"");
      }
      //main server info
      $server_info = [
        "sid"      => $result2->id,
        "type"      => $infos[type],
        "version"    => $infos[version],
        "hostname"    => $infos[name], 
        "map"      => $infos[map],
        "mod"      => $infos[mod],
        "game"      => $infos[game],
        "appid"      => $infos[appid],
        "cur_players"    => $infos[activeplayers], 
        "max_players"    => $infos[maxplayers],
        "bot_players"    => $infos[botplayers],
        "dedicated"    => ($infos[dedicated]=="d")?"Dedicated":"Listen",
        "os"      => ($infos[os]=="l")?"Linux":"Windows",
        "password"    => $infos[password],
        "secure"    => $infos[secure],
        "sversion"    => $infos[sversion],
        "timeleft"    => $rules[amx_timeleft],
        "maxrounds"    => $rules[mp_maxrounds],
        "timelimit"    => $rules[mp_timelimit],
        "nextmap"    => $rules[amx_nextmap],
        "friendlyfire"    => $rules[mp_friendlyfire],
        "address"    => $result2->address,
        "mappic"    => $mappic,
        "players"    => ""
      ];

      //get the players
      $player_array  = [];
      $int = $infos[activeplayers];
      for ($i=0; $i<$int; $i++) {
        $player = $players[$i];
        $player[name] = htmlspecialchars($player[name]);

        $player_info = array(
          "name"    => $player[name],
          "frag"    => $player[frag],
          "time"    => $player[time],
          );

        $player_array[] = $player_info;
      }
      
      $server_info[players] = $player_array;
      $server_array[] = $server_info;
    } else {
      $server_info = [
        "sid"      => $result2->id,
        "type"      => "",
        "version"    => "",
        "hostname"    => htmlsafe_recursive($result2->hostname), 
        "map"      => "",
        "mod"            => htmlspecialchars($result2->gametype),
        "game"      => "",
        "appid"      => "",
        "cur_players"    => "0", 
        "max_players"    => "0",
        "bot_players"    => "0",
        "dedicated"    => "",
        "os"      => "",
        "password"    => "",
        "secure"    => "",
        "sversion"    => "",
        "timeleft"    => "00:00",
        "maxrounds"    => "0",
        "timelimit"    => "00",
        "nextmap"    => "",
        "friendlyfire"    => "",
        "address"    => $result2->address,
        "mappic"    => "noimage",
        "players"    => ""
      ];
      $server_array[] = $server_info;
    }
    
    //close connection
    $server->Disconnect();
  }
}
/*
 *
 *     Stats
 *
 */
$stats[total]    = mysqli_num_rows( mysqli_query($mysql, "SELECT bid FROM ".$config->db_prefix."_bans") ); 
$stats[permanent]  = mysqli_num_rows( mysqli_query($mysql, "SELECT bid FROM ".$config->db_prefix."_bans WHERE ban_length = 0") ); 
$stats[active]  = mysqli_num_rows( mysqli_query($mysql, "SELECT bid FROM ".$config->db_prefix."_bans WHERE ((ban_created+(ban_length*60)) > ".time()." OR ban_length = 0)") );
$stats[temp]    = $stats[active] - $stats[permanent];
$stats[admins]  = mysqli_num_rows( mysqli_query($mysql, "SELECT id FROM ".$config->db_prefix."_amxadmins") );
$stats[servers]  = mysqli_num_rows( mysqli_query($mysql, "SELECT id FROM ".$config->db_prefix."_serverinfo") );
/*
 *
 *     Latest Ban
 *
 */
$latest_ban    = mysqli_query($mysql, "SELECT player_id, player_nick, ban_reason, ban_created, ban_length, ban_type FROM ".$config->db_prefix."_bans ORDER BY ban_created DESC LIMIT 1") or die (mysqli_error($mysql));
while($lb = mysqli_fetch_object($latest_ban)) {
  if($lb->ban_length == 0) {
    $ban_length  = 0;
  } else {
    $ban_length   = ($lb->ban_created + ($lb->ban_length * 60));
  }
  if($lb->ban_type == "SI") {
    $steamid  = "SI";
  } else {
    $steamid  = $lb->player_id;
  }

  $last_ban_arr    = [
    "steamid"  => $steamid,
    "nickname"  => htmlspecialchars(_substr($lb->player_nick, 15)),
    "reason"  => htmlspecialchars(_substr($lb->ban_reason, 15)),
    "created"  => $lb->ban_created,
    "length"  => $ban_length,
    "time"    => time());
  ];
/*
 *
 *     Template parsing
 *
 */

// Header
$title = "_TITLEVIEW";

// Section
$section = "live";

// Parsing
$smarty = new dynamicPage;
$smarty->setTemplateDir($config->templatedir);

$smarty->assign("meta","");
$smarty->assign("title",$title);
$smarty->assign("section",$section);
$smarty->assign("version_web",$config->v_web);

$smarty->assign("server",$server_array);
$smarty->assign("stats",$stats);
$smarty->assign("last_ban",$last_ban_arr);
$smarty->assign("addons",$addons_array);
$smarty->assign("rules",$rules);
$smarty->assign("rules_array",$rules_array);
$smarty->assign("anticheat_array",$anticheat_array);
$smarty->assign("players", isset($player_array) ? $player_array : NULL);
$smarty->assign("empty_result",isset($empty_result) ? $empty_result : NULL);
$smarty->assign("error",$error);

if(file_exists("templates/".$config->design."/main_header.tpl")) {
  $smarty->assign("design",$config->design);
}
$smarty->assign("dir",$config->document_root);
$smarty->assign("this",$_SERVER['PHP_SELF']);
$smarty->assign("menu",$menu);
$smarty->assign("banner",$config->banner);
$smarty->assign("banner_url",$config->banner_url);

$smarty->display('main_header.tpl');
      echo "<script type=\"text/javascript\">
  <!--
    function jumpMenu(selection, target)
    {
      var url = selection.options[selection.selectedIndex].value;
      
      if (url == \"\")
      {
        return false;
      }
      else
      {
        window.location = url;
      }
    }
  // -->
  </script>";
$smarty->display('view.tpl');
$smarty->display('main_footer.tpl');
?>