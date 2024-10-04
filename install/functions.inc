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
    // will work in despite of Windows ACLs bug
    // NOTE: use a trailing slash for folders!!!
    if (substr($path, -1) == '/') // recursively return a temporary file path
        return is__writable($path . uniqid(mt_rand()) . '.tmp');
    else if (is_dir($path))
        return is__writable($path . '/' . uniqid(mt_rand()) . '.tmp');
    
    // check tmp file for read/write capabilities
    $rm = file_exists($path);
    $f = @fopen($path, 'a');
    if ($f === false)
        return false;
    
    fclose($f);
    if (!$rm)
        unlink($path);
    return true;
}

function sql_get_privilege($mysql) {
    $privileges = [];
    // global privileges
    $query = mysqli_query($mysql, "SHOW GRANTS FOR CURRENT_USER()") or die(mysqli_error($mysql));
    $array = mysqli_fetch_assoc($query);
    foreach ($array as $v) {
        if (stristr($v, "ALL PRIVILEGES")) {
            $privileges = ["SELECT", "INSERT", "UPDATE", "DELETE", "CREATE"];
            break;
        }
        if (stristr($v, "SELECT")) $privileges[] = "SELECT";
        if (stristr($v, "INSERT")) $privileges[] = "INSERT";
        if (stristr($v, "UPDATE")) $privileges[] = "UPDATE";
        if (stristr($v, "DELETE")) $privileges[] = "DELETE";
        if (stristr($v, "CREATE")) $privileges[] = "CREATE";
    }
    // database privileges  
    mysqli_select_db($mysql, "INFORMATION_SCHEMA") or die(mysqli_error($mysql));
    $db = mysqli_real_escape_string($mysql, $_SESSION["dbdb"]);
    // fix for plesk
    $db2 = str_replace("_", "\_", $db);
    $query = mysqli_query($mysql, "SELECT * FROM `SCHEMA_PRIVILEGES` WHERE `GRANTEE` LIKE '\'" . ($_SESSION["dbuser"]) . "\'%' AND (`TABLE_SCHEMA`='" . $db . "' OR `TABLE_SCHEMA`='" . $db2 . "' OR `TABLE_SCHEMA`='mysql')") or die(mysqli_error($mysql));
    while ($result = mysqli_fetch_object($query)) {
        $privileges[] = $result->PRIVILEGE_TYPE;
    }
    return array_unique($privileges);
}

function sql_connect() {
    $mysql = @mysqli_connect($_SESSION["dbhost"], $_SESSION["dbuser"], $_SESSION["dbpass"], $_SESSION["dbdb"]);
    if (!$mysql) {
        return false;
    }
    return true;
}

function sql_create_table($table, $structure) {
    $mysql = @mysqli_connect($_SESSION["dbhost"], $_SESSION["dbuser"], $_SESSION["dbpass"], $_SESSION["dbdb"]);
    $query = @mysqli_query($mysql, "CREATE TABLE `" . $table . "` (" . $structure . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    if (!$query) return mysqli_error($mysql);
    return "_CREATED";
}

function sql_insert_data($table, $data) {
    $mysql = @mysqli_connect($_SESSION["dbhost"], $_SESSION["dbuser"], $_SESSION["dbpass"], $_SESSION["dbdb"]);
    $query = @mysqli_query($mysql, "INSERT INTO `" . $table . "` " . $data);
    if (!$query) return "_FAILED";
    return "_INSERTED";
}

function sql_insert_setting($sqlquery) {
    $mysql = @mysqli_connect($_SESSION["dbhost"], $_SESSION["dbuser"], $_SESSION["dbpass"], $_SESSION["dbdb"]);
    $query = @mysqli_query($mysql, $sqlquery);
    if (!$query) return "_FAILED";
    return "_INSERTED";
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