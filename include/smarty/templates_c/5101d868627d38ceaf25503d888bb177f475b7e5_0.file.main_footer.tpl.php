<?php
/* Smarty version 3.1.30, created on 2018-08-31 09:55:27
  from "/home/nypd/public_html/forum/bans/dd2/templates/default/main_footer.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5b8948cf15f954_74383738',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5101d868627d38ceaf25503d888bb177f475b7e5' => 
    array (
      0 => '/home/nypd/public_html/forum/bans/dd2/templates/default/main_footer.tpl',
      1 => 1532941654,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b8948cf15f954_74383738 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/nypd/public_html/forum/bans/dd2/include/smarty/plugins/modifier.lang.php';
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
