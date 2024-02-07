<?php
/* Smarty version 3.1.30, created on 2024-02-06 20:41:06
  from "/home/kampownia/web/bans.kampownia.eu/public_html/install/step6.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65c299623d8304_19568182',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dca6e00ee10f605bf0f1687eecb737bacec20d97' => 
    array (
      0 => '/home/kampownia/web/bans.kampownia.eu/public_html/install/step6.tpl',
      1 => 1707246853,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65c299623d8304_19568182 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/kampownia/web/bans.kampownia.eu/public_html/include/smarty/plugins/modifier.lang.php';
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
