<?php
/**
    Moduł pozwalający na łatwy import listy adminów z pliku users.ini
    do bazy danych z bezpośrednim przypisaniem adminów do serwerów.
	Edit by l3szcz: przerobienie funkcji mysql* na PDO
	
    @author Portek <admin@portek.net.pl>
	@author l3szcz <admin@gameslot.pl>
    @url http://cserwerek.pl/user/2-michal/ AMXX.PL::Portek
    @url http://amxx.pl/user/509-portek/ CSERWEREK.PL::Portek
    @license http://creativecommons.org/licenses/by-nc-sa/3.0/deed.pl CreativeCommons BY-NC-SA
    @version 1.3.0
*/

session_start();

if (!$_SESSION["loggedin"]) {
    header("Location: index.php");
    exit;
}

if (!has_access("bans_import")) {
    header("Location: index.php");
    exit;
}

ob_start();

$modul_site = "usersi";
$title2 = "Import adminów z users.ini";

function validateSID($steamid) {
    if (strlen(trim($steamid)) > 0) {
        $regex = "/^STEAM_0:[01]:[0-9]{7,8}$/";
        return preg_match($regex, $steamid) ? $steamid : null;
    }
    return null;
}

$pdo = getPDO();  // get PDO instance from sql.inc.php 

$stmt = $pdo->query("SELECT id, hostname FROM `{$config->db_prefix}_serverinfo`");
$serwery = $stmt->fetchAll(PDO::FETCH_ASSOC);

$smarty->assign("serwery", $serwery);

if (isset($_POST['usersImport'])) {
    if (!has_access("bans_import")) {
        header("Location: index.php");
        exit;
    }

    $serwerID = (int)$_POST['serverID'];
    $maxFileSize = $config->max_file_size * 1024 * 1024;

    if ($_FILES['usersFile']['size'] >= $maxFileSize) {
        $user_msg = "_FILETOBIG";
    }

    if (!$user_msg) {
        $filePath = "temp/" . basename($_FILES['usersFile']['name']);
        
        if (!move_uploaded_file($_FILES['usersFile']['tmp_name'], $filePath)) {
            $user_msg = "_FILEUPLOADFAIL";
        } else {
            if ($fh = fopen($filePath, "r")) {
                $content = [];
                while (!feof($fh)) {
                    $content[] = fgets($fh, 9999);
                }
                fclose($fh);

                $admini = [];
                foreach ($content as $line) {
                    if (!preg_match('/^;/', $line)) {
                        $dane = explode('"', $line);
                        $sid = validateSID($dane[1]) ?: null;

                        if (!empty($dane[1]) && !empty($dane[5]) && !empty($dane[7])) {
                            $prawa = $dane[9] ? 'yes' : $_POST['isStatic'];
                            $admini[] = [
                                'id' => $dane[1],
                                'sid' => $sid,
                                'pw' => !empty($dane[3]) ? md5($dane[3]) : '',
                                'flags' => $dane[5],
                                'access' => $dane[7],
                                'static' => $prawa
                            ];
                        }
                    }
                }

                foreach ($admini as $admin) {
                    // Check if admin exists
                    $checkQuery = "SELECT id FROM `{$config->db_prefix}_amxadmins` WHERE `steamid` = :sid OR `nickname` = :nickname";
                    $stmt = $pdo->prepare($checkQuery);
                    $stmt->execute([':sid' => $admin['sid'], ':nickname' => $admin['id']]);
                    $accIsset = $stmt->fetchColumn();

                    if (!$accIsset) {
                        // Get admin last ID
                        $stmt = $pdo->query("SELECT MAX(id) FROM `{$config->db_prefix}_amxadmins`");
                        $IDAdmin = $stmt->fetchColumn() + 1;

                        // insert new admin to database
                        $insertAdminQuery = "INSERT INTO `{$config->db_prefix}_amxadmins` 
                            (id, username, password, access, flags, steamid, nickname, ashow, created, expired, days)
                            VALUES (:id, :username, :password, :access, :flags, :steamid, :nickname, 1, :created, 0, 0)";
                        $stmt = $pdo->prepare($insertAdminQuery);
                        $stmt->execute([
                            ':id' => $IDAdmin,
                            ':username' => $admin['id'],
                            ':password' => $admin['pw'],
                            ':access' => $admin['access'],
                            ':flags' => $admin['flags'],
                            ':steamid' => $admin['sid'],
                            ':nickname' => $admin['id'],
                            ':created' => time()
                        ]);

                        // assign admin to server
                        $insertServerQuery = "INSERT INTO `{$config->db_prefix}_admins_servers` 
                            (admin_id, server_id, custom_flags, use_static_bantime) 
                            VALUES (:admin_id, :server_id, '', :static)";
                        $stmt = $pdo->prepare($insertServerQuery);
                        $stmt->execute([
                            ':admin_id' => $IDAdmin,
                            ':server_id' => $serwerID,
                            ':static' => $admin['static']
                        ]);
                    } else {
                        // check if admin is assigned to server
                        $checkServerQuery = "SELECT COUNT(*) FROM `{$config->db_prefix}_admins_servers` WHERE admin_id = :admin_id AND server_id = :server_id";
                        $stmt = $pdo->prepare($checkServerQuery);
                        $stmt->execute([':admin_id' => $accIsset, ':server_id' => $serwerID]);
                        if ($stmt->fetchColumn() == 0) {
                            // assign exists admin to new server
                            $insertServerQuery = "INSERT INTO `{$config->db_prefix}_admins_servers` 
                                (admin_id, server_id, custom_flags, use_static_bantime) 
                                VALUES (:admin_id, :server_id, '', :static)";
                            $stmt = $pdo->prepare($insertServerQuery);
                            $stmt->execute([
                                ':admin_id' => $accIsset,
                                ':server_id' => $serwerID,
                                ':static' => $admin['static']
                            ]);
                        }
                    }
                }
                unlink($filePath);
                $user_msg = "Operacja zakończona sukcesem!";
            }
        }
    }
}

ob_end_flush();
?>