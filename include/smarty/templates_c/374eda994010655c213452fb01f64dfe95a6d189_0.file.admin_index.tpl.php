<?php
/* Smarty version 3.1.30, created on 2018-08-31 09:55:27
  from "/home/nypd/public_html/forum/bans/dd2/templates/default/admin_index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5b8948cf13c7e5_81098838',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '374eda994010655c213452fb01f64dfe95a6d189' => 
    array (
      0 => '/home/nypd/public_html/forum/bans/dd2/templates/default/admin_index.tpl',
      1 => 1532941653,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b8948cf13c7e5_81098838 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/nypd/public_html/forum/bans/dd2/include/smarty/plugins/modifier.lang.php';
?>
<div id="navigation">
	<div id="main-nav">
		<ul class="tabbed">
			<li><a class="main-nav_a" id="#menu_1"><h1><?php echo smarty_modifier_lang("_ADMINAREA");?>
</h1></a></li>
			<li><a class="main-nav_a" id="#menu_2"><h1><?php echo smarty_modifier_lang("_SERVER");?>
</h1></a></li>
			<li><a class="main-nav_a" id="#menu_3"><h1><?php echo smarty_modifier_lang("_WEB");?>
</h1></a></li>
			<li><a class="main-nav_a" id="#menu_4"><h1><?php echo smarty_modifier_lang("_MODULES");?>
</h1></a></li>
		</ul>
		<div class="clearer">&nbsp;</div>
	</div>

	<div id="sub-nav">
		<div id="menu_1" style="<?php if ($_smarty_tpl->tpl_vars['menu_pos']->value == "so_up" || $_smarty_tpl->tpl_vars['menu_pos']->value == "so_in" || $_smarty_tpl->tpl_vars['menu_pos']->value == "ban_add" || $_smarty_tpl->tpl_vars['menu_pos']->value == "ban_add_online") {?>display: block;<?php } else { ?>display: none;<?php }?>">
			<ul class="tabbed">
				<li><a href="admin.php"><?php echo smarty_modifier_lang("_MENUINFO");?>
</a></li>
				<li><a href="admin.php?site=ban_add"><?php echo smarty_modifier_lang("_ADDBAN");?>
</a></li>
				<li><a href="admin.php?site=ban_add_online"><?php echo smarty_modifier_lang("_ADDBANONLINE");?>
</a></li>
			</ul>
		</div>
		<div id="menu_2" style="<?php if ($_smarty_tpl->tpl_vars['menu_pos']->value == "sm_sv" || $_smarty_tpl->tpl_vars['menu_pos']->value == "sm_bg" || $_smarty_tpl->tpl_vars['menu_pos']->value == "sm_av" || $_smarty_tpl->tpl_vars['menu_pos']->value == "sm_sa") {?>display: block;<?php } else { ?>display: none;<?php }?>">
			<ul class="tabbed">
				<li><a href="admin.php?site=sm_sv"><?php echo smarty_modifier_lang("_SETTINGS");?>
</a></li>
				<li><a href="admin.php?site=sm_bg"><?php echo smarty_modifier_lang("_MENUREASONS");?>
</a></li>
				<li><a href="admin.php?site=sm_av"><?php echo smarty_modifier_lang("_ADMINS");?>
</a></li>
				<li><a href="admin.php?site=sm_sa"><?php echo smarty_modifier_lang("_TITLEADMIN");?>
</a></li>
			</ul>
		</div>
		<div id="menu_3" style="<?php if ($_smarty_tpl->tpl_vars['menu_pos']->value == "wm_wa" || $_smarty_tpl->tpl_vars['menu_pos']->value == "wm_ul" || $_smarty_tpl->tpl_vars['menu_pos']->value == "wm_um" || $_smarty_tpl->tpl_vars['menu_pos']->value == "wm_ms" || $_smarty_tpl->tpl_vars['menu_pos']->value == "so_lg") {?>display: block;<?php } else { ?>display: none;<?php }?>">
			<ul class="tabbed">
				<li><a href="admin.php?site=wm_wa"><?php echo smarty_modifier_lang("_ADMINS");?>
</a></li>
				<li><a href="admin.php?site=wm_ul"><?php echo smarty_modifier_lang("_PERM");?>
</a></li>
				<li><a href="admin.php?site=wm_um"><?php echo smarty_modifier_lang("_MENUUSERMENU");?>
</a></li>
				<li><a href="admin.php?site=wm_ms"><?php echo smarty_modifier_lang("_SETTINGS");?>
</a></li>
				<li><a href="admin.php?site=so_lg"><?php echo smarty_modifier_lang("_MENULOGS");?>
</a></li>
			</ul>
		</div>
		<div id="menu_4" style="<?php if ($_smarty_tpl->tpl_vars['menu_pos']->value == "so_mo" || $_smarty_tpl->tpl_vars['menu_pos']->value == "iexport" || $_smarty_tpl->tpl_vars['menu_pos']->value == "usersi") {?>display: block;<?php } else { ?>display: none;<?php }?>">
			<ul class="tabbed">
				<li><a href="admin.php?site=so_mo"><?php echo smarty_modifier_lang("_MODULES");?>
</a></li>
				<li><a href="admin.php?modul=iexport"><?php echo smarty_modifier_lang("_MENUIMPORTEXPORT");?>
</a></li>
				<li><a href="admin.php?modul=usersi"><?php echo smarty_modifier_lang("_MENUIMPORTADMINS");?>
</a></li>
			</ul>
		</div>
		<div class="clearer">&nbsp;</div>
	</div>
</div><?php }
}
