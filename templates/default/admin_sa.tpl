{if $msg}<div class="_center"><span class="success">{$msg|lang}</span></div>{/if}
    <td id="main" valign="top" >
    {if $smarty.session.amxadmins_view == "yes"}
      <span class="title">{"_SERVERADMINSETTINGS"|lang}</span>
      <table>
        <tr>
          <td>
            <table>
              <tr align="center" class="title"><td colspan="4" class="fat">{"_SERVER"|lang}</td></tr>
              <tr align="center" class="title">
                <td width="1%">{"_MOD"|lang}</td>
                <td width="1%">{"_IP"|lang}</td>
                <td>{"_HOSTNAME"|lang}</td>
                <td width="1%">&nbsp;</td>
              </tr>
              {foreach from=$servers item=server}
                <form method="POST">
                  <input type="hidden" name="sid" value="{$server.sid}" />
                  <input type="hidden" name="sidname" value="{$server.hostname}" />
                  <tr class="list">
                    <td><img src="images/mods/{$server.gametype}.gif"></td>
                    <td>{$server.address}</td>
                    <td>{$server.hostname}</td>
                    <td><input type="submit" class="button" name="admins_edit" value="{"_EDITADMINS"|lang}" /></td>
                  </tr>
                </form>
              {/foreach}
            </table>
            <br />
            {if $editadmins.sidname}
              <hr />
              <br />
              <form method="POST" name="frm">
              <table border="1" width="100%">
                <tr class="title"><td colspan="9" class="fat">{"_ADMINS"|lang}: {$editadmins.sidname}</td></tr>
                <tr class="title">
                  <td align="center">{"_NAME"|lang}</td>
                  <td align="center">{"_NICKNAME"|lang}</td>
                  <td width="1%" align="center">{"_ACCESS"|lang}</td>
                  <td width="1%" align="center">{"_FLAGS"|lang}</td>
                  <td width="16%" align="center">{"_CUSTOMFLAGS"|lang}</td>
                  <td width="1%" align="center"><nobr>{"_STATICBANTIME"|lang}</nobr></td>
                  <td width="1%">{"_ACTIV"|lang}</td>
                </tr>
                {foreach from=$admins item=admin}
                <tr class="list">
                  <td align="center">{$admin.steamid}</td>
                  <td align="center">{$admin.nickname}</td>
                  <td align="center">{$admin.access}</td>
                  <td align="center">{$admin.flags}</td>
                  <td align="center">
                    <div id="cf{$admin.aid}" {if $admin.aktiv!=1}style="visibility:hidden"{/if} nowrap>
                      <input type="text" name="custom_flags[]" id="cftxt{$admin.aid}" size="16" value="{$admin.custom_flags}" {if $admin.aktiv!=1}disabled="disabled"{/if}/>
                      <img src="images/server_key.png" style="cursor:pointer;" onClick="window.open('include/amxxhelper.php?id=cftxt'+{$admin.aid},'Link','width=500,height=530,dependent=yes,resizable=yes');return false;" />
                    </div>
                  </td>
                  <td align="center">
                    <div id="usb{$admin.aid}" {if $admin.aktiv!=1}style="visibility:hidden"{/if}>
                      <select id="usbtxt{$admin.aid}"  name="use_static_bantime[]" {if $admin.aktiv!=1}disabled="disabled"{/if}><option value="no">{"_NO"|lang}</option><option value="yes">{"_YES"|lang}</option></select>
                    </div>
                  </td>
                  <td align="center">
                  <input type="hidden" name="sid" value="{$editadmins.sid}" />
                  <input type="hidden" name="sidname" value="{$editadmins.sidname}" />
                  <input type="checkbox" name="aktiv_new[]" value="{$admin.aid}" {if $admin.aktiv==1}checked{/if} 
                    onclick="this.form.elements['cftxt{$admin.aid}'].disabled = this.form.elements['usbtxt{$admin.aid}'].disabled = !this.checked;
                        document.getElementById('cf{$admin.aid}').style.visibility=(this.checked)?'visible':'hidden';
                        document.getElementById('usb{$admin.aid}').style.visibility=(this.checked)?'visible':'hidden';" /></td>
                </tr>
                {/foreach}
                  <tr align="right">
                    <td align="right"><input type="submit" class="button" name="save" value="{"_SAVE"|lang}"  {if $smarty.session.servers_edit !== "yes"}disabled{/if} /></td>
                  </tr>
                </td>
                </tr>
              </table>
              </form>
              <br />
                {include file="info_amxaccess.tpl"}
            {/if}
          {else}
            {"_NOACCESS"|lang} !!
          {/if}
          <br />
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>