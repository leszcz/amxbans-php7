{if $msg}
	<div class="notice">
		{$msg|lang}
	</div>
{/if}
		<td id="main" valign="top" >
		{if $smarty.session.amxadmins_view == "yes"}
			<span class="title">{"_USERMENUSETTINGS"|lang}</span>
			<table>
				<tr>
					<td>
				<table>
					<tr align="center" class="title"><td colspan="7" class="fat">{"_USERMENU"|lang}</td></tr>
					<tr class="title">
						<td width="1%" colspan="2">&nbsp;</td>
						<td align="center" colspan="2">{"_MENULOGGEDOUT"|lang}</td>
						<td align="center" colspan="2">{"_MENULOGGEDIN"|lang}</td>
						<td width="1%">&nbsp;</td>
					</tr>
					<tr align="center">
						<td width="1%">{"_POSITION"|lang}</td>
						<td width="1%">{"_ACTIV"|lang}</td>
						<td align="center">{"_LANGKEY1"|lang}</td>
						<td width="1%"align="center">{"_URL1"|lang}</td>
						<td align="center">{"_LANGKEY2"|lang}</td>
						<td width="1%" align="center">{"_URL2"|lang}</td>
						<td width="1%">&nbsp;</td>
					</tr>
					{section name=menu loop=$menu2 start=0 step=1}
						<form name="form" method="POST">
							<input type="hidden" name="mid" value="{$menu2[menu].id}" />
							<input type="hidden" name="pos" value="{$menu2[menu].pos}" />
							<tr>
								<td>
									<nobr>
										{if $menu2[menu].pos > 1}
												<input type="image" src="images/pfeilo.gif" name="pos_up" border="0" width="9px" height="8px" />
											{/if}
											{if $smarty.section.menu.index_next !== $menu_count}
												<input type="image" src="images/pfeilu.gif" name="pos_dn" border="0" width="9px" height="8px" 
													{if $menu2[menu].pos == 1}style="margin-left:11px;"{/if} />
											{/if}
									</nobr>
								</td>
								<td><input type="checkbox" name="activ" value="{$menu2[menu].id}" {if $menu2[menu].activ==1}checked{/if} />
								<td><nobr><input size="8" type="text" name="lang_key" value="{$menu2[menu].lang_key}" />{if $menu2[menu].lang_key} ("{$menu2[menu].lang_key|lang}"){/if}</nobr></td>
								<td><input size="15" type="text" name="url" value="{$menu2[menu].url}" /></td>
								<td><nobr><input size="8" type="text" name="lang_key2" value="{$menu2[menu].lang_key2}" />{if $menu2[menu].lang_key2} ("{$menu2[menu].lang_key2|lang}"){/if}</nobr></td>
								<td><input size="15" type="text" name="url2" value="{$menu2[menu].url2}" /></td>
								<td><nobr>
									<input type="submit" class="button" name="save" value="{"_SAVE"|lang}" {if $smarty.session.websettings_edit !== "yes"}disabled{/if} />
									<input type="submit" class="button" name="del" value="{"_DELETE"|lang}" {if $smarty.session.websettings_edit !== "yes"}disabled{/if} /></nobr>
								</td>
							</tr>
							
						</form>
					{/section}
					
				</table>
				{if $smarty.session.websettings_edit == "yes"}
					<form method="POST">
						<input type="hidden" name="pos" value="{$menu_count+1}" />
						<table width="50%" align="center">
							<tr class="title">
								<td colspan="3" class="fat">{"_USERMENUADD"|lang}</td>
							</tr>
							<tr>
								<tr class="info"><td><b>{"_LANGKEY1"|lang}:</b></td><td><input type="text" name="lang_key" /></td></tr>
								<tr class="info"><td><b>{"_URL1"|lang}:</b></td><td><input type="text" name="url" /></td></tr>
								<tr class="info"><td><b>{"_LANGKEY2"|lang}:</b></td><td><input type="text" name="lang_key2" /></td></tr>
								<tr class="info"><td><b>{"_URL2"|lang}:</b></td><td><input type="text" name="url2" /></td>
								<td>
										<input type="submit" class="button" name="new" value="{"_ADD"|lang}" {if $smarty.session.websettings_edit !== "yes"}disabled{/if} >
									</td>
								</tr>
							</tr>
						</table>
					</form>
				{/if}
			{else}
				{"_NOACCESS"|lang}
			{/if}
			</td></tr></table>
		</td>
	</tr>
</table>