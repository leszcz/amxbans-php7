<?php
/* Smarty version 3.1.30, created on 2017-12-09 06:43:04
  from "/home/crooket/cs-nypd.pl/public_html/forum/bans/templates/default/admin_ul.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a2b85f80eb957_85811707',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2239fe69de198300f88151978dc9660c136662b0' => 
    array (
      0 => '/home/crooket/cs-nypd.pl/public_html/forum/bans/templates/default/admin_ul.tpl',
      1 => 1512800717,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a2b85f80eb957_85811707 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_function_html_options')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/function.html_options.php';
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['output1']->value, 'foo');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['foo']->value) {
if ($_smarty_tpl->tpl_vars['foo']->value == "_YES") {
$_tmp_array = isset($_smarty_tpl->tpl_vars['output1']) ? $_smarty_tpl->tpl_vars['output1']->value : array();
if (!is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess) {
settype($_tmp_array, 'array');
}
$_tmp_array[0] = smarty_modifier_lang($_smarty_tpl->tpl_vars['foo']->value);
$_smarty_tpl->_assignInScope('output1', $_tmp_array);
} elseif ($_smarty_tpl->tpl_vars['foo']->value == "_NO") {
$_tmp_array = isset($_smarty_tpl->tpl_vars['output1']) ? $_smarty_tpl->tpl_vars['output1']->value : array();
if (!is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess) {
settype($_tmp_array, 'array');
}
$_tmp_array[1] = smarty_modifier_lang($_smarty_tpl->tpl_vars['foo']->value);
$_smarty_tpl->_assignInScope('output1', $_tmp_array);
}
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['output2']->value, 'foo');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['foo']->value) {
if ($_smarty_tpl->tpl_vars['foo']->value == "_YES") {
$_tmp_array = isset($_smarty_tpl->tpl_vars['output2']) ? $_smarty_tpl->tpl_vars['output2']->value : array();
if (!is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess) {
settype($_tmp_array, 'array');
}
$_tmp_array[0] = smarty_modifier_lang($_smarty_tpl->tpl_vars['foo']->value);
$_smarty_tpl->_assignInScope('output2', $_tmp_array);
} elseif ($_smarty_tpl->tpl_vars['foo']->value == "_NO") {
$_tmp_array = isset($_smarty_tpl->tpl_vars['output2']) ? $_smarty_tpl->tpl_vars['output2']->value : array();
if (!is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess) {
settype($_tmp_array, 'array');
}
$_tmp_array[1] = smarty_modifier_lang($_smarty_tpl->tpl_vars['foo']->value);
$_smarty_tpl->_assignInScope('output2', $_tmp_array);
} elseif ($_smarty_tpl->tpl_vars['foo']->value == "_OWN") {
$_tmp_array = isset($_smarty_tpl->tpl_vars['output2']) ? $_smarty_tpl->tpl_vars['output2']->value : array();
if (!is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess) {
settype($_tmp_array, 'array');
}
$_tmp_array[2] = smarty_modifier_lang($_smarty_tpl->tpl_vars['foo']->value);
$_smarty_tpl->_assignInScope('output2', $_tmp_array);
}
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

  
  <?php if ($_smarty_tpl->tpl_vars['msg']->value) {?>
    <div class="notice">
      <?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msg']->value);?>

    </div>
  <?php }?>
    <td id="main" valign="top" >
    <?php if ($_SESSION['amxadmins_view'] == "yes") {?>
      <span class="title"><?php echo smarty_modifier_lang("_ADMINLEVELSETTINGS");?>
</span>
      <table>
        <tr>
          <td>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['levels']->value, 'level');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['level']->value) {
?>
          <form method="POST">
            <input type="hidden" name="lid" value="<?php echo $_smarty_tpl->tpl_vars['level']->value['level'];?>
"></input>
            <fieldset><legend><span class="title"><?php echo smarty_modifier_lang("_LEVEL");?>
 #<?php echo $_smarty_tpl->tpl_vars['level']->value['level'];?>
</span></legend>
              <table style="border:1px;" width="100%">
                <tr class="title">
                  <td class="fat"><?php echo smarty_modifier_lang("_LEVEL");?>
</td><td colspan="6" align="center" class="fat"><?php echo smarty_modifier_lang("_BANS");?>
</td><td colspan="2" class="fat"><?php echo smarty_modifier_lang("_AMXADMINS");?>
</td><td>&nbsp</td>
                    
                </tr>
                <tr class="title">
                  <td>&nbsp</td>
                  <td><?php echo smarty_modifier_lang("_ADD");?>
</td><td><?php echo smarty_modifier_lang("_EDIT");?>
</td><td><?php echo smarty_modifier_lang("_DELETE");?>
</td><td><?php echo smarty_modifier_lang("_LEVELUNBAN");?>
</td><td><?php echo smarty_modifier_lang("_LEVELIMPORT");?>
</td><td><?php echo smarty_modifier_lang("_LEVELEXPORT");?>
</td>
                  <td><?php echo smarty_modifier_lang("_LEVELVIEW");?>
</td><td><?php echo smarty_modifier_lang("_EDIT");?>
</td><td>&nbsp</td>
                </tr>
                <tr>
                  <td rowspan="4" style="<?php if ($_smarty_tpl->tpl_vars['level']->value['level'] == $_SESSION['level']) {?>background-color:#00aa00;<?php }?>text-align:center;"><?php echo $_smarty_tpl->tpl_vars['level']->value['level'];?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'bans_add','values'=>$_smarty_tpl->tpl_vars['choose1']->value,'output'=>$_smarty_tpl->tpl_vars['output1']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['bans_add']),$_smarty_tpl);?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'bans_edit','values'=>$_smarty_tpl->tpl_vars['choose2']->value,'output'=>$_smarty_tpl->tpl_vars['output2']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['bans_edit']),$_smarty_tpl);?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'bans_delete','values'=>$_smarty_tpl->tpl_vars['choose2']->value,'output'=>$_smarty_tpl->tpl_vars['output2']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['bans_delete']),$_smarty_tpl);?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'bans_unban','values'=>$_smarty_tpl->tpl_vars['choose2']->value,'output'=>$_smarty_tpl->tpl_vars['output2']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['bans_unban']),$_smarty_tpl);?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'bans_import','values'=>$_smarty_tpl->tpl_vars['choose1']->value,'output'=>$_smarty_tpl->tpl_vars['output1']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['bans_import']),$_smarty_tpl);?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'bans_export','values'=>$_smarty_tpl->tpl_vars['choose1']->value,'output'=>$_smarty_tpl->tpl_vars['output1']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['bans_export']),$_smarty_tpl);?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'amxadmins_view','values'=>$_smarty_tpl->tpl_vars['choose1']->value,'output'=>$_smarty_tpl->tpl_vars['output1']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['amxadmins_view']),$_smarty_tpl);?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'amxadmins_edit','values'=>$_smarty_tpl->tpl_vars['choose1']->value,'output'=>$_smarty_tpl->tpl_vars['output1']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['amxadmins_edit']),$_smarty_tpl);?>
</td>
                  <td rowspan="4" <?php if ($_smarty_tpl->tpl_vars['level']->value['level'] == $_SESSION['level']) {?>style="background-color: #00aa00;" <?php }?>>
                      <input style="margin:0 auto;display:block;" type="submit" class="button" name="save" value="<?php echo smarty_modifier_lang("_SAVE");?>
" <?php if ($_SESSION['permissions_edit'] !== "yes") {?>disabled<?php }?> />
                      <?php if (($_smarty_tpl->tpl_vars['level']->value['level'] == $_smarty_tpl->tpl_vars['level_max']->value && $_smarty_tpl->tpl_vars['level']->value['level'] > 1)) {?>
                      <input style="margin:0 auto;display:block;" type="submit" class="button" name="del" value="<?php echo smarty_modifier_lang("_DELETE");?>
" onclick="return confirm('<?php echo smarty_modifier_lang("_DELLEVEL");?>
');" <?php if ($_SESSION['permissions_edit'] !== "yes") {?>disabled<?php }?> />
                      <?php }?>
                    </td>
                </tr>
                <tr class="title">
                  <td colspan="2" class="fat"><?php echo smarty_modifier_lang("_WEBADMINS");?>
</td><td colspan="2" class="fat"><?php echo smarty_modifier_lang("_WEBSETTINGS");?>
</td><td colspan="4" class="fat"><?php echo smarty_modifier_lang("_OTHER");?>
</td>
                </tr>
                <tr class="title">
                  <td><?php echo smarty_modifier_lang("_LEVELVIEW");?>
</td><td><?php echo smarty_modifier_lang("_EDIT");?>
</td>
                  <td><?php echo smarty_modifier_lang("_LEVELVIEW");?>
</td><td><?php echo smarty_modifier_lang("_EDIT");?>
</td>
                  <td><?php echo smarty_modifier_lang("_PERM");?>
</td><td><?php echo smarty_modifier_lang("_DBPRUNE");?>
</td><td><?php echo smarty_modifier_lang("_SERVEREDIT");?>
</td><td><?php echo smarty_modifier_lang("_VIEWIP");?>
</td>
                </tr>
                <tr align="center">
                  <td><?php echo smarty_function_html_options(array('name'=>'webadmins_view','values'=>$_smarty_tpl->tpl_vars['choose1']->value,'output'=>$_smarty_tpl->tpl_vars['output1']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['webadmins_view']),$_smarty_tpl);?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'webadmins_edit','values'=>$_smarty_tpl->tpl_vars['choose1']->value,'output'=>$_smarty_tpl->tpl_vars['output1']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['webadmins_edit']),$_smarty_tpl);?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'websettings_view','values'=>$_smarty_tpl->tpl_vars['choose1']->value,'output'=>$_smarty_tpl->tpl_vars['output1']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['websettings_view']),$_smarty_tpl);?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'websettings_edit','values'=>$_smarty_tpl->tpl_vars['choose1']->value,'output'=>$_smarty_tpl->tpl_vars['output1']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['websettings_edit']),$_smarty_tpl);?>
</td>
                  
                  <td><?php echo smarty_function_html_options(array('name'=>'permissions_edit','values'=>$_smarty_tpl->tpl_vars['choose1']->value,'output'=>$_smarty_tpl->tpl_vars['output1']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['permissions_edit']),$_smarty_tpl);?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'prune_db','values'=>$_smarty_tpl->tpl_vars['choose1']->value,'output'=>$_smarty_tpl->tpl_vars['output1']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['prune_db']),$_smarty_tpl);?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'servers_edit','values'=>$_smarty_tpl->tpl_vars['choose1']->value,'output'=>$_smarty_tpl->tpl_vars['output1']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['servers_edit']),$_smarty_tpl);?>
</td>
                  <td><?php echo smarty_function_html_options(array('name'=>'ip_view','values'=>$_smarty_tpl->tpl_vars['choose1']->value,'output'=>$_smarty_tpl->tpl_vars['output1']->value,'selected'=>$_smarty_tpl->tpl_vars['level']->value['ip_view']),$_smarty_tpl);?>
</td>
                </tr>
              </table>
            </fieldset>
            <div class="clearer">&nbsp</div>
          </form>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

        <form method="POST">
          <div class="_right">
            <input type="submit" class="button" name="new" value="<?php echo smarty_modifier_lang("_NEWLEVEL");?>
" <?php if ($_SESSION['permissions_edit'] !== "yes") {?>disabled<?php }?> />
          </div>
        </form>
      <?php } else { ?>
        <?php echo smarty_modifier_lang("_NOACCESS");?>

      <?php }?>
      </td></tr></table>
    </td>
  </tr>
</table><?php }
}
