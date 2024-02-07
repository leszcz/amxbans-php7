<html>
<head>
	<title>AMXBans - Ban Details</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<link href="templates/default/css/motd.css" rel="stylesheet" type="text/css" media="screen">
</head>
<body>
<div align="center">
<div align="center">
	<table>
	  <tbody>
		<tr>
		  <td class="tl"><td class="b"><td class="tr">
		</tr>
		<tr>
		  <td class="b">
		  <td class="body">
		    <div class="header">{"_YOUAREBANNED"|lang}</div>
			<table width="100%" border="0" cellpadding="0">
				<tr>
					<td class="fat">{"_NICKNAME"|lang}:</td>
					<td><img alt="" src="images/country/{if $ban_detail.cc_player}{$ban_detail.cc_player|lower}{else}clear{/if}.png"> {$ban_detail.player_nick}</td>
				</tr>
				<tr>
					<td class="fat">{"_STEAMID"|lang}:</td>
					<td>{if $ban_detail.player_id <> ""}{$ban_detail.player_id}{else}{"_NOSTEAMID"|lang}{/if}</td>
				</tr>
				<tr>
					<td class="fat">{"_EXPIRES"|lang}:</td>
					<td>{if $ban_detail.ban_length==0}{"_NOTAPPLICABLE"|lang}{else}{$ban_detail.ban_end|date_format:"%d.%m.%Y - %T"}{/if}</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td class="fat">{"_BANLENGHT"|lang}:</td>
					<td class="banDuration{if $ban_detail.ban_length==0}Perm">{"_PERMANENT"|lang}{elseif $ban_detail.ban_length==-1}">{"_UNBANNED"|lang}{else}">{$ban_detail.ban_length*60|date2word:true} ({$ban_detail.ban_length} {"_MINS"|lang}){/if}</td>
				</tr>
				<tr>
					<td class="fat">{"_REASON"|lang}:</td>
					<td>{$ban_detail.ban_reason}</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td class="fat">{"_BANBY"|lang}:</td>
					<td>{if $show_admin==1}<img alt="" src="images/country/{if $ban_detail.cc_admin}{$ban_detail.cc_admin|lower}{else}clear{/if}.png"> {$ban_detail.admin_nick}{if $ban_detail.nickname} <i>({$ban_detail.nickname})</i>{/if}{else}<i>{"_HIDDEN"|lang}</i>{/if}</td>
				</tr>
				<tr>
					<td class="fat">{"_BANON"|lang}:</td>
					<td>{$ban_detail.server_name}</td>
				</tr>
			</table>
			<div class="footer">
				<span style="float: left"><img src="images/gm_logo.png"></span>
				<span><img src="images/mr_logo.png"></span>
			</div>
		  </td>
		  <td class="b">
		</tr>
		<tr>
		  <td class="bl"><td class="b"><td class="br">
		</tr>
	  </tbody>
	</table>
</div>

</body>
</html>