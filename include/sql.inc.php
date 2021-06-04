<?php
$config->document_root = "/";
$config->path_root = getcwd();
  
function sql_set_websettings() {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);


  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_webconfig`") or die (mysqli_error($sqlcon));
  $result = mysqli_fetch_object($query);
  $config->cookie=$result->cookie;
  $config->bans_per_page=($result->bans_per_page)<1 ? 1:$result->bans_per_page;
  $config->design = $result->design;
  $config->banner = $result->banner;
  $config->banner_url = $result->banner_url;
  $config->default_lang = $result->default_lang;
  $config->start_page = $result->start_page;
  $config->show_kick_count = $result->show_kick_count;
  $config->show_comment_count = $result->show_comment_count;
  $config->show_demo_count = $result->show_demo_count;
  $config->demo_all = $result->demo_all;
  $config->max_file_size = $result->max_file_size;
  $config->file_type = $result->file_type;
  $config->comment_all = $result->comment_all;
  $config->use_capture = $result->use_capture;
  $config->auto_prune = $result->auto_prune;
  $config->max_offences = $result->max_offences;
  $config->max_offences_reason = $result->max_offences_reason;
  $config->use_demo = $result->use_demo;
  $config->use_comment = $result->use_comment;
  //set vars to an array
  $vars=[
      "cookie"=>trim($config->cookie),
      "design"=>$config->design,
      "bans_per_page"=>(int)$config->bans_per_page,
      "banner"=>$config->banner,
      "banner_url"=>$config->banner_url,
      "default_lang"=>$config->default_lang,
      "start_page"=>$config->start_page,
      "show_kick_count"=>$config->show_kick_count,
      "show_demo_count"=>$config->show_demo_count,
      "show_comment_count"=>$config->show_comment_count,
      "demo_all" => $config->demo_all,
      "comment_all" => $config->comment_all,
      "use_capture" => $config->use_capture,
      "max_file_size" => (int)$config->max_file_size,
      "file_type" => trim($config->file_type),
      "auto_prune" => (int)$config->auto_prune,
      "max_offences" => (int)$config->max_offences,
      "max_offences_reason" => $config->max_offences_reason,
      "use_demo" => (int)$result->use_demo,
      "use_comment" => $result->use_comment
  ];
  return $vars;
}
function sql_get_server($serverid=0) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  if($serverid) {
    $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_serverinfo` WHERE `id`=".$serverid." LIMIT 1") or die (mysqli_error($mysql));
  } else {
    $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_serverinfo` ORDER BY `address` ASC") or die (mysqli_error($mysql));
    $servers=[];
  }
  while($result = mysqli_fetch_object($query)) {
    $server=[
      "sid"=>$result->id,
      "timestamp"=>$result->timestamp,
      "hostname"=>$result->hostname,
      "address"=>$result->address,
      "gametype"=>$result->gametype,
      "rcon"=>$result->rcon,
      "amxban_version"=>$result->amxban_version,
      "amxban_motd"=>$result->amxban_motd,
      "motd_delay"=>$result->motd_delay,
      "amxban_menu"=>$result->amxban_menu,
      "reasons"=>$result->reasons,
      "timezone_fixx"=>$result->timezone_fixx
    ];
    if(!$serverid) $servers[]=$server;
  }
  if(!$serverid) return $servers;
  return $server;

  htmlsafe_recursive($server);
}
function sql_get_server_ids() {
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  global $config;
  $query = mysqli_query($sqlcon, "SELECT `id` FROM `".$config->db_prefix."_serverinfo` ORDER BY `address` ASC") or die (mysqli_error($mysql));
  $serverids=[];
  while($result = mysqli_fetch_object($query)) {
    $serverids[]=$result->id;
  }
  return $serverids;

  htmlsafe_recursive($serverids);
}
function sql_get_reasons_set() {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  
  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_reasons_set` ORDER BY `setname` ASC") or die (mysqli_error($mysql));
  $reasons_set=[];
  while($result = mysqli_fetch_object($query)) {
    $reason_set=array(
      "id"=>$result->id,
      "setname"=>html_safe($result->setname)
      );
    $query2 = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_reasons_to_set` WHERE `setid`=".$result->id) or die (mysqli_error($mysql));
    $reasons="";
    while($result2 = mysqli_fetch_object($query2)) {
      $reasons.=($reasons)?",".$result2->reasonid:$result2->reasonid;
    }
    $reason_set["reasonids"]=$reasons;
    $reasons_set[]=$reason_set;
  }
  return $reasons_set;
}
function sql_get_reasons() {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  
  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_reasons` ORDER BY `id` ASC") or die (mysqli_error($mysql));
  $reasons=[];
  while($result = mysqli_fetch_object($query)) {
    $reason=array(
      "id"=>$result->id,
      "reason"=>html_safe($result->reason),
      "static_bantime"=>$result->static_bantime
      );
    $reasons[]=$reason;
  }
  return $reasons;
}
function sql_get_reasons_list() {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  
  $query = mysqli_query($sqlcon, "SELECT reason FROM `".$config->db_prefix."_reasons` ORDER BY `id` ASC") or die (mysqli_error($mysql));
  while($result = mysqli_fetch_object($query)) {
    $reasons[]=$result->reason;
  }
  return $reasons;
}
function sql_get_amxadmins() {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  
  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_amxadmins` ORDER BY `access` ASC") or die (mysqli_error($mysql));
  $admins=[];
  while($result = mysqli_fetch_object($query)) {
    $admin = [
      "aid"=>$result->id,
      "username"=>html_safe($result->username),
      "password"=>$result->password,
      "access"=>$result->access,
      "flags"=>$result->flags,
      "steamid"=>$result->steamid,
      "nickname"=>html_safe($result->nickname),
      "icq"=>$result->icq,
      "ashow"=>$result->ashow,
      "created"=>$result->created,
      "expired"=>$result->expired,
      "days"=>$result->days
    ];
    $admins[]=$admin;
  }
  return $admins;
}
function sql_get_amxadmins_list() {
  
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_amxadmins` WHERE `ashow`=1 AND (`expired`=0 OR `expired`>UNIX_TIMESTAMP()) ORDER BY `access` ASC") or die (mysqli_error($mysql));
  $admins=[];
  while($result = mysqli_fetch_object($query)) {
    if(!empty($result->steamid)) {
      $steamid = htmlentities($result->steamid, ENT_QUOTES);
      $steamcomid = GetFriendId($steamid);
    }
    $admin = [
      "aid"=>$result->id,
      "username"=>html_safe($result->username),
      "comid"=>$steamcomid,
      "password"=>$result->password,
      "access"=>$result->access,
      "flags"=>$result->flags,
      "steamid"=>$result->steamid,
      "nickname"=>html_safe($result->nickname),
      "icq"=>$result->icq,
      "ashow"=>$result->ashow,
      "created"=>$result->created,
      "expired"=>$result->expired,
      "days"=>$result->days
    ];
    $admins[]=$admin;
  }
  return $admins;
}

function sql_get_amxadmins_server($server) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  
  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_amxadmins` ORDER BY `access` ASC") or die (mysqli_error($mysql));
  $admins=[];
  while($result = mysqli_fetch_object($query)) {
    $query2 = mysqli_query($sqlcon, "SELECT `custom_flags`,`use_static_bantime` FROM `".$config->db_prefix."_admins_servers` WHERE `admin_id`=".$result->id." AND `server_id`=".$server) or die (mysqli_error($mysql));
    if($result2 = mysqli_fetch_object($query2)) {
        $custom_flags=$result2->custom_flags;
        $static_bantime=$result2->use_static_bantime;
        $aktiv=1;
    } else {
        $custom_flags="";
        $static_bantime="no";
        $aktiv=0;
    }
    $admin=[
      "sid"=>$server,
      "aid"=>$result->id,
      "username"=>html_safe($result->username),
      "password"=>$result->password,
      "access"=>$result->access,
      "flags"=>$result->flags,
      "steamid"=>$result->steamid,
      "nickname"=>html_safe($result->nickname),
      "icq"=>$result->icq,
      "ashow"=>$result->ashow,
      "custom_flags"=>$custom_flags,
      "use_static_bantime"=>$static_bantime,
      "aktiv"=>$aktiv
    ];
    $admins[]=$admin;
  }
  return $admins;
}

function sql_get_servers_admins() {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);


  $ReworkedAdmins = Array( );
  $ReworkedServers = Array( );
  $ReworkedAssigned = Array( );

  Foreach( $admins AS $Admin )
    $ReworkedAdmins[ $Admin[ 'aid' ] ] = $Admin;
  
  // get servers
  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_serverinfo`") or die (mysqli_error($mysql));
  while( $result = mysqli_fetch_assoc( $query ) ) {
    $ReworkedServers = Array(
      "id" => $result->id,
      "hostname" => $result->hostname,
      "gametype" => $result->gametype
    );
  }
  mysqli_free_result( $query );
  
  // get assigned admins
  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_admins_servers`") or die (mysqli_error($mysql));
  while( $result = mysqli_fetch_assoc( $query ) ) {
    $ReworkedAssigned[ $result[ 'server_id' ] ]
      [ $result[ 'admin_id' ] ] = $result; 
  }
  mysqli_free_result( $query );
  
  return $servers_admins;
}

function sql_get_webadmins() {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $query = mysqli_query($sqlcon, "SELECT id,username,level,last_action,email FROM `".$config->db_prefix."_webadmins` ORDER BY `level`,`username`") or die (mysqli_error($mysql));
  $users=[];

  while($result = mysqli_fetch_object($query)) {
    $user=array(
      "uid"=>$result->id,
      "name"=>html_safe($result->username),
      "level"=>$result->level,
      "last_action"=>$result->last_action,
      "email"=>html_safe($result->email)
    );
    $users[]=$user;
  }
  return $users;
}

function sql_get_server_admins($server) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $serveradmins=[];
  $query = mysqli_query($sqlcon, "SELECT s.admin_id,s.custom_flags,s.use_static_bantime,a.username FROM ".$config->db_prefix."_admins_servers as s,".$config->db_prefix."_amxadmins as a WHERE s.server_id=".$server) or die (mysqli_error($mysql));
  $admins=[];
  while($result = mysqli_fetch_object($query)) {
    $admin=array(
      "admin_id"=>$result->admin_id,
      "custom_flags"=>$result->custom_flags,
      "use_static_bantime"=>$result->static_bantime,
      "username"=>html_safe($result->username)
    );
    $admins[]=$admin;
  }
  return $admins;
}
function sql_get_usermenu(&$count) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  //get menu from db
  global $count;
  $menu=[];
  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_usermenu` ORDER BY `pos` ASC") or die (mysqli_error($mysql));
  $count=0;
  while($result = mysqli_fetch_object($query)) {
    $men=array(
      "id"=>$result->id,
      "pos"=>$result->pos,
      "activ"=>$result->activ,
      "lang_key"=>html_safe($result->lang_key),
      "url"=>html_safe($result->url),
      "lang_key2"=>html_safe($result->lang_key2),
      "url2"=>html_safe($result->url2),
    );
    $count++;
    $menu[]=$men;
  }

  return $menu;
}
function sql_get_modules($aktiv_only=0,&$count) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  global $mysql;
  $select="SELECT * FROM `".$config->db_prefix."_modulconfig`";
  if($aktiv_only===1) $select.=" WHERE `activ`=1";
  $select.=" ORDER BY `name` ASC";

  $query = mysqli_query($sqlcon, $select) or die (mysqli_error());
  $modules=[];
  
  while($result = mysqli_fetch_object($query)) {
    $modul=array(
      "id"=>$result->id,
      "menuname"=>html_safe($result->menuname),
      "name"=>html_safe($result->name),
      "index"=>$result->index,
      "activ"=>$result->activ,
      "admin_page_exists"=>file_exists("include/modules/modul_".$result->name.".php")?1:0,
      "tpl_exists"=>file_exists("templates/modul_".$result->name.".tpl")?1:0,
      "index_exists"=>file_exists($result->index.".php")?1:0
      
    );
    
    $count++;
    $modules[]=$modul;
  }
  return $modules;
}
function sql_get_ban_details($bid) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);

  $query = mysqli_query($sqlcon, "SELECT ba.*, se.gametype,se.timezone_fixx, aa.nickname,aa.username FROM `".$config->db_prefix."_bans` AS ba
    LEFT JOIN `".$config->db_prefix."_serverinfo` AS se ON ba.server_ip=se.address
    LEFT JOIN `".$config->db_prefix."_amxadmins` AS aa ON (aa.steamid=ba.admin_nick OR aa.steamid=ba.admin_ip OR aa.steamid=ba.admin_id)
    WHERE ba.bid=".$bid." LIMIT 1") or die (mysqli_error($mysql));

  $ban_row=[];

  while($result = mysqli_fetch_object($query)) {
    $ban_row=array(
      "bid"       => $result->bid,
      "player_ip"     => $result->player_ip,
      "player_id"     => $result->player_id,
      "player_comid"    => $steamcomid,
    //  "player_nick"     => iconv('UTF-8', 'Windows-1251', $result->player_nick),
      "player_nick"     => $result->player_nick,
      "admin_ip"     => $result->admin_ip,
      "admin_id"     => $result->admin_id,
    //  "admin_nick"     => iconv('UTF-8', 'Windows-1251', $result->admin_nick),
      "admin_nick"     => $result->admin_nick,
      "ban_type"     => $result->ban_type,
      "ban_reason"     => $result->ban_reason,
      "ban_created"     => $result->ban_created,
      "ban_length"     => $result->ban_length,
      "ban_end"    => ($result->ban_created + ($result->ban_length * 60)),
      "server_ip"     => $result->server_ip,
      "server_name"     => $result->server_name,
      "bancount"    => $bancount
    );
  };

  $ban_row["ban_created"]=($ban_row["ban_created"] + ($ban_row["timezone_fixx"] * 60 * 60));
  $ban_row["ban_end"]=($ban_row["ban_created"] + ($ban_row["ban_length"] * 60));

  return htmlsafe_recursive ( $ban_row );
}

