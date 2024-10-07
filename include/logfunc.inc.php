<?php
declare(strict_types=1);

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

require_once 'sql.inc.php';

/**
 * Logs an action to the database
 *
 * @param string $action The action to log
 * @param string $remarks Additional remarks about the action
 * @throws PDOException If there's an error with the database operation
 */
function log_to_db(string $action, string $remarks): void {
    global $config;
    
    try {
        $pdo = getPDO();
        
        $query = "INSERT INTO `" . $config->db_prefix . "_logs` (
            `timestamp`,
            `ip`,
            `username`,
            `action`,
            `remarks`
        ) VALUES (
            UNIX_TIMESTAMP(),
            :ip,
            :username,
            :action,
            :remarks
        )";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':ip' => $_SERVER["REMOTE_ADDR"],
            ':username' => $_SESSION["uname"] ?? '',
            ':action' => $action,
            ':remarks' => $remarks
        ]);
    } catch (PDOException $e) {
        // Log the error or handle it as appropriate for your application
        error_log("Database error in log_to_db: " . $e->getMessage());
        throw $e; // Re-throw the exception if you want calling code to handle it
    }
}
?>