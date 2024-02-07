<div class="main">
	<div class="post">
		<table> 
			<tr class="title fat"> 
				<td style="width:30px;">&nbsp;</th>
				<td style="width:20px;">{"_MOD"|lang}</th> 
				<td style="width:20px;">{"_OS"|lang}</th> 
				<td style="width:20px;">{"_VAC"|lang}</th> 
				<td>{"_HOSTNAME"|lang}</th> 
				<td style="width:50px;">{"_PLAYER"|lang}</th> 
				<td style="width:130px;">{"_MAP"|lang}</th> 
			</tr> 
			{foreach from=$servers item=servers}
				<form method="post" action="admin.php?site=ban_add_online" >
					<input type="hidden" name="server" value="{$servers.id}" />
					{if $servers.mod}
						<tr class="list"> 
							<td><form method="post" action="admin.php?site=ban_add_online"><input type="hidden" name="server" value="{$servers.id}"/><input class="button" type="submit" value="{"_VIEWSETTINGS"|lang}"/></form></td>
							<td class="_center"><img alt="{$servers.mod}" title="{$servers.mod}" src="images/games/{$servers.mod}.gif" /></td> 
							<td class="_center"><img alt="{$servers.os}" title="{$servers.os}" src="images/os/{$servers.os}.png" /></td> 
							<td class="_center"><img alt="{"_VAC_ALT"|lang}" title="{"_VAC_ALT"|lang}" src="images/acheat/vac.png" /></td> 
							<td>{$servers.hostname}</td> 
							<td class="_center">
								{if $servers.bot_players}
									{$servers.cur_players-$servers.bot_players} ({$servers.cur_players})
								{else}
									{$servers.cur_players}
								{/if} 
								 / {$servers.max_players}</td> 
							<td>{$servers.map}</td> 
						</tr>						
					{else}
						<tr class="offline"> 
							<td><form method="post"  action="admin.php?site=ban_add_online"><input type="hidden" name="server" value="0"/><input class="button" type="submit" value="{"_VIEWSETTINGS"|lang}" disabled="disabled"/></form></td>
							<td class="_center">{"_NA"|lang}</td> 
							<td class="_center">{"_NA"|lang}</td> 
							<td class="_center">{"_NA"|lang}</td> 
							<td>{$servers.hostname}</td> 
							<td class="_center">{"_NA"|lang}</td> 
							<td>{"_NA"|lang}</td> 
						</tr>
					{/if}
				</form>
			{/foreach}
			</tbody> 
		</table> 
		<div class="clearer">&nbsp;</div>
	</div>
	
	<form name="frm" method="post" action="admin.php?site=ban_add_online">
		<input type="hidden" name="server" value="{$server_select}" />
		{if $playerscount}
			<div class="post">
				<table> 
					<tr class="title">
						<td style="width:250px;">{"_ADDBAN"|lang}</th> 
						<td>&nbsp;</th>
					</tr>
					<tr class="info"> 
						<td class="b">{"_BANTYPE"|lang}</td> 
						<td>
							<select name="ban_type">
								<option label="Steamid" value="S">{"_STEAMID"|lang}</option>
								<option label="Steamid &amp; IP" value="SI">{"_STEAMID"|lang} &amp; {"_IP"|lang}</option>
							</select>
						</td> 
					</tr> 
					<tr class="info">
						<td class="b">{"_REASON"|lang}</td> 
						<td>
							<select name="ban_reason">
								{html_options output=$reasons values=$reasons}
							</select> 
							{"_OR"|lang} {"_NEWREASON"|lang}: <input type="text" size="30" name="user_reason" onkeyup="document.frm.ban_reason.disabled=(this.value!='');" />
						</td> 
					</tr> 
					<tr class="info">
						<td class="b">{"_BANLENGHT"|lang}</td> 
						<td>
							<input type="text" size="8" name="ban_length" disabled="disabled" /> {"_MIN_OR"|lang} <input type="checkbox" name="perm" checked="checked" onclick="document.frm.ban_length.disabled=this.checked;" /> {"_PERMANENT"|lang}
						</td> 
					</tr> 
				</table> 
				<div class="clearer">&nbsp;</div>
			</div>
		{/if}
		<div class="post">
			<table>
				<tr class="title"> 
					<td style="width:50px;">{"_NUMBER"|lang}</th>
					<td>{"_NAME"|lang}</th> 
					<td style="width:150px;">{"_STEAMID"|lang}</th> 
					<td style="width:150px;">{"_IP"|lang}</th>
					<!-- <td style="width:50px;">{"_USERID"|lang}</th> -->
					<td style="width:50px;">{"_STATUSNAME"|lang}</th>
					<td style="width:50px;">&nbsp;</th> 
					<td style="width:50px;">&nbsp;</th> 
				</tr> 
				<!-- Users Online -->
				{if $playerscount}
					{foreach from=$players item=players}
						<tr {if $smarty.session.bans_add!="yes"  || $players.status == 1 || $players.immunity != 0}class="offline"{else}class="list"{/if}> 
							<td>#{$players.userid}</td> 
							<td>{$players.name}</td> 
							<td>{$players.steamid}</td> 
							<td><img alt="" src="images/flags/{if $players.cc}{$players.cc|lower}{else}clear{/if}.png"> {$players.ip}</td> 
							<!-- <td class="_center">#{$players.userid}</td>  -->
							<td class="_center">{$players.statusname|lang}</td> 
							<td class="_center">
								<input type="submit" class="button" name="ban" onclick="LiveBanCopyVars('{$players.name}','{$players.steamid}','{$players.ip}','{$players.userid}');
								return confirm('{"_BANPLAYER"|lang}');"
								value="{"_BAN"|lang}" {if $smarty.session.bans_add!="yes"  || $players.status == 1 || $players.immunity != 0}disabled="disabled"{/if} />
							</td> 
							<td class="_center">
								<input type="submit" class="button" name="kick" onclick="LiveBanCopyVars('{$players.name}','{$players.steamid}','{$players.ip}','{$players.userid}');
								return confirm('{"_KICKPLAYER"|lang}');"
								value="{"_KICK"|lang}" {if $players.immunity != 0}disabled="disabled"{/if} />
							</td> 
						</tr>
					{/foreach}
				{else}
					<tr> 
						<td class="error _center" colspan="8">{if $smsg!=""}<b class="error">{$smsg|lang}{else}<b>{"_NOPLAYERS"|lang}{/if}</td> 
					</tr>
				{/if}
				<!-- Users Online -->
			</table> 
			<div class="clearer">&nbsp;</div>
		</div>
	
		<input type="hidden" name="player_name" id="player_name" value="" /> 
		<input type="hidden" name="player_uid" id="player_uid" value="" /> 
		<input type="hidden" name="player_steamid" id="player_steamid" value="" /> 
		<input type="hidden" name="player_ip" id="player_ip" value="" /> 
		<input type="hidden" name="server" id="server_id_c" value="{$server_select}" /> 
		
	</form>
	<div class="clearer">&nbsp;</div>
</div>