function sql_get_ban_details_activ ( $nickname, &$count, $bid ) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);

  $query = mysqli_query($sqlcon, "SELECT ba.*,se.timezone_fixx FROM `".$config->db_prefix."_bans` AS ba
    LEFT JOIN `".$config->db_prefix."_serverinfo` AS se ON ba.server_ip=se.address
    WHERE `player_nick`='".$nickname."' AND `expired`=0 AND `bid`<>".$bid) or die (mysqli_error($mysql));

  $ban_rows=[];

  while($ban_row=mysqli_fetch_assoc($query)) {
    $count++;

    $ban_row["ban_created"]=($ban_row["ban_created"] + ($ban_row["timezone_fixx"] * 60 * 60));
    $ban_row["ban_end"]=($ban_row["ban_created"] + ($ban_row["ban_length"] * 60));
    $ban_rows[]=$ban_row;
  }

  return $ban_rows;
}

function sql_get_ban_details_exp ( $nickname, &$count, $bid ) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $query = mysqli_query($sqlcon, "SELECT ba.*,se.timezone_fixx FROM `".$config->db_prefix."_bans` AS ba
    LEFT JOIN `".$config->db_prefix."_serverinfo` AS se ON ba.server_ip=se.address
    WHERE `player_nick`='".$nickname."' AND `expired`=1 AND `bid`<>".$bid) or die (mysqli_error());
  $ban_rows=[];

  while($ban_row=mysqli_fetch_assoc($query)) {
    $count++;

    $ban_row["ban_created"]=($ban_row["ban_created"] + ($ban_row["timezone_fixx"] * 60 * 60));
    $ban_row["ban_end"]=($ban_row["ban_created"] + ($ban_row["ban_length"] * 60));
    $ban_rows[]=$ban_row;
  }

  return $ban_rows;
}

