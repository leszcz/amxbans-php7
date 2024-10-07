<?php
session_start();
if (!$_SESSION["loggedin"]) {
    header("Location:index.php");
    exit;
}

$admin_site = "ul";
$title2 = "_TITLEUSERLEVEL";

// CSRF protection: Verify token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token mismatch. Possible CSRF attack.');
    }
}

// Generate a CSRF token and store it in session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Create a unique CSRF token
}

// Secure handling of POST data
function get_post($key, $default = null) {
    return isset($_POST[$key]) ? htmlspecialchars(trim($_POST[$key]), ENT_QUOTES, 'UTF-8') : $default;
}

// Create a PDO connection
try {
    $pdo = new PDO("mysql:host=" . $config->db_host . ";dbname=" . $config->db_db, $config->db_user, $config->db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if a level ID is provided
$lid = isset($_POST["lid"]) && is_numeric($_POST["lid"]) ? (int)$_POST["lid"] : "";

// Add a new user level
if (isset($_POST["new"])) {
    $stmt = $pdo->query("SELECT COUNT(level) FROM `" . $config->db_prefix . "_levels`");
    $level_count = $stmt->fetchColumn(); // Get the count of levels

    $stmt = $pdo->prepare("INSERT INTO `" . $config->db_prefix . "_levels` (`level`) VALUES (:level)");
    $stmt->execute(['level' => $level_count + 1]);

    $user_msg = "_LEVELADDED";
    log_to_db("User Level config", "Added new level " . ($level_count + 1));
}

// Delete a user level
if (isset($_POST["del"])) {
    // Check if any users are using this level
    $stmt = $pdo->prepare("SELECT COUNT(id) FROM `" . $config->db_prefix . "_webadmins` WHERE `level` = :lid");
    $stmt->execute(['lid' => $lid]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $user_msg = "_LEVELDELFAILED";
    } else {
        // Delete the level if no users are assigned
        $stmt = $pdo->prepare("DELETE FROM `" . $config->db_prefix . "_levels` WHERE `level` = :lid LIMIT 1");
        $stmt->execute(['lid' => $lid]);

        $user_msg = "_LEVELDELETED";
        log_to_db("User Level config", "Deleted: level " . $lid);
    }
}

// Save the user level settings
if (isset($_POST["save"])) {
    $bans_add = get_post("bans_add", 'no');
    $bans_edit = get_post("bans_edit", 'no');
    $bans_delete = get_post("bans_delete", 'no');
    $bans_unban = get_post("bans_unban", 'no');
    $bans_import = get_post("bans_import", 'no');
    $bans_export = get_post("bans_export", 'no');
    $amxadmins_view = get_post("amxadmins_view", 'no');
    $amxadmins_edit = get_post("amxadmins_edit", 'no');
    $webadmins_view = get_post("webadmins_view", 'no');
    $webadmins_edit = get_post("webadmins_edit", 'no');
    $websettings_view = get_post("websettings_view", 'no');
    $websettings_edit = get_post("websettings_edit", 'no');
    $permissions_edit = get_post("permissions_edit", 'no');
    $prune_db = get_post("prune_db", 'no');
    $servers_edit = get_post("servers_edit", 'no');
    $ip_view = get_post("ip_view", 'no');

    $query = "UPDATE `" . $config->db_prefix . "_levels` SET 
        `bans_add` = :bans_add,
        `bans_edit` = :bans_edit,
        `bans_delete` = :bans_delete,
        `bans_unban` = :bans_unban,
        `bans_import` = :bans_import,
        `bans_export` = :bans_export,
        `amxadmins_view` = :amxadmins_view,
        `amxadmins_edit` = :amxadmins_edit,
        `webadmins_view` = :webadmins_view,
        `webadmins_edit` = :webadmins_edit,
        `websettings_view` = :websettings_view,
        `websettings_edit` = :websettings_edit,
        `permissions_edit` = :permissions_edit,
        `prune_db` = :prune_db,
        `servers_edit` = :servers_edit,
        `ip_view` = :ip_view
        WHERE `level` = :lid LIMIT 1";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'bans_add' => $bans_add,
        'bans_edit' => $bans_edit,
        'bans_delete' => $bans_delete,
        'bans_unban' => $bans_unban,
        'bans_import' => $bans_import,
        'bans_export' => $bans_export,
        'amxadmins_view' => $amxadmins_view,
        'amxadmins_edit' => $amxadmins_edit,
        'webadmins_view' => $webadmins_view,
        'webadmins_edit' => $webadmins_edit,
        'websettings_view' => $websettings_view,
        'websettings_edit' => $websettings_edit,
        'permissions_edit' => $permissions_edit,
        'prune_db' => $prune_db,
        'servers_edit' => $servers_edit,
        'ip_view' => $ip_view,
        'lid' => $lid
    ]);

    $user_msg = "_LEVELSAVED";

    // Log out all users with this level
    $stmt = $pdo->prepare("UPDATE `" . $config->db_prefix . "_webadmins` SET `logcode` = NULL WHERE `level` = :lid");
    $stmt->execute(['lid' => $lid]);

    // If the current user has the same level, log them out as well
    if ($_SESSION["level"] == $lid) {
        session_destroy();
        header("Location: logout.php");
        exit;
    }

    log_to_db("User Level config", "Edited: level " . $lid);
}

// Fetch all user levels from the database
$stmt = $pdo->query("SELECT * FROM `" . $config->db_prefix . "_levels` ORDER BY `level`");
$levels = [];
$choose1 = ["yes", "no"];
$output1 = ["_YES", "_NO"];
$choose2 = ["yes", "no", "own"];
$output2 = ["_YES", "_NO", "_OWN"];

while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $levels[] = $result;
}

// Assign variables to Smarty template
$smarty->assign("levels", $levels);
$smarty->assign("choose1", $choose1);
$smarty->assign("choose2", $choose2);
$smarty->assign("output1", $output1);
$smarty->assign("output2", $output2);

?>
