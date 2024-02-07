<div class="main" id="main-two-columns">
	<div class="left" id="main-left">
		<table> 
			<tr class="title"> 
				<td style="width:20px;">{"_MOD"|lang}</th> 
				<td style="width:20px;">{"_OS"|lang}</th> 
				<td style="width:20px;">{"_VAC"|lang}</th> 
				<td>{"_HOSTNAME"|lang}</th> 
				<td style="width:50px;">{"_PLAYER"|lang}</th> 
				<td style="width:200px;">{"_MAP"|lang}</th>
			</tr> 
			{if $error}
				<tr> 
					<td class="_center" colspan="6">{$error|lang}</td>
				</tr> 
			{else}
					
					{foreach from=$server item=serv}
						
						{if $serv.game}
							<tr onclick="NewToggleLayer('info_{$serv.sid}');" class="list"> 
								<td class="_center"><img alt="{$serv.game}" title="{$serv.game}" src="images/games/{$serv.mod}.gif" /></td> 
								<td class="_center"><img alt="{$serv.os}" title="{$serv.os}" src="images/os/{$serv.os}.png" /></td> 
								<td class="_center"><img alt="{"_VAC_ALT"|lang}" title="{"_VAC_ALT"|lang}" src="images/acheat/vac.png" /></td> 
								<td>{$serv.hostname}</td> 
								<td class="_center">
									{if $serv.bot_players}
										{$serv.cur_players-$serv.bot_players} ({$serv.cur_players})
									{else}
										{$serv.cur_players}
									{/if} 
									 / {$serv.max_players}
								</td> 
								<td>{$serv.map}</td> 
							</tr> 
							<tr id="info_{$serv.sid}" style="display: none"> 
								<td class="server-info" colspan="6">
									<div style="display:none">
										<table>
												<tr>
													<td style="vertical-align:top;padding-left:0px;padding-right:0px;">
														<table>
															<thead> 
																<tr class="title"> 
																	<td class="b">{"_NAME"|lang}</th> 
																	<td class="b" style="width:30px;">{"_FRAGS"|lang}</th> 
																	<td class="b" style="width:90px;">{"_ONLINE"|lang}</th>
																</tr> 
																{if $serv.cur_players >= 1}
																	{foreach from=$serv.players item=player}
																	<tr class="info">
																		<td class="vtop">{if $player.name != ""} {$player.name}{else} {"_PLAYERCONNECTING"|lang}{/if}</td> 
																		<td class="_center vtop">{$player.frag}</td> 
																		<td class="vtop">{$player.time}</td>
																	</tr>
																	
																	{/foreach}
																{else}
																	<tr>
																		<td colspan="3">{"_NOPLAYERS"|lang}</td> 
																	</tr>
																	
																{/if}
																<!-- Users Online -->
														</table>
													</td>
													<td>
														<table>
																<tr class="title"> 
																	<th class="_center fat" colspan="2">{$serv.address}</th> 
																</tr> 
																<tr>
																	<td class="_center" colspan="2">
																		<img style="border:1px #000000 solid;" src="images/maps/{$serv.mod}/{$serv.mappic}.jpg" alt="{$serv.map}" width="80%">
																	</td>
																</tr>
																<tr class="info">
																	<td class="_center">
																		
																				<a href="steam://connect/{$serv.address}" title="{"_CONNECT"|lang}" class="icons-connect icon-steam"></a>
																	</td>
																	<td class="_center">
																		<a href="hlsw://{$serv.address}" title="{"_ADDHLSW"|lang}">
																		<span title="{"_ADDHLSW"|lang}" class="icons-connect icon-hlsw" ></span>
																		</a>
																	</td>
																</tr>
																<tr class="info">
																	<td class="b">{"_NEXTMAP"|lang}</td>
																	<td>{$serv.nextmap}</td>
																</tr>
																<tr class="info">
																	<td class="b">{"_FRIENDLYFIRE"|lang}</td>
																	<td>{if $serv.friendlyfire==1}{"_YES"|lang}{else}{"_NO"|lang}{/if}</td>
																</tr>
																<tr class="info">
																	<td class="b">{"_TIMELEFT"|lang}</td>
																	<td>{$serv.timeleft} {"_MINS"|lang}</td>
																</tr>
																<tr class="info">
																	<td class="b">{"_PASSWORD"|lang}</td>
																	<td>{if $serv.password==1}{"_YES"|lang}{else}{"_NO"|lang}{/if}</td>
																</tr>
														</table>
													</td>
												</tr>
											</table>
									</div>
								</td> 
							</tr> 
							<!-- Server Online -->
						{else}
							<tr class="offline"> 
								<td class="_center"><img alt="{$serv.mod}" title="{$serv.mod}" src="images/games/{$serv.mod}.gif" /></td> 
								<td class="_center">{"_NA"|lang}</td> 
								<td class="_center">{"_NA"|lang}</td> 
								<td>{$serv.hostname}</td> 
								<td colspan="2">{"_SERVEROFFLINE"|lang}</td>
							</tr> 
						{/if}
					{/foreach}
				{/if}
			</tbody> 
		</table> 
		<div class="clearer">&nbsp;</div>
	</div>

	<div class="right sidebar" id="sidebar">
		<div class="section">
			<div class="section-title">
				<div class="left">{"_STATS"|lang}</div>
				<div class="right"><span title="{"_STATS"|lang}" class="icons-stats icon-stats"></span></div>
				
				<div class="clearer">&nbsp;</div>

			</div>
			<div class="section-content">
				<ul class="nice-list">
					<li>
						<div class="left">{"_BANSINDB"|lang}</div>
						<div class="right">{$stats.total}</div>
						<div class="clearer">&nbsp;</div>
					</li>

					<li>
						<div class="left">{"_ACTIVEBANS"|lang}</div>
						<div class="right">{$stats.active}</div>
						<div class="clearer">&nbsp;</div>
					</li>
					
					<li>
						<div class="left">{"_PERM_BANS"|lang}</div>
						<div class="right">{$stats.permanent}</div>
						<div class="clearer">&nbsp;</div>
					</li>

					<li>
						<div class="left">{"_TEMP_BANS"|lang}</div>
						<div class="right">{$stats.temp}</div>
						<div class="clearer">&nbsp;</div>
					</li>
					<li>
						<div class="left">{"_ADMINS"|lang}</div>
						<div class="right">{$stats.admins}</div>
						<div class="clearer">&nbsp;</div>
					</li>
					
					<li>
						<div class="left">{"_ACTIVE_SERVERS"|lang}</div>
						<div class="right">{$stats.servers}</div>
						<div class="clearer">&nbsp;</div>
					</li>
					
				</ul>
			</div>
		</div>

		<div class="section">
			<div class="section-title">
				<div class="left">{"_LATEST_BAN"|lang}</div>
				<div class="right"><span title="{"_LATEST_BAN"|lang}" class="icons-stats icon-clock"></span></div>
				
				<div class="clearer">&nbsp;</div>
			</div>

			<div class="section-content">
				<ul class="nice-list">
					<li>
						<div class="left">{"_PLAYER"|lang}</div>
						<div class="right">{$last_ban.nickname}</div>
						<div class="clearer">&nbsp;</div>
					</li>
					
					<li>
						<div class="left">{"_STEAMID"|lang}</div>
						<div class="right">{if $last_ban.steamid == "SI"}{"_NOTAVAILABLE"|lang}{else}{$last_ban.steamid}{/if}</div>
						<div class="clearer">&nbsp;</div>
					</li>
					
					<li>
						<div class="left">{"_REASON"|lang}</div>
						<div class="right">{$last_ban.reason}</div>
						<div class="clearer">&nbsp;</div>
					</li>
					
					<li>
						<div class="left">{"_DATE"|lang}</div>
						<div class="right">{$last_ban.created|date_format:"%Y-%m-%d %H:%M"}</div>
						<div class="clearer">&nbsp;</div>
					</li>
					
					<li>
						<div class="left">{"_EXPIRES"|lang}</div>
						<div class="right">{if $last_ban.length == 0}{"_NEVER"|lang}{else}{if $last_ban.time > $last_ban.length}{"_ALREADYEXP"|lang}{else}{$last_ban.length|date_format:"%Y-%m-%d %H:%M"}{/if}{/if}</div>
						<div class="clearer">&nbsp;</div>
					</li>

					<li><a href="ban_list.php">{"_BROWSE_ALL"|lang} &#187;</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="clearer">&nbsp;</div>
</div>