<?php
$config->document_root = "/";
$config->path_root = getcwd();
  
function getPDO() {
  global $config;
  static $pdo = null;
  if ($pdo === null) {
      try {
          $pdo = new PDO("mysql:host=" . $config->db_host . ";dbname=" . $config->db_db . ";charset=utf8mb4", $config->db_user, $config->db_pass);
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
          $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      } catch (PDOException $e) {
          die("Database connection failed: " . $e->getMessage());
      }
  }
  return $pdo;
}

function sql_set_websettings(): array {
  global $config;
  $pdo = getPDO();
  $stmt = $pdo->query("SELECT * FROM `" . $config->db_prefix . "_webconfig`");
  $result = $stmt->fetch();
  
  $config->cookie = $result['cookie'];
  $config->bans_per_page = ($result['bans_per_page'] < 1) ? 1 : $result['bans_per_page'];
  $config->design = $result['design'];
  $config->banner = $result['banner'];
  $config->banner_url = $result['banner_url'];
  $config->default_lang = $result['default_lang'];
  $config->start_page = $result['start_page'];
  $config->show_kick_count = $result['show_kick_count'];
  $config->show_comment_count = $result['show_comment_count'];
  $config->show_demo_count = $result['show_demo_count'];
  $config->demo_all = $result['demo_all'];
  $config->max_file_size = $result['max_file_size'];
  $config->file_type = $result['file_type'];
  $config->comment_all = $result['comment_all'];
  $config->use_capture = $result['use_capture'];
  $config->auto_prune = $result['auto_prune'];
  $config->max_offences = $result['max_offences'];
  $config->max_offences_reason = $result['max_offences_reason'];
  $config->use_demo = $result['use_demo'];
  $config->use_comment = $result['use_comment'];
  
  return [
      "cookie" => trim($config->cookie),
      "design" => $config->design,
      "bans_per_page" => (int)$config->bans_per_page,
      "banner" => $config->banner,
      "banner_url" => $config->banner_url,
      "default_lang" => $config->default_lang,
      "start_page" => $config->start_page,
      "show_kick_count" => $config->show_kick_count,
      "show_demo_count" => $config->show_demo_count,
      "show_comment_count" => $config->show_comment_count,
      "demo_all" => $config->demo_all,
      "comment_all" => $config->comment_all,
      "use_capture" => $config->use_capture,
      "max_file_size" => (int)$config->max_file_size,
      "file_type" => trim($config->file_type),
      "auto_prune" => (int)$config->auto_prune,
      "max_offences" => (int)$config->max_offences,
      "max_offences_reason" => $config->max_offences_reason,
      "use_demo" => (int)$config->use_demo,
      "use_comment" => $config->use_comment
  ];
}

function sql_get_server($serverid = 0) {
  global $config;
  $pdo = getPDO();
  
  if ($serverid) {
      $stmt = $pdo->prepare("SELECT * FROM `" . $config->db_prefix . "_serverinfo` WHERE `id` = :serverid LIMIT 1");
      $stmt->execute(['serverid' => $serverid]);
      return $stmt->fetch();
  } else {
      $stmt = $pdo->query("SELECT * FROM `" . $config->db_prefix . "_serverinfo` ORDER BY `address` ASC");
      return $stmt->fetchAll();
  }
}

