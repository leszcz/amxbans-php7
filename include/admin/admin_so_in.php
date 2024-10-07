<?php
session_start();
if (!$_SESSION["loggedin"]) {
    header("Location:index.php");
    exit;
}

$admin_site = "in";
$title2 = "_TITLEINFO";

$pdo = getPDO();

// optimize database tables
if (isset($_POST["optimize"])) {
    if (!has_access("prune_db")) {
        header("Location:index.php");
        exit;
    }

    $query = $pdo->prepare("SHOW TABLES FROM `" . $config->db_db . "` LIKE :prefix");
    $query->execute([':prefix' => $config->db_prefix . "_%"]);
    $tables = [];
    while ($result = $query->fetch(PDO::FETCH_NUM)) {
        $tables[] = "`" . $result[0] . "`";
    }

    if ($tables) {
        $optimizeQuery = $pdo->prepare("OPTIMIZE TABLE " . implode(',', $tables));
        $optimizeQuery->execute();
        $user_msg = "_DBOPTIMIZED";
    }
}

// truncate old bans
$prunecount = -1;
if (isset($_POST["prunedb"])) {
    if (!has_access("prune_db")) {
        header("Location:index.php");
        exit;
    }

    $query = $pdo->query(
        "SELECT ba.bid, ba.ban_created, ba.ban_length, se.timezone_fixx 
         FROM {$config->db_prefix}_bans AS ba 
         LEFT JOIN {$config->db_prefix}_serverinfo AS se ON ba.server_ip = se.address 
         WHERE ba.expired = 0"
    );

    $prunecount = 0;
    while ($result = $query->fetch(PDO::FETCH_OBJ)) {
        if (($result->ban_created + ($result->timezone_fixx * 60 * 60) + ($result->ban_length * 60)) < time() && $result->ban_length != "0") {
            $prunecount++;
            $pruneQuery = $pdo->prepare(
                "UPDATE {$config->db_prefix}_bans SET expired = 1 WHERE bid = :bid"
            );
            $pruneQuery->execute([':bid' => $result->bid]);

            $pruneInsert = $pdo->prepare(
                "INSERT INTO {$config->db_prefix}_bans_edit 
                (bid, edit_time, admin_nick, edit_reason) 
                VALUES (:bid, :edit_time, 'amxbans', 'Bantime expired')"
            );
            $pruneInsert->execute([
                ':bid' => $result->bid,
                ':edit_time' => ($result->ban_created + ($result->timezone_fixx * 60 * 60) + ($result->ban_length * 60))
            ]);
        }
    }

    $smarty->assign("prunecount", $prunecount);
    $user_msg = "_DBPRUNED";
}

// convert size to bytes
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val) - 1]);
    $num = floatval($val);  // Extract the numeric part

    switch ($last) {
        case 'g':
            $num *= 1024;
        case 'm':
            $num *= 1024;
        case 'k':
            $num *= 1024;
    }
    return $num;
}

$gd = gd_info();
$gd_version = $gd["GD Version"];
$php_settings = [
    "display_errors" => ini_get('display_errors') ?: "off",
    "register_globals" => ini_get('register_globals') == 1 ? "_ON" : "_OFF",
    "magic_quotes_gpc" => ( function_exists('get_magic_quotes_gpc') ? "_ON" : "_OFF"),
    "safe_mode" => ini_get('safe_mode') == 1 ? "_ON" : "_OFF",
    "post_max_size" => ini_get('post_max_size') . " (" . return_bytes(ini_get('post_max_size')) . " bytes)",
    "upload_max_filesize" => ini_get('upload_max_filesize') . " (" . return_bytes(ini_get('upload_max_filesize')) . " bytes)",
    "max_execution_time" => ini_get('max_execution_time'),
    "version_php" => phpversion(),
    "mysql_version" => $pdo->getAttribute(PDO::ATTR_CLIENT_VERSION),
    "bcmath" => extension_loaded('bcmath') ? "_YES" : "_NO",
    "gmp" => extension_loaded('gmp') ? "_YES" : "_NO",
    "gd" => extension_loaded('gd') ? "_YES" : "_NO",
    "version_gd" => $gd_version
];
$smarty->assign("php_settings", $php_settings);

// clear Smarty cache
if (isset($_POST["clear"])) {
    if (!has_access("prune_db")) {
        header("Location: index.php");
        exit;
    }
    $smarty->clearCompiledTemplate();
    $user_msg = "_CACHEDELETED";
}

// repair files in database
if (isset($_POST["file_repair"])) {
    $repaired = sql_get_files_count_fail(1);
}

// repair comments in database
if (isset($_POST["comment_repair"])) {
    $repaired = sql_get_comments_count_fail(1);
}

// calc database size
function db_size($name, $prefix) {
    global $pdo;

    // Properly escape the prefix for safety
    $prefix = $pdo->quote($prefix . "_%");

    // Build the query string dynamically with the escaped prefix
    $query = "SHOW TABLE STATUS FROM `" . $name . "` LIKE " . $prefix;

    // Execute the query
    $stmt = $pdo->query($query);

    $db_size = 0;
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $db_size += $result["Data_length"] + $result["Index_length"];
    }

    return $db_size ? $db_size : "_NOTAVAILABLE";
}

// format size to correct units
function format_size($size) {
    if ($size == "_NOTAVAILABLE") {
        return "NOTAVAILABLE";
    }
    if ($size >= 1073741824) {
        return round(($size / 1073741824), 2) . "GB";
    } elseif ($size >= 1048576) {
        return round(($size / 1048576), 2) . "MB";
    } elseif ($size >= 1024) {
        return round(($size / 1024), 2) . " KB";
    } else {
        return $size . " Byte";
    }
}

$smarty->assign("bans", ["count" => sql_get_bans_count(0), "activ" => sql_get_bans_count(1)]);
$smarty->assign("db_size", format_size(db_size($config->db_db, $config->db_prefix)));
$smarty->assign("auto_prune", $config->auto_prune);
$smarty->assign("comment_count", ["count" => sql_get_comments_count(0), "fail" => sql_get_comments_count_fail(0)]);
$smarty->assign("file_count", ["count" => sql_get_files_count(0), "fail" => sql_get_files_count_fail(0)]);
$smarty->assign("msg", $user_msg);
?>
