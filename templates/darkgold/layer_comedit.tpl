<td colspan=10>
	<div id="comedit_{$comments.id}" style="display: none">
		<table>
			<tr class="title">
				<td class="fat">{"_EDITCOMMENT"|lang}</td>
			</tr>
			<tr>
				<td colspan="2">
					<table>
						<form method="post">
						<input type="hidden" name="bid" value="{$ban_detail.bid}" />
						<input type="hidden" name="site" value="{$site}" />
						<input type="hidden" name="cid" value="{$comments.id}" />
						<input type="hidden" name="details_x" value="1" />
						<tr>
							<td align="right" width="30%">{"_NAME"|lang}:</td>
							<td><input type="test" size="30" name="name" value="{$comments.name}" /></td>
						</tr>
						<tr>
							<td align="right" width="30%">{"_EMAIL"|lang}:</td>
							<td><input type="test" size="30" name="email" value="{$comments.email}" /></td>
						</tr>
						<tr>
							<td align="right">{"_COMMENT"|lang}:</td>
							<td>
								{foreach from=$bbcodes item=bbcodes}
								<a href="javascript:insertAtCaret('commentce{$comments.id}', '{$bbcodes.0} {$bbcodes.1}');"><img border="0" src="images/icons/bbcode/{$bbcodes.2}" title="{$bbcodes.3}" /></a>
								{/foreach}
								<br />
								<textarea name="comment" id="commentce{$comments.id}" cols="50" rows="3" wrap="soft">{$comments.comment}</textarea>
								<br />
								{foreach from=$smilies item=smilies}
								<a href="javascript:insertAtCaret('commentce{$comments.id}', ' {$smilies.0} ');"><img border="0" src="images/icons/{$smilies.1}" title="{$smilies.2}" /></a>
								{/foreach}
							</td>
						</tr>
						<tr>
							<td colspan="2" class="_center">
								<input type="submit" name="edit_comment" onclick="return confirm('{"_SAVEEDIT"|lang}');" value="{"_SAVE"|lang}" class="button" />
							</td>
						</tr>
						</form>
					</table>
			</td></tr>
		</table>
	</div>
</td>