<?php
/* Smarty version 3.1.30, created on 2017-12-06 06:46:28
  from "/home/crooket/cs-nypd.pl/public_html/forum/bans/install/step3.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a2792440fd315_91860757',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '12cf43de9782a0581732dc028ddcfd4ad7987902' => 
    array (
      0 => '/home/crooket/cs-nypd.pl/public_html/forum/bans/install/step3.tpl',
      1 => 1512537957,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a2792440fd315_91860757 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/modifier.lang.php';
?>
<div>
	<div align="center" class="notice"><?php echo smarty_modifier_lang("_STEP3DESC");?>
<br /><?php echo smarty_modifier_lang("_STEP3DESC2");?>
</div>
	<br />
	<fieldset>
		<legend><?php echo smarty_modifier_lang("_ROOTDIRS");?>
</legend>
			<table width="100%" cellpadding="5">
				<tr class="settings_line">
					<td><?php echo smarty_modifier_lang("_DIRROOT");?>
:</td>
					<td width="1%"><input type="text" name="path_root" value="<?php echo $_smarty_tpl->tpl_vars['dirs']->value['path_root'];?>
" size="50" /></td>
				</tr>
				<tr class="settings_line">
					<td><?php echo smarty_modifier_lang("_DIRDOCUMENT");?>
:</td>
					<td><input type="text" name="document_root" value="<?php echo $_smarty_tpl->tpl_vars['dirs']->value['document_root'];?>
" size="50" /></td>
				</tr>
			</table>
	</fieldset>
<br />
	<fieldset>
		<legend><?php echo smarty_modifier_lang("_DIRCHECK");?>
</legend>
			<table width="100%" cellpadding="5">
				<tr>
					<td>include/</td>
					<td width="1%"><img src="images/<?php if ($_smarty_tpl->tpl_vars['dirs']->value['include'] == true) {?>success.gif<?php } else { ?>cross.png<?php }?>" /></td>
				</tr>
				<tr>
					<td>temp/</td>
					<td><img src="images/<?php if ($_smarty_tpl->tpl_vars['dirs']->value['temp'] == true) {?>success.gif<?php } else { ?>cross.png<?php }?>" /></td>
				</tr>
				<tr>
					<td>include/smarty/templates_c</td>
					<td valign="center"><img src="images/<?php if ($_smarty_tpl->tpl_vars['dirs']->value['templates_c'] == true) {?>success.gif<?php } else { ?>cross.png<?php }?>" /></td>
				</tr>
				<tr>
					<td>include/files/</td>
					<td><img src="images/<?php if ($_smarty_tpl->tpl_vars['dirs']->value['files'] == true) {?>success.gif<?php } else { ?>cross.png<?php }?>" /></td>
				</tr>
				<tr>
					<td>include/backup/</td>
					<td><img src="images/<?php if ($_smarty_tpl->tpl_vars['dirs']->value['backup'] == true) {?>success.gif<?php } else { ?>cross.png<?php }?>" /></td>
				</tr>
				<tr>
					<td>/</td>
					<td><img src="images/<?php if ($_smarty_tpl->tpl_vars['dirs']->value['setupphp'] == true) {?>success.gif<?php } else { ?>warning.gif<?php }?>" /></td>
				</tr>
			</table>
	</fieldset>
	<br />
	<div class="notice">
		<img src="images/success.gif" /> - <?php echo smarty_modifier_lang("_OK");?>
<br />
		<img src="images/warning.gif" /> - <?php echo smarty_modifier_lang("_SETNOTRECOMMENDED");?>
<br />
		<img src="images/cross.png" /> - <?php echo smarty_modifier_lang("_NOTWRITABLE");?>

	</div>
	<?php if ($_smarty_tpl->tpl_vars['dirs']->value['setupphp'] == false) {?>
		<div class="welcome"><img src="images/warning.gif" /> <?php echo smarty_modifier_lang("_SETUPNOTDELETABLE");?>
</div>
	<?php }
}
}
