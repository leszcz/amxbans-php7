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

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[-1]);
    $val = (int)$val;
    switch($last) {
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    return $val;
}

function is__writable($path) {
    if (substr($path, -1) == '/') {
        return is__writable($path . uniqid(mt_rand()) . '.tmp');
    } else if (is_dir($path)) {
        return is__writable($path . '/' . uniqid(mt_rand()) . '.tmp');
    }
    
    $rm = file_exists($path);
    $f = @fopen($path, 'a');
    if ($f === false)
        return false;
    
    fclose($f);
    if (!$rm)
        unlink($path);
    return true;
}

function sql_get_privilege($pdo) {
    $privileges = [];
    
    // Get the user grants from the SHOW GRANTS command
    $query = $pdo->query("SHOW GRANTS FOR CURRENT_USER()");
    $grants = $query->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($grants as $grant) {
        // Check if the user has ALL PRIVILEGES
        if (stristr($grant, "ALL PRIVILEGES")) {
            $privileges = ["SELECT", "INSERT", "UPDATE", "DELETE", "CREATE"];
            break;
        }
        
        // Parse the individual privileges
        if (stristr($grant, "SELECT")) $privileges[] = "SELECT";
        if (stristr($grant, "INSERT")) $privileges[] = "INSERT";
        if (stristr($grant, "UPDATE")) $privileges[] = "UPDATE";
        if (stristr($grant, "DELETE")) $privileges[] = "DELETE";
        if (stristr($grant, "CREATE")) $privileges[] = "CREATE";
    }
    
    // Now check specifically for database-level privileges in the information_schema
    $db = $pdo->quote($_SESSION["dbdb"]);
    
    $query = $pdo->query("
        SELECT PRIVILEGE_TYPE 
        FROM information_schema.SCHEMA_PRIVILEGES 
        WHERE GRANTEE LIKE " . $pdo->quote('%'.$_SESSION["dbuser"].'%') . " 
        AND TABLE_SCHEMA = $db
    ");
    
    while ($result = $query->fetch(PDO::FETCH_OBJ)) {
        $privileges[] = $result->PRIVILEGE_TYPE;
    }

    // Ensure unique privileges
    return array_unique($privileges);
}

function sql_connect() {
    try {
        $pdo = new PDO("mysql:host=" . $_SESSION["dbhost"] . ";dbname=" . $_SESSION["dbdb"], $_SESSION["dbuser"], $_SESSION["dbpass"]);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        return false;
    }
}

function sql_create_table($table, $structure) {
    try {
		$pdo = sql_connect();
        $query = $pdo->exec("CREATE TABLE `" . $table . "` (" . $structure . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        if ($query === false) {
            return $pdo->errorInfo()[2];
        }
        return "_CREATED";
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function sql_insert_data($table, $data) {
    try {
		$pdo = sql_connect();
        $query = $pdo->exec("INSERT INTO `" . $table . "` " . $data);
        if ($query === false) {
            return "_FAILED";
        }
        return "_INSERTED";
    } catch (PDOException $e) {
        return "_FAILED";
    }
}

function sql_insert_setting($sqlquery) {
    try {
		$pdo = sql_connect();
        $query = $pdo->exec($sqlquery);
        if ($query === false) {
            return "_FAILED";
        }
        return "_INSERTED";
    } catch (PDOException $e) {
        return "_FAILED";
    }
}

function write_cfg_file($file, $content) {
    if (!file_exists($file)) {
        if ($fh = fopen($file, "w")) {
            fputs($fh, $content);
            fclose($fh);
            return "_FILESUCCESS";
        } else {
            return "_FILEOPENERROR";
        }
    } else {
        return "_FILEEXISTS";
    }
}