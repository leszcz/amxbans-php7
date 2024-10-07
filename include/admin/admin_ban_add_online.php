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

require_once("include/rcon_hl_net.inc");

if (!function_exists('geoip_country_code_by_addr')) {
    require_once("include/geoip.inc");
}

if (!$_SESSION["loggedin"]) {
    header("Location: index.php");
    exit;
}
if (!has_access("bans_add")) {
    header("Location: index.php");
    exit;
}

$admin_site = "ban_add_online";
$title2 = "_TITLEBANADDONLINE";

$sid = isset($_POST["server"]) ? (int)$_POST["server"] : 0;

$pdo = getPDO();

// get servers list
$stmt = $pdo->query("SELECT * FROM {$config->db_prefix}_serverinfo ORDER BY hostname ASC");
$servers_array = [];
while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
    $servers_list[] = $result->id;

    $server = new Rcon();
    $server_address = explode(":", trim($result->address));
    $server->Connect($server_address[0], $server_address[1], $result->rcon);
    $infos = $server->Info();
    $server->Disconnect();

    $servers_info = [
        "id"           => $result->id,
        "hostname"     => $result->hostname,
        "address"      => $result->address,
        "rcon"         => $result->rcon,
        "map"          => $infos['map'] ?? '',
        "mod"          => $infos['mod'] ?? '',
        "os"           => $infos['os'] == "l" ? "Linux" : "Windows",
        "cur_players"  => $infos['activeplayers'] ?? 0,
        "max_players"  => $infos['maxplayers'] ?? 0,
        "bot_players"  => $infos['botplayers'] ?? 0,
    ];

    $servers_array[] = $servers_info;
}

// get hostname for server
if (!isset($servers_array[$sid]["address"])) {
    $sid = 0;
}
$hostname = $servers_array[$sid]["hostname"] ?? '';
$smarty->assign("servers", $servers_array);
$smarty->assign("hostname", $hostname);
$smarty->assign("server_select", $sid);

// get reasons list
$reasons = sql_get_reasons_list();
$smarty->assign("reasons", $reasons);

// get ban settings
$banby_output = ["SteamID", "IP"];
$banby_values = ["S", "SI"];
$smarty->assign("banby_output", $banby_output);
$smarty->assign("banby_values", $banby_values);

// perform bans or kick actions
if ((isset($_POST["ban"]) || isset($_POST["kick"])) && !empty($servers_array[$sid]["address"])) {
    $pl_name = sql_safe($_POST["player_name"]);
    $pl_uid = (int)$_POST["player_uid"];
    $pl_steamid = sql_safe($_POST["player_steamid"]);
    $pl_ip = sql_safe($_POST["player_ip"]);
    $pl_ban_reason = sql_safe($_POST["ban_reason"]);
    $pl_user_reason = sql_safe($_POST["user_reason"]);
    $pl_ban_length = (int)$_POST["ban_length"];
    $pl_perm = isset($_POST["perm"]) ? true : false;
    $pl_silent = isset($_POST["silent"]) ? false : true;

    $steamid_valid = preg_match("/^STEAM_0:(0|1):[0-9]{1,10}$/", $pl_steamid);
    $ip_valid = preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $pl_ip);
    
    $pl_reason = $pl_user_reason ?: $pl_ban_reason;
    if (empty($pl_reason)) {
        $user_msg = "_NOREASON";
    }
}

