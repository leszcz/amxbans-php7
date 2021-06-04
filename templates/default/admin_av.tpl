{foreach from=$ashow_output item=foo}
{if $foo == "_YES"}
{$ashow_output.1 = $foo|lang}
{elseif $foo == "_NO"} 
{$ashow_output.0 = $foo|lang}
{/if}
{/foreach}

{if $msg}
  <div class="notice">
    {foreach from=$msg item=msgs}
      {$msgs|lang}
      <br />
    {/foreach}
  </div>
{/if}
    <td id="main" valign="top" >
    {if $smarty.session.amxadmins_view == "yes"}
      <span class="title">{"_AMXADMINSETTINGS"|lang}</span>
      <table width="95%" align="center">
        <tr>
          <td>
        <table>
          <tr class="title"><td colspan="7">{"_MANAGEAMXADMINS"|lang}</td></tr>
          <tr class="title">
            <td align="center">{"_STEAMIDIPNAME"|lang}</td>
            <td align="center">{"_PASSWORD"|lang}</td>
            <td align="center">{"_ACCESS"|lang}</td>
            <td align="center">{"_FLAGS"|lang}</td>
            <td align="center">{"_NICKNAME"|lang}</td>
            <td>&nbsp;</td>
          </tr>
          {foreach from=$admins item=admin}
            <form method="POST">
              <input type="hidden" name="aid" value="{$admin.aid}" />
              <input type="hidden" name="created" value="{$admin.created}" />
              <tr class="info">
                <td align="center" width="10%"><input type="text" name="steamid" value="{$admin.steamid}" /></td>
                <td align="center" width="10%"><input size="15" type="password" name="password" /></td>
                <td align="center" width="10%" nowrap>
                  <input type="text" id="acc{$admin.aid}" name="access" size="25" value="{$admin.access}" />
                  <img src="images/server_key.png" style="cursor:pointer;" 
                    onClick="window.open('include/amxxhelper.php?id=acc'+{$admin.aid},'Link','width=500,height=530,dependent=yes,resizable=yes');return false;" />
                </td>
                <td align="center" width="5%"><input size="6" type="text" name="flags" value="{$admin.flags}" /></td>
                <td align="center" width="10%"><input size="15" type="text" name="nickname" value="{$admin.nickname}" /></td>
                <td align="center">
                  <nobr>
                    <img src="images/{if $admin.expired<>0 && $admin.expired<=$smarty.now}warning{else}success{/if}.gif" />
                    <input type="button" class="button" name="settings" value="{"_SETTINGS"|lang}" onClick="NewToggleLayer('layer_{$admin.aid}');" />
                    <input type="submit" class="button" name="save" value="{"_SAVE"|lang}" {if $smarty.session.amxadmins_edit !== "yes"}disabled{/if} />
                    <input type="submit" class="button" name="del" value="{"_DELETE"|lang}" onclick="return confirm('{"_DELADMIN"|lang}{"_DATALOSS"|lang}');" {if $smarty.session.amxadmins_edit !== "yes"}disabled{/if} />
                  </nobr>
                </td>
              </tr>
              <tr id="layer_{$admin.aid}" style="display: none">
                <td colspan="7">
                  <div style="display: none" align="center">
                    <table class="details">
                        <tr class="title">
                          <td colspan="3">{"_SETTINGS"|lang}</td>
                        </tr>
                        <tr class="info">
                          <td>ICQ:</td>
                          <td><nobr><input size="20" type="text" name="icq" value="{$admin.icq}" /></nobr></td>
                        </tr>
                        <tr class="info">
                          <td width="40%">{"_SHOWINADMINLIST"|lang}:</td>
                          <td>{html_options name=ashow values=$ashow output=$ashow_output selected=$admin.ashow}</td>
                        </tr>
                        <tr class="info">
                          <td>{"_ADMINVALIDITY"|lang}:</td>
                          <td><nobr><input size="5" type="text" name="days" value="{$admin.days}" /> {"_DAYS"|lang}</nobr></td>
                        </tr>
                        <tr class="info">
                          <td valign="top">{"_ADMINEXPIRATION"|lang}:</td>
                          <td>{if $admin.expired == 0}<i>{"_EVER"|lang}</i>
                            {else}{$admin.expired|date_format:"%d.%m.%Y - %T"}<br>{"_EXTENDWITH"|lang} 
                              <input size="5" type="text" name="moredays" value="{$input.moredays}" /> {"_DAYS"|lang} {"_OR"|lang} 
                              <input type="checkbox" name="noend" value="" /> {"_EVER"|lang}
                            {/if}
                          </td>
                        </tr>
                        <tr class="info">
                          <td>{"_CREATED"|lang}:</td>
                          <td>{$admin.created|date_format:"%d.%m.%Y - %T"}</td>
                        </tr>
                        <tr class="info">
                          <td>SteamID:</td>
                          <td><input type="text" name="username" value="{$admin.username}" /></td>
                          <td>
                            <input type="submit" class="button" name="save" value="{"_SAVE"|lang}" {if $smarty.session.amxadmins_edit !== "yes"}disabled{/if} />
                          </td>
                        </tr>
                    </table>
                  </div>
                </td>
              </tr>
            </form>
          {/foreach}
          
        </table>
        {if $smarty.session.amxadmins_edit == "yes"}
          <form method="POST">
            <br>
            <table>
              <tr class="title">
                <td colspan="3">{"_ADDAMXADMINS"|lang}</td>
              </tr>
              <tr>
                <tr class="info"><td class="fat">{"_STEAMIDIPNAME"|lang}:</td><td><input type="text" name="steamid" value="{$input.steamid}" /></td></tr>
                <tr class="info"><td class="fat">{"_PASSWORD"|lang}:</td><td><input type="text" name="password" value="" /></td></tr>
                <tr class="info"><td class="fat">{"_ACCESS"|lang}:</td><td>
                  <input type="text" name="access" id="addacc" value="{$input.access}" />
                  <img src="images/server_key.png" style="cursor:pointer;" 
                    onClick="window.open('include/amxxhelper.php?id=addacc','Link','width=500,height=530,dependent=yes,resizable=yes');return false;" />
                </td></tr>
                <tr class="info"><td class="fat">{"_FLAGS"|lang}:</td><td><input size="8" type="text" name="flags" value="{$input.flags}" /></td></tr>
                <tr class="info"><td width="40%" class="fat">SteamID:</td><td><input type="text" name="username" value="{$input.username}" /></td></tr>
                <tr class="info"><td class="fat">{"_NICKNAME"|lang}:</td><td><input type="text" name="nickname" value="{$input.nickname}" /></td></tr>
                <tr class="info"><td class="fat">ICQ:</td><td><input type="text" name="icq" value="{$input.icq}" /></td></tr>
                <tr class="info"><td class="fat">{"_SHOWINADMINLIST"|lang}:</td><td>
        
          <select name="ashow">
          <option value="0">{"_NO"|lang}</option>
          <option value="1">{"_YES"|lang}</option>
          </select>
        
        </td></tr>
                <tr class="info"><td valign="top"><b>{"_ADMINVALIDITY"|lang}:</td><td><nobr><input size="5" type="text" name="days" value="{$input.days|default:"30"}" /> {"_DAYS"|lang} {"_OR"|lang} <input type="checkbox" name="noend" value="yes" {if $input.noend==1}checked{/if} /> {"_EVER"|lang} </nobr></td></tr>
                <tr><td valign="top"><b>{"_ADDADMINTOSERVERS"|lang}:</td>
                  <td>
                    <select name="addtoserver[]" size="3" multiple>
                      {html_options values=$svalues output=$soutput}
                    </select>
                  </td>
                </tr>
                <tr class="info">
                  <td>
                    <b>{"_WITHSTATICBANTIME"|lang}:</b>
                  </td>
                  <td>
                    <select name="staticbantime">
                      <option value="no">{"_NO"|lang}</option>
                      <option value="yes">{"_YES"|lang}</option>
                    </select>
                  </td>
                  <td align="right">
                    <input type="submit" class="button" name="new" value="{"_ADD"|lang}" >
                  </td>
                </tr>
              </tr>
            </table>
          </form>
        {/if}
        <br />
          {include file="info_amxaccess.tpl"}
      {else}
        {"_NOACCESS"|lang} !!
      {/if}
      </td></tr></table>
    </td>
  </tr>
</table>