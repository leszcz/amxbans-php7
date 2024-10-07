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

function db_backup($structur, $droptable, $deleteall, $download, $bansonly): void {
    global $config;

    $pdo = getPDO();
    $datei = "########################################\n"; 
    $datei .= "#\n"; 
    $datei .= "# AMXBans backup: " . date("d.m.Y H:i:s") . "\n"; 
    $datei .= "#\n";
    $datei .= "########################################\n"; 
    $datei .= "\n\n"; 

    // get all tables from database
    if (!$bansonly) {
        $tables = [];
        $stmt = $pdo->query("SHOW TABLES");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
    } else {
        $tables[] = $config->db_prefix . "_bans";
    }

    // perform actions on all tables
    foreach ($tables as $tab) {
        $datei .= "########################################\n"; 
        $datei .= "# table: " . $tab . "\n"; 
        $datei .= "########################################\n"; 
        $datei .= "\n"; 

        // Drop table if requested
        if ($droptable == true) { 
            $datei .= "DROP TABLE IF EXISTS `" . $tab . "`;\n\n"; 
        }

        // get table structure
        $datei .= "CREATE TABLE IF NOT EXISTS `" . $tab . "` (\n"; 
        $stmt = $pdo->query("DESCRIBE " . $tab);
        $num = $stmt->rowCount();
        $end = 0; 
        while ($info = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            $tab_name = $info["Field"]; 
            $tab_type = $info["Type"]; 
            $tab_null = ($info["Null"] == "NO") ? " NOT NULL" : " NULL"; 
            $tab_default = !is_null($info["Default"]) ? " DEFAULT '" . $info["Default"] . "'" : ""; 
            $tab_extra = !empty($info["Extra"]) ? " " . $info["Extra"] : ""; 
            $end++; 
            $tab_komma = ($end < $num) ? ",\n" : ""; 
            $datei .= " `" . $tab_name . "` " . $tab_type . $tab_null . $tab_default . $tab_extra . $tab_komma; 
        }

        // get keys from table
        $keyarray = [];
        $stmt = $pdo->query("SHOW KEYS FROM " . $tab);
        while ($info = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            $keyname = $info["Key_name"]; 
            if ($keyname != "PRIMARY" && $info["Non_unique"] == 0) { 
                $keyname = "UNIQUE|" . $keyname; 
            }
            if (!isset($keyarray[$keyname])) { 
                $keyarray[$keyname] = []; 
            } 
            $keyarray[$keyname][] = $info["Column_name"]; 
        }

        // save keys to file
        if (!empty($keyarray)) { 
            foreach ($keyarray as $keyname => $columns) { 
                $datei .= ",\n"; 
                if ($keyname == "PRIMARY") { 
                    $datei .= "PRIMARY KEY ("; 
                } elseif (strpos($keyname, "UNIQUE") === 0) { 
                    $datei .= "UNIQUE " . substr($keyname, 7) . " ("; 
                } else { 
                    $datei .= "KEY " . $keyname . " ("; 
                }
                $datei .= implode(", ", $columns) . ")"; 
            }
        }

        $datei .= ");\n\n"; 

        // Backup table if table has only structure
        if ($structur == false) {
            if ($deleteall == true) { 
                $datei .= "DELETE FROM `" . $tab . "`;\n\n"; 
            }

            // fetch all data from table
            $stmt = $pdo->query("SELECT * FROM `" . $tab . "`");
            while ($info = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                $fieldnames = "`" . implode("`, `", array_keys($info)) . "`"; 
                $values = "'" . implode("', '", array_map([$pdo, 'quote'], array_values($info))) . "'";
                $datei .= "INSERT INTO `" . $tab . "` (" . $fieldnames . ") VALUES (" . $values . ");\n"; 
            }
            $datei .= "\n\n"; 
        } 
    }

    // save backup as file
    $file_local = "include/backup/" . date("Y-m-d_H-i-s") . (($bansonly) ? "_bans" : "") . ".sql";
    if (file_put_contents($file_local, $datei) !== false) {
        if ($download == true) {
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }

            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Disposition: attachment; filename="' . basename($file_local) . '"'); 
            header('Content-Type: application/octet-stream');
            header('Content-Length: ' . filesize($file_local));
            header('Pragma: public');
            readfile($file_local);
        }
        return "_BACKUPSUCCESS";
    } else { 
        return "_BACKUPFAILNOFILE";
    }
}