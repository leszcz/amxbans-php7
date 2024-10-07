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

try {
    // Połączenie z bazą danych za pomocą PDO
    $pdo = new PDO("mysql:host=" . $_SESSION["dbhost"] . ";dbname=" . $_SESSION["dbdb"], $_SESSION["dbuser"], $_SESSION["dbpass"]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$dbprefix = $_SESSION["dbprefix"];

// _bbcodes
$data_create[$dbprefix."_bbcode"] = "( `open_tag`, `close_tag`, `url`, `name`) VALUES
          ('[b]', '[/b]', 'bold.png', 'bold'),
          ('[i]', '[/i]', 'italic.png', 'italic'),
          ('[u]', '[/u]', 'underline.png', 'underline'),
          ('[center]', '[/center]', 'center.png', 'center')";
          
// _smilies
$data_create[$dbprefix."_smilies"] = "( `code`, `url`, `name`) VALUES
          (':D', 'big_smile.png', 'Big Grin'),
          ('8)', 'cool.png', 'Cool'),
          (':/', 'hmm.png', 'Hmm'),
          ('lol', 'lol.png', 'lol'),
          (':(', 'mad.png', 'Mad'),
          (':|', 'neutral.png', 'Neutral'),
          (':roll:', 'roll.png', 'RollEyes'),
          (':*(', 'sad.png', 'Sad'),
          (':)', 'smile.png', 'Smilie'),
          (':P', 'tongue.png', 'Tongue'),
          (';)', 'wink.png', 'Wink'),
          (':O', 'yikes.png', 'Yikes')";
          
// _usermenu
$usermenu_query = "INSERT INTO `".$dbprefix."_usermenu` ( `pos`, `activ`, `lang_key`, `url`, `lang_key2`, `url2`) VALUES
          (1, 1, '_HOME', 'index.php', '_HOME', 'index.php'),
          (2, 1, '_BANLIST', 'ban_list.php', '_BANLIST', 'ban_list.php'),
          (3, 1, '_ADMLIST', 'admin_list.php', '_ADMLIST', 'admin_list.php'),
          (4, 1, '_SEARCH', 'search.php', '_SEARCH', 'search.php'),
          (5, 1, '_SERVER', 'view.php', '_SERVER', 'view.php'),
          (6, 1, '_LOGIN', 'login.php', '_LOGOUT', 'logout.php')";
          
// _webconfig
$websettings_query = "INSERT INTO `".$dbprefix."_webconfig` ( `cookie`, `bans_per_page`, `design`, `banner`, `banner_url`, `default_lang`, `start_page`,
          `show_comment_count`, `show_demo_count`, `show_kick_count`, `demo_all`, `comment_all`, `use_capture`, `max_file_size`,
          `file_type`, `auto_prune`, `use_demo`, `use_comment`) VALUES
          ( 'amxbans', 50, 'default', 'amxbans.png', 'http://www.gm-community.net', 'english', 'ban_list.php', 1, 1, 1, 0, 0, 1, 2, 'dem,zip,rar,jpg,gif,png', 0, 1, 1)";
          
// _userlevel
$userlevel_query = "INSERT INTO `".$dbprefix."_levels` (`level`, `bans_add`, `bans_edit`, `bans_delete`, `bans_unban`, `bans_import`, `bans_export`,
          `amxadmins_view`, `amxadmins_edit`, `webadmins_view`, `webadmins_edit`, `websettings_view`, `websettings_edit`, `permissions_edit`, `prune_db`,
          `servers_edit`, `ip_view`) VALUES
          (1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes')";
          
// _webadmins
$webadmin_query = "INSERT INTO `".$dbprefix."_webadmins` ( `username`, `password`, `level`, `email`) VALUES
          ( :username, :password, 1, :email)";

// Przygotowanie i wykonanie zapytania dla webadmins z PDO
$stmt = $pdo->prepare($webadmin_query);
$stmt->execute([
    'username' => $_SESSION["adminuser"],
    'password' => password_hash($_SESSION["adminpass"], PASSWORD_DEFAULT),
    'email'    => $_SESSION["adminemail"]
]);

// _logs
$log_query = "INSERT INTO `".$dbprefix."_logs` (`timestamp`, `ip`, `username`, `action`, `remarks`) VALUES
          (UNIX_TIMESTAMP(NOW()), :ip, :username, 'Install', 'Installation AMXBans ".$config->v_web."')";

// Przygotowanie i wykonanie zapytania dla logs z PDO
$stmt = $pdo->prepare($log_query);
$stmt->execute([
    'ip'       => $_SERVER["REMOTE_ADDR"],
    'username' => $_SESSION["adminuser"]
]);

// module iexport
$modules_install["iexport"] = "INSERT INTO `".$dbprefix."_modulconfig` ( `menuname`, `name`, `index`, `activ`) VALUES
          ('_MENUIMPORTEXPORT', 'iexport', '', 1)";

?>