function sql_get_server_ids(): array {
  global $config;
  $pdo = getPDO();
  $stmt = $pdo->query("SELECT `id` FROM `" . $config->db_prefix . "_serverinfo` ORDER BY `address` ASC");
  return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function sql_get_reasons_set(): array {
  global $config;
  $pdo = getPDO();
  $stmt = $pdo->query("SELECT * FROM `" . $config->db_prefix . "_reasons_set` ORDER BY `setname` ASC");
  $reasons_set = [];
  while ($result = $stmt->fetch()) {
      $reason_set = [
          "id" => $result['id'],
          "setname" => html_safe($result['setname'])
      ];
      $stmt2 = $pdo->prepare("SELECT * FROM `" . $config->db_prefix . "_reasons_to_set` WHERE `setid` = :setid");
      $stmt2->execute(['setid' => $result['id']]);
      $reasons = [];
      while ($result2 = $stmt2->fetch()) {
          $reasons[] = $result2['reasonid'];
      }
      $reason_set["reasonids"] = implode(",", $reasons);
      $reasons_set[] = $reason_set;
  }
  return $reasons_set;
}

function sql_get_reasons(): array {
  global $config;
  $pdo = getPDO();

  $query = "SELECT * FROM `" . $config->db_prefix . "_reasons` ORDER BY `id` ASC";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $reasons = [];

  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $reason = [
          "id" => $result['id'],
          "reason" => html_safe($result['reason']),
          "static_bantime" => $result['static_bantime']
      ];
      $reasons[] = $reason;
  }

  return $reasons;
}

function sql_get_reasons_list(): array {
  global $config;
  $pdo = getPDO();

  $query = "SELECT reason FROM `" . $config->db_prefix . "_reasons` ORDER BY `id` ASC";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $reasons = [];

  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $reasons[] = $result['reason'];
  }

  return $reasons;
}

function sql_get_amxadmins(): array {
  global $config;
  $pdo = getPDO();

  $query = "SELECT * FROM `" . $config->db_prefix . "_amxadmins` ORDER BY `access` ASC";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $admins = [];

  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $admin = [
          "aid" => $result['id'],
          "username" => html_safe($result['username']),
          "password" => $result['password'],
          "access" => $result['access'],
          "flags" => $result['flags'],
          "steamid" => $result['steamid'],
          "nickname" => html_safe($result['nickname']),
          "icq" => $result['icq'],
          "ashow" => $result['ashow'],
          "created" => $result['created'],
          "expired" => $result['expired'],
          "days" => $result['days']
      ];
      $admins[] = $admin;
  }

  return $admins;
}

function sql_get_amxadmins_list(): array {
  global $config;
  $pdo = getPDO();

  $query = "SELECT * FROM `" . $config->db_prefix . "_amxadmins` 
            WHERE `ashow` = 1 AND (`expired` = 0 OR `expired` > UNIX_TIMESTAMP()) 
            ORDER BY `access` ASC";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $admins = [];

  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $steamcomid = '';
      if (!empty($result['steamid'])) {
          $steamid = htmlentities($result['steamid'], ENT_QUOTES, 'UTF-8');
          $steamcomid = GetFriendId($steamid);
      }

      $admin = [
          "aid" => $result['id'],
          "username" => html_safe($result['username']),
          "comid" => $steamcomid,
          "password" => $result['password'],
          "access" => $result['access'],
          "flags" => $result['flags'],
          "steamid" => $result['steamid'],
          "nickname" => html_safe($result['nickname']),
          "icq" => $result['icq'],
          "ashow" => $result['ashow'],
          "created" => $result['created'],
          "expired" => $result['expired'],
          "days" => $result['days']
      ];
      $admins[] = $admin;
  }

  return $admins;
}

function sql_get_amxadmins_server($server): array {
  global $config;
  $pdo = getPDO();

  $query = "SELECT a.*, as.custom_flags, as.use_static_bantime
            FROM `" . $config->db_prefix . "_amxadmins` a
            LEFT JOIN `" . $config->db_prefix . "_admins_servers` as ON a.id = as.admin_id AND as.server_id = :server
            ORDER BY a.access ASC";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute(['server' => $server]);

  $admins = [];

  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $admin = [
          "sid" => $server,
          "aid" => $result['id'],
          "username" => html_safe($result['username']),
          "password" => $result['password'],
          "access" => $result['access'],
          "flags" => $result['flags'],
          "steamid" => $result['steamid'],
          "nickname" => html_safe($result['nickname']),
          "icq" => $result['icq'],
          "ashow" => $result['ashow'],
          "custom_flags" => $result['custom_flags'] ?? '',
          "use_static_bantime" => $result['use_static_bantime'] ?? 'no',
          "aktiv" => isset($result['custom_flags']) ? 1 : 0
      ];
      $admins[] = $admin;
  }

  return $admins;
}

