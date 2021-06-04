<?php
/* Smarty version 3.1.30, created on 2017-12-05 05:33:10
  from "/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/install/step1.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a26218677fcf5_85376531',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '53d6e01015c2186fab0a62ad3418e6438f9282ec' => 
    array (
      0 => '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/install/step1.tpl',
      1 => 1512448127,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../include/license.php' => 1,
  ),
),false)) {
function content_5a26218677fcf5_85376531 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/include/smarty/plugins/modifier.lang.php';
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
