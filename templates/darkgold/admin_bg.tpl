	{if $msg<>""}
		<div class="notice">
			{$msg|lang}
		</div>
	{/if}
		<td id="main" valign="top" >
		{if $smarty.session.amxadmins_view == "yes"}
			<span class="title">{"_REASONSSETTINGS"|lang}</span>
			<table>
				<tr>
					<td>
						<table>
							<tr class="title">
								<td colspan="3">{"_REASONSSETS"|lang}</td>
							</tr>
							{foreach from=$reasons_set item=reasons_set}
								<form method="POST">
									<input type="hidden" name="rsid" value="{$reasons_set.id}" />
									<tr class="list">
										<td>
											{if $reasons_set.setname == ""}&nbsp;{else}{$reasons_set.setname}{/if}
										</td>
										<td align="center">
											<input type="button" class="button" onclick="NewToggleLayer('layer_{$reasons_set.id}');" value="{"_EDIT"|lang}" />
											<input type="submit" class="button" name="delset" value="{"_DEL"|lang}" {if $smarty.session.servers_edit !== "yes"}disabled{/if} />
										</td>
									</tr>
									<tr id="layer_{$reasons_set.id}" style="display: none">
										<td colspan="3">
											<div style="display: none" align="center">
											<table class="details" width="95%">
												<form method="POST">
													<tr class="title">
														<td colspan="4">{"_EDITSET"|lang}</td>
													</tr>
													<tr class="info">
														<td>{"_NAME"|lang}:</td>
														<td><input type="text" name="setname" value="{$reasons_set.setname}" /></td>
														<td><input type="submit" class="button" name="saveset" value="{"_SAVESET"|lang}" {if $smarty.session.servers_edit !== "yes"}disabled{/if} /></td>
													</tr>
													<tr class="title">
														<td width="33%">{"_REASON"|lang}</td><td width="33%">{"_STATICBANTIME"|lang}</td><td>{"_ACTIV"|lang}</td>
													</tr>
													{section name=reasons loop=$reasons}
														<tr class="info">
															<td>{$reasons[reasons].reason}</td>
															<td>{$reasons[reasons].static_bantime}</td>
															<td><input type="checkbox" name="aktiv[]" value="{$reasons[reasons].id}" {$reasons_set.reasonids|strinstr:",":$reasons[reasons].id:"checked"} /></td>
														</tr>
													{/section}
												</form>
											</table></div>
										</td>
									</tr>
			
								</form>
							{/foreach}
							<div class="clearer"></div>
							<tr class="info">
								<form method="POST">
									<td align="center"><input type="text" name="setname" value="" /></td>
									<td align="center"><input type="submit" class="button" name="newset" value="{"_NEWSET"|lang}" {if $smarty.session.servers_edit !== "yes"}disabled{/if} /></td>
								</form>
							</tr>
						</table>
						
						<table border="1" width="100%">
							<tr class="title">
								<td colspan="3" class="fat">{"_REASONS"|lang}</td>
							</tr>
							<tr align="center" class="title">
								<td width="30%" align="center">{"_REASON"|lang}</td>
								<td align="center">{"_STATICBANTIME"|lang}</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								{section name=reasons loop=$reasons}
									<form method="POST">
										<input type="hidden" name="rid" value="{$reasons[reasons].id}" />
										<tr class="info">
											<td align="center"><input type="text" name="reason" value="{$reasons[reasons].reason}" /></td>
											<td align="center"><input type="text" name="static_bantime" value="{$reasons[reasons].static_bantime}" /></td>
											<td align="center">
												<input type="submit" class="button" name="reasonsave" value="{"_SAVE"|lang}" {if $smarty.session.servers_edit !== "yes"}disabled{/if} />
												<input type="submit" class="button" name="reasondel" value="{"_DEL"|lang}" {if $smarty.session.servers_edit !== "yes"}disabled{/if} />
											</td>
										</tr>
									</form>
								{/section}
							</tr>
							<tr><td colspan="3">&nbsp</td></tr>
							<tr class="info">
								<form method="POST">
									<td align="center"><input type="text" name="reason" value="" /></td>
									<td align="center"><input type="text" name="static_bantime" value="" /></td>
									<td align="center"><input type="submit" class="button" name="newreason" value="{"_NEWREASON"|lang}" {if $smarty.session.servers_edit !== "yes"}disabled{/if} /></td>
								</form>
							</tr>
						</table>
					{else}
						{"_NOACCESS"|lang} !!
					{/if}
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>