function sql_get_servers_admins(): array {
  global $config;
  $pdo = getPDO();

  $ReworkedAdmins = [];
  $ReworkedServers = [];
  $ReworkedAssigned = [];

  // Get admins
  $stmt = $pdo->query("SELECT * FROM `".$config->db_prefix."_amxadmins`");
  while ($admin = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $ReworkedAdmins[$admin['id']] = $admin;
  }

  // Get servers
  $stmt = $pdo->query("SELECT * FROM `".$config->db_prefix."_serverinfo`");
  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $ReworkedServers[$result['id']] = [
          "id" => $result['id'],
          "hostname" => $result['hostname'],
          "gametype" => $result['gametype']
      ];
  }

  // Get assigned admins
  $stmt = $pdo->query("SELECT * FROM `".$config->db_prefix."_admins_servers`");
  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $ReworkedAssigned[$result['server_id']][$result['admin_id']] = $result; 
  }

  // Combine the data
  $servers_admins = [
      'admins' => $ReworkedAdmins,
      'servers' => $ReworkedServers,
      'assigned' => $ReworkedAssigned
  ];

  return $servers_admins;
}

function sql_get_webadmins(): array {
  global $config;
  $pdo = getPDO();

  $query = "SELECT id, username, level, last_action, email 
            FROM `" . $config->db_prefix . "_webadmins` 
            ORDER BY `level`, `username`";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $users = [];

  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $user = [
          "uid" => $result['id'],
          "name" => html_safe($result['username']),
          "level" => $result['level'],
          "last_action" => $result['last_action'],
          "email" => html_safe($result['email'])
      ];
      $users[] = $user;
  }
  
  return $users;
}

function sql_get_server_admins($server): array {
  global $config;
  $pdo = getPDO();

  $query = "SELECT s.admin_id, s.custom_flags, s.use_static_bantime, a.username 
            FROM " . $config->db_prefix . "_admins_servers AS s
            JOIN " . $config->db_prefix . "_amxadmins AS a ON s.admin_id = a.id
            WHERE s.server_id = :server";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute(['server' => $server]);

  $admins = [];

  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $admin = [
          "admin_id" => $result['admin_id'],
          "custom_flags" => $result['custom_flags'],
          "use_static_bantime" => $result['use_static_bantime'], // Note: Changed from static_bantime to use_static_bantime
          "username" => html_safe($result['username'])
      ];
      $admins[] = $admin;
  }

  return $admins;
}

function sql_get_usermenu(&$count): array {
  global $config;
  $pdo = getPDO();

  $query = "SELECT * FROM `" . $config->db_prefix . "_usermenu` ORDER BY `pos` ASC";
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $menu = [];
  $count = 0;

  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $menuItem = [
          "id" => $result['id'],
          "pos" => $result['pos'],
          "activ" => $result['activ'],
          "lang_key" => html_safe($result['lang_key']),
          "url" => html_safe($result['url']),
          "lang_key2" => html_safe($result['lang_key2']),
          "url2" => html_safe($result['url2']),
      ];
      $count++;
      $menu[] = $menuItem;
  }

  return $menu;
}

