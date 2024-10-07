<?php

/*

    AMXBans v6.0

    Copyright 2009, 2010 by SeToY & |PJ|ShOrTy

    This file is part of AMXBans.

    AMXBans is free software, but it's licensed under the
    Creative Commons - Attribution-NonCommercial-ShareAlike 2.0

    AMXBans is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

    You should have received a copy of the cc-nC-SA along with AMXBans.
    If not, see <http://creativecommons.org/licenses/by-nc-sa/2.0/>.

*/

session_start();

if (!$_SESSION["loggedin"]) {
    header("Location: index.php");
    exit;
}
if (!has_access("bans_export") && !has_access("bans_import")) {
    header("Location: index.php");
    exit;
}

require_once("iexport_func/modul_iexport_dbbackup.php");

ob_start();

$modul_site = "iexport";
$title2 = "_TITLEIEXPORT";

// Download backup
if (isset($_POST["dbdownfile"])) {
    if (!has_access("bans_export") && !has_access("bans_import")) {
        header("Location: index.php");
        exit;
    }
    
    $file = basename($_POST["localfile"]);
    $filepath = "include/backup/" . $file;
    
    if (!file_exists($filepath)) {
        $user_msg = "_FILENOTAVAILABLE";
    } else {
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }
        header("Content-Type: application/download");
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        readfile($filepath);
        unset($_POST["dbdownfile"]);
    }
}

// Delete backup
if (isset($_POST["delfile"])) {
    if (!has_access("bans_export") && !has_access("bans_import")) {
        header("Location: index.php");
        exit;
    }

    $file = basename($_POST["localfile"]);
    $filepath = "include/backup/" . $file;

    if (file_exists($filepath) && is_file($filepath)) {
        if (unlink($filepath)) {
            $user_msg = "_FILEDELSUCCESS";
        } else {
            $user_msg = "_FILEDELFAILED";
        }
    } else {
        $user_msg = "_FILENOTFOUND";
    }
}

// Create .sql backup
if (isset($_POST["dbexp"])) {
    if (!has_access("bans_export") && !has_access("bans_import")) {
        header("Location: index.php");
        exit;
    }

    $type = isset($_POST["structur"]);
    $droptable = isset($_POST["droptable"]);
    $deleteall = isset($_POST["deleteall"]);
    $download = isset($_POST["download"]);

    $user_msg = db_backup($type, $droptable, $deleteall, $download, false);
}

// Create bans .sql backup
if (isset($_POST["dbbansexp"])) {
    if (!has_access("bans_export") && !has_access("bans_import")) {
        header("Location: index.php");
        exit;
    }

    $download = isset($_POST["download"]);
    $user_msg = db_backup(false, true, false, $download, true);
}

