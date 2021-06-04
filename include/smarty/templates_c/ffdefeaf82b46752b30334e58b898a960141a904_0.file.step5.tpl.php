<?php
/* Smarty version 3.1.30, created on 2017-12-06 06:46:32
  from "/home/crooket/cs-nypd.pl/public_html/forum/bans/install/step5.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a27924821c670_55007703',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ffdefeaf82b46752b30334e58b898a960141a904' => 
    array (
      0 => '/home/crooket/cs-nypd.pl/public_html/forum/bans/install/step5.tpl',
      1 => 1512537957,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a27924821c670_55007703 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/modifier.lang.php';
?>
<div>
  <div align="center" class="notice"><?php echo smarty_modifier_lang("_STEP5DESC");?>
</div>
  <br />
  <fieldset>
    <legend><?php echo smarty_modifier_lang("_ADMINSETTINGS");?>
</legend>
    <table width="100%" cellpadding="5">
      <tr class="settings_line">
        <td><?php echo smarty_modifier_lang("_USER");?>
:</td>
        <td width="1%"><input type="text" name="adminuser" value="<?php if ($_smarty_tpl->tpl_vars['admin']->value[0] || $_SESSION['adminuser']) {
echo (($tmp = @$_smarty_tpl->tpl_vars['admin']->value[0])===null||$tmp==='' ? $_SESSION['adminuser'] : $tmp);
} else { ?>admin<?php }?>" size="20" /></td>
      </tr>
      <tr class="settings_line">
        <td><?php echo smarty_modifier_lang("_PASSWORD");?>
:</td>
        <td width="1%"><input type="password" name="adminpass" value="<?php if ($_smarty_tpl->tpl_vars['adminpass']->value || $_SESSION['adminpass']) {
echo (($tmp = @$_smarty_tpl->tpl_vars['adminpass']->value)===null||$tmp==='' ? $_SESSION['adminpass'] : $tmp);
}?>" size="20" /></td>
      </tr>
      <tr class="settings_line">
        <td><?php echo smarty_modifier_lang("_PASSWORD2");?>
:</td>
        <td width="1%"><input type="password" name="adminpass2" value="<?php if ($_smarty_tpl->tpl_vars['adminpass']->value || $_SESSION['adminpass2']) {
echo (($tmp = @$_smarty_tpl->tpl_vars['adminpass']->value)===null||$tmp==='' ? $_SESSION['adminpass'] : $tmp);
}?>" size="20" /></td>
      </tr>
      <tr class="settings_line">
        <td><?php echo smarty_modifier_lang("_EMAILADR");?>
:</td>
        <td width="1%"><input type="text" name="adminemail" value="<?php if ($_smarty_tpl->tpl_vars['admin']->value[1] || $_SESSION['adminemail']) {
echo (($tmp = @$_smarty_tpl->tpl_vars['admin']->value[1])===null||$tmp==='' ? $_SESSION['adminemail'] : $tmp);
} else { ?>admin@domain.com<?php }?>" size="20" /></td>
      </tr>
    </table>
  <br />
  <?php if ($_smarty_tpl->tpl_vars['validate']->value) {?>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['validate']->value, 'validates');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['validates']->value) {
?>
      <div class="error"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['validates']->value);?>
</div>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

  <?php }?>
  <?php if ($_smarty_tpl->tpl_vars['msg']->value) {?>
    <div class="<?php if ($_smarty_tpl->tpl_vars['msg']->value == "_ADMINOK") {?>success<?php } else { ?>error<?php }?>"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msg']->value);?>
</div>
  <?php }
}
}
