{if $smarty.session.bans_edit=="yes" || ($smarty.session.bans_edit=="own" && $smarty.session.uname == $ban_detail.nickname || $smarty.session.uname == $ban_detail.admin_nick)}
	<td colspan="10">
		<div style="display:none;" align="center">
			<table class="details">
				<tr class="title">
					<td>{"_EDITBAN"|lang}</td>
				</tr>
				<tr>
					<td colspan="2">
						<table width="100%">
							<form method="post">
								<input type="hidden" name="site" value="{$site}" />
								<input type="hidden" name="bid" value="{$ban_detail.bid}" />
								<input type="hidden" name="details_x" value="1" />
								<input type="hidden" name="ban_length_old" value="{$ban_detail.ban_length}" />
								<input type="hidden" name="ban_created" value="{$ban_detail.ban_created}" />
								<input type="hidden" name="ban_type" value="{$ban_detail.ban_type}" />
	
								<tr class="info">
									<td class="fat">{"_NICKNAME"|lang}:</td>
									<td>
										<input type="text" size="40" id="id0" name="player_nick" value="{$ban_detail.player_nick}" {if $ban_detail.ban_length == -1}disabled{/if} />
									</td>
								</tr>
								<tr class="info">
									<td class="fat">{"_STEAMID"|lang}:</td>
									<td>
										<input type="text" size="40" id="id1" name="player_id" value="{$ban_detail.player_id}" {if $ban_detail.ban_length == -1}disabled{/if} />
									</td>
								</tr>
								<tr class="info"><td class="fat">{"_IP"|lang}:</td>
									<td>
										{if $smarty.session.ip_view=="yes"}
											<input type="text" size="40" id="id2" name="player_ip" value="{$ban_detail.player_ip}" {if $ban_detail.ban_length == -1}disabled{/if} />{else}<i>{"_HIDDEN"|lang}</i>
										{/if}
									</td>
								</tr>
								<tr class="info">
									<td class="fat">{"_REASON"|lang}:</td>
									<td>
										<input type="text" size="40" id="id4" name="ban_reason" value="{$ban_detail.ban_reason}" {if $ban_detail.ban_length == -1}disabled{/if}/>
									</td>
								</tr>
								<tr class="info">
									<td class="fat">{"_BANLENGHT"|lang}:</td>
									<td>
										{if $smarty.session.bans_edit=="yes" || ($smarty.session.bans_edit=="own" && $smarty.session.uname == $ban_detail.nickname || $smarty.session.uname == $ban_detail.admin_nick)}
											<input type="text" size="10" id="id5" name="ban_length" value="{if $ban_detail.ban_length != -1}{$ban_detail.ban_length}{/if}"  {if $ban_detail.ban_length == -1}disabled{/if}/> {"_MINS"|lang}
											<b><input type="checkbox" 
												onclick="this.form.id0.disabled=this.checked;
														this.form.id1.disabled=this.checked;
														this.form.id2.disabled=this.checked;
														this.form.id3.disabled=this.checked;
														this.form.id4.disabled=this.checked;
														this.form.id5.disabled=this.checked" name="unban"
														/> {"_UNBANPLAYER"|lang}
										{else}
											{$ban_detail.ban_length}
										{/if} 
									</td>
								</tr>
								<tr class="info">
									<td class="fat">{"_EDITREASON"|lang}:</td>
									<td>
										<textarea name="edit_reason" id="edit_reason" cols="50" rows="3" wrap="soft"></textarea>
									</td>
								</tr>
							</table>
							<div class="_right"><input type="submit" class="button" name="edit_ban" onclick="return confirm('{"_SAVEEDIT"|lang}');" value="{"_SAVE"|lang}" /></div>
						</form>
						{if $ban_details_edits}
							<br />
							<table width="100%" cellspacing="0" border="1">
								<tr class="title">
									<td colspan="3">{"_BANDETAILSEDITS"|lang}</td>
								</tr>
								<tr class="info">
									<td width="1%"><b>{"_DATE"|lang}</b></td>
									<td width="1%"><b>{"_ADMIN"|lang}</b></td>
									<td><b>{"_EDITREASON"|lang}</b></td>
								</tr>
								{foreach from=$ban_details_edits item=ban_details_edits}
									<tr class="info">
										<td nowrap>{$ban_details_edits.edit_time|date_format:"%Y-%m-%d"}</td>
										<td nowrap>{$ban_details_edits.admin_nick}</td>
										<td>{$ban_details_edits.edit_reason|bbcode2html}</td>
									</tr>
								{/foreach}
							</table>
						{/if}
					</td>
				</tr>
			</table>
		</div>
	</td>
{/if}