function sql_get_modules($active_only = 0, &$count): array {
  global $config;
  $pdo = getPDO();

  $query = "SELECT * FROM `" . $config->db_prefix . "_modulconfig`";
  $params = [];

  if ($active_only === 1) {
      $query .= " WHERE `activ` = :active";
      $params[':active'] = 1;
  }

  $query .= " ORDER BY `name` ASC";

  $stmt = $pdo->prepare($query);
  $stmt->execute($params);

  $modules = [];
  $count = 0;
  
  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $modul = [
          "id" => $result['id'],
          "menuname" => html_safe($result['menuname']),
          "name" => html_safe($result['name']),
          "index" => $result['index'],
          "activ" => $result['activ'],
          "admin_page_exists" => file_exists("include/modules/modul_" . $result['name'] . ".php") ? 1 : 0,
          "tpl_exists" => file_exists("templates/modul_" . $result['name'] . ".tpl") ? 1 : 0,
          "index_exists" => file_exists($result['index'] . ".php") ? 1 : 0
      ];
      
      $count++;
      $modules[] = $modul;
  }

  return $modules;
}

function sql_get_ban_details($bid): array {
  global $config;
  $pdo = getPDO();

  $query = "SELECT ba.*, se.gametype, se.timezone_fixx, aa.nickname, aa.username 
            FROM `".$config->db_prefix."_bans` AS ba
            LEFT JOIN `".$config->db_prefix."_serverinfo` AS se ON ba.server_ip = se.address
            LEFT JOIN `".$config->db_prefix."_amxadmins` AS aa ON (aa.steamid = ba.admin_nick OR aa.steamid = ba.admin_ip OR aa.steamid = ba.admin_id)
            WHERE ba.bid = :bid LIMIT 1";

  $stmt = $pdo->prepare($query);
  $stmt->execute(['bid' => $bid]);

  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result) {
      // Get steamcomid if player_id exists
      $steamcomid = '';
      if (!empty($result['player_id'])) {
          $steamcomid = GetFriendId($result['player_id']);
      }

      $ban_row = [
          "bid"           => $result['bid'],
          "player_ip"     => $result['player_ip'],
          "player_id"     => $result['player_id'],
          "player_comid"  => $steamcomid,
          "player_nick"   => $result['player_nick'],
          "admin_ip"      => $result['admin_ip'],
          "admin_id"      => $result['admin_id'],
          "admin_nick"    => $result['admin_nick'],
          "ban_type"      => $result['ban_type'],
          "ban_reason"    => $result['ban_reason'],
          "ban_created"   => $result['ban_created'],
          "ban_length"    => $result['ban_length'],
          "server_ip"     => $result['server_ip'],
          "server_name"   => $result['server_name'],
          "timezone_fixx" => $result['timezone_fixx']
      ];

      // Calculate ban_created and ban_end
      $ban_row["ban_created"] = ($ban_row["ban_created"] + ($ban_row["timezone_fixx"] * 60 * 60));
      $ban_row["ban_end"] = ($ban_row["ban_created"] + ($ban_row["ban_length"] * 60));

      // Get bancount (if needed)
      $stmt = $pdo->prepare("SELECT COUNT(*) FROM `".$config->db_prefix."_bans` WHERE player_id = :player_id AND expired = 1");
      $stmt->execute(['player_id' => $ban_row['player_id']]);
      $ban_row["bancount"] = $stmt->fetchColumn();

      return htmlsafe_recursive($ban_row);
  }

  return null; // Return null if no ban found
}

function sql_get_ban_details_activ(string $nickname, int &$count, int $bid): array {
  global $config;
  $pdo = getPDO();
  
  $query = "SELECT ba.*, se.timezone_fixx 
            FROM `{$config->db_prefix}_bans` AS ba
            LEFT JOIN `{$config->db_prefix}_serverinfo` AS se ON ba.server_ip = se.address
            WHERE `player_nick` = :nickname AND `expired` = 0 AND `bid` != :bid";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute(['nickname' => $nickname, 'bid' => $bid]);

  $ban_rows = [];
  $count = 0;

  while ($ban_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $count++;
      $ban_row['ban_created'] += ($ban_row['timezone_fixx'] * 3600);
      $ban_row['ban_end'] = $ban_row['ban_created'] + ($ban_row['ban_length'] * 60);
      $ban_rows[] = $ban_row;
  }

  return $ban_rows;
}

