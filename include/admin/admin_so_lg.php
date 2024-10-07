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

$admin_site = "lg";
$title2 = "_TITLELOGS";

// Uzyskanie obiektu PDO dla bazy danych
$pdo = getPDO();

// Usuwanie logów
if (isset($_POST["delall"])) {
    if (!has_access("websettings_view")) {
        header("Location:index.php");
        exit;
    }
    $query = $pdo->prepare("DELETE FROM `{$config->db_prefix}_logs`");
    $query->execute();
    $user_msg = "_LOGDELETED";
    log_to_db("Logs del", "Deleted all logs");
}

if (isset($_POST["delolder"])) {
    if (!has_access("websettings_view")) {
        header("Location:index.php");
        exit;
    }
    $days = (int)$_POST["days"];
    $query = $pdo->prepare("DELETE FROM `{$config->db_prefix}_logs` WHERE UNIX_TIMESTAMP(NOW()) - `timestamp` > :time_limit");
    $query->execute([':time_limit' => $days * 84600]);
    $user_msg = "_LOGDELETED";
    log_to_db("Logs del", "Deleted logs older than {$days} days");
}

// Pobranie logów z filtrowaniem
$filter = [];
$filterQuery = "";

if (isset($_POST["username"]) && $_POST["username"] !== "---") {
    if (!has_access("websettings_view")) {
        header("Location:index.php");
        exit;
    }
    $username = $_POST["username"];
    $filter[] = "`username` = :username";
    $smarty->assign("username_checked", $username);
}

if (isset($_POST["action"]) && $_POST["action"] !== "---") {
    if (!has_access("websettings_view")) {
        header("Location:index.php");
        exit;
    }
    $action = $_POST["action"];
    $filter[] = "`action` = :action";
    $smarty->assign("action_checked", $action);
}

if ($filter) {
    $filterQuery = "WHERE " . implode(" AND ", $filter);
}

$logsQuery = $pdo->prepare("SELECT * FROM `{$config->db_prefix}_logs` {$filterQuery}");
$params = [];

if (isset($username)) {
    $params[':username'] = $username;
}
if (isset($action)) {
    $params[':action'] = $action;
}
$logsQuery->execute($params);
$logs = $logsQuery->fetchAll(PDO::FETCH_ASSOC);

$smarty->assign("logs", $logs);

// Pobranie wszystkich nazw użytkowników
$usernames = ["---" => "---"];
$userQuery = $pdo->query("SELECT DISTINCT `username` FROM `{$config->db_prefix}_logs` ORDER BY `id`");

while ($result = $userQuery->fetch(PDO::FETCH_OBJ)) {
    if ($result->username !== "") {
        $usernames[html_safe($result->username)] = html_safe($result->username);
    }
}
$smarty->assign("usernames", $usernames);

// Pobranie wszystkich akcji
$actions = ["---" => "---"];
$actionQuery = $pdo->query("SELECT DISTINCT `action` FROM `{$config->db_prefix}_logs` ORDER BY `id`");

while ($result = $actionQuery->fetch(PDO::FETCH_OBJ)) {
    if ($result->action !== "") {
        $actions[html_safe($result->action)] = html_safe($result->action);
    }
}
$smarty->assign("actions", $actions);
?>