function sql_get_ban_details_motd_exp ( $steamid, &$count ) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);

  $query = mysqli_query($sqlcon, "SELECT ba.*,se.timezone_fixx FROM `".$config->db_prefix."_bans` AS ba
    LEFT JOIN `".$config->db_prefix."_serverinfo` AS se ON ba.server_ip=se.address
    WHERE `player_nick`='".$nickname."' AND `expired`=1 ORDER BY ban_created DESC") or die (mysqli_error());

  $ban_rows=[];

  while($ban_row=mysqli_fetch_assoc($query)) {
    $ban_row["ban_created"]=($ban_row["ban_created"] + ($ban_row["timezone_fixx"] * 60 * 60));
    $ban_row["ban_end"]=($ban_row["ban_created"] + ($ban_row["ban_length"] * 60));

    if($ban_row["server_ip"]!="website") {
      if($show_admin==0 && $ban_row["nickname"]!="") {
        $ban_row["admin_nick"]=$ban_row["nickname"];
      }
    }

    $ban_row["player_nick"]=html_safe($ban_row["player_nick"]);
    $ban_row["admin_nick"]=html_safe($ban_row["admin_nick"]);
    $ban_row["server_name"]=html_safe($ban_row["server_name"]);

    $ban_rows[]=$ban_row;
    $count++;
  }

  return $ban_rows;
}

