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

$admin_site = "ms";
$title2 = "_TITLESITE";

try {
    // Establish a PDO connection
    $pdo = new PDO("mysql:host=" . $config->db_host . ";dbname=" . $config->db_db, $config->db_user, $config->db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Searching for templates (designs)
$designs = [];
$d = opendir($config->path_root . "/templates/");
while ($f = readdir($d)) {
    if ($f === "." || $f === "..") {
        continue;
    }
    if (is_dir($config->path_root . "/templates/" . $f)) {
        $designs[$f] = $f;
    }
}
closedir($d);

// Searching for banners
$banners = ["" => "---"];
$d = opendir($config->path_root . "/images/banner/");
while ($f = readdir($d)) {
    if ($f === "." || $f === ".." || is_dir($config->path_root . "/images/banner/" . $f)) {
        continue;
    }
    if (is_file($config->path_root . "/images/banner/" . $f) && $f !== "index.php") {
        $banners[$f] = $f;
    }
}
closedir($d);

// Searching for start pages
$start_pages = [];
$forbidden_files = ["index.php", "login.php", "logout.php", "admin.php", "search.php", "setup.php", "motd.php"];
$d = opendir($config->path_root . "/");
while ($f = readdir($d)) {
    if ($f === "." || $f === ".." || is_dir($config->path_root . "/" . $f)) {
        continue;
    }
    if (is_file($f) && !in_array($f, $forbidden_files) && substr($f, -3) === "php") {
        $start_pages[$f] = $f;
    }
}
closedir($d);

// Saving settings
if (isset($_POST["save"])) {
    if (!has_access("websettings_edit")) {
        header("Location:index.php");
        exit;
    }

    // Sanitize and prepare inputs
    $cookie = htmlspecialchars($_POST["cookie"]);
    $design = $_POST["design"] === "---" ? "" : htmlspecialchars($_POST["design"]);
    $bans_per_page = (is_numeric($_POST["bans_per_page"]) && $_POST["bans_per_page"] > 1) ? (int)$_POST["bans_per_page"] : 10;
    $banner = $_POST["banner"] === "---" ? "" : htmlspecialchars($_POST["banner"]);
    $banner_url = htmlspecialchars(trim($_POST["banner_url"]));
    $language = htmlspecialchars($_POST["language"]);
    $start_page = htmlspecialchars($_POST["start_page"]);
    $show_comment_count = (int)$_POST["show_comment_count"];
    $show_demo_count = (int)$_POST["show_demo_count"];
    $show_kick_count = (int)$_POST["show_kick_count"];
    $use_demo = (int)$_POST["use_demo"];
    $use_comment = (int)$_POST["use_comment"];
    $demo_all = (int)$_POST["demo_all"];
    $comment_all = (int)$_POST["comment_all"];
    $use_capture = (int)$_POST["use_capture"];
    $auto_prune = (int)$_POST["auto_prune"];
    $max_offences = (is_numeric($_POST["max_offences"]) && $_POST["max_offences"] > 1) ? (int)$_POST["max_offences"] : 10;
    $max_offences_reason = htmlspecialchars($_POST["max_offences_reason"] === "" ? "max offences reached" : $_POST["max_offences_reason"]);
    $max_file_size = (int)$_POST["max_file_size"];
    $file_type = htmlspecialchars($_POST["file_type"]);

    // Prepare update query
    $update_query = "
        UPDATE `" . $config->db_prefix . "_webconfig` SET 
        `cookie` = :cookie, 
        `design` = :design, 
        `bans_per_page` = :bans_per_page, 
        `banner` = :banner, 
        `banner_url` = :banner_url, 
        `default_lang` = :language, 
        `start_page` = :start_page, 
        `show_comment_count` = :show_comment_count, 
        `show_demo_count` = :show_demo_count, 
        `show_kick_count` = :show_kick_count, 
        `use_demo` = :use_demo, 
        `use_comment` = :use_comment, 
        `demo_all` = :demo_all, 
        `comment_all` = :comment_all, 
        `use_capture` = :use_capture, 
        `auto_prune` = :auto_prune, 
        `max_offences` = :max_offences, 
        `max_offences_reason` = :max_offences_reason, 
        `max_file_size` = :max_file_size, 
        `file_type` = :file_type 
        WHERE `id` = 1 LIMIT 1
    ";

    // Execute update query with prepared statement
    $stmt = $pdo->prepare($update_query);
    $stmt->execute([
        'cookie' => $cookie,
        'design' => $design,
        'bans_per_page' => $bans_per_page,
        'banner' => $banner,
        'banner_url' => $banner_url,
        'language' => $language,
        'start_page' => $start_page,
        'show_comment_count' => $show_comment_count,
        'show_demo_count' => $show_demo_count,
        'show_kick_count' => $show_kick_count,
        'use_demo' => $use_demo,
        'use_comment' => $use_comment,
        'demo_all' => $demo_all,
        'comment_all' => $comment_all,
        'use_capture' => $use_capture,
        'auto_prune' => $auto_prune,
        'max_offences' => $max_offences,
        'max_offences_reason' => $max_offences_reason,
        'max_file_size' => $max_file_size,
        'file_type' => $file_type
    ]);

    $user_msg = "_CONFIGSAVED";
    log_to_db("Websetting config", "Changed");

    // Set language
    $_SESSION["lang"] = $language;

    // Clear Smarty cache
    $smarty->clearCompiledTemplate();
}

// Fetch and set web settings
$vars = sql_set_websettings();

$smarty->assign("yesno_select", ["_YES", "_NO"]);
$smarty->assign("yesno_values", [1, 0]);
$smarty->assign("vars", $vars);
$smarty->assign("designs", $designs);
$smarty->assign("banners", $banners);
$smarty->assign("start_pages", $start_pages);
?>
