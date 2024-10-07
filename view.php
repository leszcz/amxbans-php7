<?php
session_start();
// Include required files
require_once("include/config.inc.php");
require_once("include/access.inc.php");
require_once("include/menu.inc.php");
require_once("include/functions.inc.php");
require_once("include/rcon_hl_net.inc.php");
require_once("include/sql.inc.php"); // Include your PDO connection

// Prepare the server array and other variables
$server_array = [];
$addons_array = [];
$rules_array = [];
$anticheat_array = [];

// Fetch server information using PDO
$query = "SELECT * FROM {$config->db_prefix}_serverinfo ORDER BY hostname ASC";
$statement = $pdo->prepare($query);
$statement->execute();
$servers = $statement->fetchAll(PDO::FETCH_OBJ);

foreach ($servers as $result2) {
    $split_address = explode(":", $result2->address);
    $ip = $split_address[0];
    $port = $split_address[1];

    if ($ip && $port) {
        $server = new Rcon();
        $ip = gethostbyname($ip);
        $server->Connect($ip, $port, $result2->rcon);

        // Fetch server info
        $infos = $server->Info();
        if ($infos) {
            $players = $server->Players();
            $rules = $server->ServerRules();

            // Copy rules to the rules array for the template
            if (is_array($rules)) {
                foreach ($rules as $k => $v) {
                    $rules_array[] = array("name" => $k, "value" => $v);
                }
            }

            // Check if map picture exists
            $mappic = file_exists("images/maps/{$infos['mod']}/{$infos['map']}.jpg") ? $infos['map'] : "noimage";

            // Create addons array (Anti-cheat systems)
            if (is_array($rules)) {
                if ($infos['secure']) $anticheat_array[] = array("name" => "VAC", "version" => "2", "url" => "");
                if ($rules['sbsrv_version']) $anticheat_array[] = array("name" => "Steambans", "version" => $rules['sbsrv_version'], "url" => "http://www.steambans.com");
                if ($rules['hlg_version']) $anticheat_array[] = array("name" => "HLGuard", "version" => $rules['hlg_version'], "url" => "");
            }

            // Server info
            $server_info = [
                "sid" => $result2->id,
                "type" => $infos['type'],
                "version" => $infos['version'],
                "hostname" => $infos['name'],
                "map" => $infos['map'],
                "mod" => $infos['mod'],
                "game" => $infos['game'],
                "appid" => $infos['appid'],
                "cur_players" => $infos['activeplayers'],
                "max_players" => $infos['maxplayers'],
                "bot_players" => $infos['botplayers'],
                "dedicated" => ($infos['dedicated'] === "d") ? "Dedicated" : "Listen",
                "os" => ($infos['os'] === "l") ? "Linux" : "Windows",
                "password" => $infos['password'],
                "secure" => $infos['secure'],
                "sversion" => $infos['sversion'],
                "timeleft" => $rules['amx_timeleft'] ?? '00:00',
                "maxrounds" => $rules['mp_maxrounds'] ?? 0,
                "timelimit" => $rules['mp_timelimit'] ?? 0,
                "nextmap" => $rules['amx_nextmap'] ?? '',
                "friendlyfire" => $rules['mp_friendlyfire'] ?? '',
                "address" => $result2->address,
                "mappic" => $mappic,
                "players" => []
            ];

            // Get players
            foreach ($players as $player) {
                $server_info['players'][] = [
                    "name" => htmlspecialchars($player['name']),
                    "frag" => $player['frag'],
                    "time" => $player['time'],
                ];
            }

            $server_array[] = $server_info;
        } else {
            // Server not reachable
            $server_array[] = [
                "sid" => $result2->id,
                "type" => "",
                "version" => "",
                "hostname" => htmlspecialchars($result2->hostname),
                "map" => "",
                "mod" => htmlspecialchars($result2->gametype),
                "game" => "",
                "appid" => "",
                "cur_players" => 0,
                "max_players" => 0,
                "bot_players" => 0,
                "dedicated" => "",
                "os" => "",
                "password" => "",
                "secure" => "",
                "sversion" => "",
                "timeleft" => "00:00",
                "maxrounds" => 0,
                "timelimit" => "00",
                "nextmap" => "",
                "friendlyfire" => "",
                "address" => $result2->address,
                "mappic" => "noimage",
                "players" => []
            ];
        }
        // Close the connection
        $server->Disconnect();
    }
}

// Get stats using PDO
$stats = [
    'total' => $pdo->query("SELECT COUNT(bid) FROM {$config->db_prefix}_bans")->fetchColumn(),
    'permanent' => $pdo->query("SELECT COUNT(bid) FROM {$config->db_prefix}_bans WHERE ban_length = 0")->fetchColumn(),
    'active' => $pdo->query("SELECT COUNT(bid) FROM {$config->db_prefix}_bans WHERE ((ban_created + (ban_length * 60)) > " . time() . " OR ban_length = 0)")->fetchColumn(),
    'temp' => 0, // Temporary bans count will be calculated later
    'admins' => $pdo->query("SELECT COUNT(id) FROM {$config->db_prefix}_amxadmins")->fetchColumn(),
    'servers' => $pdo->query("SELECT COUNT(id) FROM {$config->db_prefix}_serverinfo")->fetchColumn(),
];

// Calculate temporary bans
$stats['temp'] = $stats['active'] - $stats['permanent'];

// Fetch latest ban
$query = "SELECT player_id, player_nick, ban_reason, ban_created, ban_length, ban_type FROM {$config->db_prefix}_bans ORDER BY ban_created DESC LIMIT 1";
$statement = $pdo->prepare($query);
$statement->execute();
$latest_ban = $statement->fetch(PDO::FETCH_OBJ);

$last_ban_arr = [];
if ($latest_ban) {
    $ban_length = $latest_ban->ban_length == 0 ? 0 : ($latest_ban->ban_created + ($latest_ban->ban_length * 60));
    $steamid = $latest_ban->ban_type == "SI" ? "SI" : $latest_ban->player_id;

    $last_ban_arr = [
        "steamid" => $steamid,
        "nickname" => htmlspecialchars(_substr($latest_ban->player_nick, 15)),
        "reason" => htmlspecialchars(_substr($latest_ban->ban_reason, 15)),
        "created" => $latest_ban->ban_created,
        "length" => $ban_length,
        "time" => time(),
    ];
}

// Parsing the template
$smarty = new dynamicPage;
$smarty->setTemplateDir($config->templatedir);
$smarty->assign("meta", "");
$smarty->assign("title", "_TITLEVIEW");
$smarty->assign("section", "live");
$smarty->assign("version_web", $config->v_web);
$smarty->assign("server", $server_array);
$smarty->assign("stats", $stats);
$smarty->assign("last_ban", $last_ban_arr);
$smarty->assign("addons", $addons_array);
$smarty->assign("rules", $rules);
$smarty->assign("rules_array", $rules_array);
$smarty->assign("anticheat_array", $anticheat_array);
$smarty->assign("players", isset($player_array) ? $player_array : NULL);
$smarty->assign("empty_result", isset($empty_result) ? $empty_result : NULL);
$smarty->assign("error", $error);

if (file_exists("templates/" . $config->design . "/main_header.tpl")) {
    $smarty->assign("design", $config->design);
}
$smarty->assign("dir", $config->document_root);
$smarty->assign("this", $_SERVER['PHP_SELF']);
$smarty->assign("menu", $menu);
$smarty->assign("banner", $config->banner);
$smarty->assign("banner_url", $config->banner_url);

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