function sql_get_comments_count($bid) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $query = mysqli_query($sqlcon, "SELECT COUNT(*) FROM `".$config->db_prefix."_comments`".(($bid) ?" WHERE `bid`=".$bid : "")) or die (mysqli_error());
  return mysqli_data_seek($query,0);
}
function sql_get_comments_count_fail($repair=0) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $repaired=0;
  //first search in the db for files without a ban
  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_comments` WHERE `bid` NOT IN (SELECT DISTINCT `bid` FROM `".$config->db_prefix."_bans`)") or die (mysqli_error());

  if(!$repair) return mysqli_num_rows($query);

  while($result = mysqli_fetch_object($query)) {
    //delete db entry
    $query2 = mysqli_query($sqlcon, "DELETE FROM `".$config->db_prefix."_comments` WHERE `id`=".$result->id." LIMIT 1") or die (mysqli_error());
    $repaired++;
  }
  return $repaired;
}
function sql_get_comments($bid,&$count) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_comments` WHERE `bid`=".$bid." ORDER BY `date` ASC") or die (mysqli_error());
  //Array aufbereiten
  $comments=[];
  while($result = mysqli_fetch_object($query)) {
    $comment=array(
      "id"=>$result->id,
      "name"=>html_safe($result->name),
      "comment"=>html_safe($result->comment),
      "email"=>html_safe($result->email),
      "addr"=>$result->addr,
      "date"=>$result->date
    );
    $count++;
    $comments[]=$comment;
  }
  return $comments;
}
function sql_get_files_count($bid) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $query = mysqli_query($sqlcon, "SELECT COUNT(*) FROM `".$config->db_prefix."_files`".(($bid) ?" WHERE `bid`=".$bid : "")) or die (mysqli_error());
  return mysqli_data_seek($query,0);
}
function sql_get_files_count_fail($repair=0) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $fail=0;
  $repaired=0;
  //first search in the db for files without a ban
  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_files` WHERE `bid` NOT IN (SELECT DISTINCT `bid` FROM `".$config->db_prefix."_bans`)") or die (mysqli_error());
  $fail=mysqli_num_rows($query);
  //search files without db entry
  $d=opendir($config->path_root."/include/files/");
  while($f=readdir($d)) {
    if($f=="." || $f==".." || is_dir($config->path_root."/include/files/".$f) || $f=="index.php") continue;
    if(is_file($config->path_root."/include/files/".$f)) {
      $f=str_replace("_thumb","",$f);
      $query2 = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_files` WHERE `demo_file`='".$f."'") or die (mysqli_error());
      if(!mysqli_num_rows($query2)) {
        $files[]=$f;
        $fail++;
      }
    }
  }
  closedir($d);
  if(!$repair) return $fail;
  //delete fails from db
  while($result = mysqli_fetch_object($query)) {
    //delete files
    if(file_exists("include/files/".$result->demo_file)) unlink("include/files/".$result->demo_file);
    if(file_exists("include/files/".$result->demo_file."_thumb")) unlink("include/files/".$result->demo_file."_thumb");
    //delete db entry
    $query3 = mysqli_query($sqlcon, "DELETE FROM `".$config->db_prefix."_files` WHERE `id`=".$result->id." LIMIT 1") or die (mysqli_error());
    $repaired++;
  }
  //delete files from dir without db entry
  for($i=0;$i<sizeof($files);$i++) {
    if(file_exists("include/files/".$files[$i])) unlink("include/files/".$files[$i]);
    if(file_exists("include/files/".$files[$i]."_thumb")) unlink("include/files/".$files[$i]."_thumb");
    $repaired++;
  }
  return $repaired;
}
function sql_get_files($bid,&$count) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_files` WHERE `bid`=".$bid." ORDER BY `upload_time` ASC") or die (mysqli_error());
  //Array aufbereiten
  $files=[];
  while($result = mysqli_fetch_object($query)) {
    if(file_exists("include/files/".$result->demo_file."_thumb")) $thumb=1;
    $file=[
      "id"=>$result->id,
      "demo_file"=>$result->demo_file,
      "demo_real"=>html_safe($result->demo_real),
      "comment"=>html_safe($result->comment),
      "upload_time"=>$result->upload_time,
      "down_count"=>$result->down_count,
      "name"=>html_safe($result->name),
      "email"=>html_safe($result->email),
      "file_size"=>$result->file_size,
      "addr"=>$result->addr,
      "thumb"=>$thumb
    ];
    $count++;
    $files[]=$file;
  }
  return $files;
}
function sql_get_search_amxadmins(&$amxadmins,&$nickadmins) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $query = mysqli_query($sqlcon, "SELECT `admin_id`,`admin_nick` FROM `".$config->db_prefix."_bans` GROUP BY `admin_nick` ORDER BY `admin_nick`") or die (mysqli_error());
  //Array aufbereiten
  while($result = mysqli_fetch_object($query)) {
    if($result->admin_id <> "")  $nickadmins[]=array("steam"=>$result->admin_id,"nick"=>html_safe($result->admin_nick));
  }

  $query = mysqli_query($sqlcon, "SELECT ba.admin_id,ba.admin_nick,aa.nickname,aa.steamid FROM ".$config->db_prefix."_bans as ba,".$config->db_prefix."_amxadmins as aa WHERE ba.admin_id=aa.steamid GROUP BY `admin_id`") or die (mysqli_error($mysql));
  //Array aufbereiten
  while($result = mysqli_fetch_object($query)) {
    if($result->admin_id <> "")  $amxadmins[]=array("steam"=>$result->admin_id,"nick"=>html_safe($result->nickname));
  }


}
function sql_get_search_servers() {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $query = mysqli_query($sqlcon, "SELECT `server_ip`,`server_name` FROM `".$config->db_prefix."_bans` GROUP BY `server_ip` ORDER BY `server_name`") or die (mysqil_error());
  //Array aufbereiten
  while($result = mysqli_fetch_object($query)) {
    if($result->server_name=="website") {
      $servers["website"]="_WEB";
    } else {
      $servers[$result->server_ip]=htmlsafe_recursive($result->server_name);
    }
  }
  return $servers;
}
function sql_get_search_reasons() {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $query = mysqli_query($sqlcon, "SELECT DISTINCT `ban_reason` FROM `".$config->db_prefix."_bans` ORDER BY `ban_reason`") or die (mysqli_error($mysql));
  //Array aufbereiten
  while($result = mysqli_fetch_object($query)) {
    $reasons[$result->ban_reason]=html_safe($result->ban_reason);
  }
  return $reasons;
}
function sql_get_search_bans ( $search, $active=1, &$count=0 ) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);

  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_bans` WHERE ".$search." AND `expired`=".(($active==1)?0:1)." ORDER BY `ban_created` DESC") or die (mysqli_error());

  while ( $result = mysqli_fetch_object ( $query ) ) {
    if ( !empty ( $result->player_id ) ) {
      $steamid  = htmlentities($result->player_id, ENT_QUOTES);
      $steamcomid  = GetFriendId($steamid);
      $query2    = mysqli_query($sqlcon, "SELECT COUNT(*) FROM `".$config->db_prefix."_bans` WHERE `player_nick`='".$nickname."' AND `expired`=1");
      $bancount  = mysqli_data_seek( $query2, 0 );
    }
    $ban_row=[
      "bid"       => $result->bid,
      "player_ip"     => $result->player_ip,
      "player_id"     => $result->player_id,
      "player_comid"    => $steamcomid,
      "player_nick"     => $result->player_nick,
      "admin_ip"     => $result->admin_ip,
      "admin_id"     => $result->admin_id,
      "admin_nick"     => $result->admin_nick,
      "ban_type"     => $result->ban_type,
      "ban_reason"     => $result->ban_reason,
      "ban_created"     => $result->ban_created,
      "ban_length"     => $result->ban_length,
      "ban_end"    => ($result->ban_created + ($result->ban_length * 60)),
      "server_ip"     => $result->server_ip,
      "server_name"     => $result->server_name,
      "bancount"    => $bancount
    ];
    if($config->show_kick_count=="1") {
      $ban_row["kick_count"]=$result->ban_kicks;
      $ban_page["show_kicks"]=1;
    }
    if($config->show_demo_count=="1") {
      $file_count=0;
      sql_get_files($result->bid,$file_count);
      $ban_row["demo_count"]=$file_count;
      $ban_page["show_demos"]=1;
    }
    if($config->show_comment_count=="1") {
      $comment_count=0;
      sql_get_comments($result->bid,$comment_count);
      $ban_row["comment_count"]=$comment_count;
      $ban_page["show_comments"]=1;
    }
    $count++;
    $ban_list[]=$ban_row;
  }
  return $ban_list;
  htmlsafe_recursive($ban_list);
}
function sql_get_bans_count($activ_only=1) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  $query = mysqli_query($sqlcon, "SELECT COUNT(*) FROM `".$config->db_prefix."_bans`".(($activ_only) ?" WHERE `expired`=0":"")) or die (mysqli_error($mysql));
  return mysqli_data_seek($query,0);
}
function sql_get_logs($filter) {
  global $config;
  $sqlcon = mysqli_connect($config->db_host,$config->db_user,$config->db_pass, $config->db_db);
  if($filter) $where="WHERE ".$filter;
  $query = mysqli_query($sqlcon, "SELECT * FROM `".$config->db_prefix."_logs` ".$where." ORDER BY `timestamp` DESC LIMIT 100") or die (mysqli_error($mysql));
  //Array aufbereiten
  $rows=[];
  while($row=mysqli_fetch_assoc($query)) {
    $rows[]=$row;
  }
  array_walk_recursive($rows,"escape_array");
  return $rows;
}
function escape_array($value,$key) {
  $value=html_safe($value);
}
?>