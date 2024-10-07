<?php
session_start();
ini_set("display_errors", 0);

// Include necessary files
require_once("include/config.inc.php");
require_once("include/access.inc.php");
require_once("include/menu.inc.php");
require_once("include/steam.inc.php");
require_once("include/sql.inc.php");
require_once("include/logfunc.inc.php");
require_once("include/functions.inc.php");
require_once("include/geoip.inc");
require_once("include/thumbs.inc.php");

// Create a new PDO connection
$pdo = new PDO("mysql:host={$config->db_host};dbname={$config->db_db}", $config->db_user, $config->db_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
]);

// Template generation
$title = "_TITLEBANLIST";
$smarty = new dynamicPage;
$smarty->setTemplateDir($config->templatedir);  

// Sanitize input
$bid = filter_input(INPUT_GET, 'bid', FILTER_VALIDATE_INT) ?? filter_input(INPUT_POST, 'bid', FILTER_VALIDATE_INT);

// Determine user site loader
$user_site = '';
if ($bid) {
    if (isset($_POST["details_x"])) {
        $user_site = 'bd'; // Ban details
    } elseif (isset($_POST["edit_x"])) {
        $user_site = 'be'; // Ban edit
    }

    // Include user specific file if exists
    if (file_exists("include/user/user_$user_site.php")) {
        include("include/user/user_$user_site.php");
    }
}

