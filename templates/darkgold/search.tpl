<div class="main">
	<table>
		<tr class="title">
			<td style="width:150px;" class="fat">{"_SEARCH"|lang}</th> 
			<td>&nbsp;</th>
			<td style="width:50px;">&nbsp;</th>
		</tr>
		<tr>
			<form method="post" style="display:inline;">
				<td class="b">{"_NICKNAME"|lang}</td> 
				<td><input type="text" size="40" name="nick" style="width:200px;" /></td> 
				<td><input type="submit" name="submit" class="button" value="{"_SEARCH"|lang}" /></td>
			</form>
		</tr> 
		<tr>
			<form method="post" style="display:inline;">
				<td class="b">{"_STEAMID"|lang}</td> 
				<td><input type="text" name="steamid" size="40" style="width:200px;"/></td> 
				<td><input type="submit" class="button" name="submit" value="{"_SEARCH"|lang}" /></td> 
			</form>
		</tr> 
		<tr>
			<form method="post" style="display:inline;">
				<td class="b">{"_IP"|lang}</td> 
				<td><input type="text" name="ip" size="40" style="width:200px;"/></td> 
				<td><input type="submit" class="button" name="submit" value="{"_SEARCH"|lang}"/></td>
			</form>
		</tr> 
		<tr> 
			<form method="post" style="display:inline;">
				<td class="b">{"_REASON"|lang}</td> 
				<td><input type="text" name="reason" size="40" style="width:200px;"/></td> 
				<td><input type="submit" name="submit" class="button" value="{"_SEARCH"|lang}"/></td>
			</form>
		</tr> 
		<tr> 
			<form method="post" name="searchdate" style="display:inline;">
				<td class="b">{"_DATE"|lang}</td> 
				<td>
					<input type="text" name="date" value="{$smarty.now|date_format:"%d-%m-%Y"}" style="width:200px;" />
					&nbsp;<script language="javascript" src="calendar1.js"></script>
					<a href="javascript:cal1.popup();">
						<img src="images/calendar.png" width="16" height="16" border="0" alt="{"_PICK_DATE"|lang}" title="{"_PICK_DATE"|lang}"/>
					</a>
				</td> 
				<td><input type="submit" class="button" value="{"_SEARCH"|lang}"/></td> 
			</form>
			<script language="javascript" type="text/javascript">
			<!--
				var cal1 = new calendar1(document.forms['searchdate'].elements['date']);
				cal1.year_scroll = true;
				cal1.time_comp = false;
			-->
			</script>
		</tr> 
		<tr> 
			<form method="post" style="display:inline;">
				<td class="b">{"_PLAYERSWITH"|lang}</td> 
				<td>
					<select name='timesbanned'> 
						<option value='2'>2</option> 
						<option value='3'>3</option> 
						<option value='4'>4</option> 
						<option value='5'>5</option> 
						<option value='6'>6</option> 
						<option value='7'>7</option> 
						<option value='8'>8</option> 
						<option value='9'>9</option> 
						<option value='10'>10</option> 
					</select>
					{"_MOREBANSHIS"|lang}
				</td> 
				<td><input type="submit" class="button" name="submit" value="{"_SEARCH"|lang}"/></td> 
			</form>
		</tr> 
		<tr> 
			<td class="b">{"_ADMIN"|lang}</td> 
			<td>
				<form method="post" name="form_admin" style="display:inline;">
					<select name="admin" size="1">
						<optgroup label="{"_AMXADMINS"|lang}">
							{foreach from=$amxadmins item=amxadmins}
								<option value="{$amxadmins.steam}">{$amxadmins.nick}</option>
							{/foreach}
						</optgroup>
					<!--	<optgroup label="{"_OTHER"|lang} {"_ADMINS"|lang}">
							{foreach from=$admins item=admins}
								<option value="{$admins.steam}">{$admins.nick}</option>
							{/foreach}
						</optgroup> -->
					</select>
				</form>
			</td> 
			<td><form method="post"><input type="button" class="button" onclick="form_admin.submit();" value="{"_SEARCH"|lang}"/></form></td> 
		</tr> 
		<tr> 
			<td class="b">{"_SERVER"|lang}</td> 
			<td>
				<form method="post" name="form_server" style="display:inline;">
					{html_options name=server options=$servers|lang}
				</form> 
			</td> 
			<td><form method="post"><input type="button" class="button" onclick="form_server.submit();" value="{"_SEARCH"|lang}"/></form></td> 
		</tr> 
	</table> 
