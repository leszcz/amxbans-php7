	{if $msg}
		<div class="notice">
			{$msg|lang}
		</div>
	{/if}
		<td id="main" valign="top" >
		{if $smarty.session.amxadmins_view == "yes"}
			<span class="title">{"_MODULSETTINGS"|lang}</span>
			<table width="95%">
				<tr>
					<td>
						<table>
							<tr class="title"><td colspan="8" class="fat">{"_MODULSETTINGS2"|lang}</td></tr>
							<tr class="title">
								<td width="1%">{"_ACTIV"|lang}</td>
								<td>{"_NAMELANGKEY"|lang}</td>
								<td width="1%">{"_INDEXSITE"|lang}</td>
								<td width="1%">{"_NAME"|lang}</td>
								<td width="1%"><nobr>{"_ADMINSITE"|lang}</nobr></td>
								<td width="1%">{"_TEMPLATE"|lang}</td>
								<td width="1%"><nobr>{"_INDEXSITE"|lang}</nobr></td>
								<td width="1%">&nbsp;</td>
							</tr>
							{section name=modules loop=$modules2 start=0 step=1}
								<form name="form" method="POST">
									<input type="hidden" name="mid" value="{$modules2[modules].id}" />
									<input type="hidden" name="mname" value="{$modules2[modules].menuname}" />
									<tr class="settings_line">
										
										<td><input type="checkbox" name="activ" value="{$modules2[modules].id}" {if $modules2[modules].activ==1}checked{/if} />
										<td><nobr><input type="text" name="menuname" value="{$modules2[modules].menuname}" /> ("{$modules2[modules].menuname|lang:"hlm"}")</nobr></td>
										<td><input type="text" name="index" value="{$modules2[modules].index}" /></td>
										<td><input type="text" size="12" name="name" value="{$modules2[modules].name}" /></td>
										<td><img src="images/{if $modules2[modules].admin_page_exists==0}warning{else}success{/if}.gif" /></td>
										<td><img src="images/{if $modules2[modules].tpl_exists==0}warning{else}success{/if}.gif" /></td>
										<td>
											{if $modules2[modules].index == ""}{"_NO"|lang}{else}
											<img src="images/{if $modules2[modules].index_exists==0}warning{else}success{/if}.gif" />{/if}
										</td>
										<td>
											<input type="submit" class="button" name="save" value="{"_SAVE"|lang}" {if $smarty.session.websettings_edit !== "yes"}disabled{/if} />
										</td>
									</tr>
								</form>
							{/section}
						</table>
						<br />
					{else}
						{"_NOACCESS"|lang}
					{/if}
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>