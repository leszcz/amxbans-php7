<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>AMXBans - {$title}</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="" />
<meta name="Description" content="" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />
{$meta}
<link rel="stylesheet" type="text/css" href="{$dir}/include/amxbans.css" />
<script type="text/javascript" language="JavaScript" src="/layer.js"></script>
</head>

<body>

<table border='0' cellpadding='0' cellspacing='0' width='100%'>
  <tr>
    <td width='100%' valign='top' style='padding: 20px'>
    <table border='0' cellpadding='0' cellspacing='0' width='100%'>
      <tr>
        <td>

        <table cellspacing='1' class='listtable' width='100%'>
          <tr>
            <td height='16' colspan='2' class='listtable_top'><b><font color='red' size="6">Вы забанены. Позор Вам!</font></b></td>
          </tr>
          <tr bgcolor="#D3D8DC">
            <td height='16' width='30%' class='listtable_1' bgcolor='#e0e0e0'>{"_PLAYER"|lang}</td>
            <td height='16' width='70%' class='listtable_1' bgcolor='#e0e0e0'>{$ban_detail.player_nick}</td>
          </tr>
          <tr bgcolor="#D3D8DC">
            <td height='16' width='30%' class='listtable_1' bgcolor='#eeeeee'>{"_BANTYPE"|lang}</td>
            <td height='16' width='70%' class='listtable_1' bgcolor='#eeeeee'>{$ban_info.ban_type}</td>
          </tr>
          <tr bgcolor="#D3D8DC">
            <td height='16' width='30%' class='listtable_1' bgcolor='#e0e0e0'>SteamID</td>
            <td height='16' width='70%' class='listtable_1' bgcolor='#e0e0e0'>{if $ban_info.player_id == "&nbsp;"}<i><font color='#677882'>{"_NOSTEAMID"|lang}</font></i>{else}{$ban_info.player_id}{/if}</td>
          </tr>
		  
          <tr bgcolor="#D3D8DC">
            <td height='16' width='30%' class='listtable_1' bgcolor='#eeeeee'>{"_IP"|lang}</td>
            <td height='16' width='70%' class='listtable_1' bgcolor='#eeeeee'>{$ban_info.player_ip}</td>
          </tr>
          <tr bgcolor="#D3D8DC">
            <td height='16' width='30%' class='listtable_1' bgcolor='#e0e0e0'>{"_INVOKED"|lang}</td>
            <td height='16' width='70%' class='listtable_1' bgcolor='#e0e0e0'>{$ban_info.ban_start}</td>
          </tr>
          <tr bgcolor="#D3D8DC">
            <td height='16' width='30%' class='listtable_1' bgcolor='#eeeeee'>{"_BANLENGHT"|lang}</td>
            <td height='16' width='70%' class='listtable_1' bgcolor='#eeeeee'>{$ban_info.ban_duration}</td>
          </tr>
          <tr bgcolor="#D3D8DC">
            <td height='16' width='30%' class='listtable_1' bgcolor='#e0e0e0'>{"_EXPIRES"|lang}</td>
            <td height='16' width='70%' class='listtable_1' bgcolor='#e0e0e0'>{$ban_info.ban_end}</td>
          </tr>
          <tr bgcolor="#D3D8DC">
            <td height='16' width='30%' class='listtable_1' bgcolor='#eeeeee'>{"_REASON"|lang}</td>
            <td height='16' width='70%' class='listtable_1' bgcolor='#eeeeee'>{$ban_info.ban_reason}</td>
          </tr>
          <tr bgcolor="#D3D8DC">
            <td height='16' width='30%' class='listtable_1' bgcolor='#e0e0e0'>{"_BANBY"|lang}</td>
            <td height='16' width='70%' class='listtable_1' bgcolor='#e0e0e0'>{if ($display_admin == "enabled") || ($smarty.session.bans_add == "yes")}{$ban_info.admin_name}{else}<i><font color='#677882'>{"_HIDDEN"|lang}</font></i>{/if}</td>
          </tr>
          <tr bgcolor="#D3D8DC">
            <td height='16' width='30%' class='listtable_1' bgcolor='#eeeeee'>{"_BANON"|lang}</td>
            <td height='16' width='70%' class='listtable_1' bgcolor='#eeeeee'>{$ban_info.server_name}</td>
          </tr>
 
  	    

				<table cellspacing='1' width='100%'>
					<tr>
						
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>

</body>

</html>