// Import banned.cfg
if (isset($_POST["bancfgupl"])) {
    if (!has_access("bans_export") && !has_access("bans_import")) {
        header("Location: index.php");
        exit;
    }

    $pdo = getPDO();
    $reason = $_POST["reason"];
    $plnick = $_POST["player_nick"];
    $server = $_POST["server_name"];
    $date = explode("-", trim($_POST["ban_created"]));

    if (empty($reason) || empty($plnick) || empty($server) || empty($date) || count($date) != 3) {
        $user_msg = "_NOREQUIREDFIELDS";
    } else {
        $date = strtotime($date[2] . $date[1] . $date[0]);
        $file = $_FILES['filename']['name'];
        $types = ["cfg", "txt"];

        if (empty($file)) {
            $user_msg = "_FILENOFILE";
        } else {
            $file_type = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($file_type, $types)) {
                $user_msg = "_FILETYPENOTALLOWED";
            }
        }

        if ($_FILES['filename']['size'] >= ($config->max_file_size * 1024 * 1024)) {
            $user_msg = "_FILETOBIG";
        }

        if (empty($user_msg)) {
            $temp_file = "temp/" . $file;
            if (!move_uploaded_file($_FILES['filename']['tmp_name'], $temp_file)) {
                $user_msg = "_FILEUPLOADFAIL";
            } else {
                $handle = fopen($temp_file, "r");
                $status["imported"] = 0;
                $status["failed"] = 0;

                while (!feof($handle)) {
                    $n = fgets($handle, 128);
                    $bans = explode(" ", $n);
                    $time = (int)trim($bans[1]);
                    $reason_real = !empty($bans[4]) ? implode(" ", array_slice($bans, 4)) : $reason;

                    if (trim($bans[0]) == "" || trim($bans[0]) == "//" || $time != 0) {
                        $status["failed"]++;
                        continue;
                    }

                    if (trim($bans[0]) == "banid") {
                        $steamid = trim($bans[2]);
                        if (!preg_match("/^STEAM_0:(0|1):[0-9]{1,18}$/", $steamid)) {
                            $status["failed"]++;
                            continue;
                        }

                        // Check if ban exists
                        $stmt = $pdo->prepare("SELECT `player_id` FROM `{$config->db_prefix}_bans` WHERE `player_id` = ? AND `expired` = 0");
                        $stmt->execute([$steamid]);
                        if ($stmt->rowCount()) {
                            $status["failed"]++;
                            continue;
                        }

                        // Insert ban
                        $stmt = $pdo->prepare("INSERT INTO `{$config->db_prefix}_bans` (`player_id`, `player_nick`, `admin_nick`, `ban_type`, `ban_reason`, `ban_created`, `ban_length`, `server_name`, `imported`) 
                            VALUES (?, ?, ?, 'S', ?, ?, ?, ?, 1)");
                        $stmt->execute([$steamid, $plnick, $_SESSION["uname"], $reason_real, $date, $time, $server]);
                        $status["imported"]++;
                    } elseif (trim($bans[0]) == "banip") {
                        // Similar logic for IP bans
                    }
                }

                fclose($handle);
                unlink($temp_file);
                $smarty->assign("status", $status);
            }
        }
    }
}

// Export banned.cfg
if (isset($_POST["bancfgexp"])) {
    if (!has_access("bans_export") && !has_access("bans_import")) {
        header("Location: index.php");
        exit;
    }

    $onlyperm = isset($_POST["onlyperm"]);
    $increason = isset($_POST["increason"]);
    $download = isset($_POST["download"]);

    $file = "temp/banned.cfg";
    if (file_exists($file)) {
        unlink($file);
    }

    $status["exported"] = 0;

    if ($handle = fopen($file, "w")) {
        $pdo = getPDO();
        $stmt = $pdo->query("SELECT `player_id`, `ban_length`, `ban_reason` FROM `{$config->db_prefix}_bans`" . ($onlyperm ? " WHERE `expired` = 0" : ""));
        
        while ($result = $stmt->fetch()) {
            $line = "banid " . $result['ban_length'] . ".0 " . trim($result['player_id']) . ($increason ? " // " . trim($result['ban_reason']) : "") . "\n";
            fputs($handle, $line);
            $status["exported"]++;
        }

        fclose($handle);
        $user_msg = "_EXPORTSUCCESS";
        $smarty->assign("statusexport", $status);

        if ($download) {
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
            header("Content-Type: application/download");
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            readfile($file);
            unset($_POST["download"]);
        }
    } else {
        $user_msg = "_EXPORTFAILED";
    }
}

// Search backups
$backups = [];
$count = 0;
$d = opendir($config->path_root . "/include/backup/");
while ($f = readdir($d)) {
    if ($f != "." && $f != ".." && !is_dir($config->path_root . "/backup/" . $f) && substr($f, -3) == "sql") {
        $backups[] = $f;
        $count++;
    }
}
closedir($d);

if (!empty($backups)) {
    rsort($backups);
}
$smarty->assign("backups", $backups);
$smarty->assign("count", $count);

// Find imported bans
$pdo = getPDO();
$stmt = $pdo->query("SELECT `bid` FROM `{$config->db_prefix}_bans` WHERE `imported` = 1");
$smarty->assign("importcount", $stmt->rowCount());

ob_end_flush();
?>