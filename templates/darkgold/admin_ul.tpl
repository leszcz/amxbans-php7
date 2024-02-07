	{if $msg}
		<div class="notice">
			{$msg|lang}
		</div>
	{/if}
		<td id="main" valign="top" >
		{if $smarty.session.amxadmins_view == "yes"}
			<span class="title">{"_ADMINLEVELSETTINGS"|lang}</span>
			<table>
				<tr>
					<td>
				{foreach from=$levels item=levels}
					<form method="POST">
						<input type="hidden" name="lid" value="{$levels.level}"></input>
						<fieldset><legend><span class="title">{"_LEVEL"|lang} #{$levels.level}</span></legend>
							<table style="border:1px;" width="100%">
								<tr class="title">
									<td class="fat">{"_LEVEL"|lang}</td><td colspan="6" align="center" class="fat">{"_BANS"|lang}</td><td colspan="2" class="fat">{"_AMXADMINS"|lang}</td><td>&nbsp</td>
										
								</tr>
								<tr class="title">
									<td>&nbsp</td>
									<td>{"_ADD"|lang}</td><td>{"_EDIT"|lang}</td><td>{"_DELETE"|lang}</td><td>{"_LEVELUNBAN"|lang}</td><td>{"_LEVELIMPORT"|lang}</td><td>{"_LEVELEXPORT"|lang}</td>
									<td>{"_LEVELVIEW"|lang}</td><td>{"_EDIT"|lang}</td><td>&nbsp</td>
								</tr>
								<tr>
									<td rowspan="4" style="{if $levels.level==$smarty.session.level}background-color:#00aa00;{/if}text-align:center;">{$levels.level}</td>
									<td>{html_options name=bans_add values=$choose1 output=$output1|lang selected=$levels.bans_add}</td>
									<td>{html_options name=bans_edit values=$choose2 output=$output2|lang selected=$levels.bans_edit}</td>
									<td>{html_options name=bans_delete values=$choose2 output=$output2|lang selected=$levels.bans_delete}</td>
									<td>{html_options name=bans_unban values=$choose2 output=$output2|lang selected=$levels.bans_unban}</td>
									<td>{html_options name=bans_import values=$choose1 output=$output1|lang selected=$levels.bans_import}</td>
									<td>{html_options name=bans_export values=$choose1 output=$output1|lang selected=$levels.bans_export}</td>
									<td>{html_options name=amxadmins_view values=$choose1 output=$output1|lang selected=$levels.amxadmins_view}</td>
									<td>{html_options name=amxadmins_edit values=$choose1 output=$output1|lang selected=$levels.amxadmins_edit}</td>
									<td rowspan="4" {if $levels.level==$smarty.session.level}style="background-color: #00aa00;" {/if}>
											<input style="margin:0 auto;display:block;" type="submit" class="button" name="save" value="{"_SAVE"|lang}" {if $smarty.session.permissions_edit !== "yes"}disabled{/if} />
											{if ($levels.level == $level_max && $levels.level > 1)}
											<input style="margin:0 auto;display:block;" type="submit" class="button" name="del" value="{"_DELETE"|lang}" onclick="return confirm('{"_DELLEVEL"|lang}');" {if $smarty.session.permissions_edit !== "yes"}disabled{/if} />
											{/if}
										</td>
								</tr>
								<tr class="title">
									<td colspan="2" class="fat">{"_WEBADMINS"|lang}</td><td colspan="2" class="fat">{"_WEBSETTINGS"|lang}</td><td colspan="4" class="fat">{"_OTHER"|lang}</td>
								</tr>
								<tr class="title">
									<td>{"_LEVELVIEW"|lang}</td><td>{"_EDIT"|lang}</td>
									<td>{"_LEVELVIEW"|lang}</td><td>{"_EDIT"|lang}</td>
									<td>{"_PERM"|lang}</td><td>{"_DBPRUNE"|lang}</td><td>{"_SERVEREDIT"|lang}</td><td>{"_VIEWIP"|lang}</td>
								</tr>
								<tr align="center">
									<td>{html_options name=webadmins_view values=$choose1 output=$output1|lang selected=$levels.webadmins_view}</td>
									<td>{html_options name=webadmins_edit values=$choose1 output=$output1|lang selected=$levels.webadmins_edit}</td>
									<td>{html_options name=websettings_view values=$choose1 output=$output1|lang selected=$levels.websettings_view}</td>
									<td>{html_options name=websettings_edit values=$choose1 output=$output1|lang selected=$levels.websettings_edit}</td>
									
									<td>{html_options name=permissions_edit values=$choose1 output=$output1|lang selected=$levels.permissions_edit}</td>
									<td>{html_options name=prune_db values=$choose1 output=$output1|lang selected=$levels.prune_db}</td>
									<td>{html_options name=servers_edit values=$choose1 output=$output1|lang selected=$levels.servers_edit}</td>
									<td>{html_options name=ip_view values=$choose1 output=$output1|lang selected=$levels.ip_view}</td>
								</tr>
							</table>
						</fieldset>
						<div class="clearer">&nbsp</div>
					</form>
				{/foreach}
				<form method="POST">
					<div class="_right">
						<input type="submit" class="button" name="new" value="{"_NEWLEVEL"|lang}" {if $smarty.session.permissions_edit !== "yes"}disabled{/if} />
					</div>
				</form>
			{else}
				{"_NOACCESS"|lang}
			{/if}
			</td></tr></table>
		</td>
	</tr>
</table>