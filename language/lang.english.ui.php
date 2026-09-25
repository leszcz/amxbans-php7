<?php
// AMXBans 7 - texts of the new interface (English)

define("_LANGUAGE","Language");
define("_TOGGLE_THEME","Toggle dark mode");
define("_CLOSE","Close");
define("_CANCEL","Cancel");
define("_NEXT","Next");
define("_OPTIONAL","optional");
define("_YOU","you");
define("_UNCHANGED","unchanged");
define("_NOTSET","not set");
define("_INSTALLED","installed");
define("_WRITABLE","writable");
define("_NOT_WRITABLE","not writable");
define("_IN_TIME","in %s");
define("_TIME_AGO","%s ago");
define("_MIN_CHARS","At least %d characters.");

define("_ERR_FORBIDDEN","You are not allowed to do this.");
define("_ERR_NOTFOUND","Page not found.");
define("_ERR_BADREQUEST","Bad request.");

define("_QUICKSEARCH","Nickname, SteamID or reason…");
define("_STEAMPROFILE","Steam profile");
define("_ACTIVEBAN","Active ban");
define("_EXPIREDBAN","Expired");
define("_LATESTBAN","Latest ban");
define("_BANNOTFOUND","Ban not found.");
define("_BANDELETED","Ban deleted");
define("_NOBANSTEAMID","A SteamID is required for this ban type!");
define("_BBCODE_HINT","You can use [b], [i], [u], [quote] and [url=https://…]text[/url].");
define("_TOOFAST","Please wait a moment before posting again.");
define("_CUSTOM_REASON","custom");
define("_NOADMINS","No admins");
define("_NOSERVERS","No servers");
define("_NOSERVERS_HINT","No servers yet. Servers are added automatically when the AMXBans plugin connects to this database.");
define("_MOTD_APPEAL","If you think this ban is a mistake, contact the server administration.");

define("_LOGIN_INTRO","Log in to manage bans, admins and servers.");
define("_LOGINBLOCKED_FOR","Try again in %d minute(s).");
define("_LOGIN_TRIES_LEFT","Attempts left: %d");

define("_WELCOME_BACK","Welcome back, %s");
define("_RECENT_BANS","Recent bans");
define("_BANS_TODAY","Bans today");
define("_BANS_WEEK","Bans in the last 7 days");
define("_ORPHANED","orphaned");
define("_REPAIRED","Repaired entries");
define("_SETUP_STILL_PRESENT","setup.php still exists. Delete it from the server - it is no longer needed.");
define("_HTTPS_RECOMMENDED","This site is not served over HTTPS. Logins and cookies can be intercepted - enable HTTPS (e.g. a free Let's Encrypt certificate).");

define("_NORCON","The RCON password of this server is not set.");
define("_RCON_CLEAR","Remove the saved RCON password");
define("_RCONPW_INVALID","The RCON password must not contain quotation marks and may have at most 32 characters.");
define("_MOTD_SUGGESTED","Suggested URL");
define("_URLINVALID","Only http(s) and relative links are allowed.");
define("_USERMENU_HINT","Language keys (e.g. _HOME) are translated automatically; plain text is shown as it is. Login/logout buttons are always shown in the header.");
define("_FILETYPES_HINT","Comma separated, e.g. dem,zip,jpg,png. Script files (php, html, js, svg…) are always blocked.");
define("_CANNOT_CHANGE_OWN_LEVEL","You cannot change your own level.");
define("_CANNOT_DELETE_SELF","You cannot delete your own account.");
define("_CANNOT_REMOVE_OWN_PERMISSION","You cannot remove the \"permissions\" right from your own level.");
define("_YOURLEVEL_NOTE","This is your own level - changes apply to you immediately.");
define("_CHECK_RELEASES","Check for new releases");

define("_DB_BACKUP","Database backup");
define("_ALL_TABLES","All AMXBans tables");
define("_STRUCTURE_ONLY","Structure only (no data)");
define("_DOWNLOAD_NOW","Download immediately (do not store on the server)");
define("_CREATE_BACKUP","Create backup");
define("_ONLY_PERMANENT","Only permanent bans");
define("_WITH_REASON","Add ban reason as comment");
define("_DELETE_IMPORTED","Delete imported bans");
define("_IMPORTSUCCESS","Import finished");
define("_IMPORT_RESULT","%d imported, %d skipped");
define("_USERSI_RESULT","%d admins added, %d assigned to the server, %d lines skipped");

define("_INSTALLATION","Installation");
define("_INSTALLFAILED","Installation failed");
define("_DBOK","Database connection works.");
define("_SETUP_STEP_REQUIREMENTS","Requirements");
define("_SETUP_STEP_DATABASE","Database");
define("_SETUP_STEP_ADMIN","Administrator");
define("_SETUP_STEP_INSTALL","Install");
define("_SETUP_DBHOST","Database host");
define("_SETUP_DBNAME","Database name");
define("_SETUP_DBUSER","Database user");
define("_SETUP_DBPASS","Database password");
define("_SETUP_DBPREFIX","Table prefix");
define("_SETUP_PREFIX_HINT","Must be the same prefix as configured in the AMXBans plugin (amxbans_tableprefix without the trailing _).");
define("_SETUP_DB_FAILED","Cannot connect to the database");
define("_SETUP_EXISTING","Existing AMXBans tables were found - they will be kept and only missing tables are created.");
define("_SETUP_ADMIN_OPTIONAL","Your existing web admins keep working. Fill in the form only if you want to add another admin.");
define("_SETUP_INSTALL_NOW","Install");
define("_SETUP_DONE","AMXBans has been installed.");
define("_SETUP_WRITE_MANUALLY","include/db.config.inc.php could not be written. Create it with the following content:");
define("_SETUP_DELETE_HINT","For security reasons delete setup.php now.");
define("_SETUP_DELETE","Delete setup.php");
define("_SETUP_LOCKED","AMXBans is already installed");
define("_SETUP_LOCKED_TEXT","To reinstall, delete include/db.config.inc.php first. Otherwise delete setup.php from the server.");