// Default ban list display
$ban_page = "";
if (!$user_site) {
    // Count active bans
    $stmt = $pdo->query("SELECT COUNT(bid) AS count FROM {$config->db_prefix}_bans WHERE expired = 0");
    $ban_count[0] = $stmt->fetch()->count;

    // Count all bans
    $stmt = $pdo->query("SELECT COUNT(bid) AS count FROM {$config->db_prefix}_bans");
    $ban_count[1] = $stmt->fetch()->count;

    // Calculate pagination
    $ban_page_max = ceil($ban_count[0] / $config->bans_per_page);
    $page = filter_input(INPUT_GET, 'site', FILTER_VALIDATE_INT) ?: 1;
    $page = min(max($page, 1), $ban_page_max);

    // Calculate limits for pagination
    $min = ($config->bans_per_page * $page) - $config->bans_per_page;
    $min = max(0, (int)$min);
    $ban_page = [
        "current" => $page,
        "max_page" => $ban_page_max ?: 1,
        "per_page" => $config->bans_per_page,
        "first_ban" => $ban_count[0] ? $min + 1 : $min,
        "max_ban" => $ban_count[0],
        "all_ban" => $ban_count[1]
    ];

    // Get bans for current page
    $stmt = $pdo->prepare("
        SELECT ba.*, se.gametype, se.timezone_fixx, aa.nickname 
        FROM {$config->db_prefix}_bans AS ba
        LEFT JOIN {$config->db_prefix}_serverinfo AS se ON ba.server_ip = se.address
        LEFT JOIN {$config->db_prefix}_amxadmins AS aa ON (aa.steamid = ba.admin_nick OR aa.steamid = ba.admin_ip OR aa.steamid = ba.admin_id)
        WHERE ba.expired = 0
        ORDER BY ba.ban_created DESC 
        LIMIT :min, :max
    ");
    $stmt->bindValue(':min', $min, PDO::PARAM_INT);
    $stmt->bindValue(':max', $config->bans_per_page, PDO::PARAM_INT);
    $stmt->execute();

    // Build ban list
    $ban_list = [];
    $gi = geoip_open($config->path_root . "/include/GeoIP.dat", GEOIP_STANDARD);
    
    while ($result = $stmt->fetch()) {
        $steamid = $result->player_id ? htmlentities($result->player_id, ENT_QUOTES) : "";
        $steamcomid = $steamid ? GetFriendId($steamid) : "";
        $cc = $cn = "";
        
        if ($result->player_ip) {
            $cc = geoip_country_code_by_addr($gi, $result->player_ip);
            $cn = geoip_country_name_by_addr($gi, $result->player_ip);
        }

        $ban_row = [
            "bid" => $result->bid,
            "player_ip" => $result->player_ip,
            "player_id" => $result->player_id,
            "player_comid" => $steamcomid,
            "player_nick" => htmlspecialchars($result->player_nick),
            "admin_ip" => $result->admin_ip,
            "admin_id" => $result->admin_id,
            "admin_nick" => htmlspecialchars($result->admin_nick),
            "ban_type" => $result->ban_type,
            "ban_reason" => htmlspecialchars($result->ban_reason),
            "ban_created" => ($result->ban_created + ($result->timezone_fixx * 60 * 60)),
            "ban_length" => $result->ban_length,
            "ban_end" => ($result->ban_created + ($result->ban_length * 60) + ($result->timezone_fixx * 60 * 60)),
            "server_ip" => $result->server_ip,
            "server_name" => htmlspecialchars($result->server_name),
            "cc" => $cc,
            "cn" => $cn
        ];

        // Get previous offenses
        $offense_stmt = $pdo->prepare("
            SELECT COUNT(*) AS count FROM {$config->db_prefix}_bans 
            WHERE ((player_id = :player_id AND ban_type = 'S') OR (player_ip = :player_ip AND ban_type = 'SI')) 
            AND expired = 1
        ");
        $offense_stmt->execute(['player_id' => $result->player_id, 'player_ip' => $result->player_ip]);
        $ban_row["bancount"] = $offense_stmt->fetch()->count;

        // Add ban_row to ban_list
        $ban_list[] = $ban_row;
    }

    geoip_close($gi);

    $smarty->assign("ban_list", $ban_list);
    $smarty->assign("ban_page", $ban_page);
}

// Ban deletion logic
if (isset($_POST["del_ban_x"]) && $bid) {
    if (!has_access("bans_edit")) {
        $smarty->assign("_ACCESSINVALID", "_ACCESSINVALID");
        header("Location:index.php");
        exit;
    }

    // Delete uploaded files related to the ban
    $stmt = $pdo->prepare("SELECT `id`, `demo_file` FROM {$config->db_prefix}_files WHERE `bid` = :bid");
    $stmt->execute(['bid' => $bid]);
    while ($file = $stmt->fetch()) {
        if (file_exists("include/files/" . $file->demo_file)) {
            unlink("include/files/" . $file->demo_file);
            if (file_exists("include/files/" . $file->demo_file . "_thumb")) {
                unlink("include/files/" . $file->demo_file . "_thumb");
            }
            $pdo->prepare("DELETE FROM {$config->db_prefix}_files WHERE `id` = :id")->execute(['id' => $file->id]);
        }
    }

    // Delete ban comments and ban itself
    $pdo->prepare("DELETE FROM {$config->db_prefix}_comments WHERE `bid` = :bid")->execute(['bid' => $bid]);
    $ban_row = sql_get_ban_details($bid);  // Make sure sql_get_ban_details uses PDO
    $pdo->prepare("DELETE FROM {$config->db_prefix}_bans WHERE `bid` = :bid LIMIT 1")->execute(['bid' => $bid]);

    log_to_db("Ban edit", "Deleted ban: ID $bid (<" . sql_safe($ban_row["player_nick"]) . "> <" . sql_safe($ban_row["player_id"]) . ">)");

    header("Location:index.php");
    exit;
}

// Render Smarty templates
$smarty->assign("meta", "");
$smarty->assign("title", $title);
$smarty->assign("version_web", $config->v_web);
$smarty->assign("menu", $menu);
$smarty->assign("this", $_SERVER['PHP_SELF']);

if (file_exists("templates/" . $config->design . "/main_header.tpl")) {
    $smarty->assign("design", $config->design);
}

$smarty->display('main_header.tpl');
if ($user_site !== "") {
    $smarty->display("user_$user_site.tpl");
} else {
    $smarty->display('ban_list.tpl');
}
$smarty->display('main_footer.tpl');
