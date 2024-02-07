<td colspan=10>
	<table>
		<tr>
			<td colspan="2">
				<table>
					<form method="post">
						<input type="hidden" name="bid" value="{$ban_detail.bid}" />
						<input type="hidden" name="site" value="{$site}" />
						<input type="hidden" name="details_x" value="1" />
						<div id="add_comment" class="post">
							<form method="post">
								<table> 
										<tr class="title"> 
											<td colspan="2" class="fat">{"_NEWCOMMENT"|lang}</th> 
										</tr>
										<tr class="info"> 
											<td style="width: 150px;">{"_NAME"|lang}</td> 
											<td class="vtop">
												<input type="text" size="30" name="name" value="{if $smarty.session.loggedin=="true"}{$smarty.session.uname}" disabled{else}"{/if}/>
												{if $smarty.session.loggedin=="true"}<input type="hidden" name="name" value="{$smarty.session.uname}" />{/if}
											</td>
										</tr>
										<tr class="info"> 
											<td>{"_EMAIL"|lang}</td> 
											<td class="vtop">
												<input type="text" size="30" name="email" value="{if $smarty.session.loggedin=="true" && $smarty.session.email!=""}{$smarty.session.email}" disabled{else}"{/if}/>
												{if $smarty.session.loggedin=="true" && $smarty.session.email!=""}<input type="hidden" name="email" value="{$smarty.session.email}" />{/if} 
											</td>
										</tr>
										<tr class="info"> 
											<td>{"_COMMENT"|lang}:</td>
											<td>
												{foreach from=$bbcodes item=bbcodes}
													<a href="javascript:insertAtCaret('commentc', '{$bbcodes.0} {$bbcodes.1}');"><img border="0" src="images/icons/bbcode/{$bbcodes.2}" title="{$bbcodes.3}" /></a>
												{/foreach}
												<br />
													<textarea name="comment" id="commentc" cols="50" rows="3" wrap="soft"></textarea>
												<br />
												{foreach from=$smilies item=smilies}
													<a href="javascript:insertAtCaret('commentc', ' {$smilies.0} ');"><img border="0" src="images/icons/{$smilies.1}" title="{$smilies.2}" /></a>
												{/foreach}
											</td>
										</tr>
										{if $smarty.session.loggedin!=true}
										<tr class="info"> 
											<td>{"_SCODE"|lang}</td> 
											<td>{"_SCODEENTER"|lang}<br>
												<img src="include/captcha.php" alt="Security code" style="border: 1px #000000 solid;"><br>
												<input type='text' size="30" name='verify' id="verify_code">
											</td> 
										</tr>
										{/if}
										<tr> 
											<td colspan="2" class="_center"><input class="button" type="submit" name="add_comment" value="{"_ADD"|lang}" /></th> 
										</tr>

								</table> 
							</form>
						</div>
					</form>
				</table>
			</td>
		</tr>
	</table>
</td>