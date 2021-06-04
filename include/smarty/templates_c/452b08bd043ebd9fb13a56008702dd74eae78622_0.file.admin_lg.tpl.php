<?php
/* Smarty version 3.1.30, created on 2018-08-31 09:56:48
  from "/home/nypd/public_html/forum/bans/dd2/templates/default/admin_lg.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5b8949205808c2_64852844',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '452b08bd043ebd9fb13a56008702dd74eae78622' => 
    array (
      0 => '/home/nypd/public_html/forum/bans/dd2/templates/default/admin_lg.tpl',
      1 => 1532941654,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b8949205808c2_64852844 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/nypd/public_html/forum/bans/dd2/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_function_html_options')) require_once '/home/nypd/public_html/forum/bans/dd2/include/smarty/plugins/function.html_options.php';
if (!is_callable('smarty_modifier_date_format')) require_once '/home/nypd/public_html/forum/bans/dd2/include/smarty/plugins/modifier.date_format.php';
if ($_smarty_tpl->tpl_vars['msg']->value) {?>
  <div class="success"><?php echo smarty_modifier_lang(((string)$_smarty_tpl->tpl_vars['msg']->value));?>
</div>
<?php }?>
    <td id="main" valign="top">
    <?php if ($_SESSION['amxadmins_view'] == "yes") {?>
      <span class="title"><?php echo smarty_modifier_lang("_LOGS");?>
</span>
      <table>
        <tr>
          <td>
            <tr>
              <td>
          <table>
            <tr class="title">
              <td colspan="3" class="fat"><?php echo smarty_modifier_lang("_FILTER");?>
</td>
            </tr>
            <form method="POST" name="sort">
              <tr class="info" colspan="3"><td><?php echo smarty_modifier_lang("_USER");?>
</td><td><?php echo smarty_function_html_options(array('name'=>'username','options'=>$_smarty_tpl->tpl_vars['usernames']->value,'selected'=>$_smarty_tpl->tpl_vars['username_checked']->value),$_smarty_tpl);?>
</td></tr>
              <tr class="info" colspan="3">
                  <td><?php echo smarty_modifier_lang("_ACTION");?>
</td><td><?php echo smarty_function_html_options(array('name'=>'action','options'=>$_smarty_tpl->tpl_vars['actions']->value,'selected'=>$_smarty_tpl->tpl_vars['action_checked']->value),$_smarty_tpl);?>
</td>
                  <td colspan="3"><input type="submit" class="button" name="sort" value="<?php echo smarty_modifier_lang("_GO");?>
" /></td>
              </tr>
            </form>
          </table><br />
          <table>
            <tr class="title">
              <td colspan="3" class="fat"><?php echo smarty_modifier_lang("_DELETE");?>
</td>
            </tr>
            <form method="POST" name="clear">
              <tr class="info"><td><?php echo smarty_modifier_lang("_ALL");?>
</td><td width="1%"><input type="submit" class="button" name="delall" onclick="return confirm('<?php echo smarty_modifier_lang("_DELLOGSALL");
echo smarty_modifier_lang("_DATALOSS");?>
');" value="<?php echo smarty_modifier_lang("_DELETE");?>
" /></td></tr>
              <tr class="info"><td><?php echo smarty_modifier_lang("_OLDERTHEN");?>
 <input type="text" size="3" name="days" > <?php echo smarty_modifier_lang("_DAYS");?>
</td><td><input size="1" type="submit" class="button" name="delolder" onclick="return confirm('<?php echo smarty_modifier_lang("_DELLOGS");
echo smarty_modifier_lang("_DATALOSS");?>
');" value="<?php echo smarty_modifier_lang("_DELETE");?>
" /></td></tr>  
            </form>
          </table>
          <br />
          <table border="1" width="100%">
            <tr class="title">
              <td colspan="5" class="fat"><?php echo smarty_modifier_lang("_ACTIONLOGS");?>
</td>
            </tr>
            <tr class="title">
              <td width="1%"><nobr><?php echo smarty_modifier_lang("_INVOKED");?>
</nobr></td>
              <td width="1%" align="center"><?php echo smarty_modifier_lang("_USER");?>
</td>
              <td width="1%" align="center"><?php echo smarty_modifier_lang("_IP");?>
</td>
              <td width="1%" align="center"><nobr><?php echo smarty_modifier_lang("_ACTION");?>
</nobr></td>
              <td><?php echo smarty_modifier_lang("_REMARKS");?>
</td>
            </tr>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['logs']->value, 'log');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['log']->value) {
?>
              <tr class="list">
                <td width="1%"><nobr><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['log']->value['timestamp'],"%d.%m.%Y - %T");?>
</nobr></td>
                <td width="1%" align="center"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['log']->value['username'], ENT_QUOTES, 'UTF-8', true);?>
</td>
                <td width="1%" align="center"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['log']->value['ip'], ENT_QUOTES, 'UTF-8', true);?>
</td>
                <td width="1%" align="center"><nobr><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['log']->value['action'], ENT_QUOTES, 'UTF-8', true);?>
</nobr></td>
                <td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['log']->value['remarks'], ENT_QUOTES, 'UTF-8', true);?>
</td>
              </tr>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

          </table>
          <br />
        

        </td>
        </tr>
      </table>
    <?php } else { ?>
      <?php echo smarty_modifier_lang("_NOACCESS");?>

    <?php }?>
    </td>
  </tr>
</table><?php }
}
