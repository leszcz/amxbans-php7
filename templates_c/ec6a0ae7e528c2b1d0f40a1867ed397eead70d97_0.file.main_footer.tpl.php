<?php
/* Smarty version 3.1.30, created on 2024-02-07 02:01:33
  from "/home/kampownia/web/bans.kampownia.eu/public_html/templates/default/main_footer.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65c2e47dbed459_49633191',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ec6a0ae7e528c2b1d0f40a1867ed397eead70d97' => 
    array (
      0 => '/home/kampownia/web/bans.kampownia.eu/public_html/templates/default/main_footer.tpl',
      1 => 1707246853,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65c2e47dbed459_49633191 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/kampownia/web/bans.kampownia.eu/public_html/include/smarty/plugins/modifier.lang.php';
if (!$_smarty_tpl->tpl_vars['ajaxlist']->value) {?>
					<div class="smallfont">
						<span style="float:left"><strong>AMXBans <span style="color: #e90042">#</span> Gm <?php echo $_smarty_tpl->tpl_vars['version_web']->value;?>
</strong> by <a href="http://gm-community.net" title="GmStaff, xPaw, Sho0ter" target="_blank">Larte Team</a>.</span>
						<span style="float:right"><strong><?php echo smarty_modifier_lang("_DESIGN_BY");?>
</strong>: <a href="http://gm-community.net" title="GmStaff">GmStaff</a></span>
					</div>
				</div>
			</div>
		<br /><br />
	</body>
	</html>
<?php }
}
}
