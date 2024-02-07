{if $msg<>""}
	<div class="success">{"$msg"|lang}</div>
{/if}
<div class="main" id="main-two-columns">
	<div class="left" id="main-left">
		<table> 
			<tr class="title">
				<td colspan="2" class="fat">{"_SERVERSETUP"|lang}</th> 
			</tr> 
			<tr class="info">
				<td class="b" style="width:200px;">PHP {"_VERSION"|lang}</td> 
				<td>{$php_settings.version_php}</td>
			</tr>
			<tr class="info">
				<td class="b" style="width:200px;">MySQL {"_VERSION"|lang}</td> 
				<td>{$php_settings.mysql_version} <img alt="" src="images/generic/{if $php_settings.mysql_version >=4.1}accept{else}exclamation{/if}.png" /></td>
			</tr>
			<tr class="info">
				<td class="b" style="width:200px;">safe_mode</td> 
				<td>{$php_settings.safe_mode|lang} <img alt="" src="images/generic/accept.png" /></td>
			</tr>
			<tr class="info">
				<td class="b" style="width:200px;">register_globals</td> 
				<td>{$php_settings.register_globals|lang} <img src="images/generic/{if $php_settings.register_globals=="_OFF"}accept{else}exclamation{/if}.png" /></td>
			</tr>
			<tr class="info">
				<td class="b" style="width:200px;">magic_quotes_gpc</td> 
				<td>{$php_settings.magic_quotes_gpc|lang} <img src="images/generic/{if $php_settings.magic_quotes_gpc=="_OFF"}accept{else}exclamation{/if}.png" /></td>
			</tr>
			<tr class="info">
				<td class="b" style="width:200px;">display_errors</td> 
				<td>{$php_settings.display_errors}</td>
			</tr>
			<tr class="info">
				<td class="b" style="width:200px;">post_max_size</td> 
				<td>{$php_settings.post_max_size}</td>
			</tr>
			<tr class="info">
				<td class="b" style="width:200px;">upload_max_filesize</td> 
				<td>{$php_settings.upload_max_filesize}</td>
			</tr>
			<tr class="info">
				<td class="b" style="width:200px;">max_execution_time</td> 
				<td>{$php_settings.max_execution_time}</td>
			</tr>
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
						<div class="left">{"_DBSIZE"|lang}</div>
						<div class="right">{$db_size}</div>
						<div class="clearer">&nbsp;</div>
					</li>
					<li>
						<div class="left">{"_BANSINDB"|lang}</div>
						<div class="right">{$bans.count}</div>
						<div class="clearer">&nbsp;</div>
					</li>
					<li>
						<div class="left">{"_ACTIVEBANS"|lang}</div>
						<div class="right">{$bans.activ}</div>
						<div class="clearer">&nbsp;</div>
					</li>
					<li>
						<div class="left">{"_COMMENTS"|lang}</div>
						<div class="right">
							{$comment_count.count}
							{if $comment_count.fail != 0} ({$comment_count.fail} {"_ERROR"|lang})
								<img src="images/generic/exclamation.png" />
								&nbsp;<input type="submit" name="comment_repair" value="{"_REPAIR"|lang}" />
							{/if}</div>
						<div class="clearer">&nbsp;</div>
					</li>
					<li>
						<div class="left">{"_BL_FILES"|lang}</div>
						<div class="right">
							{$file_count.count}
							{if $file_count.fail != 0} ({$file_count.fail} {"_ERROR"|lang})
								<img src="images/generic/exclamation.png" />
								&nbsp;<input type="submit" name="file_repair" value="{"_REPAIR"|lang}" />
							{/if}
						</div>
					</li>
				</ul>
			</div>
		</div>

		<div class="section">
			<div class="section-title">
				{"_OTHERFUNCTIONS"|lang}
			</div>
			<div class="section-content">
				<form method="post">
					<ul class="nice-list">
						<li>
							<div class="left">{"_CLEARCACHE"|lang}</div>
							<div class="right">
								<input type="submit" class="button" name="clear" value="{"_DELETE"|lang}" {if $smarty.session.websettings_edit != "yes"}disabled{/if}/>
							</div>
							<div class="clearer">&nbsp;</div>
						</li>
						<li>
							<div class="left">{"_DBOPTIMIZE"|lang}</div>
							<div class="right">
								<input type="submit" class="button" name="optimize" value="OK" {if $smarty.session.websettings_edit != "yes"}disabled{/if}/>
							</div>
							<div class="clearer">&nbsp;</div>
						</li>
						<li>
							<div class="left">{"_PRUNEDB"|lang}</div>
							<div class="right">
								<input type="submit" class="button" name="prunedb" value="OK" {if $smarty.session.websettings_edit != "yes"}disabled{/if}/>
							</div>
							<div class="clearer">&nbsp;</div>
						</li>
					</ul>
				</form>
			</div>
		</div>
	</div>
	<div class="clearer">&nbsp;</div>
</div>
