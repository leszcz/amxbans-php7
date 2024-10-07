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

$admin_site = "av";
$title2 = "_TITLEAMXADMINS";

$pdo = getPDO(); 

$aid = isset($_POST["aid"]) ? (int)$_POST["aid"] : "";

// remove AMXX administrator
if (isset($_POST["del"])) {
    if (!has_access("amxadmins_view")) {
        header("Location: index.php");
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM `{$config->db_prefix}_amxadmins` WHERE `id` = :aid LIMIT 1");
    $stmt->execute([':aid' => $aid]);

    $stmt = $pdo->prepare("DELETE FROM `{$config->db_prefix}_admins_servers` WHERE `admin_id` = :aid");
    $stmt->execute([':aid' => $aid]);

    $user_msg = $row1['_AMXADMINDELETED'];
    log_to_db("AMXXAdmin config", "Deleted admin: " . sql_safe($_POST["username"]));
}

// Validate request data
if (isset($_POST["save"]) || isset($_POST["new"])) {
    if (!has_access("amxadmins_view")) {
        header("Location: index.php");
        exit;
    }

    $username = sql_safe($_POST["username"]);
    $password = $_POST["password"] ? md5($_POST["password"]) : '';
    $access = sql_safe($_POST["access"]);
    $flags = sql_safe($_POST["flags"]);
    $steamid = sql_safe($_POST["steamid"]);
    $nickname = sql_safe($_POST["nickname"]);
    $icq = (int)$_POST["icq"];
}

// Edit AMXX administrator
if (isset($_POST["save"])) {
    if (!has_access("amxadmins_view")) {
        header("Location: index.php");
        exit;
    }

    if (isset($_POST["noend"])) {
        $days = 0;
        $exp = "0";
    } elseif (isset($_POST["moredays"]) && (int)$_POST["moredays"] != "") {
        $days = (int)$_POST["days"] + (int)$_POST["moredays"];
        $exp = "(`created` + (:days * 86400))";
    } else {
        $days = (int)$_POST["days"];
        $exp = ($days <= 0) ? "0" : "(`created` + (:days * 86400))";
    }

    $password_sql = $password ? " `password` = :password, " : "";

    $stmt = $pdo->prepare("UPDATE `{$config->db_prefix}_amxadmins` SET 
            `username` = :username,
            {$password_sql}
            `access` = :access,
            `flags` = :flags,
            `steamid` = :steamid,
            `nickname` = :nickname,
            `icq` = :icq,
            `ashow` = :ashow,
            `expired` = $exp,
            `days` = :days
            WHERE `id` = :aid LIMIT 1");

    $params = [
        ':username'  => $username,
        ':access'    => $access,
        ':flags'     => $flags,
        ':steamid'   => $steamid,
        ':nickname'  => $nickname,
        ':icq'       => $icq,
        ':ashow'     => (int)$_POST["ashow"],
        ':days'      => $days,
        ':aid'       => $aid,
    ];

    if ($password) {
        $params[':password'] = $password;
    }

    $stmt->execute($params);
    $user_msg = $row1['_AMXADMINSAVESUCCESS'];
    log_to_db("AMXXAdmin config", "Edited admin: " . sql_safe($_POST["username"]) . " (nick: " . sql_safe($_POST["nickname"]) . ")");
}

// Add AMXX administrator
if (isset($_POST["new"])) {
    if (!has_access("amxadmins_view")) {
        header("Location: index.php");
        exit;
    }

    $days = isset($_POST["noend"]) ? 0 : (int)$_POST["days"];
    $exp = ($days > 0) ? "(UNIX_TIMESTAMP() + (:days * 86400))," : "0,";

    if ($days == 0 && !isset($_POST["noend"])) {
        $user_msg = $row1['_NOVALIDTIME'];
    } else {
        // add AMXX administrator
        $stmt = $pdo->prepare("INSERT INTO `{$config->db_prefix}_amxadmins` 
                (`username`, `password`, `access`, `flags`, `steamid`, `nickname`, `icq`, `ashow`, `created`, `expired`, `days`) 
                VALUES 
                (:username, :password, :access, :flags, :steamid, :nickname, :icq, :ashow, UNIX_TIMESTAMP(), $exp, :days)");

        $stmt->execute([
            ':username' => $username,
            ':password' => $password,
            ':access'   => $access,
            ':flags'    => $flags,
            ':steamid'  => $steamid,
            ':nickname' => $nickname,
            ':icq'      => $icq,
            ':ashow'    => (int)$_POST["ashow"],
            ':days'     => $days
        ]);

        $adminid = $pdo->lastInsertId();
        $addtoserver = $_POST["addtoserver"];
        $sban = sql_safe($_POST["staticbantime"]);

        // add administrator to servers
        if (is_array($addtoserver)) {
            foreach ($addtoserver as $v) {
                $stmt = $pdo->prepare("INSERT INTO `{$config->db_prefix}_admins_servers` 
                    (`admin_id`, `server_id`, `custom_flags`, `use_static_bantime`) 
                    VALUES (:admin_id, :server_id, '', :sban)");

                $stmt->execute([
                    ':admin_id' => $adminid,
                    ':server_id' => $v,
                    ':sban' => $sban
                ]);
            }
        }

        $user_msg = $row1['_AMXADMINADDED'];
        log_to_db("AMXXAdmin config", "Added admin: " . sql_safe($username));
    }
}

// get AMXX administrators
$admins = sql_get_amxadmins();

// get servers list
$servers = sql_get_server();
$svalues = $soutput = [];

if (is_array($servers)) {
    foreach ($servers as $v) {
        $svalues[] = $v["sid"];
        $soutput[] = $v["hostname"];
    }
}

$smarty->assign("yesno_choose", ["yes", "no"]);
$smarty->assign("yesno_output", ["_YES", "_NO"]);
$smarty->assign("ashow_output", ['_NO', '_YES']);
$smarty->assign("ashow", [0, 1]);
$smarty->assign("admins", $admins);
$smarty->assign("svalues", $svalues);
$smarty->assign("soutput", $soutput);

?>
