<?php
/* Smarty version 3.1.30, created on 2024-10-07 07:08:18
  from "/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/admin_av.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_670388e2757a48_91189563',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '945ef956591b2b562d1a81d0ff9fe5e99edd9308' => 
    array (
      0 => '/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/admin_av.tpl',
      1 => 1707251794,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:info_amxaccess.tpl' => 1,
  ),
),false)) {
function content_670388e2757a48_91189563 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_function_html_options')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/function.html_options.php';
if (!is_callable('smarty_modifier_date_format')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.date_format.php';
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ashow_output']->value, 'foo');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['foo']->value) {
if ($_smarty_tpl->tpl_vars['foo']->value == "_YES") {
$_tmp_array = isset($_smarty_tpl->tpl_vars['ashow_output']) ? $_smarty_tpl->tpl_vars['ashow_output']->value : array();
if (!is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess) {
settype($_tmp_array, 'array');
}
$_tmp_array[1] = smarty_modifier_lang($_smarty_tpl->tpl_vars['foo']->value);
$_smarty_tpl->_assignInScope('ashow_output', $_tmp_array);
} elseif ($_smarty_tpl->tpl_vars['foo']->value == "_NO") {?> 
<?php $_tmp_array = isset($_smarty_tpl->tpl_vars['ashow_output']) ? $_smarty_tpl->tpl_vars['ashow_output']->value : array();
if (!is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess) {
settype($_tmp_array, 'array');
}
$_tmp_array[0] = smarty_modifier_lang($_smarty_tpl->tpl_vars['foo']->value);
$_smarty_tpl->_assignInScope('ashow_output', $_tmp_array);
}
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>


<?php if ($_smarty_tpl->tpl_vars['msg']->value) {?>
  <div class="notice">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['msg']->value, 'msgs');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['msgs']->value) {
?>
      <?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msgs']->value);?>

      <br />
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

  </div>
<?php }?>
    <td id="main" valign="top" >
    <?php if ($_SESSION['amxadmins_view'] == "yes") {?>
      <span class="title"><?php echo smarty_modifier_lang("_AMXADMINSETTINGS");?>
</span>
      <table width="95%" align="center">
        <tr>
          <td>
        <table>
          <tr class="title"><td colspan="7"><?php echo smarty_modifier_lang("_MANAGEAMXADMINS");?>
</td></tr>
          <tr class="title">
            <td align="center"><?php echo smarty_modifier_lang("_STEAMIDIPNAME");?>
</td>
            <td align="center"><?php echo smarty_modifier_lang("_PASSWORD");?>
</td>
            <td align="center"><?php echo smarty_modifier_lang("_ACCESS");?>
</td>
            <td align="center"><?php echo smarty_modifier_lang("_FLAGS");?>
</td>
            <td align="center"><?php echo smarty_modifier_lang("_NICKNAME");?>
</td>
            <td>&nbsp;</td>
          </tr>
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['admins']->value, 'admin');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['admin']->value) {
?>
            <form method="POST">
              <input type="hidden" name="aid" value="<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
" />
              <input type="hidden" name="created" value="<?php echo $_smarty_tpl->tpl_vars['admin']->value['created'];?>
" />
              <tr class="info">
                <td align="center" width="10%"><input type="text" name="steamid" value="<?php echo $_smarty_tpl->tpl_vars['admin']->value['steamid'];?>
" /></td>
                <td align="center" width="10%"><input size="15" type="password" name="password" /></td>
                <td align="center" width="10%" nowrap>
                  <input type="text" id="acc<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
" name="access" size="25" value="<?php echo $_smarty_tpl->tpl_vars['admin']->value['access'];?>
" />
                  <img src="images/server_key.png" style="cursor:pointer;" 
                    onClick="window.open('include/amxxhelper.php?id=acc'+<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
,'Link','width=500,height=530,dependent=yes,resizable=yes');return false;" />
                </td>
                <td align="center" width="5%"><input size="6" type="text" name="flags" value="<?php echo $_smarty_tpl->tpl_vars['admin']->value['flags'];?>
" /></td>
                <td align="center" width="10%"><input size="15" type="text" name="nickname" value="<?php echo $_smarty_tpl->tpl_vars['admin']->value['nickname'];?>
" /></td>
                <td align="center">
                  <nobr>
                    <img src="images/<?php if ($_smarty_tpl->tpl_vars['admin']->value['expired'] <> 0 && $_smarty_tpl->tpl_vars['admin']->value['expired'] <= time()) {?>warning<?php } else { ?>success<?php }?>.gif" />
                    <input type="button" class="button" name="settings" value="<?php echo smarty_modifier_lang("_SETTINGS");?>
" onClick="NewToggleLayer('layer_<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
');" />
                    <input type="submit" class="button" name="save" value="<?php echo smarty_modifier_lang("_SAVE");?>
" <?php if ($_SESSION['amxadmins_edit'] !== "yes") {?>disabled<?php }?> />
                    <input type="submit" class="button" name="del" value="<?php echo smarty_modifier_lang("_DELETE");?>
" onclick="return confirm('<?php echo smarty_modifier_lang("_DELADMIN");
echo smarty_modifier_lang("_DATALOSS");?>
');" <?php if ($_SESSION['amxadmins_edit'] !== "yes") {?>disabled<?php }?> />
                  </nobr>
                </td>
              </tr>
              <tr id="layer_<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
" style="display: none">
                <td colspan="7">
                  <div style="display: none" align="center">
                    <table class="details">
                        <tr class="title">
                          <td colspan="3"><?php echo smarty_modifier_lang("_SETTINGS");?>
</td>
                        </tr>
                        <tr class="info">
                          <td>ICQ:</td>
                          <td><nobr><input size="20" type="text" name="icq" value="<?php echo $_smarty_tpl->tpl_vars['admin']->value['icq'];?>
" /></nobr></td>
                        </tr>
                        <tr class="info">
                          <td width="40%"><?php echo smarty_modifier_lang("_SHOWINADMINLIST");?>
:</td>
                          <td><?php echo smarty_function_html_options(array('name'=>'ashow','values'=>$_smarty_tpl->tpl_vars['ashow']->value,'output'=>$_smarty_tpl->tpl_vars['ashow_output']->value,'selected'=>$_smarty_tpl->tpl_vars['admin']->value['ashow']),$_smarty_tpl);?>
</td>
                        </tr>
                        <tr class="info">
                          <td><?php echo smarty_modifier_lang("_ADMINVALIDITY");?>
:</td>
                          <td><nobr><input size="5" type="text" name="days" value="<?php echo $_smarty_tpl->tpl_vars['admin']->value['days'];?>
" /> <?php echo smarty_modifier_lang("_DAYS");?>
</nobr></td>
                        </tr>
                        <tr class="info">
                          <td valign="top"><?php echo smarty_modifier_lang("_ADMINEXPIRATION");?>
:</td>
                          <td><?php if ($_smarty_tpl->tpl_vars['admin']->value['expired'] == 0) {?><i><?php echo smarty_modifier_lang("_EVER");?>
</i>
                            <?php } else {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['admin']->value['expired'],"%d.%m.%Y - %T");?>
<br><?php echo smarty_modifier_lang("_EXTENDWITH");?>
 
                              <input size="5" type="text" name="moredays" value="<?php echo $_smarty_tpl->tpl_vars['input']->value['moredays'];?>
" /> <?php echo smarty_modifier_lang("_DAYS");?>
 <?php echo smarty_modifier_lang("_OR");?>
 
                              <input type="checkbox" name="noend" value="" /> <?php echo smarty_modifier_lang("_EVER");?>

                            <?php }?>
                          </td>
                        </tr>
                        <tr class="info">
                          <td><?php echo smarty_modifier_lang("_CREATED");?>
:</td>
                          <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['admin']->value['created'],"%d.%m.%Y - %T");?>
</td>
                        </tr>
                        <tr class="info">
                          <td>SteamID:</td>
                          <td><input type="text" name="username" value="<?php echo $_smarty_tpl->tpl_vars['admin']->value['username'];?>
" /></td>
                          <td>
                            <input type="submit" class="button" name="save" value="<?php echo smarty_modifier_lang("_SAVE");?>
" <?php if ($_SESSION['amxadmins_edit'] !== "yes") {?>disabled<?php }?> />
                          </td>
                        </tr>
                    </table>
                  </div>
                </td>
              </tr>
            </form>
          <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

          
        </table>
        <?php if ($_SESSION['amxadmins_edit'] == "yes") {?>
          <form method="POST">
            <br>
            <table>
              <tr class="title">
                <td colspan="3"><?php echo smarty_modifier_lang("_ADDAMXADMINS");?>
</td>
              </tr>
              <tr>
                <tr class="info"><td class="fat"><?php echo smarty_modifier_lang("_STEAMIDIPNAME");?>
:</td><td><input type="text" name="steamid" value="<?php echo $_smarty_tpl->tpl_vars['input']->value['steamid'];?>
" /></td></tr>
                <tr class="info"><td class="fat"><?php echo smarty_modifier_lang("_PASSWORD");?>
:</td><td><input type="text" name="password" value="" /></td></tr>
                <tr class="info"><td class="fat"><?php echo smarty_modifier_lang("_ACCESS");?>
:</td><td>
                  <input type="text" name="access" id="addacc" value="<?php echo $_smarty_tpl->tpl_vars['input']->value['access'];?>
" />
                  <img src="images/server_key.png" style="cursor:pointer;" 
                    onClick="window.open('include/amxxhelper.php?id=addacc','Link','width=500,height=530,dependent=yes,resizable=yes');return false;" />
                </td></tr>
                <tr class="info"><td class="fat"><?php echo smarty_modifier_lang("_FLAGS");?>
:</td><td><input size="8" type="text" name="flags" value="<?php echo $_smarty_tpl->tpl_vars['input']->value['flags'];?>
" /></td></tr>
                <tr class="info"><td width="40%" class="fat">SteamID:</td><td><input type="text" name="username" value="<?php echo $_smarty_tpl->tpl_vars['input']->value['username'];?>
" /></td></tr>
                <tr class="info"><td class="fat"><?php echo smarty_modifier_lang("_NICKNAME");?>
:</td><td><input type="text" name="nickname" value="<?php echo $_smarty_tpl->tpl_vars['input']->value['nickname'];?>
" /></td></tr>
                <tr class="info"><td class="fat">ICQ:</td><td><input type="text" name="icq" value="<?php echo $_smarty_tpl->tpl_vars['input']->value['icq'];?>
" /></td></tr>
                <tr class="info"><td class="fat"><?php echo smarty_modifier_lang("_SHOWINADMINLIST");?>
:</td><td>
        
          <select name="ashow">
          <option value="0"><?php echo smarty_modifier_lang("_NO");?>
</option>
          <option value="1"><?php echo smarty_modifier_lang("_YES");?>
</option>
          </select>
        
        </td></tr>
                <tr class="info"><td valign="top"><b><?php echo smarty_modifier_lang("_ADMINVALIDITY");?>
:</td><td><nobr><input size="5" type="text" name="days" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['input']->value['days'])===null||$tmp==='' ? "30" : $tmp);?>
" /> <?php echo smarty_modifier_lang("_DAYS");?>
 <?php echo smarty_modifier_lang("_OR");?>
 <input type="checkbox" name="noend" value="yes" <?php if ($_smarty_tpl->tpl_vars['input']->value['noend'] == 1) {?>checked<?php }?> /> <?php echo smarty_modifier_lang("_EVER");?>
 </nobr></td></tr>
                <tr><td valign="top"><b><?php echo smarty_modifier_lang("_ADDADMINTOSERVERS");?>
:</td>
                  <td>
                    <select name="addtoserver[]" size="3" multiple>
                      <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['svalues']->value,'output'=>$_smarty_tpl->tpl_vars['soutput']->value),$_smarty_tpl);?>

                    </select>
                  </td>
                </tr>
                <tr class="info">
                  <td>
                    <b><?php echo smarty_modifier_lang("_WITHSTATICBANTIME");?>
:</b>
                  </td>
                  <td>
                    <select name="staticbantime">
                      <option value="no"><?php echo smarty_modifier_lang("_NO");?>
</option>
                      <option value="yes"><?php echo smarty_modifier_lang("_YES");?>
</option>
                    </select>
                  </td>
                  <td align="right">
                    <input type="submit" class="button" name="new" value="<?php echo smarty_modifier_lang("_ADD");?>
" >
                  </td>
                </tr>
              </tr>
            </table>
          </form>
        <?php }?>
        <br />
          <?php $_smarty_tpl->_subTemplateRender("file:info_amxaccess.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

      <?php } else { ?>
        <?php echo smarty_modifier_lang("_NOACCESS");?>
 !!
      <?php }?>
      </td></tr></table>
    </td>
  </tr>
</table><?php }
}
