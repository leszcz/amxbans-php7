<?php
/* Smarty version 3.1.30, created on 2018-08-31 09:55:43
  from "/home/nypd/public_html/forum/bans/dd2/templates/default/admin_bg.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5b8948dff0de04_29434549',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'abab1706acae3b700323a666fa0c55a547643bba' => 
    array (
      0 => '/home/nypd/public_html/forum/bans/dd2/templates/default/admin_bg.tpl',
      1 => 1532941653,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b8948dff0de04_29434549 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/nypd/public_html/forum/bans/dd2/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_modifier_strinstr')) require_once '/home/nypd/public_html/forum/bans/dd2/include/smarty/plugins/modifier.strinstr.php';
?>
  <?php if ($_smarty_tpl->tpl_vars['msg']->value <> '') {?>
    <div class="notice">
      <?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msg']->value);?>

    </div>
  <?php }?>
    <td id="main" valign="top" >
    <?php if ($_SESSION['amxadmins_view'] == "yes") {?>
      <span class="title"><?php echo smarty_modifier_lang("_REASONSSETTINGS");?>
</span>
      <table>
        <tr>
          <td>
            <table>
              <tr class="title">
                <td colspan="3"><?php echo smarty_modifier_lang("_REASONSSETS");?>
</td>
              </tr>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['reasons_set']->value, 'reason_set');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['reason_set']->value) {
?>
                <form method="POST">
                  <input type="hidden" name="rsid" value="<?php echo $_smarty_tpl->tpl_vars['reason_set']->value['id'];?>
" />
                  <tr class="list">
                    <td>
                      <?php if ($_smarty_tpl->tpl_vars['reason_set']->value['setname'] == '') {?>&nbsp;<?php } else {
echo $_smarty_tpl->tpl_vars['reason_set']->value['setname'];
}?>
                    </td>
                    <td align="center">
                      <input type="button" class="button" onclick="NewToggleLayer('layer_<?php echo $_smarty_tpl->tpl_vars['reason_set']->value['id'];?>
');" value="<?php echo smarty_modifier_lang("_EDIT");?>
" />
                      <input type="submit" class="button" name="delset" value="<?php echo smarty_modifier_lang("_DEL");?>
" <?php if ($_SESSION['servers_edit'] !== "yes") {?>disabled<?php }?> />
                    </td>
                  </tr>
                  <tr id="layer_<?php echo $_smarty_tpl->tpl_vars['reason_set']->value['id'];?>
" style="display: none">
                    <td colspan="3">
                      <div style="display: none" align="center">
                      <table class="details" width="95%">
                        <form method="POST">
                          <tr class="title">
                            <td colspan="4"><?php echo smarty_modifier_lang("_EDITSET");?>
</td>
                          </tr>
                          <tr class="info">
                            <td><?php echo smarty_modifier_lang("_NAME");?>
:</td>
                            <td><input type="text" name="setname" value="<?php echo $_smarty_tpl->tpl_vars['reason_set']->value['setname'];?>
" /></td>
                            <td><input type="submit" class="button" name="saveset" value="<?php echo smarty_modifier_lang("_SAVESET");?>
" <?php if ($_SESSION['servers_edit'] !== "yes") {?>disabled<?php }?> /></td>
                          </tr>
                          <tr class="title">
                            <td width="33%"><?php echo smarty_modifier_lang("_REASON");?>
</td><td width="33%"><?php echo smarty_modifier_lang("_STATICBANTIME");?>
</td><td><?php echo smarty_modifier_lang("_ACTIV");?>
</td>
                          </tr>
                          <?php
$__section_reasons_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_reasons']) ? $_smarty_tpl->tpl_vars['__smarty_section_reasons'] : false;
$__section_reasons_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['reasons']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_reasons_0_total = $__section_reasons_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_reasons'] = new Smarty_Variable(array());
if ($__section_reasons_0_total != 0) {
for ($__section_reasons_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index'] = 0; $__section_reasons_0_iteration <= $__section_reasons_0_total; $__section_reasons_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index']++){
?>
                            <tr class="info">
                              <td><?php echo $_smarty_tpl->tpl_vars['reasons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index'] : null)]['reason'];?>
</td>
                              <td><?php echo $_smarty_tpl->tpl_vars['reasons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index'] : null)]['static_bantime'];?>
</td>
                              <td><input type="checkbox" name="aktiv[]" value="<?php echo $_smarty_tpl->tpl_vars['reasons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index'] : null)]['id'];?>
" <?php echo smarty_modifier_strinstr($_smarty_tpl->tpl_vars['reason_set']->value['reasonids'],",",$_smarty_tpl->tpl_vars['reasons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index'] : null)]['id'],"checked");?>
 /></td>
                            </tr>
                          <?php
}
}
if ($__section_reasons_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_reasons'] = $__section_reasons_0_saved;
}
?>
                        </form>
                      </table></div>
                    </td>
                  </tr>
      
                </form>
              <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

              <div class="clearer"></div>
              <tr class="info">
                <form method="POST">
                  <td align="center"><input type="text" name="setname" value="" /></td>
                  <td align="center"><input type="submit" class="button" name="newset" value="<?php echo smarty_modifier_lang("_NEWSET");?>
" <?php if ($_SESSION['servers_edit'] !== "yes") {?>disabled<?php }?> /></td>
                </form>
              </tr>
            </table>
            
            <table border="1" width="100%">
              <tr class="title">
                <td colspan="3" class="fat"><?php echo smarty_modifier_lang("_REASONS");?>
</td>
              </tr>
              <tr align="center" class="title">
                <td width="30%" align="center"><?php echo smarty_modifier_lang("_REASON");?>
</td>
                <td align="center"><?php echo smarty_modifier_lang("_STATICBANTIME");?>
</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <?php
$__section_reasons_1_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_reasons']) ? $_smarty_tpl->tpl_vars['__smarty_section_reasons'] : false;
$__section_reasons_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['reasons']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_reasons_1_total = $__section_reasons_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_reasons'] = new Smarty_Variable(array());
if ($__section_reasons_1_total != 0) {
for ($__section_reasons_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index'] = 0; $__section_reasons_1_iteration <= $__section_reasons_1_total; $__section_reasons_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index']++){
?>
                  <form method="POST">
                    <input type="hidden" name="rid" value="<?php echo $_smarty_tpl->tpl_vars['reasons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index'] : null)]['id'];?>
" />
                    <tr class="info">
                      <td align="center"><input type="text" name="reason" value="<?php echo $_smarty_tpl->tpl_vars['reasons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index'] : null)]['reason'];?>
" /></td>
                      <td align="center"><input type="text" name="static_bantime" value="<?php echo $_smarty_tpl->tpl_vars['reasons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_reasons']->value['index'] : null)]['static_bantime'];?>
" /></td>
                      <td align="center">
                        <input type="submit" class="button" name="reasonsave" value="<?php echo smarty_modifier_lang("_SAVE");?>
" <?php if ($_SESSION['servers_edit'] !== "yes") {?>disabled<?php }?> />
                        <input type="submit" class="button" name="reasondel" value="<?php echo smarty_modifier_lang("_DEL");?>
" <?php if ($_SESSION['servers_edit'] !== "yes") {?>disabled<?php }?> />
                      </td>
                    </tr>
                  </form>
                <?php
}
}
if ($__section_reasons_1_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_reasons'] = $__section_reasons_1_saved;
}
?>
              </tr>
              <tr><td colspan="3">&nbsp</td></tr>
              <tr class="info">
                <form method="POST">
                  <td align="center"><input type="text" name="reason" value="" /></td>
                  <td align="center"><input type="text" name="static_bantime" value="" /></td>
                  <td align="center"><input type="submit" class="button" name="newreason" value="<?php echo smarty_modifier_lang("_NEWREASON");?>
" <?php if ($_SESSION['servers_edit'] !== "yes") {?>disabled<?php }?> /></td>
                </form>
              </tr>
            </table>
          <?php } else { ?>
            <?php echo smarty_modifier_lang("_NOACCESS");?>
 !!
          <?php }?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table><?php }
}
