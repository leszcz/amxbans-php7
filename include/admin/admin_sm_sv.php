<?php
session_start();

if (!$_SESSION["loggedin"]) {
    header("Location: index.php");
    exit;
}
if (!has_access("amxadmins_view")) {
    header("Location: index.php");
    exit;
}

require_once("include/rcon_hl_net.inc.php");

$admin_site = "sv";
$title2 = "_TITLESERVER";

$sid = isset($_POST["sid"]) ? (int)$_POST["sid"] : "";

$smsg = "";
$pdo = getPDO();

// function to perform RCON request and get response
function rcon_send($command, $sid, $max_response_pages = 0) {
    global $pdo, $config;

    $stmt = $pdo->prepare("SELECT address, rcon FROM {$config->db_prefix}_serverinfo WHERE id = :sid");
    $stmt->execute([':sid' => $sid]);
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    if ($result) {
        $server_address = explode(":", trim($result->address));
        $server_rcon = $result->rcon;

        $server = new Rcon();
        if ($server->Connect($server_address[0], $server_address[1], $server_rcon)) {
            $response = $server->RconCommand($command, $max_response_pages);
            $server->Disconnect();

            return $response ? trim($response) : false;
        }
    }
    return false;
}

// perform RCON commands actions
if ((isset($_POST["rconcommand"]) || isset($_POST["rconuserstart_$sid"])) && $sid) {
    if (!has_access("servers_edit")) {
        header("Location: index.php");
        exit;
    }

    // disabled RCON commands
    $denied_cmds = ["rcon_password", "_restart", "quit", "changelevel"];
    $max_response_pages = 0;
    $command = "";

    // predefined commands
    if (isset($_POST["command"])) {
        switch ($_POST["command"]) {
            case 'reload': $command = "amx_reloadadmins"; break;
            case 'restart': $command = "restart"; $max_response_pages = 3; break;
            case 'status': $command = "status"; break;
            case 'plugins': $command = "amx_plugins"; $max_response_pages = 3; break;
            case 'modules': $command = "amx_modules"; $max_response_pages = 3; break;
            case 'metalist': $command = "meta list"; break;
        }
    }

    // check if user define custom rcon command
    if (isset($_POST["rconuserstart_$sid"])) {
        $command = sql_safe(trim($_POST["rconuser"]));
    }

    // check if command is not disabled
    foreach ($denied_cmds as $v) {
        if (stripos($command, $v) !== false) {
            $denied = true;
            $user_msg = "_RCON_CMDDENIED";
            $hide_response = true;
            break;
        }
    }

    // request RCON command
    if ($command && !$denied) {
        $response = rcon_send($command, $sid, $max_response_pages);
    }

    if (!$user_msg) {
        $user_msg = "_RCON_SERVERRESPONSE";
    }

    if (!$hide_response) {
        $smsg = nl2br(html_safe($response));
        if (stripos($response, "Bad rcon") !== false) {
            $user_msg = "_WRONGRCON";
            $smsg = "";
        }
        if (!$response) {
            $user_msg = "_RCON_TIMEDOUT";
            $smsg = "";
        }
    }
}

// save server settings
if (isset($_POST["save"]) && $_POST["rcon"] !== "***PROTECTED***") {
    if (!has_access("servers_edit")) {
        header("Location: index.php");
        exit;
    }

    $stmt = $pdo->prepare("UPDATE {$config->db_prefix}_serverinfo SET 
        rcon = :rcon, 
        amxban_motd = :motd, 
        motd_delay = :motd_delay, 
        amxban_menu = :amxban_menu, 
        reasons = :reasons, 
        timezone_fixx = :timezone_fixx 
        WHERE id = :sid LIMIT 1");

    $stmt->execute([
        ':rcon' => sql_safe($_POST["rcon"]),
        ':motd' => sql_safe($_POST["amxban_motd"]),
        ':motd_delay' => (int)$_POST["motd_delay"],
        ':amxban_menu' => (int)$_POST["amxban_menu"],
        ':reasons' => (int)$_POST["reasons"],
        ':timezone_fixx' => (int)$_POST["timezone_fixx"],
        ':sid' => $sid
    ]);

    $user_msg = '_SERVERSAVED';
    log_to_db("Server config", "Edited server: " . html_safe($_POST["sidname"]));
}

// delete server
if (isset($_POST["del"])) {
    if (!has_access("servers_edit")) {
        header("Location: index.php");
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM {$config->db_prefix}_serverinfo WHERE id = :sid LIMIT 1");
    $stmt->execute([':sid' => $sid]);

    $stmt = $pdo->prepare("DELETE FROM {$config->db_prefix}_admins_servers WHERE server_id = :sid");
    $stmt->execute([':sid' => $sid]);

    $user_msg = '_SERVERDELETED';
    log_to_db("Server config", "Deleted server: " . html_safe($_POST["sidname"]));
}

// get servers
$servers = sql_get_server();

// get reasons sets
$stmt = $pdo->query("SELECT * FROM {$config->db_prefix}_reasons_set ORDER BY setname ASC");
$reasons_values = [""];
$reasons_choose = [""];
while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
    $reasons_values[] = $result->id;
    $reasons_choose[] = $result->setname;
}

$timezone_values = range(-12, 12);
$timezone_output = array_map(fn($v) => ($v >= 0 ? "+$v" : "$v"), $timezone_values);
$delay_choose = [2, 3, 4, 5, 7, 10];
$menu_choose = [0, 1];

// predefined rcon commands
$rcon_cmds = ["reload", "restart", "status", "plugins", "modules", "metalist"];
$rcon_cmdkeys = ["_RCON_RELOADADMINS", "_RCON_RESTARTMAP", "_RCON_STATUS", "_RCON_PLUGINS", "_RCON_MODULES", "_RCON_METALIST"];

// get URL for MOTD
$motd_url = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$motd_url = str_replace(basename($motd_url), "motd.php?sid=%s&adm=%d&lang=%s", $motd_url);

$smarty->assign("motd_url", $motd_url);
$smarty->assign("rcon_cmds", $rcon_cmds);
$smarty->assign("rcon_cmdkeys", $rcon_cmdkeys);
$smarty->assign("timezone_values", $timezone_values);
$smarty->assign("timezone_output", $timezone_output);
$smarty->assign("delay_choose", $delay_choose);
$smarty->assign("menu_choose", $menu_choose);
$smarty->assign("reasons_choose", $reasons_choose);
$smarty->assign("reasons_values", $reasons_values);
$smarty->assign("servers", $servers);
$smarty->assign("server_activ", $sid);
$smarty->assign("smsg", $smsg);
?>
