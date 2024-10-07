<?php
declare(strict_types=1);

session_start();

require_once("include/config.inc.php");
require_once("include/steam.inc.php");
require_once("include/sql.inc.php");
require_once("include/functions.inc.php");

$smarty = new dynamicPage();
$smarty->setTemplateDir($config->templatedir);

$admin_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($admin_id === false || $admin_id === null) {
    die('Invalid admin ID');
}

try {
    $pdo = getPDO();

    // Fetch servers for the admin
    $servers_query = "SELECT si.hostname 
                      FROM `{$config->db_prefix}_admins_servers` AS as_
                      LEFT JOIN `{$config->db_prefix}_serverinfo` AS si 
                      ON as_.server_id = si.id 
                      WHERE as_.admin_id = :admin_id";

    $stmt = $pdo->prepare($servers_query);
    $stmt->execute(['admin_id' => $admin_id]);
    $servers = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Fetch admin data
    $admin_query = "SELECT * FROM `{$config->db_prefix}_amxadmins` WHERE `id` = :admin_id LIMIT 1";
    $stmt = $pdo->prepare($admin_query);
    $stmt->execute(['admin_id' => $admin_id]);
    $admin_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin_data) {
        throw new Exception('Admin not found');
    }

    // Generate template
    $title = "_TITLEADMINLIST";
    $smarty->assign("servers", $servers);
    $smarty->assign("admin", $admin_data);
    $smarty->assign("design", $config->design);

    $smarty->display('admin_ajax.tpl');

} catch (PDOException $e) {
    // Log the error and display a user-friendly message
    error_log("Database error in admin_ajax.php: " . $e->getMessage());
    die("An error occurred while fetching data. Please try again later.");
} catch (Exception $e) {
    die($e->getMessage());
}
?>