// ban player
if (isset($_POST["ban"]) && !empty($servers_array[$sid]["address"]) && empty($user_msg)) {
    $time = $pl_perm ? 0 : max(0, $pl_ban_length);
    $type = $_POST["ban_type"];

    if (!$steamid_valid && $type == "S") {
        $user_msg = "_STEAMIDINVALID";
    } elseif (!$ip_valid && $type == "SI") {
        $user_msg = "_IPINVALID";
    }

    if (empty($user_msg)) {
        $stmt = $pdo->prepare("INSERT INTO `{$config->db_prefix}_bans` 
            (`player_ip`, `player_id`, `player_nick`, `admin_nick`, `admin_id`, `ban_type`, `ban_reason`, `cs_ban_reason`, `ban_created`, `ban_length`, `server_name`) 
            VALUES 
            (:player_ip, :player_id, :player_nick, :admin_nick, :admin_id, :ban_type, :ban_reason, :cs_ban_reason, UNIX_TIMESTAMP(), :ban_length, 'website')");
        $stmt->execute([
            ':player_ip'    => $pl_ip,
            ':player_id'    => $pl_steamid,
            ':player_nick'  => $pl_name,
            ':admin_nick'   => $_SESSION["uname"],
            ':admin_id'     => $_SESSION["uname"],
            ':ban_type'     => $type,
            ':ban_reason'   => $pl_reason,
            ':cs_ban_reason'=> $pl_reason,
            ':ban_length'   => $pl_ban_length
        ]);

        $server_address = explode(":", trim($servers_array[$sid]["address"]));
        $server = new Rcon();
        if ($server->Connect($server_address[0], $server_address[1], $servers_array[$sid]["rcon"])) {
            $response = $server->RconCommand("kick #$pl_uid \"$pl_reason\"");
            $server->Disconnect();
        }
        $user_msg = '_BANADDSUCCESS';
        log_to_db("Add ban online", "nick: $pl_name <$pl_steamid><$pl_ip> banned for $pl_ban_length minutes");
    }
}

// player kick
if (isset($_POST["kick"]) && !empty($servers_array[$sid]["address"])) {
    $server_address = explode(":", trim($servers_array[$sid]["address"]));
    $server = new Rcon();
    if ($server->Connect($server_address[0], $server_address[1], $servers_array[$sid]["rcon"])) {
        $response = $server->RconCommand("kick #$pl_uid \"$pl_reason\"");
        if (substr($response, 1)) {
            $user_msg = "_PLAYERKICKED";
            log_to_db("Kick online", "nick: $pl_name <$pl_steamid><$pl_ip> kicked");
        }
        $server_msg = $servers_array[$sid]["address"] . "<br>" . substr($response, 1); // Debug
        $server->Disconnect();
    }
}

// get players online list
if (!empty($servers_array[$sid]["mod"])) {
    $server_address = explode(":", trim($servers_array[$sid]["address"]));
    $server = new Rcon();
    if ($server->Connect($server_address[0], $server_address[1], $servers_array[$sid]["rcon"])) {
        $response = $server->ServerPlayers();
        $re = explode("\x0A", $response);

        if (!empty($response)) {
            if ($re[0] != "Bad rcon_password." && $re[1] != "Bad rcon_password.") {
                $players = [];
                $count = 0;
                foreach ($re as $v) {
                    $pl = explode("\xFC", $v);
                    if (!is_array($pl)) {
                        break;
                    }

                    $gi = geoip_open("include/GeoIP.dat", GEOIP_STANDARD);
                    $cc = geoip_country_code_by_addr($gi, $pl[3]);
                    $cn = geoip_country_name_by_addr($gi, $pl[3]);
                    geoip_close($gi);

                    $statusname = match ($pl[4]) {
                        0 => "_PLAYER",
                        1 => "_BOT",
                        2 => "_HLTV",
                        default => "_UNKNOWN"
                    };

                    $players[] = [
                        "name" => htmlspecialchars($pl[0]),
                        "userid" => $pl[1],
                        "steamid" => $pl[2],
                        "ip" => $pl[3],
                        "status" => $pl[4],
                        "immunity" => $pl[5],
                        "statusname" => $statusname,
                        "cc" => $cc,
                        "cn" => $cn
                    ];
                    $count++;
                }

                $smarty->assign("playerscount", $count);
                $smarty->assign("players", $players);
                $smarty->assign("players_sid", $sid);
            } else {
                $smsg = "_WRONGRCON";
            }
        }
        $server->Disconnect();
    }
} else {
    $smsg = "_SERVEROFFLINE";
}

$smarty->assign("smsg", $smsg ?? null);
$smarty->assign("user_msg", $user_msg ?? null);
$smarty->assign("server_msg", $server_msg ?? null);
?>
