		<td id="main" valign="top" >
		{if $smarty.session.amxadmins_view == "yes"}
			<span class="title">{"_MENUIMPORTADMINS"|lang}<br /><font size="1px">({"_MENUIMPORTDESC"|lang})</font></span>
			<table width="50%"><tr><td>
				{if $smarty.session.bans_import == "yes"}
				<table border="1" width="100%">
					<tr class="title"><td colspan="2" class="fat"><b>{"_U_IMPORT"|lang}</b></td></tr>
					<form method="POST" enctype="multipart/form-data">
					<tr class="settings_line">
						<td valign="top">&nbsp;<b>{"_U_SELECT"|lang}</b><br /> 
							<input type="hidden" name="MAX_FILE_SIZE" value="50000" />
							&nbsp;<input name="usersFile" type="file" /> <br />
							&nbsp;{"_U_STATICTIME"|lang}* <select name="isStatic">
										<option value="yes">{"_U_STATICSELECTYES"|lang}</option>
										<option value="no">{"_U_STATICSELECTNO"|lang}</option>
									</select><br />
							{"_U_SETTOSERVER"|lang} <select name="serverID">
										{foreach from=$serwery item=serwery}
											<option value="{$serwery.id}">{$serwery.hostname}</option>
										{/foreach}
									</select>
						</td>
						<td width="1%">
							<input type="submit" class="button" class="button" name="usersImport" value="{"_BACKUP"|lang}" />
						</td>
					</tr>
					</form>
				<div class="warnings" style="display: none">
					<table>
							<tr onclick="NewToggleLayer('info_1');" class="list">
								<td style="width:20px;"><button value="Uwagi">{"_U_BUTTONINFO"|lang}</button></td>
								<td>{$server.hostname}</td>
							</tr>
							<tr id="info_1" style="display: none">
								<td colspan="2">
									<div style="display: none;">
										<table class="details">
											
											<ul>
												<li>* - {"_U_WARNINGS1"|lang}</li>
											</ul>
											
									<div style="color:#696969;font-size:11px;text-align:right;">Module AdminImport by <a href="http://cserwerek.pl/user/2-michal/" target="_blank">Portek</a> @ <a href="http://amxx.pl/topic/49358-import-adminow-z-usersini/" target="_blank">AMXX.PL</a>
									<font size="1px">v 1.2.0</font></div>
										</table>
									</div>
								</td>
							</tr>
					</table>
				</div>
			</table>
			<br />
			{/if}
			
			{else}
				{"_NOACCESS"|lang}
			{/if}
			</td></tr></table>
		</td>
	</tr>
</table>