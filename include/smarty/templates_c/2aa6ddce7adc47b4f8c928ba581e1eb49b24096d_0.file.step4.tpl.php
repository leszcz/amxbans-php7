<?php
/* Smarty version 3.1.30, created on 2024-10-07 06:27:06
  from "/home/leszczu/web/amxbans.1free.eu/public_html/install/step4.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_67037f3a8db7a7_00222829',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2aa6ddce7adc47b4f8c928ba581e1eb49b24096d' => 
    array (
      0 => '/home/leszczu/web/amxbans.1free.eu/public_html/install/step4.tpl',
      1 => 1707251794,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67037f3a8db7a7_00222829 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.lang.php';
?>
<div>
  <div align="center" class="notice"><?php echo smarty_modifier_lang("_STEP4DESC");?>
</div>
  <br />
  <fieldset>
    <legend><?php echo smarty_modifier_lang("_DBSETTINGS");?>
</legend>
      <table width="100%" cellpadding="5">
        <tr>
          <td><?php echo smarty_modifier_lang("_HOST");?>
:</td>
          <td width="1%"><input type="text" name="dbhost" value="<?php if ($_smarty_tpl->tpl_vars['db']->value[0] || $_SESSION['dbhost']) {
echo (($tmp = @$_smarty_tpl->tpl_vars['db']->value[0])===null||$tmp==='' ? $_SESSION['dbhost'] : $tmp);
} else { ?>127.0.0.1<?php }?>" size="20" /></td>
        </tr>
        <tr>
          <td><?php echo smarty_modifier_lang("_USER");?>
:</td>
          <td width="1%"><input type="text" name="dbuser" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['db']->value[1])===null||$tmp==='' ? $_SESSION['dbuser'] : $tmp);?>
" size="20" /></td>
        </tr>
        <tr>
          <td><?php echo smarty_modifier_lang("_PASSWORD");?>
:</td>
          <td width="1%"><input type="password" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['db']->value[2])===null||$tmp==='' ? $_SESSION['dbpass'] : $tmp);?>
" name="dbpass" size="20" /></td>
        </tr>
        <tr>
          <td><?php echo smarty_modifier_lang("_DATABASE");?>
:</td>
          <td width="1%"><input type="text" name="dbdb" value="<?php if ($_smarty_tpl->tpl_vars['db']->value[3] || $_SESSION['dbdb']) {
echo (($tmp = @$_smarty_tpl->tpl_vars['db']->value[3])===null||$tmp==='' ? $_SESSION['dbdb'] : $tmp);
} else { ?>amxbans<?php }?>" size="20" /></td>
        </tr>
        <tr>
          <td><?php echo smarty_modifier_lang("_TBLPREFIX");?>
:</td>
          <td width="1%"><input type="text" name="dbprefix" value="<?php if ($_smarty_tpl->tpl_vars['db']->value[4] || $_SESSION['dbprefix']) {
echo (($tmp = @$_smarty_tpl->tpl_vars['db']->value[4])===null||$tmp==='' ? $_SESSION['dbprefix'] : $tmp);
} else { ?>amx<?php }?>" size="20" /></td>
        </tr>
      </table>
  </fieldset>
  <?php if ($_smarty_tpl->tpl_vars['prevs']->value) {?>
    <fieldset>
      <legend><?php echo smarty_modifier_lang("_DBPREVILEGES");?>
</legend>
      <table width="100%" cellpadding="2">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['prevs']->value, 'prev');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['prev']->value) {
?>
          <tr class="settings_line">
            <td><b><?php echo $_smarty_tpl->tpl_vars['prev']->value['name'];?>
</b></td>
            <td width="1%"><img src="images/<?php if ($_smarty_tpl->tpl_vars['prev']->value['value'] == 1) {?>success.gif<?php } else { ?>cross.png<?php }?>" /></td>
          </tr>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

      </table>
  <?php }?>
  <br />
  <?php if ($_smarty_tpl->tpl_vars['msg']->value) {?>
    <div class="<?php if ($_smarty_tpl->tpl_vars['msg']->value == "_DBOK") {?>success<?php } else { ?>error<?php }?>"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msg']->value);?>
</div>
  <?php }
}
}
