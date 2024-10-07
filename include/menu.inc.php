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

require_once("config.inc.php");
require_once("sql.inc.php");

/**
 * Fetches the user menu from the database
 *
 * @return array The user menu items
 * @throws PDOException If there's an error with the database operation
 */
function getUserMenu(): array {
    global $config;
    
    try {
        $pdo = getPDO();
        
        $query = "SELECT * FROM `" . $config->db_prefix . "_usermenu` WHERE `activ` = 1 ORDER BY `pos` ASC";
        
        $stmt = $pdo->query($query);
        
        $menu = [];
        while ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
            $men = [
                "id" => $result->id,
                "pos" => $result->pos,
                "activ" => $result->activ,
                "lang_key" => $result->lang_key,
                "url" => $result->url,
                "lang_key2" => $result->lang_key2,
                "url2" => $result->url2
            ];
            $menu[] = $men;
        }
        
        return htmlsafe_recursive($menu);
    } catch (PDOException $e) {
        // Log the error or handle it as appropriate for your application
        error_log("Database error in getUserMenu: " . $e->getMessage());
        throw $e; // Re-throw the exception if you want calling code to handle it
    }
}

// Fetch the user menu
$menu = getUserMenu();

?>