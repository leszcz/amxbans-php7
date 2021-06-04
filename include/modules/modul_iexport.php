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

if(!$_SESSION["loggedin"]) {
	header("Location:index.php");
}
if ( !has_access("bans_export") && !has_access("bans_import")) {
	header("Location:index.php");
	exit;
}

require_once("iexport_func/modul_iexport_dbbackup.php");

ob_start();

$modul_site="iexport";
$title2="_TITLEIEXPORT";

//download eines backups
if(isset($_POST["dbdownfile"])) {
	if ( !has_access("bans_export") && !has_access("bans_import")) {
		header("Location:index.php");
		exit;
	}
	$file=basename($_POST["localfile"]);
	$filepath="include/backup/".$file;
	if(!file_exists($filepath)) {
		$user_msg="_FILENOTAVAILABLE";
	}
	if(!$user_msg) {
		if(ini_get('zlib.output_compression')) 
			ini_set('zlib.output_compression', 'Off');
		#header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		#header('Content-Disposition: attachment; filename="'.basename($file).'"'); 
		#header('Content-Type: attachment/octet-stream');
		#header('Content-Length: '.filesize($filepath));
		#header('Pragma: public');
		#set_time_limit(0);
		#readfile($filepath);
		
		$file2 = fopen("$filepath","r");  
		header("Content-Type: application/download");  
		header('Content-Disposition: attachment; filename="'.basename($file).'"');  
		fpassthru($file2);  
		fclose($file2);
		
		unset($_POST["dbdownfile"]);
	}
}
//delete backup
if(isset($_POST["delfile"])) {
	if ( !has_access("bans_export") && !has_access("bans_import")) {
		header("Location:index.php");
		exit;
	}
	$file=basename($_POST["localfile"]);
	$filepath="include/backup/".$file;
	if(file_exists($filepath) && is_file($filepath)) {
		//delete the file
		if(unlink($filepath)) {
			if($query) $user_msg="_FILEDELSUCCESS";
		} else { $user_msg="_FILEDELFAILED"; }
	} else { $user_msg="_FILENOTFOUND"; }
}
//create db .sql backup
if(isset($_POST["dbexp"])) {
	if ( !has_access("bans_export") && !has_access("bans_import")) {
		header("Location:index.php");
		exit;
	}
	$type=(isset($_POST["structur"]))?true:false;
	$droptable=(isset($_POST["droptable"]))?true:false;
	$deleteall=(isset($_POST["deleteall"]))?true:false;
	$download=(isset($_POST["download"]))?true:false;
	
	# type: bool (true structure only)
	# droptable: bool
	# deleteall: bool
	# download: bool
	# bansonly: bool
	$user_msg=db_backup($type,$droptable,$deleteall,$download,false);
}
//create bans .sql backup
if(isset($_POST["dbbansexp"])) {
	if ( !has_access("bans_export") && !has_access("bans_import")) {
		header("Location:index.php");
		exit;
	}
	$download=(isset($_POST["download"]))?true:false;
	
	# type: bool (true structure only)
	# droptable: bool
	# deleteall: bool
	# download: bool
	# bansonly: bool
	$user_msg=db_backup(false,true,false,$download,true);
}
//import banned.cfg
if(isset($_POST["bancfgupl"])) {
	if ( !has_access("bans_export") && !has_access("bans_import")) {
		header("Location:index.php");
		exit;
	}
	$reason=mysql_real_escape_string($_POST["reason"]);
	$plnick=mysql_real_escape_string($_POST["player_nick"]);
	$server=mysql_real_escape_string($_POST["server_name"]);
	$date=explode("-",trim($_POST["ban_created"]));
	
	if($reason=="" || $plnick=="" || $server=="" || $date=="" || sizeof($date)!=3) {
		$user_msg="_NOREQUIREDFIELDS";
	} else {
		$date=(int)strtotime($date[2].$date[1].$date[0]);
		$file=mysql_real_escape_string($_FILES['filename']['name']);
		$types=array("cfg","txt");
		
		if($file=="") {
			$user_msg="_FILENOFILE";
		} else {
			$file_type=substr(strrchr($file, '.'),1); 
			if(!in_array($file_type,$types)) $user_msg="_FILETYPENOTALLOWED";
		}
		if($_FILES['filename']['size'] >= ($config->max_file_size*1024*1024)) $user_msg="_FILETOBIG";
		if(!$user_msg) {
			if(!move_uploaded_file($_FILES['filename']['tmp_name'], "temp/".$file)) {
				$user_msg="_FILEUPLOADFAIL";
			} else {
				$handle = fopen("temp/".$file,"r");
				$status["imported"]=0;
				$status["failed"]=0;
				while (!feof($handle))
				{
					$n = fgets($handle,128);
					$bans=explode(" ",$n);
					$time=(int)trim($bans[1]);
					
					//amxbans 5.x hack, exported banned.cfg with reason
					$reason_real="";
					if($bans[4] != "") {
						for($i=4;$i<=sizeof($bans);$i++) {
							$reason_real.=mysql_real_escape_string($bans[$i])." ";
						}
					} else {
						$reason_real=$reason;
					}
					trim($reason_real);
					$steamid=mysql_real_escape_string(trim($bans[2]));
					if(trim($bans[0])=="" || trim($bans[0])=="//") continue;
					if($time!=0) {$status["failed"]++;;continue;}
					
					if(trim($bans[0])=="banid") {
						//ban with steamid
						if(!preg_match("/^STEAM_0:(0|1):[0-9]{1,18}/",$steamid)) { $status["failed"]++; continue;}
						//search for a already existing permanent ban
						$query = mysql_query("SELECT `player_id` FROM `".$config->db_prefix."_bans` WHERE `player_id`='".$steamid."' AND `expired`=0") or die (mysql_error());
						if(mysql_num_rows($query)) {$status["failed"]++; continue;}
						//write ban to db
						$status["imported"]++;
						$query = mysql_query("INSERT INTO `".$config->db_prefix."_bans` 
							(`player_id`,`player_nick`,`admin_nick`,`ban_type`,`ban_reason`,`ban_created`,`ban_length`,`server_name`,`imported`) 
							VALUES 
							('".$steamid."','".$plnick."','".$_SESSION["uname"]."','S','".$reason_real."',".$date.",".$time.",'".$server."',1)
							") or die (mysql_error());
					}else if(trim($bans[0])=="banip") {
						//ban with ip
						if(!preg_match("/^[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$/",$steamid)) { $status["failed"]++; continue;}
						//search for a already existing permanent ban
						$query = mysql_query("SELECT `player_ip` FROM `".$config->db_prefix."_bans` WHERE `player_id`='".$steamid."' AND `expired`=0") or die (mysql_error());
						if(mysql_num_rows($query)) {$status["failed"]++; continue;}
						//write ban to db
						$status["imported"]++;
						$query = mysql_query("INSERT INTO `".$config->db_prefix."_bans` 
							(`player_ip`,`player_nick`,`admin_nick`,`ban_type`,`ban_reason`,`ban_created`,`ban_length`,`server_name`,`imported`) 
							VALUES 
							('".$steamid."','".$plnick."','".$_SESSION["uname"]."','SI','".$reason_real."',".$date.",".$time.",'".$server."',1)
							") or die (mysql_error());
					} else { $status["failed"]++; continue;}
				}
				fclose($handle);
				//del temp file
				unlink("temp/".$file);
				$smarty->assign("status",$status);
			} 
		}
	}
}
//export banned.cfg
if(isset($_POST["bancfgexp"])) {
	if ( !has_access("bans_export") && !has_access("bans_import")) {
		header("Location:index.php");
		exit;
	}
	$onlyperm=(isset($_POST["onlyperm"]))?true:false;
	$increason=(isset($_POST["increason"]))?true:false;
	$download=(isset($_POST["download"]))?true:false;
	
	$file="temp/banned.cfg";
	if(file_exists($file)) unlink($file);
	
	$status["exported"]=0;
	if($handle = fopen($file,"w")) {
		$query = mysql_query("SELECT `player_id`,`ban_length`,`ban_reason` FROM `".$config->db_prefix."_bans`".(($onlyperm)?" WHERE `expired`=0":"")) or die (mysql_error());
		while($result = mysql_fetch_object($query)) {
			$line="banid ".$result->ban_length.".0 ".trim($result->player_id).(($increason)?" // ".trim($result->ban_reason):"")."\n";
			$n = fputs($handle,$line);
			$status["exported"]++;
		};
		fclose($handle);
		$user_msg="_EXPORTSUCCESS";
		$smarty->assign("statusexport",$status);
		if(file_exists($file) && $download) {
			if(ini_get('zlib.output_compression')) 
				ini_set('zlib.output_compression', 'Off');
			#header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			#header('Content-Disposition: attachment; filename="'.basename($file).'"'); 
			#header('Content-Type: attachment/octet-stream');
			#header('Content-Length: '.filesize($file));
			#header('Pragma: public');
			#set_time_limit(0);
			#readfile($file);
			
			$file2 = fopen("$file","r");  
			header("Content-Type: application/download");  
			header('Content-Disposition: attachment; filename="'.basename($file).'"');  
			fpassthru($file2);  
			fclose($file2);
			
			unset($_POST["download"]);
		}
	} else {
		$user_msg="_EXPORTFAILED";
	}
	
			
}
//db check from amxbans 5.x data
if(isset($_POST["bandbcheck"])) {
	if ( !has_access("bans_export") && !has_access("bans_import")) {
		header("Location:index.php");
		exit;
	}
	#$dbdata["host"]=mysql_real_escape_string($_POST["impdbhost"]);
	$dbdata["host"]=gethostbyname($_POST["impdbhost"]);
	$dbdata["user"]=mysql_real_escape_string($_POST["impdbuser"]);
	$dbdata["pass"]=mysql_real_escape_string($_POST["impdbpw"]);
	$dbdata["database"]=mysql_real_escape_string($_POST["impdbdb"]);
	$dbdata["table"]=mysql_real_escape_string($_POST["impdbtable"]);
	$dbdata["onlyperm"]=(isset($_POST["onlyperm"]))?true:false;
	$dbdata["dellocal"]=(isset($_POST["dellocal"]))?true:false;
	//connect to db
	$mysql2 = @mysql_connect($dbdata["host"],$dbdata["user"],$dbdata["pass"]) or $user_msg="_DBLOGINFAILED";
	if(!$user_msg) $resource2 = @mysql_select_db($dbdata["database"],$mysql2) or $user_msg="_DBSELECTDBFAILED";
	if(!$user_msg) $query2 = @mysql_query("SELECT * FROM `".$dbdata["table"]."` WHERE `ban_length`=0",$mysql2) or $user_msg="_TABLESSELECTFAILED";
	if(!$user_msg) {
		$user_msg="_DBDATAOK";
		$dbcheck="OK";
	}
	$smarty->assign("dbdata",$dbdata);
	$smarty->assign("dbcheck",$dbcheck);
}
//import amxbans 5.x from db
if(isset($_POST["bandbimp"])) {
	if ( !has_access("bans_export") && !has_access("bans_import")) {
		header("Location:index.php");
		exit;
	}
	@set_time_limit(0);
	$onlyperm=(isset($_POST["onlyperm"]))?true:false;
	$dellocal=(isset($_POST["dellocal"]))?true:false;
	
	#$dbdata["host"]=mysql_real_escape_string($_POST["impdbhost"]);
	$dbdata["host"]=gethostbyname($_POST["impdbhost"]);
	$dbdata["user"]=mysql_real_escape_string($_POST["impdbuser"]);
	$dbdata["pass"]=mysql_real_escape_string($_POST["impdbpw"]);
	$dbdata["database"]=mysql_real_escape_string($_POST["impdbdb"]);
	$dbdata["table"]=mysql_real_escape_string($_POST["impdbtable"]);
	$dbdata["onlyperm"]=$onlyperm;
	$dbdata["dellocal"]=$dellocal;
	//connect to db for import
	$mysql2 = @mysql_connect($dbdata["host"],$dbdata["user"],$dbdata["pass"]) or $user_msg="_DBLOGINFAILED";
	//select the database for import
	if(!$user_msg) $resource2 = @mysql_select_db($dbdata["database"],$mysql2) or $user_msg="_DBSELECTDBFAILED";
	//get all bans from table for import
	if(!$user_msg) $query2 = @mysql_query("SELECT * FROM `".$dbdata["table"]."`".(($onlyperm)?" WHERE `ban_length`=0":"")." ORDER BY `ban_created` DESC",$mysql2) or $user_msg="_TABLESSELECTFAILED";
	
	if(!$user_msg) {
		$status["imported"]=0;
		$status["failed"]=0;
	
		if($dellocal) {
			//delete all local bans include edit logs
			$query=mysql_query("DELETE FROM `".$config->db_prefix."_bans`",$mysql) or die (mysql_error());
			$query=mysql_query("DELETE FROM `".$config->db_prefix."_bans_edit`",$mysql) or die (mysql_error());
			$user_msg="_LOCALTABLEDELETED";
		}
		while($result2 = mysql_fetch_object($query2)) {
			//is the table a 5.x history table?
			if($result2->unban_created != "") {$history=true;}
			
			if(!$dellocal) {
				//search if the ban to import exists
				$query = mysql_query("SELECT * FROM `".$config->db_prefix."_bans` WHERE 
							`player_id`='".$result2->player_id."' AND
							`player_ip`='".$result2->player_ip."' AND
							`player_nick`='".mysql_real_escape_string($result2->player_nick)."' AND
							`ban_created`='".$result2->ban_created."' AND
							`ban_length`='".$result2->ban_length."' LIMIT 1",$mysql) or die (mysql_error());
							
				if(mysql_num_rows($query)) { $status["failed"]++; continue; }
			}
			//some filter for bad bans
			if($result2->player_id == "" && $result2->player_ip == "") { $status["failed"]++; continue; } //steamid & ip empty
			if(stristr($result2->player_id,"STEAM") === false && $result2->player_ip == "") { $status["failed"]++; continue; } //bad steamid & ip empty
			//bans from history are always expired
			if($history) {
				$expired=1;
			} else {
				$expired=(($result2->ban_created + ($result2->ban_length * 60)) < time() && $result2->ban_length != 0) ? "1" : "0";
			}
			//save the ban to new table
			$query = mysql_query("INSERT INTO `".$config->db_prefix."_bans` 
				(`player_ip`,`player_id`,`player_nick`,`admin_ip`,`admin_id`,`admin_nick`,`ban_type`,`ban_reason`,`ban_created`,`ban_length`,`server_ip`,`server_name`,`expired`,`imported`) 
				VALUES 
				('".$result2->player_ip."',
				'".$result2->player_id."',
				'".mysql_real_escape_string($result2->player_nick)."',
				'".$result2->admin_ip."',
				'".$result2->admin_id."',
				'".mysql_real_escape_string($result2->admin_nick)."',
				'".$result2->ban_type."',
				'".mysql_real_escape_string($result2->ban_reason)."',
				'".$result2->ban_created."',
				'".$result2->ban_length."',
				'".$result2->server_ip."',
				'".mysql_real_escape_string($result2->server_name)."',
				'".$expired."',
				'1')",$mysql) or die (mysql_error());
			//if importing the history table save the edit details
			if($history) {
				$insertid=mysql_insert_id($mysql) or die (mysql_error());
				$query3 = mysql_query("INSERT INTO `".$config->db_prefix."_bans_edit` (`bid`,`edit_time`,`admin_nick`,`edit_reason`) VALUES ('".
							$insertid."','".$result2->unban_created."','".mysql_real_escape_string($result2->unban_admin_nick)."','".mysql_real_escape_string($result2->unban_reason)."')",$mysql) or die (mysql_error());
			}
			if($query) $status["imported"]++;
			
		}
		$smarty->assign("status",$status);
	}
	$smarty->assign("dbdata",$dbdata);
}
//del all imported bans
if(isset($_POST["delimport"])) {
	if ( !has_access("bans_export") && !has_access("bans_import")) {
		header("Location:index.php");
		exit;
	}
	$count=-1;
	$query = mysql_query("DELETE FROM `".$config->db_prefix."_bans` WHERE `imported`=1") or die (mysql_error());
	$smarty->assign("delcount",mysql_affected_rows());
}
//set all to not imported
if(isset($_POST["setnotimported"])) {
	if ( !has_access("bans_export") && !has_access("bans_import")) {
		header("Location:index.php");
		exit;
	}
	$count=-1;
	$query = mysql_query("UPDATE `".$config->db_prefix."_bans` SET `imported`=0 WHERE `imported`=1") or die (mysql_error());
	$smarty->assign("updatecount",mysql_affected_rows());
}
//search backups
$d=opendir($config->path_root."/include/backup/");
$count=0;
while($f=readdir($d)) {
	if($f=="." || $f==".." || is_dir($config->path_root."/backup/".$f)) continue;
	if(substr($f,-3,3)=="sql") {
		$backups[]=$f;
		$count++;
	}
}
if(is_array($backups)) rsort($backups);
closedir($d);

//Anzahl importierter Banns suchen
$query = mysql_query("SELECT `bid` FROM `".$config->db_prefix."_bans` WHERE `imported`=1",$mysql) or die (mysql_error());
$smarty->assign("importcount",mysql_num_rows($query));

$smarty->assign("backups",$backups);
$smarty->assign("count",$count);

ob_end_flush();
?>