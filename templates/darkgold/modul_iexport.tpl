		<td id="main" valign="top" >
		{if $smarty.session.amxadmins_view == "yes"}
			<span class="title">{"_DATAIMPORTEXPORT"|lang}</span>
			<table>
				<tr>
					<td>
						{if $smarty.session.bans_import == "yes" && $smarty.session.bans_export == "yes"}
						<table border="1" width="100%">
							<tr class="title"><td colspan="2" class="fat">{"_DATABASE"|lang}</td></tr>
							<form method="POST">
							<tr class="info">
								<td valign="top">&nbsp;<b>{"_LOCALBACKUPS"|lang}:</b><br /> &nbsp;<select name="localfile" style="width: 200px;">{html_options values=$backups output=$backups}</select></td>
								<td width="1%">
									<input type="submit" class="button" class="button" name="dbdownfile" value="{"_DOWNLOAD"|lang}" {if $count==0}disabled="disabled"{/if} /><br />
									<input type="submit" class="button" class="button" name="delfile" value="{"_DELETE"|lang}" onclick="return confirm('{"_DELBACKUP"|lang}{"_DATALOSS"|lang}');" {if $count==0}disabled="disabled"{/if} />
								</td>
							</tr>
							</form>
							<tr class="title">
								<td colspan="2" class="fat">{"_BACKUPALL"|lang}</td>
							</tr>
							<form method="POST">
							<tr class="info">
								<td>
									<input type="checkbox" name="structur" /> {"_ONLYSTRUCTUR"|lang}<br />
									<input type="checkbox" name="droptable" /> {"_INCLUDEDROP"|lang}<br />
									<input type="checkbox" name="deleteall" /> {"_INCLUDEDELETE"|lang}<br />
									<input type="checkbox" name="download" /> {"_DOWNLOADAFTER"|lang}
								</td>
								<td valign="bottom" width="1%"><input type="submit" class="button" name="dbexp" value="{"_BACKUP"|lang}" /></td>
							</tr>
							</form>
							<tr class="title">
								<td colspan="2" class="fat">{"_BACKUPBANS"|lang}</td>
							</tr>
							<form method="POST">
							<tr class="info">
								<td>
									<input type="checkbox" name="download" /> {"_DOWNLOADAFTER"|lang}
								</td>
								<td valign="bottom" width="1%"><input type="submit" class="button" name="dbbansexp" value="{"_BACKUP"|lang}" /></td>
							</tr>
							</form>
							
						</table>
						<br />
						{/if}
						
						<table border="1" width="100%">
							<tr class="title"><td colspan="7" class="fat">{"_BANSIEXPORT"|lang}</td></tr>
							{if $smarty.session.bans_import == "yes"}
								<tr class="titlebottom">
									<td colspan="2">{"_IMP_FILE"|lang}</td>
								</tr>
								<form name="bannedcfg" method="POST" enctype="multipart/form-data">
								<tr class="info">
									<td>
										&nbsp;<input size="32" type="text" name="reason" value="{"_IMPORT"|lang}" /> {"_REASON"|lang}<br />
										&nbsp;<input size="32" type="text" name="player_nick" value="{"_UNKNOWN"|lang}" /> {"_NICKNAME"|lang}<br />
										&nbsp;<input size="32" type="text" name="server_name" value="{"_WEB"|lang}" /> {"_SERVER"|lang}<br />
										&nbsp;<input size="28" type="text" name="ban_created" value="{$smarty.now|date_format:"%d-%m-%Y"}" />
										&nbsp;<script language="javascript" src="calendar1.js" /><a href="javascript:cal1.popup();"><img src="images/date.png" width="16" height="16" border="0" title="Pick a date"></a>
											 {"_DATE"|lang}<br />
										&nbsp;<input class="input_file" type="file" size="30" name="filename">
									</td>
									<td width="1%" valign="bottom"><input type="submit" class="button" name="bancfgupl" onclick="return confirm('{"_DATAIMPORT"|lang}');" value="{"_IMPORT"|lang}" /></td>
									<script language="JavaScript">
									<!--
										var cal1 = new calendar1(document.forms['bannedcfg'].elements['ban_created']);
										cal1.year_scroll = true;
										cal1.time_comp = false;
									-->
									</script>
								</tr>
								<tr class="title">
									<td colspan="2">{"_IMP_DB"|lang}</td>
								</tr>
								<tr class="info">
									<td>{if $dbcheck == "OK"}
										<input type="hidden" name="impdbhost" value="{$dbdata.host}" />
										<input type="hidden" name="impdbuser" value="{$dbdata.user}" />
										<input type="hidden" name="impdbpw" value="{$dbdata.pass}" />
										<input type="hidden" name="impdbdb" value="{$dbdata.database}" />
										<input type="hidden" name="impdbtable" value="{$dbdata.table}" />
										{/if}
										&nbsp;<input size="32" type="text" name="impdbhost" value="{if $dbdata}{$dbdata.host}{else}localhost{/if}" {if $dbcheck == "OK"}disabled="disabled"{/if} /> {"_DBHOST"|lang}<br />
										&nbsp;<input size="32" type="text" name="impdbuser" value="{if $dbdata}{$dbdata.user}{else}user{/if}"  {if $dbcheck == "OK"}disabled="disabled"{/if} /> {"_DBUSER"|lang}<br />
										&nbsp;<input size="32" type="password" name="impdbpw" value="{if $dbdata}{$dbdata.pass}{/if}"  {if $dbcheck == "OK"}disabled="disabled"{/if} /> {"_DBPASSWORD"|lang}<br />
										&nbsp;<input size="32" type="text" name="impdbdb" value="{if $dbdata}{$dbdata.database}{else}amxbans{/if}"  {if $dbcheck == "OK"}disabled="disabled"{/if} /> {"_DBDATABASE"|lang}<br />
										&nbsp;<input size="32" type="text" name="impdbtable" value="{if $dbdata}{$dbdata.table}{else}amx_bans{/if}"  {if $dbcheck == "OK"}disabled="disabled"{/if} /> {"_DBTABLE"|lang}<br />
										<input type="checkbox" name="onlyperm" {if $dbdata.onlyperm}checked{/if} /> {"_ONLYPERMANENT"|lang}<br />
										<input type="checkbox" name="dellocal" {if $dbdata.dellocal}checked{/if} /> {"_DELETELOCALTABLE"|lang}<br />
									</td>
									<td width="1%" valign="bottom">
										{if $dbcheck == "OK"}<img src="images/success.gif" />{/if}
										<input type="submit" class="button" name="bandbcheck" value="{"_CONCHECK"|lang}" {if $dbcheck == "OK"}disabled="disabled"{/if} />
										<input type="submit" class="button" name="bandbimp" value="{"_IMPORT"|lang}" onclick="return confirm('{"_DATAIMPORT"|lang}');" {if $dbcheck != "OK"}disabled="disabled"{/if} />
									</td>
									
								</tr>
								<tr class="info">
									<td>&nbsp;{"_DELALLIMPORTED"|lang} {if $importcount >= 0}<b>({$importcount})</b>{/if}</td>
									<td width="1%" valign="bottom"><input type="submit" class="button" name="delimport" onclick="return confirm('{"_DELIMPORT"|lang}{"_DATALOSS"|lang}');" value="{"_DELETE"|lang}" {if $importcount == 0}disabled="disabled"{/if}/></td>
								</tr>
								<tr class="info">
									<td>&nbsp;{"_SETALLNOTIMPORTED"|lang}</td>
									<td width="1%" valign="bottom"><input type="submit" class="button" name="setnotimported" onclick="return confirm('{"_SETIMPORT"|lang}');" value="{"_SET"|lang}" /></td>
								</tr>
								</form>
							{/if}
							{if $smarty.session.bans_export == "yes"}
							<form method="POST">
							<tr class="title">
								<td colspan="2">{"_EXP_FILE"|lang}</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" name="onlyperm" /> {"_ONLYPERMANENT"|lang}<br />
									<input type="checkbox" name="increason" /> {"_INCLUDEREASON"|lang}<br />
									<input type="checkbox" name="download" /> {"_DOWNLOADAFTER"|lang}
								</td>
								<td valign="bottom" width="1%"><input type="submit" class="button" name="bancfgexp" value="{"_EXPORT"|lang}" /></td>
							</tr>
							</form>
							{/if}
						</table>
						<br />
						<center><div class="admin_msg">
							{if $msg}{$msg|lang}<br />{/if}
							{if $statusexport.exported || $statusexport.exported===0}{"_EXPORTED"|lang}: {$statusexport.exported}<br />{/if}
							{if $status}{"_IMPORTED"|lang}: {$status.imported} / {"_FAILED"|lang}: {$status.failed}<br />{/if}
							{if $delcount || $delcount===0}{"_DELETEDBANS"|lang}: {$delcount}<br />{/if}
							{if $updatecount || $updatecount===0}{"_UPDATEDBANSNOTIMPORTED"|lang}: {$updatecount}<br />{/if}
						</div></center>
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