function sql_get_ban_details_exp(string $nickname, int &$count, int $bid): array {
  global $config;
  $pdo = getPDO();
  
  $query = "SELECT ba.*, se.timezone_fixx 
            FROM `{$config->db_prefix}_bans` AS ba
            LEFT JOIN `{$config->db_prefix}_serverinfo` AS se ON ba.server_ip = se.address
            WHERE `player_nick` = :nickname AND `expired` = 1 AND `bid` != :bid";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute(['nickname' => $nickname, 'bid' => $bid]);

  $ban_rows = [];
  $count = 0;

  while ($ban_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $count++;
      $ban_row['ban_created'] += ($ban_row['timezone_fixx'] * 3600);
      $ban_row['ban_end'] = $ban_row['ban_created'] + ($ban_row['ban_length'] * 60);
      $ban_rows[] = $ban_row;
  }

  return $ban_rows;
}

function sql_get_ban_details_motd_exp(string $steamid, int &$count): array {
  global $config;
  $pdo = getPDO();
  
  $query = "SELECT ba.*, se.timezone_fixx 
            FROM `{$config->db_prefix}_bans` AS ba
            LEFT JOIN `{$config->db_prefix}_serverinfo` AS se ON ba.server_ip = se.address
            WHERE `player_id` = :steamid AND `expired` = 1 
            ORDER BY ban_created DESC";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute(['steamid' => $steamid]);

  $ban_rows = [];
  $count = 0;

  while ($ban_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $ban_row['ban_created'] += ($ban_row['timezone_fixx'] * 3600);
      $ban_row['ban_end'] = $ban_row['ban_created'] + ($ban_row['ban_length'] * 60);

      if ($ban_row['server_ip'] !== "website") {
          if (($show_admin ?? 0) == 0 && $ban_row['nickname'] !== "") {
              $ban_row['admin_nick'] = $ban_row['nickname'];
          }
      }

      $ban_row['player_nick'] = html_safe($ban_row['player_nick']);
      $ban_row['admin_nick'] = html_safe($ban_row['admin_nick']);
      $ban_row['server_name'] = html_safe($ban_row['server_name']);

      $ban_rows[] = $ban_row;
      $count++;
  }

  return $ban_rows;
}

function sql_get_comments_count(?int $bid = null): int {
  global $config;
  $pdo = getPDO();
  
  $query = "SELECT COUNT(*) FROM `{$config->db_prefix}_comments`";
  $params = [];
  
  if ($bid !== null) {
      $query .= " WHERE `bid` = :bid";
      $params['bid'] = $bid;
  }
  
  $stmt = $pdo->prepare($query);
  $stmt->execute($params);
  
  return (int) $stmt->fetchColumn();
}

function sql_get_comments_count_fail(bool $repair = false): int {
  global $config;
  $pdo = getPDO();
  
  $query = "SELECT * FROM `{$config->db_prefix}_comments` 
            WHERE `bid` NOT IN (SELECT DISTINCT `bid` FROM `{$config->db_prefix}_bans`)";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute();
  
  if (!$repair) {
      return $stmt->rowCount();
  }
  
  $repaired = 0;
  while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
      $deleteQuery = "DELETE FROM `{$config->db_prefix}_comments` WHERE `id` = :id LIMIT 1";
      $deleteStmt = $pdo->prepare($deleteQuery);
      $deleteStmt->execute(['id' => $result->id]);
      $repaired++;
  }
  
  return $repaired;
}

function sql_get_comments(int $bid, int &$count): array {
  global $config;
  $pdo = getPDO();
  
  $query = "SELECT * FROM `{$config->db_prefix}_comments` 
            WHERE `bid` = :bid ORDER BY `date` ASC";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute(['bid' => $bid]);
  
  $comments = [];
  $count = 0;
  
  while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
      $comment = [
          "id" => $result->id,
          "name" => html_safe($result->name),
          "comment" => html_safe($result->comment),
          "email" => html_safe($result->email),
          "addr" => $result->addr,
          "date" => $result->date
      ];
      $count++;
      $comments[] = $comment;
  }
  
  return $comments;
}

