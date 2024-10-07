<?php
session_start();
if (!$_SESSION["loggedin"]) {
    header("Location:index.php");
    exit;
}

if (!has_access("websettings_view")) {
    header("Location:index.php");
    exit;
}

$admin_site = "mo";
$title2 = "_TITLEMODULE";

$mid = isset($_POST["mid"]) ? (int)$_POST["mid"] : "";
$modules_menu_count = 0;

$pdo = getPDO();

// save module
if (isset($_POST["save"])) {
    if (!has_access("websettings_edit")) {
        header("Location:index.php");
        exit;
    }
    
    // get queries prepared to use module
    $stmt = $pdo->prepare("UPDATE `{$config->db_prefix}_modulconfig` 
        SET `activ` = :activ, 
            `menuname` = :menuname, 
            `name` = :name, 
            `index` = :index 
        WHERE `id` = :mid 
        LIMIT 1");
    
    // prepare params to execute queries
    $stmt->execute([
        ':activ' => isset($_POST["activ"]) ? 1 : 0,
        ':menuname' => $_POST["menuname"],
        ':name' => $_POST["name"],
        ':index' => $_POST["index"],
        ':mid' => $mid
    ]);

    $user_msg = '_MODULSAVED';
    log_to_db("Modules config", "Edited module: ID " . $mid);
}

// get modules list
$modules2 = sql_get_modules(0, $tmp);

$smarty->assign("modules_menu_count", $modules_menu_count);
$smarty->assign("modules2", $modules2);
?>
