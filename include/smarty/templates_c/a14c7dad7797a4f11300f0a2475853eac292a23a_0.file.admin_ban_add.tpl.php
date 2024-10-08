<?php
/* Smarty version 3.1.30, created on 2024-10-07 07:08:31
  from "/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/admin_ban_add.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_670388ef82f7c2_46910361',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a14c7dad7797a4f11300f0a2475853eac292a23a' => 
    array (
      0 => '/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/admin_ban_add.tpl',
      1 => 1707251794,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_670388ef82f7c2_46910361 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_function_html_options')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/function.html_options.php';
if ($_smarty_tpl->tpl_vars['msg']->value <> '') {?>
	<div class="success"><?php echo smarty_modifier_lang(((string)$_smarty_tpl->tpl_vars['msg']->value));?>
</div>
<?php }?>
<div class="main">
	<div class="post">
		<?php if ($_SESSION['bans_add'] == "yes") {?>
			<form method="post">
				<table> 
					<tr class="title">
						<td style="width:250px;" class="fat"><?php echo smarty_modifier_lang("_ADDBAN");?>
</th> 
						<td>&nbsp;</th>
					</tr>
					<tr class="info"> 
						<td class="b"><?php echo smarty_modifier_lang("_NICKNAME");?>
</td> 
						<td><input type="text" size="40" name="name" <?php if ($_smarty_tpl->tpl_vars['inputs']->value['name'] != '') {?>value="<?php echo $_smarty_tpl->tpl_vars['inputs']->value['name'];?>
"<?php }?>/></td> 
					</tr> 
					<tr class="info"> 
						<td class="b"><?php echo smarty_modifier_lang("_STEAMID");?>
</td> 
						<td><input type="text" size="40" name="steamid" /></td> 
					</tr> 
					<tr class="info"> 
						<td class="b"><?php echo smarty_modifier_lang("_IP");?>
</td> 
						<td><input type="text" size="40" name="ip" <?php if ($_smarty_tpl->tpl_vars['inputs']->value['ip'] != '') {?>value="<?php echo $_smarty_tpl->tpl_vars['inputs']->value['ip'];?>
"<?php }?>/></td>  
					</tr> 
					<tr class="info"> 
						<td class="b"><?php echo smarty_modifier_lang("_BANTYPE");?>
</td> 
						<td>
							<select name="ban_type"><?php echo smarty_function_html_options(array('output'=>$_smarty_tpl->tpl_vars['banby_output']->value,'values'=>$_smarty_tpl->tpl_vars['banby_values']->value,'selected'=>$_smarty_tpl->tpl_vars['inputs']->value['type']),$_smarty_tpl);?>
</select>
						</td> 
					</tr> 
					<tr class="info"> 
						<td class="b"><?php echo smarty_modifier_lang("_REASON");?>
</td> 
						<td>
							<select name="ban_reason"><?php echo smarty_function_html_options(array('output'=>$_smarty_tpl->tpl_vars['reasons']->value,'values'=>$_smarty_tpl->tpl_vars['reasons']->value,'selected'=>$_smarty_tpl->tpl_vars['inputs']->value['reason']),$_smarty_tpl);?>
</select>
								<?php echo smarty_modifier_lang("_OR");?>
 <br /><input type="checkbox" name="reasoncheck" <?php if ($_smarty_tpl->tpl_vars['inputs']->value['reason_custom'] == 1) {?>checked<?php }?>/>
								<?php echo smarty_modifier_lang("_REASON");?>
: <input type="text" size="30" name="user_reason" <?php if ($_smarty_tpl->tpl_vars['inputs']->value['reason_custom'] == 1) {?>value="<?php echo $_smarty_tpl->tpl_vars['inputs']->value['reason'];?>
"<?php }?>/>
						</td> 
					</tr> 
					<tr class="info"> 
						<td class="b"><?php echo smarty_modifier_lang("_BANLENGHT");?>
</td> 
						<td>
							<input type="text" size="8" name="ban_length" <?php if ($_smarty_tpl->tpl_vars['inputs']->value['length'] > 0) {?>value="<?php echo $_smarty_tpl->tpl_vars['inputs']->value['length'];?>
"<?php }?>/> <?php echo smarty_modifier_lang("_MINS");?>
 
								<?php echo smarty_modifier_lang("_OR");?>
 <br /><input type="checkbox" name="perm" <?php if ($_smarty_tpl->tpl_vars['inputs']->value['length'] == 0) {?>checked<?php }?>/> <?php echo smarty_modifier_lang("_PERMANENT");?>

						</td> 
					</tr> 
				</table>
				<div class="_right">
					<input type="submit" class="button" name="save" value="<?php echo smarty_modifier_lang("_ADD");?>
" />
				</div> 
			</form>
		<?php } else { ?>
			<?php echo smarty_modifier_lang("_NOACCESS");?>
 !!
		<?php }?>
	</div>
	<div class="clearer">&nbsp;</div>
</div><?php }
}
