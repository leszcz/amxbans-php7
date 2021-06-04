<?php
/* Smarty version 3.1.30, created on 2017-11-21 13:10:43
  from "/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans/install/step1.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a1417c3835356_25241700',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '489b015cece2f8ef79c60b820ebb54b68ef4a6ad' => 
    array (
      0 => '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans/install/step1.tpl',
      1 => 1510723488,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../include/license.php' => 1,
  ),
),false)) {
function content_5a1417c3835356_25241700 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans/include/smarty/plugins/modifier.lang.php';
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
