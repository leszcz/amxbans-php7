<?php
/* Smarty version 3.1.30, created on 2018-09-02 03:11:31
  from "/home/nypd/public_html/forum/bans/dd2/templates/default/admin_wa.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5b8b8d23369d15_60612579',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ca430c795be75870e9f8324b115cf2a2ea7352ec' => 
    array (
      0 => '/home/nypd/public_html/forum/bans/dd2/templates/default/admin_wa.tpl',
      1 => 1532941654,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b8b8d23369d15_60612579 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/nypd/public_html/forum/bans/dd2/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_function_html_options')) require_once '/home/nypd/public_html/forum/bans/dd2/include/smarty/plugins/function.html_options.php';
if (!is_callable('smarty_modifier_date_format')) require_once '/home/nypd/public_html/forum/bans/dd2/include/smarty/plugins/modifier.date_format.php';
if ($_smarty_tpl->tpl_vars['msg']->value) {?>
  <div class="notice">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['msg']->value, 'msgs');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['msgs']->value) {
?>
      <?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msgs']->value);?>

    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

  </div>
<?php }?>
    <td id="main" valign="top" >
    <?php if ($_SESSION['amxadmins_view'] == "yes") {?>
      <span class="title"><?php echo smarty_modifier_lang("_WEBADMINSSETTINGS");?>
</span>
      <table>
        <tr>
          <td>
        <table>
          <tr class="title"><td colspan="5" class="fat"><?php echo smarty_modifier_lang("_WEBADMINS");?>
</td></tr>
          <tr class="title">
            <td><?php echo smarty_modifier_lang("_NAME");?>
</td>
            <td align="center" width="1%"><?php echo smarty_modifier_lang("_LEVEL");?>
</td>
            <td width="1%"><?php echo smarty_modifier_lang("_EMAIL");?>
<td align="center" width="1%"><?php echo smarty_modifier_lang("_LASTLOGIN");?>
</td>
            <td width="1%"><b>&nbsp;</b></td>
          </tr>
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['users']->value, 'user');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['user']->value) {
?>
            <form method="POST" name="<?php echo $_smarty_tpl->tpl_vars['user']->value['uid'];?>
">
              <input type="hidden" name="uid" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['uid'];?>
" />
              <input type="hidden" name="newpw" id="newpw<?php echo $_smarty_tpl->tpl_vars['user']->value['uid'];?>
" value="" />
              <tr class="list">
                <td align="center">
                  <?php if ($_SESSION['webadmins_edit'] == "yes") {?>
                    <input type="text" name="name" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['name'];?>
"/>
                  <?php } else { ?>
                    <input type="hidden" name="name" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['name'];?>
"/>
                    <b><?php echo $_smarty_tpl->tpl_vars['user']->value['name'];?>
</b>
                  <?php }?>
                </td>
                <td align="center" width="1%">
                  <?php if ($_SESSION['webadmins_edit'] == "yes") {?>
                    <?php echo smarty_function_html_options(array('name'=>'level','values'=>$_smarty_tpl->tpl_vars['levels']->value,'output'=>$_smarty_tpl->tpl_vars['levels']->value,'selected'=>$_smarty_tpl->tpl_vars['user']->value['level']),$_smarty_tpl);?>

                  <?php } else { ?>
                    <input type="hidden" name="level" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['level'];?>
"/>
                    <?php echo $_smarty_tpl->tpl_vars['user']->value['level'];?>

                  <?php }?>
                </td>
                <td align="center"><input type="text" size="40" name="email" value="<?php echo $_smarty_tpl->tpl_vars['user']->value['email'];?>
" <?php if (!($_SESSION['uname'] == $_smarty_tpl->tpl_vars['user']->value['name'] || $_SESSION['webadmins_edit'] == "yes")) {?>disabled<?php }?>/></td>
                <td align="center"><nobr>
                  <?php if ($_smarty_tpl->tpl_vars['user']->value['last_action']) {?>
                    <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['user']->value['last_action'],"%d.%m.%Y - %T");?>

                  <?php } else { ?>
                    <i><?php echo smarty_modifier_lang("_NEVER");?>
</i>
                  <?php }?>
                </nobr></td>
                <td align="center"><nobr>
                      <input type="submit" class="button" name="save" value="<?php echo smarty_modifier_lang("_SAVE");?>
" <?php if (!($_SESSION['uname'] == $_smarty_tpl->tpl_vars['user']->value['name'] || $_SESSION['webadmins_edit'] == "yes")) {?>disabled<?php }?> />
                      &nbsp;
                      <input type="submit" class="button" name="del" value="<?php echo smarty_modifier_lang("_DELETE");?>
" onclick="return confirm('<?php echo smarty_modifier_lang("_DELADMIN");?>
');" <?php if ($_SESSION['webadmins_edit'] !== "yes") {?>disabled<?php }?> />
                      &nbsp;
                      <input type="submit" class="button" name="setnewpw" value="<?php echo smarty_modifier_lang("_NEWPASSWORD");?>
" 
                        onclick="<?php if ($_SESSION['uname'] == $_smarty_tpl->tpl_vars['user']->value['name']) {?>alert('<?php echo smarty_modifier_lang("_YOURPASSWORD");?>
');<?php }?>return SetNewPassword('newpw<?php echo $_smarty_tpl->tpl_vars['user']->value['uid'];?>
','<?php echo smarty_modifier_lang("_ENTERPASSWORD");?>
','<?php echo smarty_modifier_lang("_PASSWORD2");?>
','<?php echo smarty_modifier_lang("_PASSWORDNOTMATCH");?>
');" 
                          <?php if (!($_SESSION['uname'] == $_smarty_tpl->tpl_vars['user']->value['name'] || $_SESSION['webadmins_edit'] == "yes")) {?>disabled<?php }?> />
                
                </nobr></td>
              </tr>
            </form>
          <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

        </table>
        <?php if ($_SESSION['webadmins_edit'] == "yes") {?>
          <form method="POST">
            <table border="1" width="50%">
              <tr class="title">
                <td colspan="4"><?php echo smarty_modifier_lang("_WEBADMINADD");?>
</td>
              </tr>
              <tr class="info"><td><?php echo smarty_modifier_lang("_NAME");?>
:</td><td align="left"><input type="text" name="name" value="<?php echo $_smarty_tpl->tpl_vars['input']->value['name'];?>
" /></td></tr>
              <tr class="info"><td><?php echo smarty_modifier_lang("_EMAIL");?>
:</td><td align="left"><input type="text" size="40" name="email" value="<?php echo $_smarty_tpl->tpl_vars['input']->value['email'];?>
" /></td></tr>
              <tr class="info"><td><?php echo smarty_modifier_lang("_PASSWORD");?>
:</td><td align="left"><input type="password" name="pw" /></td></tr>
              <tr class="info"><td><?php echo smarty_modifier_lang("_PASSWORD2");?>
:</td><td align="left"><input type="password" name="pw2" /></td></tr>
              <tr class="info">
                <td><?php echo smarty_modifier_lang("_LEVEL");?>
:</td>
                <td><?php echo smarty_function_html_options(array('name'=>'level','values'=>$_smarty_tpl->tpl_vars['levels']->value,'output'=>$_smarty_tpl->tpl_vars['levels']->value,'selected'=>$_smarty_tpl->tpl_vars['input']->value['level']),$_smarty_tpl);?>
</td>
                <td>
                  <input type="submit" class="button" name="new" value="<?php echo smarty_modifier_lang("_ADD");?>
" <?php if ($_SESSION['webadmins_edit'] !== "yes") {?>disabled<?php }?> />&nbsp;
                  <input type="reset" class="button" value="<?php echo smarty_modifier_lang("_CLEAR");?>
">
                </td>
              </tr>
            </table>
          </form>
        <?php }?>
      <?php } else { ?>
        <?php echo smarty_modifier_lang("_NOACCESS");?>

      <?php }?>
      </td></tr></table>
    </td>
  </tr>
</table><?php }
}
