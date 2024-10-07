<?php
/* Smarty version 3.1.30, created on 2024-10-07 06:39:35
  from "/home/leszczu/web/amxbans.1free.eu/public_html/install/step6.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6703822720cd25_41878417',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '01d28e994b4f721a411060e75384e9fd996ed9bd' => 
    array (
      0 => '/home/leszczu/web/amxbans.1free.eu/public_html/install/step6.tpl',
      1 => 1707251794,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6703822720cd25_41878417 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.lang.php';
?>
<div>
	<div align="center" class="notice"><?php echo smarty_modifier_lang("_STEP6DESC");?>
<br /><?php echo smarty_modifier_lang("_STEP6DESC2");?>
</div>
	<br />
	<fieldset>
		<legend><?php echo smarty_modifier_lang("_ROOTDIRS");?>
</legend>
		<table width="100%" cellpadding="5">
			<tr class="settings_line">
				<td width="40%"><?php echo smarty_modifier_lang("_DIRROOT");?>
:</td>
				<td><?php echo $_SESSION['path_root'];?>
</td>
			</tr>
			<tr class="settings_line">
				<td><?php echo smarty_modifier_lang("_DIRDOCUMENT");?>
:</td>
				<td><?php echo $_SESSION['document_root'];?>
</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo smarty_modifier_lang("_DBSETTINGS");?>
</legend>
		<table width="100%" cellpadding="5">
			<tr class="settings_line">
				<td width="40%"><?php echo smarty_modifier_lang("_HOST");?>
:</td>
				<td><?php echo $_SESSION['dbhost'];?>
</td>
			</tr>
			<tr class="settings_line">
				<td><?php echo smarty_modifier_lang("_USER");?>
:</td>
				<td><?php echo $_SESSION['dbuser'];?>
</td>
			</tr>
			<tr class="settings_line">
				<td><?php echo smarty_modifier_lang("_DATABASE");?>
:</td>
				<td><?php echo $_SESSION['dbdb'];?>
</td>
			</tr>
			<tr class="settings_line">
				<td><?php echo smarty_modifier_lang("_TBLPREFIX");?>
:</td>
				<td><?php echo $_SESSION['dbprefix'];?>
</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo smarty_modifier_lang("_ADMINSETTINGS");?>
</legend>
		<table width="100%" cellpadding="5">
			<tr class="settings_line">
				<td width="40%"><?php echo smarty_modifier_lang("_USER");?>
:</td>
				<td><?php echo $_SESSION['adminuser'];?>
</td>
			</tr>
			<tr class="settings_line">
				<td><?php echo smarty_modifier_lang("_EMAILADR");?>
:</td>
				<td><?php echo $_SESSION['adminemail'];?>
</td>
			</tr>
		</table>
	</fieldset><?php }
}
