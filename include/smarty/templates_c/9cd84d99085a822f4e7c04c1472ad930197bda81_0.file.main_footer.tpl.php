<?php
/* Smarty version 3.1.30, created on 2017-12-09 06:41:39
  from "/home/crooket/cs-nypd.pl/public_html/forum/bans/templates/default/main_footer.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a2b85a30e02c9_88056791',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9cd84d99085a822f4e7c04c1472ad930197bda81' => 
    array (
      0 => '/home/crooket/cs-nypd.pl/public_html/forum/bans/templates/default/main_footer.tpl',
      1 => 1512800718,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a2b85a30e02c9_88056791 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/modifier.lang.php';
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
