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
                {foreach from=$bbcodes item=bbcode}
                <a href="javascript:insertAtCaret('commentce{$comments.id}', '{$bbcode.0} {$bbcode.1}');"><img border="0" src="images/icons/bbcode/{$bbcode.2}" title="{$bbcode.3}" /></a>
                {/foreach}
                <br />
                <textarea name="comment" id="commentce{$comments.id}" cols="50" rows="3" wrap="soft">{$comments.comment}</textarea>
                <br />
                {foreach from=$smilies item=smilie}
                <a href="javascript:insertAtCaret('commentce{$comments.id}', ' {$smilie.0} ');"><img border="0" src="images/icons/{$smilie.1}" title="{$smilie.2}" /></a>
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