function sql_get_files_count(?int $bid = null): int {
  global $config;
  $pdo = getPDO();
  
  $query = "SELECT COUNT(*) FROM `{$config->db_prefix}_files`";
  $params = [];
  
  if ($bid !== null) {
      $query .= " WHERE `bid` = :bid";
      $params['bid'] = $bid;
  }
  
  $stmt = $pdo->prepare($query);
  $stmt->execute($params);
  
  return (int) $stmt->fetchColumn();
}

function sql_get_files_count_fail(bool $repair = false): int {
  global $config;
  $pdo = getPDO();
  
  $fail = 0;
  $repaired = 0;
  
  // First search in the db for files without a ban
  $query = "SELECT * FROM `{$config->db_prefix}_files` 
            WHERE `bid` NOT IN (SELECT DISTINCT `bid` FROM `{$config->db_prefix}_bans`)";
  $stmt = $pdo->prepare($query);
  $stmt->execute();
  $fail = $stmt->rowCount();
  
  // Search files without db entry
  $files = [];
  $dirPath = $config->path_root . "/include/files/";
  $dirIterator = new DirectoryIterator($dirPath);
  foreach ($dirIterator as $fileInfo) {
      if ($fileInfo->isDot() || $fileInfo->isDir() || $fileInfo->getFilename() === "index.php") {
          continue;
      }
      $fileName = str_replace("_thumb", "", $fileInfo->getFilename());
      $stmt = $pdo->prepare("SELECT COUNT(*) FROM `{$config->db_prefix}_files` WHERE `demo_file` = :filename");
      $stmt->execute(['filename' => $fileName]);
      if ($stmt->fetchColumn() == 0) {
          $files[] = $fileName;
          $fail++;
      }
  }
  
  if (!$repair) {
      return $fail;
  }
  
  // Delete fails from db
  $stmt = $pdo->prepare($query);
  $stmt->execute();
  while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
      // Delete files
      @unlink($dirPath . $result->demo_file);
      @unlink($dirPath . $result->demo_file . "_thumb");
      // Delete db entry
      $deleteStmt = $pdo->prepare("DELETE FROM `{$config->db_prefix}_files` WHERE `id` = :id LIMIT 1");
      $deleteStmt->execute(['id' => $result->id]);
      $repaired++;
  }
  
  // Delete files from dir without db entry
  foreach ($files as $file) {
      @unlink($dirPath . $file);
      @unlink($dirPath . $file . "_thumb");
      $repaired++;
  }
  
  return $repaired;
}

function sql_get_files(int $bid, int &$count): array {
  global $config;
  $pdo = getPDO();
  
  $query = "SELECT * FROM `{$config->db_prefix}_files` 
            WHERE `bid` = :bid ORDER BY `upload_time` ASC";
  
  $stmt = $pdo->prepare($query);
  $stmt->execute(['bid' => $bid]);
  
  $files = [];
  $count = 0;
  
  while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
      $thumb = file_exists("include/files/{$result->demo_file}_thumb") ? 1 : 0;
      $file = [
          "id" => $result->id,
          "demo_file" => $result->demo_file,
          "demo_real" => html_safe($result->demo_real),
          "comment" => html_safe($result->comment),
          "upload_time" => $result->upload_time,
          "down_count" => $result->down_count,
          "name" => html_safe($result->name),
          "email" => html_safe($result->email),
          "file_size" => $result->file_size,
          "addr" => $result->addr,
          "thumb" => $thumb
      ];
      $count++;
      $files[] = $file;
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

function sql_get_search_servers(): array {
  global $config;
  $pdo = getPDO();
  
  $query = "SELECT `server_ip`, `server_name` FROM `{$config->db_prefix}_bans` GROUP BY `server_ip` ORDER BY `server_name`";
  $stmt = $pdo->query($query);
  
  $servers = [];
  while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
      if ($result->server_name == "website") {
          $servers["website"] = "_WEB";
      } else {
          $servers[$result->server_ip] = htmlsafe_recursive($result->server_name);
      }
  }
  return $servers;
}

