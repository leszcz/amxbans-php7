<?php
session_start();

if ($_SESSION["loggedin"]) {
    header("Location:index.php");
    exit;
}

require_once("include/config.inc.php");
require_once("include/functions.inc.php");

$max_trys = 3;  // Max tries before user is blocked
$max_trys_block = 10;  // Minutes to block login after max tries wrong logins

// Connect to the database using PDO
try {
    $pdo = new PDO("mysql:host=" . $config->db_host . ";dbname=" . $config->db_db, $config->db_user, $config->db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_POST["action"])) {
    $uname = filter_var(trim($_POST["user"]), FILTER_SANITIZE_STRING);
    $upass = filter_var(trim($_POST["pass"]), FILTER_SANITIZE_STRING);

    if (!$uname || !$upass) {
        $_SESSION["loginfailed"]++;
        $msg = "_LOGINFAILED";
    } else {
        // Check if the username exists
        $stmt = $pdo->prepare("SELECT id, password, try, last_action, level, email FROM `" . $config->db_prefix . "_webadmins` WHERE username = :uname LIMIT 1");
        $stmt->execute(['uname' => $uname]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $try = $user['try'];
            $last_action = $user['last_action'];

            // Check if the account is blocked
            if ($try >= $max_trys && (time() < ($last_action + $max_trys_block * 60))) {
                $msg = "_LOGINBLOCKED";
                $block_left = $last_action + ($max_trys_block * 60) - time();
                $loginblocked = true;
            } else {
                // Check password
                if (password_verify($upass, $user['password'])) {
                    $_SESSION["loginfailed"] = 0;

                    // Set remember me cookie if selected
                    if ($_POST["remember"] === "yes") {
                        setcookie($config->cookie, session_id() . ":" . $_SESSION["lang"], time() + (60 * 60 * 24 * 7), "/", $_SERVER["HTTP_HOST"]);
                    }

                    // Initialize session variables
                    $_SESSION["uid"] = $user['id'];
                    $_SESSION["uname"] = $uname;
                    $_SESSION["email"] = $user['email'];
                    $_SESSION["level"] = $user['level'];
                    $_SESSION["sid"] = session_id();
                    $_SESSION["loggedin"] = true;

                    // Fetch permissions for the user level
                    $stmt = $pdo->prepare("SELECT * FROM `" . $config->db_prefix . "_levels` WHERE level = :level LIMIT 1");
                    $stmt->execute(['level' => $user['level']]);
                    $permissions = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Assign permissions to session
                    foreach ($permissions as $perm => $value) {
                        $_SESSION[$perm] = $value;
                    }

                    // Update login details in the database
                    $stmt = $pdo->prepare("UPDATE `" . $config->db_prefix . "_webadmins` SET `logcode` = :sid, `last_action` = UNIX_TIMESTAMP(), `try` = 0 WHERE `id` = :id");
                    $stmt->execute(['sid' => session_id(), 'id' => $_SESSION["uid"]]);

                    header("Location:index.php");
                    exit;
                } else {
                    $_SESSION["loginfailed"]++;
                    $try++;

                    // Log failed login
                    require_once("include/logfunc.inc.php");
                    log_to_db("Login failed", ($try == $max_trys) ? "login blocked (" . $max_trys_block . " minutes)" : "login failed (try: " . $try . "/" . $max_trys . ")");

                    $msg = "_LOGINFAILEDPW";
                    $loginfailed = true;

                    // Update the number of failed attempts and possibly block
                    if ($try < $max_trys) {
                        $stmt = $pdo->prepare("UPDATE `" . $config->db_prefix . "_webadmins` SET `try` = :try, `logcode` = NULL WHERE username = :uname LIMIT 1");
                    } else {
                        $stmt = $pdo->prepare("UPDATE `" . $config->db_prefix . "_webadmins` SET `try` = :try, `logcode` = NULL, `last_action` = UNIX_TIMESTAMP() WHERE username = :uname LIMIT 1");
                        $msg = "_LOGINBLOCKED";
                        $block_left = $max_trys_block * 60;
                        $loginblocked = true;
                    }
                    $stmt->execute(['try' => $try, 'uname' => $uname]);
                }
            }
        } else {
            $_SESSION["loginfailed"]++;
            $msg = "_LOGINFAILED";
        }
    }
}

require_once("include/menu.inc.php");

// Template parsing
$title = "_TITLELOGIN";
$section = "login";

$smarty = new dynamicPage;
$smarty->setTemplateDir($config->templatedir);
$smarty->assign("meta", "");
$smarty->assign("title", $title);
$smarty->assign("section", $section);
$smarty->assign("banner", $config->banner);
$smarty->assign("banner_url", $config->banner_url);
$smarty->assign("version_web", $config->v_web);
$smarty->assign("dir", $config->document_root);
$smarty->assign("this", $_SERVER['PHP_SELF']);
$smarty->assign("menu", $menu);
$smarty->assign("msg", $msg);

if ($loginblocked) {
    $smarty->assign("block_left", $block_left);
} else if ($loginfailed) {
    $smarty->assign("try", $try);
}

if (file_exists("templates/" . $config->design . "/main_header.tpl")) {
    $smarty->assign("design", $config->design);
}

$smarty->display('main_header.tpl');
$smarty->display('login.tpl');
$smarty->display('main_footer.tpl');
?>
