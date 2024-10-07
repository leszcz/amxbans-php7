<?php
session_start();

include("include/config.inc.php");

try {
    // Establish a PDO connection
    $pdo = new PDO("mysql:host=" . $config->db_host . ";dbname=" . $config->db_db, $config->db_user, $config->db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Unset the session cookie
if (isset($_COOKIE[$config->cookie])) {
    setcookie($config->cookie, '', time() - 3600, "/", $_SERVER['HTTP_HOST'], isset($_SERVER["HTTPS"]), true); // Use secure and HTTPOnly flags if HTTPS is used
}

// Clear logcode from the database for the logged-in user
if (isset($_SESSION["uid"])) {
    $stmt = $pdo->prepare("UPDATE `" . $config->db_prefix . "_webadmins` SET `logcode` = NULL WHERE `id` = :uid");
    $stmt->execute(['uid' => (int)$_SESSION["uid"]]);
}

// Clear all session variables
session_unset();

// Preserve language preference
$lang_temp = isset($_SESSION["lang"]) ? $_SESSION["lang"] : null;

// Destroy the session
session_destroy();

// Start a new session to maintain the language preference
session_start();
if ($lang_temp) {
    $_SESSION["lang"] = $lang_temp;
}

// Redirect to the homepage
header("Location:index.php");
exit;
?>
