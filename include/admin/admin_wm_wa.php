<?php
session_start();
if (!$_SESSION["loggedin"]) {
    header("Location:index.php");
    exit;
}

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

if (!has_access("amxadmins_view")) {
    header("Location:index.php");
    exit;
}

$admin_site = "wa";
$title2 = "_TITLEWEBADMIN";

// Connect to the database using PDO
try {
    $pdo = new PDO("mysql:host=" . $config->db_host . ";dbname=" . $config->db_db, $config->db_user, $config->db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if UID is provided
$uid = isset($_POST["uid"]) && is_numeric($_POST["uid"]) ? (int)$_POST["uid"] : "";

// Fetch levels from the database
$levels = [];
$stmt = $pdo->query("SELECT `level` FROM `" . $config->db_prefix . "_levels` ORDER BY `level`");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $levels[] = $row['level'];
}

// Function to check if an admin with the same nickname or email exists
function checkAdmin($pdo, $nickname, $email)
{
    global $config;
    $stmt = $pdo->prepare("SELECT * FROM `" . $config->db_prefix . "_webadmins` WHERE `username` = :nickname OR `email` = :email");
    $stmt->execute(['nickname' => $nickname, 'email' => $email]);
    return $stmt->rowCount() > 0;
}

// Save or create a new web admin
if (isset($_POST["save"]) || isset($_POST["new"])) {
    if (!has_access("amxadmins_view")) {
        header("Location:index.php");
        exit;
    }
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $user_msg = "_INVALID_EMAIL";
    }
}

// Change password
if (isset($_POST["setnewpw"])) {
    if (!has_access("amxadmins_view")) {
        header("Location:index.php");
        exit;
    }

    $newpw = $_POST["newpw"];
    if (strlen($newpw) < 4) {
        $user_msg = "_PASSWORD_TOO_SHORT";
    }

    if (!$user_msg) {
        $hashed_pw = password_hash($newpw, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE `" . $config->db_prefix . "_webadmins` SET `password` = :password WHERE `id` = :uid LIMIT 1");
        if ($stmt->execute(['password' => $hashed_pw, 'uid' => $uid])) {
            log_to_db("Webadmin config", "Edited user: " . htmlspecialchars($_POST["name"]) . " (id: " . $uid . ") changed password");

            // Send an email notification to the user
            $to = $_POST["email"];
            $subject = 'AMXBans: Your login has changed';
            $msg = 'Your account password has been changed by ' . $_SESSION["uname"] . '. Your new password is: ' . $newpw;
            $headers = 'From: ' . $_SESSION["email"] . "\r\n" . 'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $msg, $headers);

            // Log the user out if their own password was changed
            if ($_SESSION["uname"] == $_POST["name"]) {
                header("Location: logout.php");
                exit;
            }
        } else {
            $user_msg = "_PASSWORD_CHANGE_FAILED";
        }
    }
}

// Save web admin changes
if (isset($_POST["save"])) {
    if (!has_access("amxadmins_view")) {
        header("Location:index.php");
        exit;
    }

    if (!$user_msg) {
        $stmt = $pdo->prepare("UPDATE `" . $config->db_prefix . "_webadmins` SET 
                `username` = :name, `level` = :level, `email` = :email, `logcode` = '' 
                WHERE `id` = :uid LIMIT 1");
        if ($stmt->execute([
            'name' => $name,
            'level' => (int)$_POST["level"],
            'email' => $email,
            'uid' => $uid
        ])) {
            $user_msg = "_WADMINSAVED";
            log_to_db("Webadmin config", "Edited user: " . htmlspecialchars($_POST["name"]) . " (id: " . $uid . ")");
        } else {
            $user_msg = "_WEBADMIN_SAVE_FAILED";
        }
    }
}

// Delete a web admin
if (isset($_POST["del"])) {
    if (!has_access("amxadmins_view")) {
        header("Location:index.php");
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM `" . $config->db_prefix . "_webadmins` WHERE `id` = :uid LIMIT 1");
    if ($stmt->execute(['uid' => $uid])) {
        $user_msg = "_WADMINDELETED";
        log_to_db("Webadmin config", "Deleted user: " . htmlspecialchars($_POST["name"]));
    }
}

// Add a new web admin
if (isset($_POST["new"])) {
    $pw = $_POST["pw"];
    $pw2 = $_POST["pw2"];
    $level = (int)$_POST["level"];

    if ($pw !== $pw2) {
        $user_msg = "_PASSWORDNOTMATCH";
    }

    if (checkAdmin($pdo, $name, $email)) {
        $user_msg = "_WADMINADDEDFAILED";
    }

    if (!$user_msg) {
        $hashed_pw = password_hash($pw, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO `" . $config->db_prefix . "_webadmins` (`username`, `password`, `level`, `email`) 
                               VALUES (:name, :password, :level, :email)");
        if ($stmt->execute(['name' => $name, 'password' => $hashed_pw, 'level' => $level, 'email' => $email])) {
            $user_msg = "_WADMINADDED";
            log_to_db("User Level config", "Added user: " . htmlspecialchars($_POST["name"]) . " (level " . $level . ")");
        } else {
            $user_msg = "_WADMINADDEDFAILED";
        }
    }
}

// Fetch all web admins
$users = sql_get_webadmins($pdo);

// Assign variables to Smarty template
$smarty->assign("users", $users);
$smarty->assign("levels", $levels);
?>
