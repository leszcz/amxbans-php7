<?php
/**
	Modu� pozwalaj�cy w �atwy spos�b zaimportowa� list� admin�w z pliku users.ini
	do bazy danych z bezpo�rednim przypisaniem admin�w do serwer�w.
	
	@author Portek <admin@portek.net.pl>  
	@url http://cserwerek.pl/user/2-michal/ AMXX.PL::Portek
	@url http://amxx.pl/user/509-portek/ CSERWEREK.PL::Portek
	@license http://creativecommons.org/licenses/by-nc-sa/3.0/deed.pl CreativeCommons BY-NC-SA
	@version 1.2.0
	
*/

session_start();

if(!$_SESSION["loggedin"]) {
	header("Location:index.php");
}
if (!has_access("bans_import")) {
	header("Location:index.php");
	exit;
}


ob_start();

$modul_site="usersi";
$title2="Import adminów z users.ini";

function validateSID($steamid){
	if(strlen(trim($steamid)) != 0)
	{
		$regex = "/^STEAM_0:[01]:[0-9]{7,8}$/";
		if(!preg_match($regex, $steamid))
		{
			return 0;
		} else {
			return $steamid;
		}
	} return 0;
}

$query = mysql_query("SELECT id,hostname from `".$config->db_prefix."_serverinfo`;");

while ($row = mysql_fetch_assoc($query)){
	$serwery[] = $row;
}

$smarty->assign("serwery",$serwery);

if(isset($_POST['usersImport'])){
	if ( !has_access("bans_import")) {
		header("Location:index.php");
		exit;
	}
	
	$serwerID = $_POST['serverID'];
	if($_FILES['usersFile']['size'] >= ($config->max_file_size*1024*1024)) $user_msg="_FILETOBIG";
	if(!$user_msg){
		if(!move_uploaded_file($_FILES['usersFile']['tmp_name'], "temp/".$_FILES['usersFile']['name'])) {
				$user_msg="_FILEUPLOADFAIL";
			} else {
				if($fh = fopen("temp/".$_FILES['usersFile']['name'],"r")){
				  while (!feof($fh)){
					 $content[] = fgets($fh,9999);
				  }
					fclose($fh);
					$admini = array();
					for($i=0;$i<count($content);$i++){						
						if(!preg_match('/^;/',$content[$i])) {
							$dane = explode('"',$content[$i]);
							validateSID($dane[1])?$sid=$dane[1]:$sid=null;
							
							if($dane[1]!=null && $dane[5]!=null && $dane[7]!=null /*&& !in_array($dane[1],array('haM', 'STEAM_0:0:123456', '123.45.67.89', 'My Name'))*/){
								if($dane[9]!=null) { $prawa = $dane[9]?yes:no; } else { $prawa = $_POST['isStatic']; }
								$admini[] = [
									'id' => str_replace(array("\\","\0","\n","\r","\x1a","'",'"','`'),array("\\\\","\\0","\\n","\\r","\Z","\'",'\"','\`'),$dane[1]),
									'sid' => $sid, 
									'pw' => $dane[3]?md5($dane[3]):'', 
									'flags' => $dane[5], 
									'access' => $dane[7], 
									'static' => $prawa
								];
							}
						}
					}

					for($i=0;$i<count($admini);$i++){
						$IDAdmin = mysql_fetch_row(mysql_query("SELECT `id` from `".$config->db_prefix."_amxadmins` ORDER by `id` DESC;"));
						$checkData .= $admini[$i]['sid']?" `steamid`='{$admini[$i]['sid']}' OR":"";
						$checkData .= $admini[$i]['id']?" `nickname`='{$admini[$i]['id']}'":'';
						
						$accIsset = mysql_fetch_row(mysql_query("SELECT `id` from `".$config->db_prefix."_amxadmins` WHERE {$checkData};"));
						$checkData = '';
						if(empty($accIsset[0])) {
							mysql_query("INSERT INTO `".$config->db_prefix."_amxadmins` 
								(`id`, `username`, `password`, `access`, `flags`, `steamid`, `nickname`, `icq`, `ashow`, `created`, `expired`, `days`)
								VALUES 
								(".($IDAdmin[0]+1).",'".$admini[$i]['id']."','".$admini[$i]['pw']."','".$admini[$i]['flags']."','".$admini[$i]['access']."','".$admini[$i]['sid']."','".$admini[$i]['id']."','','1','".time()."', '0', '0')
								") or die (mysql_error());
								
						mysql_query("INSERT INTO `".$config->db_prefix."_admins_servers` 
									(`admin_id`, `server_id`, `custom_flags`, `use_static_bantime`) VALUES (".($IDAdmin[0]+1).", ".$serwerID.", '', '".$admini[$i]['static']."');");
						} else {
							$query = mysql_fetch_row(mysql_query("SELECT count(*) from `".$config->db_prefix."_admins_servers` WHERE `admin_id` ={$accIsset[0]} AND `server_id`={$serwerID} LIMIT 1;"));
							if($query[0]==0) {
								mysql_query("INSERT INTO `".$config->db_prefix."_admins_servers` 
									(`admin_id`, `server_id`, `custom_flags`, `use_static_bantime`) VALUES (".$accIsset[0].", ".$serwerID.", '', '".$admini[$i]['static']."');");
								}
						}
					}
					unlink("temp/".$_FILES['usersFile']['name']);
					$user_msg="Operacja zakończona sukcesem!";
				}
		}
	}
}


ob_end_flush();
?>