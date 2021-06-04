{if $msg}
  <div class="notice">
    {$msg|lang}
    {if $smsg}
      <br /><br />
        <div class="rcon_box">
          <pre>{$smsg}</pre>
          <div class="clearer">&nbsp;</div>
        </div>
    {else}
      <center><div class="admin_msg">{"_NOACCESS"|lang}</div></center>
    {/if}
  </div>
{/if}
    <td id="main" valign="top" class="admin_list">
      {if $smarty.session.servers_edit == "yes"}
        <span class="title">{"_SERVERSETTINGS"|lang}</span>
        <table>
          <tr>
            <td>
              <table>
                <tr class="title">
                  <td width="1%">{"_MOD"|lang}</td>
                  <td width="1%">{"_IP"|lang}</td>
                  <td>{"_HOSTNAME"|lang}</td>
                  <td width="1%" align="center">{"_LASTSEEN"|lang}</td>
                </tr>
                {foreach from=$servers item=server}
                  <tr class="list" style="cursor:pointer;"onClick="NewToggleLayer('layer_{$server.sid}');">
                    <td align="center"><img src="images/mods/{$server.gametype}.gif" /></td>
                    <td align="center">{$server.address}</td>
                    <td align="center">{$server.hostname}</td>
                    <td align="center"><nobr>{$server.timestamp|date_format:"%d. %b %Y - %T"}</nobr></td>
                  </tr>
                  <tr id="layer_{$server.sid}" {if $server.sid != $server_activ}style="display: none"{/if}>
                    <td colspan="5">
                      <div style="display:none" align="center">
                      <fieldset>
                        <legend>{"_SERVERSETTINGS"|lang} {$server.hostname}</legend>
                        <table class="details">
                          <form name="rcon_{$server.sid}" method="POST">
                            <tr class="title">
                              <td colspan="5">{"_SERVERSETTINGS"|lang}</td>
                            </tr>
                            <tr class="info">
                              <td>{"_RCONPW"|lang}:</td>
                              <td width="60%">
                                {if $smarty.session.servers_edit == "yes"}
                                  <input type="password" name="rcon" value="{$server.rcon}" />
                                {else}
                                  <i>{"_HIDDEN"|lang}</i>
                                {/if}
                              </td>
                              <td>&nbsp;</td>
                              <td rowspan="5" width="1%" valign="bottom">
                                <input type="hidden" name="sid" value="{$server.sid}" />
                                <input type="hidden" name="sidname" value="{$server.hostname}" />
                                <input type="submit" class="button" name="save" value="{"_SAVE"|lang}" {if $smarty.session.servers_edit !== "yes"}disabled{/if} />
                                <input type="submit" class="button" name="del" value="{"_DEL"|lang}" onclick="return confirm('{"_DELSERVER"|lang}{"_DATALOSS"|lang}');" {if $smarty.session.servers_edit !== "yes"}disabled{/if} />
                              </td>
                            </tr>
                            <tr class="info">
                              <td>{"_MOTDURL"|lang}:</td>
                              <td><input type="text" size="70" name="amxban_motd" id="{$server.sid}" value="{$server.amxban_motd}" /></td>
                              <td><input type="button" class="button" value="{"_AUTO"|lang}" onclick="document.getElementById('{$server.sid}').value='{$motd_url}';" /></td>
                            </tr>
                            <tr class="info">
                              <td>{"_MOTDDELAY"|lang}:</td>
                              <td>{html_options name=motd_delay values=$delay_choose output=$delay_choose selected=$server.motd_delay} {"_SECS"|lang}</td>
                            </tr>
        <!--                    <tr class="info">
                              <td>{"_SERVERMENU"|lang}</td>
                              <td>{html_options name=amxban_menu values=$menu_choose output=$menu_choose selected=$server.amxban_menu}</td>
                            </tr>
        -->                      <tr class="info">
                              <td>{"_REASONSSET"|lang}:</td>
                              <td>{html_options name=reasons values=$reasons_values output=$reasons_choose selected=$server.reasons}</td>
                            </tr>
                            <tr class="info">
                              <td>{"_TIMEZONEFIXX"|lang}:</td>
                              <td>{html_options name=timezone_fixx values=$timezone_values output=$timezone_output selected=$server.timezone_fixx} {"_HOURS"|lang}</td>
                            </tr>
                            {if $server.rcon}
                              <tr class="info"><td colspan="4">&nbsp;</td></tr>
                              <tr class="title">
                                <td colspan="5">{"_SERVERRCON"|lang}</td>
                              </tr>
                              {if $smarty.session.servers_edit == "yes"}
                              <tr class="info">
                                <td valign="top">{"_RCON_PREDEFINED"|lang}:</td>
                                <td>
        <!--                        <select name="command" size="3">
                                    {section name=rcon_cmds loop=$rcon_cmds}
                                      <option value="{$rcon_cmds[rcon_cmds]}" {if $smarty.section.rcon_cmds.index == 0}selected{/if}>{$rcon_cmdkeys[rcon_cmds]|lang}</option>
                                    {/section}
                                  </select>
        -->
                                  <select name="command" size="1">
                                    {section name=rcon_cmds loop=$rcon_cmds}
                                      <option value="{$rcon_cmds[rcon_cmds]}" {if $smarty.section.rcon_cmds.index == 0}selected{/if}>{$rcon_cmdkeys[rcon_cmds]|lang}</option>
                                    {/section}
                                  </select>
                                </td>
                                <td>
                                  <input type="submit" class="button" name="rconcommand" value="{"_RCON_SEND"|lang}" />
                                </td>
                                <td></td>
                              </tr>
                              <tr class="info">
                                <td>{"_RCON_USERDEFINED"|lang}:</td>
                                <td><input type="test" size="70" name="rconuser" onkeyup="document.rcon_{$server.sid}.rconuserstart_{$server.sid}.disabled=(this.value=='');" /></td>
                                <td><input type="submit" class="button" name="rconuserstart_{$server.sid}" value="{"_RCON_SEND"|lang}" disabled /></td>
                              </tr>
                              {else}
                                <tr class="info"><td class="admin_msg">{"_NOACCESS"|lang} !!</td></tr>
                              {/if}  
                            {/if}
                          </form>
                        </table>
                      </fieldset></div>
                    </td>
                  </tr>
                {/foreach}
              </table>
            </td>
          </tr>
        </table>
        <div class="clearer">&nbsp;</div>
      {/if}
    </td>
  </tr>
</table>
</div>