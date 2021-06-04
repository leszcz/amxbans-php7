<?php
/* Smarty version 3.1.30, created on 2017-12-08 09:07:09
  from "/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/templates/default/main_footer.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a2a482d36a6e5_62410300',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c24caff00e5054aa0237545e2a2b1f7f7ecdd53f' => 
    array (
      0 => '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/templates/default/main_footer.tpl',
      1 => 1512448107,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a2a482d36a6e5_62410300 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/include/smarty/plugins/modifier.lang.php';
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
