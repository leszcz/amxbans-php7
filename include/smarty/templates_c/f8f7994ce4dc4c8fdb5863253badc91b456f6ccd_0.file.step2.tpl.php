<?php
/* Smarty version 3.1.30, created on 2024-10-07 06:14:23
  from "/home/leszczu/web/amxbans.1free.eu/public_html/install/step2.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_67037c3f131313_44574757',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f8f7994ce4dc4c8fdb5863253badc91b456f6ccd' => 
    array (
      0 => '/home/leszczu/web/amxbans.1free.eu/public_html/install/step2.tpl',
      1 => 1707251794,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67037c3f131313_44574757 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.lang.php';
?>
<div>
	<div align="center" class="notice"><?php echo smarty_modifier_lang("_STEP2DESC");?>
</div>
	<fieldset>
	<legend><?php echo smarty_modifier_lang("_SERVERSETUP");?>
</legend>
	<table width="80%" align="center">
		<tr class="settings_line">
			<td>PHP <?php echo smarty_modifier_lang("_VERSION");?>
</td>
			<td><img src="images/<?php if ($_smarty_tpl->tpl_vars['php_settings']->value['version_php'] >= 5.0) {?>success<?php } else { ?>warning<?php }?>.gif" /> <?php echo $_smarty_tpl->tpl_vars['php_settings']->value['version_php'];?>
</td>
		</tr>
		<tr class="settings_line">
			<td>MySQL <?php echo smarty_modifier_lang("_VERSION");?>
</td>
			<td><img src="images/<?php if ($_smarty_tpl->tpl_vars['php_settings']->value['mysql_version'] >= 4.1) {?>success<?php } else { ?>warning<?php }?>.gif" /> <?php echo $_smarty_tpl->tpl_vars['php_settings']->value['mysql_version'];?>
</td>
		</tr>
		<tr class="settings_line">
			<td width="40%">safe_mode</td>
			<td valign="center"><img src="images/<?php if ($_smarty_tpl->tpl_vars['php_settings']->value['safe_mode'] == "_ON") {?>success<?php } else { ?>warning<?php }?>.gif" /> <?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['php_settings']->value['safe_mode']);?>
</td>
		</tr>
		<tr class="settings_line">
			<td>register_globals</td>
			<td><img src="images/<?php if ($_smarty_tpl->tpl_vars['php_settings']->value['register_globals'] == "_OFF") {?>success<?php } else { ?>warning<?php }?>.gif" /> <?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['php_settings']->value['register_globals']);?>
</td>
		</tr>
		<tr class="settings_line">
			<td>magic_quotes_gpc</td>
			<td><img src="images/<?php if ($_smarty_tpl->tpl_vars['php_settings']->value['magic_quotes_gpc'] == "_OFF") {?>success<?php } else { ?>warning<?php }?>.gif" /> <?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['php_settings']->value['magic_quotes_gpc']);?>
</td>
		</tr>
		<tr class="settings_line">
			<td>upload_max_filesize</td>
			<td><?php echo $_smarty_tpl->tpl_vars['php_settings']->value['upload_max_filesize'];?>
</td>
		</tr>
		<tr class="settings_line">
			<td>max_execution_time</td>
			<td><?php echo $_smarty_tpl->tpl_vars['php_settings']->value['max_execution_time'];?>
 <?php echo smarty_modifier_lang("_SEC");?>
</td>
		</tr>
	</table>
	</fieldset>
	<br />
	<div class="notice">
		<img src="images/success.gif" /> - <?php echo smarty_modifier_lang("_SETRECOMMENDED");?>
<br />
		<img src="images/warning.gif" /> - <?php echo smarty_modifier_lang("_SETNOTRECOMMENDED");?>

	</div>
</div><?php }
}
