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

$admin_site = "sa";
$title2 = "_TITLESERVERADMINS";

$pdo = getPDO();  // Zakładamy, że getPDO() zwraca obiekt PDO

$sid = isset($_POST["sid"]) ? (int)$_POST["sid"] : "";

$reasons_choose = "";
$reasons_values = "";

// Zapisz zmiany
if (isset($_POST["save"])) {
    $aktiv = $_POST["aktiv_new"];
    $custom_flags = $_POST["custom_flags"];
    $use_static_bantime = $_POST["use_static_bantime"];
    $user_id = $_POST["hid_uid"];

    // Usuń wszystkich administratorów dla danego serwera
    $stmt = $pdo->prepare("DELETE FROM `{$config->db_prefix}_admins_servers` WHERE `server_id` = :sid");
    $stmt->execute([':sid' => $sid]);

    // Przeszukaj nowe ustawienia
    if (is_array($aktiv)) {
        foreach ($aktiv as $k => $aid) {
            if ((int)$aid) {
                $cflags = sql_safe(trim($custom_flags[$k]));
                $sban = sql_safe(trim($use_static_bantime[$k]));
                $uid = sql_safe(trim($user_id[$k]));

                // Zapisz administratora do bazy danych
                $stmt = $pdo->prepare("INSERT INTO `{$config->db_prefix}_admins_servers` 
                    (`admin_id`, `server_id`, `custom_flags`, `use_static_bantime`) 
                    VALUES (:aid, :sid, :custom_flags, :use_static_bantime)");

                $stmt->execute([
                    ':aid' => (int)$aid,
                    ':sid' => $sid,
                    ':custom_flags' => $cflags,
                    ':use_static_bantime' => $sban
                ]);
            }
        }
    }

    $user_msg = '_SADMINSAVED';
    $smarty->assign("msg", $user_msg);
    log_to_db("Server Admin config", "Edited admins on server: " . sql_safe($_POST["sidname"]));
}

// Edytuj administratorów
if (isset($_POST["admins_edit"])) {
    $editadmins = [
        "sidname" => html_safe($_POST["sidname"]),
        "sid" => $sid
    ];
    $smarty->assign("editadmins", $editadmins);

    $admins = sql_get_amxadmins_server($sid);  // Pobierz administratorów dla danego serwera
    $smarty->assign("admins", $admins);
}

// Pobierz listę serwerów
$servers = sql_get_server();

$delay_choose = [1, 2, 5, 10];
$yesno_choose = ["yes", "no"];
$yesno_output = ["_YES", "_NO"];
$onetwo_choose = [1, 0];

$smarty->assign("onetwo_choose", $onetwo_choose);
$smarty->assign("delay_choose", $delay_choose);
$smarty->assign("yesno_choose", $yesno_choose);
$smarty->assign("yesno_output", $yesno_output);
$smarty->assign("reasons_choose", $reasons_choose);
$smarty->assign("reasons_values", $reasons_values);
$smarty->assign("servers", $servers);
?>
