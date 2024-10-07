<?php
session_start();
if (!$_SESSION["loggedin"]) {
    header("Location:index.php");
    exit;
}
if (!has_access("amxadmins_view")) {
    header("Location:index.php");
    exit;
}

$admin_site = "um";
$title2 = "_TITLEUSERMENU";

global $config;

// Function to change the menu position using PDO
function menu_change_pos($pdo, $mid, $pos, $pos_new) {
    global $config;
    
    // Temporarily set the menu item to position 0
    $query = $pdo->prepare("UPDATE `".$config->db_prefix."_usermenu` SET `pos`=0 WHERE `id`=:mid LIMIT 1");
    $query->execute(['mid' => $mid]);
    
    if ($pos == $pos_new - 1 || $pos == $pos_new + 1) {
        // Swap positions (one step up or down)
        $sql = "UPDATE `".$config->db_prefix."_usermenu` SET `pos`=`pos`" . (($pos_new < $pos) ? "+" : "-") . "1 WHERE `pos`=:pos_new LIMIT 1";
        $query = $pdo->prepare($sql);
        $query->execute(['pos_new' => $pos_new]);
    } else {
        // Move to a different position (multiple steps)
        $sql = "UPDATE `".$config->db_prefix."_usermenu` SET `pos`=`pos`" . (($pos_new < $pos) ? "+" : "-") . "1 
                WHERE `pos`".(($pos_new < $pos) ? "<" : ">").":pos AND `pos`".(($pos_new < $pos) ? ">=" : "<=").":pos_new";
        $query = $pdo->prepare($sql);
        $query->execute(['pos' => $pos, 'pos_new' => $pos_new]);
    }

    // Set new position for the updated menu item
    $query = $pdo->prepare("UPDATE `".$config->db_prefix."_usermenu` SET `pos`=:pos_new WHERE `id`=:mid LIMIT 1");
    $query->execute(['pos_new' => $pos_new, 'mid' => $mid]);

    // Log the position change
    // log_to_db("Usermenu config", "Changed menu: position " . $pos . " -> " . $pos_new);
}

if (isset($_POST["mid"])) {
    $mid = (int)$_POST["mid"];
} else {
    $mid = "";
}

// Create a PDO connection
try {
    $pdo = new PDO("mysql:host=".$config->db_host.";dbname=".$config->db_db, $config->db_user, $config->db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Delete menu
if (isset($_POST["del"])) {
    if (!has_access("amxadmins_view")) {
        header("Location:index.php");
        exit;
    }
    $query = $pdo->prepare("DELETE FROM `".$config->db_prefix."_usermenu` WHERE `id`=:mid LIMIT 1");
    $query->execute(['mid' => $mid]);
    $user_msg = '_USERMENUDELETED';
    log_to_db("Usermenu config", "Deleted menu: ID: " . $mid);
}

// Add new menu
if (isset($_POST["new"])) {
    if (!has_access("amxadmins_view")) {
        header("Location:index.php");
        exit;
    }
    $query = $pdo->prepare("INSERT INTO `".$config->db_prefix."_usermenu` (`pos`, `activ`, `url`, `lang_key`, `url2`, `lang_key2`) 
              VALUES (:pos, 1, :url, :lang_key, :url2, :lang_key2)");
    $query->execute([
        'pos' => (int)$_POST["pos"],
        'url' => $_POST["url"],
        'lang_key' => $_POST["lang_key"],
        'url2' => $_POST["url2"],
        'lang_key2' => $_POST["lang_key2"]
    ]);
    $user_msg = '_USERMENUADDED';
    log_to_db("Usermenu config", "Added new menu item");
}

// Change position using up/down buttons
if (isset($_POST["pos_up_x"]) || isset($_POST["pos_dn_x"])) {
    $pos = (int)$_POST["pos"];
    $pos_new = $pos;
    if (isset($_POST["pos_up_x"])) $pos_new--;
    if (isset($_POST["pos_dn_x"])) $pos_new++;
    
    menu_change_pos($pdo, $mid, $pos, $pos_new);
    
    $user_msg = '_USERMENUPOSSAVED';
}

// Save menu changes
if (isset($_POST["save"])) {
    if (!has_access("amxadmins_view")) {
        header("Location:index.php");
        exit;
    }

    // Update menu item details
    $query = $pdo->prepare("UPDATE `".$config->db_prefix."_usermenu` SET 
          `activ`=:activ, 
          `url`=:url, 
          `lang_key`=:lang_key, 
          `url2`=:url2, 
          `lang_key2`=:lang_key2 
          WHERE `id`=:mid LIMIT 1");
    $query->execute([
        'activ' => isset($_POST["activ"]) ? 1 : 0,
        'url' => $_POST["url"],
        'lang_key' => $_POST["lang_key"],
        'url2' => $_POST["url2"],
        'lang_key2' => $_POST["lang_key2"],
        'mid' => $mid
    ]);
    $user_msg = '_USERMENUSAVED';
    log_to_db("Usermenu config", "Edited menu: ID " . $mid);
}

// Get full menu
$menu2 = sql_get_usermenu($count);

// Activate menu changes
include("include/menu.inc.php");

$activ_choose = ["no", "yes"];
$smarty->assign("activ_choose", $activ_choose);
$smarty->assign("menu_count", $count);
$smarty->assign("menu2", $menu2);
?>
