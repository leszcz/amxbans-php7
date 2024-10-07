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

$admin_site = "bg";
$title2 = "_TITLEREASONS";

$pdo = getPDO();

// add new reasons set
if (isset($_POST["newset"])) {
    if (!has_access("servers_edit")) {
        header("Location: index.php");
        exit;
    }
    
    $setname = sql_safe($_POST["setname"]);
    
    if (!validate_value($setname, "name", $error, 1, 31, "REASONSET")) {
        $user_msg = $error;
    }

    if (!$user_msg) {
        $stmt = $pdo->prepare("INSERT INTO `{$config->db_prefix}_reasons_set` (`setname`) VALUES (:setname)");
        $stmt->execute([':setname' => $setname]);

        $user_msg = '_REASONSETADDED';
        log_to_db("Reasons config", "Added Set: $setname");
    }
}

// add new reason
if (isset($_POST["newreason"])) {
    if (!has_access("servers_edit")) {
        header("Location: index.php");
        exit;
    }

    $reason = sql_safe($_POST["reason"]);
    $time = (int)$_POST["static_bantime"];
    
    if (!validate_value($reason, "name", $error, 1, 99, "REASON")) {
        $user_msg = $error;
    }

    if (!$user_msg) {
        $stmt = $pdo->prepare("INSERT INTO `{$config->db_prefix}_reasons` (`reason`, `static_bantime`) VALUES (:reason, :time)");
        $stmt->execute([':reason' => $reason, ':time' => $time]);

        $user_msg = '_REASONADDED';
        log_to_db("Reasons config", "Added Reason: $reason ($time min)");
    }
}

$rsid = (int)$_POST["rsid"];
$rid = (int)$_POST["rid"];

// remove reasons set
if (isset($_POST["delset"])) {
    if (!has_access("servers_edit")) {
        header("Location: index.php");
        exit;
    }

    $setname = html_safe($_POST["setname"]);
    
    $stmt = $pdo->prepare("DELETE FROM `{$config->db_prefix}_reasons_set` WHERE `id` = :rsid LIMIT 1");
    $stmt->execute([':rsid' => $rsid]);

    $stmt = $pdo->prepare("DELETE FROM `{$config->db_prefix}_reasons_to_set` WHERE `setid` = :rsid");
    $stmt->execute([':rsid' => $rsid]);

    $user_msg = '_REASONSETDELETED';
    log_to_db("Reasons config", "Deleted set: $setname");
}

// save reasons set
if (isset($_POST["saveset"])) {
    if (!has_access("servers_edit")) {
        header("Location: index.php");
        exit;
    }

    $setname = sql_safe($_POST["setname"]);

    if (!validate_value($setname, "name", $error, 1, 31, "REASONSET")) {
        $user_msg = $error;
    }

    if (!$user_msg) {
        $stmt = $pdo->prepare("DELETE FROM `{$config->db_prefix}_reasons_to_set` WHERE `setid` = :rsid");
        $stmt->execute([':rsid' => $rsid]);

        if (isset($_POST["aktiv"])) {
            foreach ($_POST["aktiv"] as $v) {
                $stmt = $pdo->prepare("INSERT INTO `{$config->db_prefix}_reasons_to_set` (`setid`, `reasonid`) VALUES (:setid, :reasonid)");
                $stmt->execute([':setid' => $rsid, ':reasonid' => $v]);
            }
        }

        $stmt = $pdo->prepare("UPDATE `{$config->db_prefix}_reasons_set` SET `setname` = :setname WHERE `id` = :rsid LIMIT 1");
        $stmt->execute([':setname' => $setname, ':rsid' => $rsid]);

        $user_msg = '_REASONSSETSAVED';
        log_to_db("Reasons config", "Edited set: $setname");
    }
}

// delete reason
if (isset($_POST["reasondel"])) {
    if (!has_access("servers_edit")) {
        header("Location: index.php");
        exit;
    }

    $reason = html_safe($_POST["reason"]);

    $stmt = $pdo->prepare("DELETE FROM `{$config->db_prefix}_reasons` WHERE `id` = :rid LIMIT 1");
    $stmt->execute([':rid' => $rid]);

    $stmt = $pdo->prepare("DELETE FROM `{$config->db_prefix}_reasons_to_set` WHERE `reasonid` = :rid");
    $stmt->execute([':rid' => $rid]);

    $user_msg = '_REASONDELETED';
    log_to_db("Reasons config", "Deleted reason: $reason");
}

// save reason
if (isset($_POST["reasonsave"])) {
    if (!has_access("servers_edit")) {
        header("Location: index.php");
        exit;
    }

    $reason = sql_safe($_POST["reason"]);
    $time = (int)$_POST["static_bantime"];

    if (!validate_value($reason, "name", $error, 1, 99, "REASON")) {
        $user_msg = $error;
    }

    if (!$user_msg) {
        $stmt = $pdo->prepare("UPDATE `{$config->db_prefix}_reasons` SET `reason` = :reason, `static_bantime` = :time WHERE `id` = :rid LIMIT 1");
        $stmt->execute([':reason' => $reason, ':time' => $time, ':rid' => $rid]);

        $user_msg = '_REASONSAVED';
        log_to_db("Reasons config", "Edited reason: $reason ($time min)");
    }
}

// get reasons set
$reasons_set = sql_get_reasons_set();
$smarty->assign("reasons_set", $reasons_set);

// get reason
$reasons = sql_get_reasons();
$smarty->assign("reasons", $reasons);

$check_values = ["1", "0"];
$check_output = ["Ja", "Nein"];
$smarty->assign("check_values", $check_values);
$smarty->assign("check_output", $check_output);
?>
