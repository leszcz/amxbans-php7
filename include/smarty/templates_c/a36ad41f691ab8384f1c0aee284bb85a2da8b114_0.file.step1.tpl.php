<?php
/* Smarty version 3.1.30, created on 2020-11-09 09:47:14
  from "C:\xampp\htdocs\bans\install\step1.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5fa902129d3339_68118732',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a36ad41f691ab8384f1c0aee284bb85a2da8b114' => 
    array (
      0 => 'C:\\xampp\\htdocs\\bans\\install\\step1.tpl',
      1 => 1576191600,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../include/license.php' => 1,
  ),
),false)) {
function content_5fa902129d3339_68118732 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once 'C:\\xampp\\htdocs\\bans\\include\\smarty\\plugins\\modifier.lang.php';
?>
<div class="step">
	<div align="center">
		<b><?php echo smarty_modifier_lang("_WELCOME");?>
 <?php echo smarty_modifier_lang("_WELCOME2");?>
</b>
<br /><br />
		<textarea align="center" cols="75" rows="20" readonly>
			<?php $_smarty_tpl->_subTemplateRender("file:../include/license.php", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		</textarea>
		<br />
			<input type="checkbox" name="chkbox" id="chkbox" onclick="agree()" /> <?php echo smarty_modifier_lang("_LICENSEAGREE");?>

		<br /> <br />
		<a href="http://creativecommons.org/licenses/by-nc-sa/3.0/" target="_blank"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" /></a>
		<br /> <br />
	</div>
</div>
<?php }
}