</div>
<div class="clearer">&nbsp;</div>

{if $msg}
	<center class="admin_msg">{$msg|lang}</center><br />
{/if}
{if $search_done==1}
	<fieldset><legend><span class="title">{"_SEARCHRESULT"|lang}</span></legend>
		<table width="95%" cellpadding="2">
			<tr>
				<td>
					<table cellpadding="2">
						<tr>
							<td width="100%" colspan="6"><span style="font-weight:bold;color:red">{"_ACTIVEBANS"|lang} ({$ban_list_aktiv_count})</span></td>
						</tr>
						<tr class="title">
							<td width="80">{"_DATE"|lang}</td>
							<td>{"_PLAYER"|lang}</td>
							<td>{"_STEAMID"|lang}</td>
							<td>{"_ADMIN"|lang}</td>
							<td>{"_REASON"|lang}</td>
							<td width="80">{"_LENGHT"|lang}</td>
							<td></td>
						</tr>
						{foreach from=$ban_list_aktiv item=ban_list_aktiv}
							<tr class="list" style="cursor:pointer;" onClick="NewToggleLayer('layer_{$ban_list_aktiv.bid}');">
								<td>{$ban_list_aktiv.ban_created|date_format:"%d.%m.%Y"}</td>
								<td>{$ban_list_aktiv.player_nick}</td>
								<td>{if !in_array($ban_list_aktiv.player_id,  array('STEAM_ID_LAN', 'VALVE_ID_LAN', '', '0'))}{$ban_list_aktiv.player_id}{else}{"_NOTAVAILABLE"|lang}{/if}</td>
								<td>{$ban_list_aktiv.admin_nick}</td>
								<td>{$ban_list_aktiv.ban_reason}</td>
								<td nowrap>{if $ban_list_aktiv.ban_length>0}{$ban_list_aktiv.ban_length*60|date2word:true}{else}{"_PERMANENT"|lang}{/if}</td>
								<td><a href="ban_list.php?bid={$ban_list_aktiv.bid}"><img src="images/page.png" border="0" title="{"_DETAILS"|lang}"/></a></td>
							</tr>
							<tr id="layer_{$ban_list_aktiv.bid}" style="display: none">
								<td colspan=10>
									<div style="display: none" align="center">
										<table class="details">
											<tr class="title">
												<td style="width:200px;">{"_BANDETAILS"|lang}</td>
												<td></td>
											</tr>
											<tr class="info">
												<td class="b">{"_NICKNAME"|lang}</td>
												<td>{$ban_list_aktiv.player_nick}</td>
											</tr>
											{if !in_array($ban_list_aktiv.player_id,  array('STEAM_ID_LAN', 'VALVE_ID_LAN', '', '0'))}
												<tr class="info">
													<td class="b">{"_STEAMID"|lang}</td>
													<td>{$ban_list_aktiv.player_id}</td>
												</tr>
												<tr class="info">
													<td class="b">{"_STEAMCOMID"|lang}</td>
													<td>
														<a target="_blank" href="http://steamcommunity.com/profiles/{$ban_list_aktiv.player_comid}">{$ban_list_aktiv.player_comid}</a>
													</td>
												</tr>
											{/if}
											<tr class="info">
												<td class="b">{"_IP"|lang}</td>
												<td>
													{if $smarty.session.ip_view=="yes"}
														{if $ban_list_aktiv.player_ip}
															{$ban_list_aktiv.player_ip}
														{else}
															<i>{"_NOTAVAILABLE"|lang}</i>
														{/if}
													{else}
														<span style='font-style:italic;font-weight:bold'>{"_HIDDEN"|lang}</span>
													{/if}
												</td>
											</tr>
											<tr class="info">
												<td class="b">{"_BANTYPE"|lang}</td>
												<td>
													{if $ban_list_aktiv.ban_type=="S"}
														{"_STEAMID"|lang}
													{elseif $ban_list_aktiv.ban_type=="SI"}
														{"_STEAMID&IP"|lang}
													{else}
															{"_NOTAVAILABLE"|lang}
														{/if}
													</td>
												</tr>
											<tr class="info">
												<td class="b">{"_REASON"|lang}</td>
												<td>{$ban_list_aktiv.ban_reason}</td>
											</tr>
											<tr class="info">
												<td class="b">{"_INVOKED"|lang}</td>
												<td>{$ban_list_aktiv.ban_created|date_format:"%d.%m.%Y - %T"}</td>
											</tr>
											<tr class="info">
												<td class="b">{"_EXPIRES"|lang}</td>
												<td>
													{if $ban_list_aktiv.ban_length==0}
														<span style="font-weight:bold;color:red">{"_NOTAPPLICABLE"|lang}</span>
													{else}
														{$ban_list_aktiv.ban_end|date_format:"%d.%m.%Y - %T"}
														{if $ban_list_aktiv.ban_end < $smarty.now}
															({"_ALREADYEXP"|lang})
														{else}
															<i>({$ban_list_aktiv.ban_end-$smarty.now|date2word} {"_REMAINING"|lang})</i>
														{/if}
													{/if}
												</td>
											</tr>
											<tr class="info">
												<td class="b">{"_BANBY"|lang}</td>
												<td>{$ban_list_aktiv.admin_nick}{if $ban_list_aktiv.nickname} <span style="font-size: 12px">({$ban_list_aktiv.nickname})</span>{/if}</td>
											</tr>
											<tr class="info">
												<td class="b">{"_BANON"|lang}</td>
												<td>{if $ban_list_aktiv.server_name == "website"}{"_WEB"|lang}{else}{$ban_list_aktiv.server_name}{/if}</td>
											</tr>
											<tr class="info" style="border: 0">
												<td class="b">{"_TOTALEXPBANS"|lang}</td>
												<td>{$ban_list_aktiv.bancount}</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						{/foreach}
					</table>
					<br />
					<table cellpadding="2">
						<tr>
							<td width="100%" colspan="6"><span style="font-weight:bold;color:green">{"_EXPIREDBANS"|lang} ({$ban_list_exp_count})</span></td>
						</tr>
						<tr class="title">
							<td width="80">{"_DATE"|lang}</td>
							<td>{"_PLAYER"|lang}</td>
							<td>{"_STEAMID"|lang}</td>
							<td>{"_ADMIN"|lang}</td>
							<td>{"_REASON"|lang}</td>
							<td width="80">{"_LENGHT"|lang}</td>
							<td></td>
						</tr>
						{foreach from=$ban_list_exp item=ban_list_exp}
							<tr class="list" style="cursor:pointer;" onClick="NewToggleLayer('layer_{$ban_list_exp.bid}');">
								<td>{$ban_list_exp.ban_created|date_format:"%d.%m.%Y"}</td>
								<td>{$ban_list_exp.player_nick}</td>
								<td>{if !in_array($ban_list_exp.player_id,  array('STEAM_ID_LAN', 'VALVE_ID_LAN', '', '0'))}{$ban_list_exp.player_id}{else}{"_NOTAVAILABLE"|lang}{/if}</td>
								<td>{$ban_list_exp.admin_nick}</td>
								<td>{$ban_list_exp.ban_reason}</td>
								<td nowrap>{if $ban_list_exp.ban_length>0}{$ban_list_exp.ban_length*60|date2word:true}{else}{"_PERMANENT"|lang}{/if}</td>
								<td><a href="ban_list.php?bid={$ban_list_exp.bid}"><img src="images/page.png" border="0" title="{"_DETAILS"|lang}"/></a></td>
							</tr>
							<tr id="layer_{$ban_list_exp.bid}" style="display: none">
								<td colspan=10>
									<div style="display: none" align="center">
										<table class="details">
											<tr class="title">
												<td style="width:200px;">{"_BANDETAILS"|lang}</td>
												<td></td>
											</tr>
											<tr class="info">
												<td class="b">{"_NICKNAME"|lang}</td>
												<td>{$ban_list_exp.player_nick}</td>
											</tr>
											{if !in_array($ban_list_exp.player_id,  array('STEAM_ID_LAN', 'VALVE_ID_LAN', '', '0'))}
												<tr class="info">
													<td class="b">{"_STEAMID"|lang}</td>
													<td>{$ban_list_exp.player_id}</td>
												</tr>
												<tr class="info">
													<td class="b">{"_STEAMCOMID"|lang}</td>
													<td>
														<a target="_blank" href="http://steamcommunity.com/profiles/{$ban_list_exp.player_comid}">{$ban_list_exp.player_comid}</a>
													</td>
												</tr>
											{/if}
											<tr class="info">
												<td class="b">{"_IP"|lang}</td>
												<td>
													{if $smarty.session.ip_view=="yes"}
														{if $ban_list_exp.player_ip}
															{$ban_list_exp.player_ip}
														{else}
															<i>{"_NOTAVAILABLE"|lang}</i>
														{/if}
													{else}
														<span style='font-style:italic;font-weight:bold'>{"_HIDDEN"|lang}</span>
													{/if}
												</td>
											</tr>
											<tr class="info">
												<td class="b">{"_BANTYPE"|lang}</td>
												<td>
													{if $ban_list_exp.ban_type=="S"}
														{"_STEAMID"|lang}
													{elseif $ban_list_exp.ban_type=="SI"}
														{"_STEAMID&IP"|lang}
													{else}
															{"_NOTAVAILABLE"|lang}
														{/if}
													</td>
												</tr>
											<tr class="info">
												<td class="b">{"_REASON"|lang}</td>
												<td>{$ban_list_exp.ban_reason}</td>
											</tr>
											<tr class="info">
												<td class="b">{"_INVOKED"|lang}</td>
												<td>{$ban_list_exp.ban_created|date_format:"%d.%m.%Y - %T"}</td>
											</tr>
											<tr class="info">
												<td class="b">{"_EXPIRES"|lang}</td>
												<td>
													{if $ban_list_exp.ban_length==0}
														<span style="font-weight:bold;color:red">{"_NOTAPPLICABLE"|lang}</span>
													{else}
														{$ban_list_exp.ban_end|date_format:"%d.%m.%Y - %T"}
														{if $ban_list_exp.ban_end < $smarty.now}
															({"_ALREADYEXP"|lang})
														{else}
															<i>({$ban_list_exp.ban_end-$smarty.now|date2word} {"_REMAINING"|lang})</i>
														{/if}
													{/if}
												</td>
											</tr>
											<tr class="info">
												<td class="b">{"_BANBY"|lang}</td>
												<td>{$ban_list_exp.admin_nick}{if $ban_list_exp.nickname} <span style="font-size: 12px">({$ban_list_exp.nickname})</span>{/if}</td>
											</tr>
											<tr class="info">
												<td class="b">{"_BANON"|lang}</td>
												<td>{if $ban_list_exp.server_name == "website"}{"_WEB"|lang}{else}{$ban_list_exp.server_name}{/if}</td>
											</tr>
											<tr class="info" style="border: 0">
												<td class="b">{"_TOTALEXPBANS"|lang}</td>
												<td>{$ban_list_exp.bancount}</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						{/foreach}
					</table>
				</td>
			</tr>
		</table>
	</fieldset>
{/if}