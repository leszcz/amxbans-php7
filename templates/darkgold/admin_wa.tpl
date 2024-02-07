{if $msg}
	<div class="notice">
		{foreach from=$msg item=msg}
			{$msg|lang}
		{/foreach}
	</div>
{/if}
		<td id="main" valign="top" >
		{if $smarty.session.amxadmins_view == "yes"}
			<span class="title">{"_WEBADMINSSETTINGS"|lang}</span>
			<table>
				<tr>
					<td>
				<table>
					<tr class="title"><td colspan="5" class="fat">{"_WEBADMINS"|lang}</td></tr>
					<tr class="title">
						<td>{"_NAME"|lang}</td>
						<td align="center" width="1%">{"_LEVEL"|lang}</td>
						<td width="1%">{"_EMAIL"|lang}<td align="center" width="1%">{"_LASTLOGIN"|lang}</td>
						<td width="1%"><b>&nbsp;</b></td>
					</tr>
					{foreach from=$users item=users}
						<form method="POST" name="{$users.uid}">
							<input type="hidden" name="uid" value="{$users.uid}" />
							<input type="hidden" name="newpw" id="newpw{$users.uid}" value="" />
							<tr class="list">
								<td align="center">
									{if $smarty.session.webadmins_edit == "yes"}
										<input type="text" name="name" value="{$users.name}"/>
									{else}
										<input type="hidden" name="name" value="{$users.name}"/>
										<b>{$users.name}</b>
									{/if}
								</td>
								<td align="center" width="1%">
									{if $smarty.session.webadmins_edit == "yes"}
										{html_options name=level values=$levels output=$levels selected=$users.level}
									{else}
										<input type="hidden" name="level" value="{$users.level}"/>
										{$users.level}
									{/if}
								</td>
								<td align="center"><input type="text" size="40" name="email" value="{$users.email}" {if !($smarty.session.uname == $users.name || $smarty.session.webadmins_edit == "yes")}disabled{/if}/></td>
								<td align="center"><nobr>
									{if $users.last_action}
										{$users.last_action|date_format:"%d.%m.%Y - %T"}
									{else}
										<i>{"_NEVER"|lang}</i>
									{/if}
								</nobr></td>
								<td align="center"><nobr>
											<input type="submit" class="button" name="save" value="{"_SAVE"|lang}" {if !($smarty.session.uname == $users.name || $smarty.session.webadmins_edit == "yes")}disabled{/if} />
											&nbsp;
											<input type="submit" class="button" name="del" value="{"_DELETE"|lang}" onclick="return confirm('{"_DELADMIN"|lang}');" {if $smarty.session.webadmins_edit !== "yes"}disabled{/if} />
											&nbsp;
											<input type="submit" class="button" name="setnewpw" value="{"_NEWPASSWORD"|lang}" 
												onclick="{if $smarty.session.uname == $users.name}alert('{"_YOURPASSWORD"|lang}');{/if}return SetNewPassword('newpw{$users.uid}','{"_ENTERPASSWORD"|lang}','{"_PASSWORD2"|lang}','{"_PASSWORDNOTMATCH"|lang}');" 
													{if !($smarty.session.uname == $users.name || $smarty.session.webadmins_edit == "yes")}disabled{/if} />
								
								</nobr></td>
							</tr>
						</form>
					{/foreach}
				</table>
				{if $smarty.session.webadmins_edit == "yes"}
					<form method="POST">
						<table border="1" width="50%">
							<tr class="title">
								<td colspan="4">{"_WEBADMINADD"|lang}</td>
							</tr>
							<tr class="info"><td>{"_NAME"|lang}:</td><td align="left"><input type="text" name="name" value="{$input.name}" /></td></tr>
							<tr class="info"><td>{"_EMAIL"|lang}:</td><td align="left"><input type="text" size="40" name="email" value="{$input.email}" /></td></tr>
							<tr class="info"><td>{"_PASSWORD"|lang}:</td><td align="left"><input type="password" name="pw" /></td></tr>
							<tr class="info"><td>{"_PASSWORD2"|lang}:</td><td align="left"><input type="password" name="pw2" /></td></tr>
							<tr class="info">
								<td>{"_LEVEL"|lang}:</td>
								<td>{html_options name=level values=$levels output=$levels selected=$input.level}</td>
								<td>
									<input type="submit" class="button" name="new" value="{"_ADD"|lang}" {if $smarty.session.webadmins_edit !== "yes"}disabled{/if} />&nbsp;
									<input type="reset" class="button" value="{"_CLEAR"|lang}">
								</td>
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