function sql_get_search_reasons(): array {
  global $config;
  $pdo = getPDO();
  
  $query = "SELECT DISTINCT `ban_reason` FROM `{$config->db_prefix}_bans` ORDER BY `ban_reason`";
  $stmt = $pdo->query($query);
  
  $reasons = [];
  while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
      $reasons[$result->ban_reason] = html_safe($result->ban_reason);
  }
  return $reasons;
}

function sql_get_search_bans(string $search, bool $active = true, int &$count = 0): array {
  global $config;
  $pdo = getPDO();

  $query = "SELECT * FROM `{$config->db_prefix}_bans` WHERE {$search} AND `expired` = :expired ORDER BY `ban_created` DESC";
  $stmt = $pdo->prepare($query);
  $stmt->execute(['expired' => $active ? 0 : 1]);

  $ban_list = [];
  while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
      $steamcomid = '';
      $bancount = 0;
      if (!empty($result->player_id)) {
          $steamid = htmlentities($result->player_id, ENT_QUOTES);
          $steamcomid = GetFriendId($steamid);
          $query2 = $pdo->prepare("SELECT COUNT(*) FROM `{$config->db_prefix}_bans` WHERE `player_nick` = :nickname AND `expired` = 1");
          $query2->execute(['nickname' => $result->player_nick]);
          $bancount = $query2->fetchColumn();
      }
      $ban_row = [
          "bid" => $result->bid,
          "player_ip" => $result->player_ip,
          "player_id" => $result->player_id,
          "player_comid" => $steamcomid,
          "player_nick" => $result->player_nick,
          "admin_ip" => $result->admin_ip,
          "admin_id" => $result->admin_id,
          "admin_nick" => $result->admin_nick,
          "ban_type" => $result->ban_type,
          "ban_reason" => $result->ban_reason,
          "ban_created" => $result->ban_created,
          "ban_length" => $result->ban_length,
          "ban_end" => ($result->ban_created + ($result->ban_length * 60)),
          "server_ip" => $result->server_ip,
          "server_name" => $result->server_name,
          "bancount" => $bancount
      ];
      if ($config->show_kick_count == "1") {
          $ban_row["kick_count"] = $result->ban_kicks;
          $ban_page["show_kicks"] = 1;
      }
      if ($config->show_demo_count == "1") {
          $file_count = 0;
          sql_get_files($result->bid, $file_count);
          $ban_row["demo_count"] = $file_count;
          $ban_page["show_demos"] = 1;
      }
      if ($config->show_comment_count == "1") {
          $comment_count = 0;
          sql_get_comments($result->bid, $comment_count);
          $ban_row["comment_count"] = $comment_count;
          $ban_page["show_comments"] = 1;
      }
      $count++;
      $ban_list[] = $ban_row;
  }
  return htmlsafe_recursive($ban_list);
}

function sql_get_bans_count(bool $active_only = true): int {
  global $config;
  $pdo = getPDO();
  
  $query = "SELECT COUNT(*) FROM `{$config->db_prefix}_bans`" . ($active_only ? " WHERE `expired` = 0" : "");
  return (int) $pdo->query($query)->fetchColumn();
}

function sql_get_logs(?string $filter = null): array {
  global $config;
  $pdo = getPDO();
  
  $query = "SELECT * FROM `{$config->db_prefix}_logs`";
  if ($filter) {
      $query .= " WHERE " . $filter;
  }
  $query .= " ORDER BY `timestamp` DESC LIMIT 100";
  
  $stmt = $pdo->query($query);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  array_walk_recursive($rows, "escape_array");
  return $rows;
}

function escape_array($value,$key) {
  $value = html_safe($value);
}
?>