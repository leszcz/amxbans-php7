<?php
/* Smarty version 3.1.30, created on 2018-08-31 09:56:50
  from "/home/nypd/public_html/forum/bans/dd2/templates/default/login.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5b894922688a20_04821487',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2efb40d7b3c8ab5b88aab9a62fe71be3ec6a8029' => 
    array (
      0 => '/home/nypd/public_html/forum/bans/dd2/templates/default/login.tpl',
      1 => 1532941654,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b894922688a20_04821487 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/nypd/public_html/forum/bans/dd2/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_modifier_date2word')) require_once '/home/nypd/public_html/forum/bans/dd2/include/smarty/plugins/modifier.date2word.php';
?>
<table width="50%" border="1" cellpadding="2">
	<tr class="table_head">
		<td>&nbsp;</td>
	</tr>
	<tr class="table_list">
		<td>
			<form name="loginform" action="login.php" method="post">
				<fieldset><legend><span class='title'><?php echo smarty_modifier_lang("_LOGIN");?>
</span></legend>
			<table width='20%'>
				<tr><td class='enter' class="fat"><?php echo smarty_modifier_lang("_USERNAME");?>
:</span></td> <td class='enter'><input type="text" name="user"></td><td><input type='checkbox' value='yes' name='remember'></input> <?php echo smarty_modifier_lang("_REMEMBERME");?>
</td></tr>
				<tr><td class='enter' class="fat"><?php echo smarty_modifier_lang("_PASSWORD");?>
:</span></td> <td class='enter'><input type="password" name="pass""></td><td><button type="submit" name="action" id="action2" value="Login"><?php echo smarty_modifier_lang("_LOGIN");?>
</button></td></tr>
				<?php if ($_smarty_tpl->tpl_vars['msg']->value) {?><span class='errored'><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msg']->value);?>
</span><br /><?php }?>
				<?php if ($_smarty_tpl->tpl_vars['try']->value) {?><span class='errored'><?php echo smarty_modifier_lang("_LOGINTRY");?>
 <?php echo $_smarty_tpl->tpl_vars['try']->value;?>
/3</span><br /><?php }?>
				<?php if ($_smarty_tpl->tpl_vars['block_left']->value) {?><span class='errored'><?php echo smarty_modifier_date2word($_smarty_tpl->tpl_vars['block_left']->value,true);?>
 <?php echo smarty_modifier_lang("_REMAINING");?>
</span><br /><?php }?>
			</table>
				</fieldset>
			</form>
		</td>
	</tr>
</table><?php }
}
