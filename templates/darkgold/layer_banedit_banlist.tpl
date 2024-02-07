{if $smarty.session.bans_edit=="yes" || ($smarty.session.bans_edit=="own" && $smarty.session.uname == $ban_list.nickname || $smarty.session.uname == $ban_list.admin_nick)}
<td colspan="10">
	<div style="display:none;">
		<table>
			<tr class="title">
				<td>{"_EDITBAN"|lang}</td>
			</tr>
			<tr>
				<td colspan="2">
					<table width="100%">
						<form method="post" action="ban_list.php">
							<input type="hidden" name="site" value="{$site}" />
							<input type="hidden" name="bid" value="{$ban_list.bid}" />
							<input type="hidden" name="details_x" value="1" />
							<input type="hidden" name="ban_length_old" value="{$ban_list.ban_length}" />
							<input type="hidden" name="ban_created" value="{$ban_list.ban_created}" />
							<input type="hidden" name="ban_type" value="{$ban_list.ban_type}" />

							<tr class="info">
								<td class="b">{"_NICKNAME"|lang}:</td>
								<td>
									<input type="text" size="40" id="id0" name="player_nick" value="{$ban_list.player_nick}" {if $ban_list.ban_length == -1}disabled{/if} />
								</td>
							</tr>
							<tr class="info">
								<td class="b">{"_STEAMID"|lang}:</td>
								<td>
									<input type="text" size="40" id="id1" name="player_id" value="{$ban_list.player_id}" {if $ban_list.ban_length == -1}disabled{/if} />
								</td>
							</tr>
							<tr class="info"><td class="b">{"_IP"|lang}:</td>
								<td>
									{if $smarty.session.ip_view=="yes"}
										<input type="text" size="40" id="id2" name="player_ip" value="{$ban_list.player_ip}" {if $ban_list.ban_length == -1}disabled{/if} />{else}<i>{"_HIDDEN"|lang}</i>
									{/if}
								</td>
							</tr>
							<tr class="info">								<td class="b">{"_REASON"|lang}:</td>
								<td>
									<input type="text" size="40" id="id4" name="ban_reason" value="{$ban_list.ban_reason}" {if $ban_list.ban_length == -1}disabled{/if}/>
								</td>
							</tr>
							<tr class="info">								<td class="b">{"_BANLENGHT"|lang}:</td>
								<td>
									{if $smarty.session.bans_edit=="yes" || ($smarty.session.bans_edit=="own" && $smarty.session.uname == $ban_list.nickname || $smarty.session.uname == $ban_list.admin_nick)}
										<input type="text" size="10" id="id5" name="ban_length" value="{if $ban_list.ban_length != -1}{$ban_list.ban_length}{/if}"  {if $ban_list.ban_length == -1}disabled{/if}/> {"_MINS"|lang}
										<b><input type="checkbox" 
											onclick="this.form.id0.disabled=this.checked;
													this.form.id1.disabled=this.checked;
													this.form.id2.disabled=this.checked;
													this.form.id3.disabled=this.checked;
													this.form.id4.disabled=this.checked;
													this.form.id5.disabled=this.checked" name="unban"
													/> {"_UNBANPLAYER"|lang}
									{else}
										{$ban_list.ban_length}
									{/if} 
								</td>
							</tr>
							<tr class="info">								<td class="b">{"_EDITREASON"|lang}:</td>
								<td>
									<textarea name="edit_reason" id="edit_reason" cols="50" rows="3" wrap="soft"></textarea>
								</td>
							</tr>
						</table>
						<div class="_right"><input type="submit" class="button" name="edit_ban" onclick="return confirm('{"_SAVEEDIT"|lang}');" value="{"_SAVE"|lang}" /></div>
					</form>
				</td>
			</tr>
		</table>
	</div>
</td>
{/if}