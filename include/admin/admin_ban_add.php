<?php
session_start();
if (!$_SESSION["loggedin"]) {
    header("Location: index.php");
    exit;
}
if (!has_access("bans_add")) {
    header("Location: index.php");
    exit;
}

$admin_site = "ban_add";
$title2 = "_TITLEBANADD";

$pdo = getPDO();

// save ban
if (isset($_POST["save"])) {
    $reason_custom = 0;
    
    if (isset($_POST["reasoncheck"]) && $_POST["reasoncheck"] === "yes") {
        $reason = trim($_POST["user_reason"]);
        $reason_custom = 1;
    } else {
        $reason = $_POST["ban_reason"];
    }

    if (!$reason) {
        $reason = $_POST["ban_reason"];
    }

    $ban_length = isset($_POST["perm"]) && $_POST["perm"] === "yes" ? 0 : (int)$_POST["ban_length"];
    if ($ban_length < 0) {
        $ban_length = 0;
    }

    $ban_type = $_POST["ban_type"];
    $name = trim($_POST["name"]);
    $steamid = trim($_POST["steamid"]);
    $ip = trim($_POST["ip"]);

    // validate data from request
    if ($ip && !filter_var($ip, FILTER_VALIDATE_IP)) {
        $user_msg = "_IPINVALID";
    }
    if (empty($name)) {
        $user_msg = "_NOBANNAME";
    }
    if (empty($steamid) && $ban_type === "S") {
        $user_msg = "_NOBANSTEAMID";
    }
    if (empty($ip) && $ban_type === "SI") {
        $user_msg = "_NOIP";
    }

    // check if ban is exists
    if (empty($user_msg)) {
        $query = "SELECT * FROM `{$config->db_prefix}_bans` WHERE `expired` = 0";
        $params = [];
        if ($steamid) {
            $query .= " AND `player_id` = :steamid";
            $params[':steamid'] = $steamid;
        }
        if ($ip) {
            $query .= " AND `player_ip` = :ip";
            $params[':ip'] = $ip;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            $user_msg = "_ACTIVBANEXISTS";
        }
    }

    // add ban
    if (empty($user_msg)) {
        $stmt = $pdo->prepare("INSERT INTO `{$config->db_prefix}_bans` 
            (`player_ip`, `player_id`, `player_nick`, `admin_nick`, `admin_id`, `ban_type`, `ban_reason`, `cs_ban_reason`, `ban_created`, `ban_length`, `server_name`)
            VALUES (:ip, :steamid, :name, :admin_nick, :admin_id, :ban_type, :reason, :cs_ban_reason, UNIX_TIMESTAMP(), :ban_length, 'website')");
        
        $stmt->execute([
            ':ip'          => $ip,
            ':steamid'     => $steamid,
            ':name'        => $name,
            ':admin_nick'  => $_SESSION["uname"],
            ':admin_id'    => $_SESSION["uname"],
            ':ban_type'    => $ban_type,
            ':reason'      => $reason,
            ':cs_ban_reason' => $reason,
            ':ban_length'  => $ban_length
        ]);

        $user_msg = '_BANADDSUCCESS';
        log_to_db("Add ban", "playernick: $name / time: $ban_length");
    } else {
        // save ban data in form
        $inputs = [
            "name"         => $name,
            "steamid"      => $steamid,
            "ip"           => $ip,
            "reason"       => $reason,
            "reason_custom"=> $reason_custom,
            "length"       => $ban_length,
            "type"         => $ban_type
        ];
        $smarty->assign("inputs", $inputs);
    }
}

// get reasons list
$reasons = sql_get_reasons_list();
$smarty->assign("reasons", $reasons);

$banby_output = ["IP", "SteamID"];
$banby_values = ["SI", "S"];
$smarty->assign("banby_output", $banby_output);
$smarty->assign("banby_values", $banby_values);

?>
