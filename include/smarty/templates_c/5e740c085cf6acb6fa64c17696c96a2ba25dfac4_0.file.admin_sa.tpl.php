<?php
/* Smarty version 3.1.30, created on 2024-10-07 07:08:20
  from "/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/admin_sa.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_670388e4292f40_54771932',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5e740c085cf6acb6fa64c17696c96a2ba25dfac4' => 
    array (
      0 => '/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/admin_sa.tpl',
      1 => 1707251794,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:info_amxaccess.tpl' => 1,
  ),
),false)) {
function content_670388e4292f40_54771932 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.lang.php';
if ($_smarty_tpl->tpl_vars['msg']->value) {?><div class="_center"><span class="success"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msg']->value);?>
</span></div><?php }?>
    <td id="main" valign="top" >
    <?php if ($_SESSION['amxadmins_view'] == "yes") {?>
      <span class="title"><?php echo smarty_modifier_lang("_SERVERADMINSETTINGS");?>
</span>
      <table>
        <tr>
          <td>
            <table>
              <tr align="center" class="title"><td colspan="4" class="fat"><?php echo smarty_modifier_lang("_SERVER");?>
</td></tr>
              <tr align="center" class="title">
                <td width="1%"><?php echo smarty_modifier_lang("_MOD");?>
</td>
                <td width="1%"><?php echo smarty_modifier_lang("_IP");?>
</td>
                <td><?php echo smarty_modifier_lang("_HOSTNAME");?>
</td>
                <td width="1%">&nbsp;</td>
              </tr>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['servers']->value, 'server');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['server']->value) {
?>
                <form method="POST">
                  <input type="hidden" name="sid" value="<?php echo $_smarty_tpl->tpl_vars['server']->value['sid'];?>
" />
                  <input type="hidden" name="sidname" value="<?php echo $_smarty_tpl->tpl_vars['server']->value['hostname'];?>
" />
                  <tr class="list">
                    <td><img src="images/mods/<?php echo $_smarty_tpl->tpl_vars['server']->value['gametype'];?>
.gif"></td>
                    <td><?php echo $_smarty_tpl->tpl_vars['server']->value['address'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['server']->value['hostname'];?>
</td>
                    <td><input type="submit" class="button" name="admins_edit" value="<?php echo smarty_modifier_lang("_EDITADMINS");?>
" /></td>
                  </tr>
                </form>
              <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

            </table>
            <br />
            <?php if ($_smarty_tpl->tpl_vars['editadmins']->value['sidname']) {?>
              <hr />
              <br />
              <form method="POST" name="frm">
              <table border="1" width="100%">
                <tr class="title"><td colspan="9" class="fat"><?php echo smarty_modifier_lang("_ADMINS");?>
: <?php echo $_smarty_tpl->tpl_vars['editadmins']->value['sidname'];?>
</td></tr>
                <tr class="title">
                  <td align="center"><?php echo smarty_modifier_lang("_NAME");?>
</td>
                  <td align="center"><?php echo smarty_modifier_lang("_NICKNAME");?>
</td>
                  <td width="1%" align="center"><?php echo smarty_modifier_lang("_ACCESS");?>
</td>
                  <td width="1%" align="center"><?php echo smarty_modifier_lang("_FLAGS");?>
</td>
                  <td width="16%" align="center"><?php echo smarty_modifier_lang("_CUSTOMFLAGS");?>
</td>
                  <td width="1%" align="center"><nobr><?php echo smarty_modifier_lang("_STATICBANTIME");?>
</nobr></td>
                  <td width="1%"><?php echo smarty_modifier_lang("_ACTIV");?>
</td>
                </tr>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['admins']->value, 'admin');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['admin']->value) {
?>
                <tr class="list">
                  <td align="center"><?php echo $_smarty_tpl->tpl_vars['admin']->value['steamid'];?>
</td>
                  <td align="center"><?php echo $_smarty_tpl->tpl_vars['admin']->value['nickname'];?>
</td>
                  <td align="center"><?php echo $_smarty_tpl->tpl_vars['admin']->value['access'];?>
</td>
                  <td align="center"><?php echo $_smarty_tpl->tpl_vars['admin']->value['flags'];?>
</td>
                  <td align="center">
                    <div id="cf<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
" <?php if ($_smarty_tpl->tpl_vars['admin']->value['aktiv'] != 1) {?>style="visibility:hidden"<?php }?> nowrap>
                      <input type="text" name="custom_flags[]" id="cftxt<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
" size="16" value="<?php echo $_smarty_tpl->tpl_vars['admin']->value['custom_flags'];?>
" <?php if ($_smarty_tpl->tpl_vars['admin']->value['aktiv'] != 1) {?>disabled="disabled"<?php }?>/>
                      <img src="images/server_key.png" style="cursor:pointer;" onClick="window.open('include/amxxhelper.php?id=cftxt'+<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
,'Link','width=500,height=530,dependent=yes,resizable=yes');return false;" />
                    </div>
                  </td>
                  <td align="center">
                    <div id="usb<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
" <?php if ($_smarty_tpl->tpl_vars['admin']->value['aktiv'] != 1) {?>style="visibility:hidden"<?php }?>>
                      <select id="usbtxt<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
"  name="use_static_bantime[]" <?php if ($_smarty_tpl->tpl_vars['admin']->value['aktiv'] != 1) {?>disabled="disabled"<?php }?>><option value="no"><?php echo smarty_modifier_lang("_NO");?>
</option><option value="yes"><?php echo smarty_modifier_lang("_YES");?>
</option></select>
                    </div>
                  </td>
                  <td align="center">
                  <input type="hidden" name="sid" value="<?php echo $_smarty_tpl->tpl_vars['editadmins']->value['sid'];?>
" />
                  <input type="hidden" name="sidname" value="<?php echo $_smarty_tpl->tpl_vars['editadmins']->value['sidname'];?>
" />
                  <input type="checkbox" name="aktiv_new[]" value="<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
" <?php if ($_smarty_tpl->tpl_vars['admin']->value['aktiv'] == 1) {?>checked<?php }?> 
                    onclick="this.form.elements['cftxt<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
'].disabled = this.form.elements['usbtxt<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
'].disabled = !this.checked;
                        document.getElementById('cf<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
').style.visibility=(this.checked)?'visible':'hidden';
                        document.getElementById('usb<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
').style.visibility=(this.checked)?'visible':'hidden';" /></td>
                </tr>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                  <tr align="right">
                    <td align="right"><input type="submit" class="button" name="save" value="<?php echo smarty_modifier_lang("_SAVE");?>
"  <?php if ($_SESSION['servers_edit'] !== "yes") {?>disabled<?php }?> /></td>
                  </tr>
                </td>
                </tr>
              </table>
              </form>
              <br />
                <?php $_smarty_tpl->_subTemplateRender("file:info_amxaccess.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

            <?php }?>
          <?php } else { ?>
            <?php echo smarty_modifier_lang("_NOACCESS");?>
 !!
          <?php }?>
          <br />
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table><?php }
}
