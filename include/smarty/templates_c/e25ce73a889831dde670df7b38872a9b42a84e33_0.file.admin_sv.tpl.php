<?php
/* Smarty version 3.1.30, created on 2024-10-07 07:08:21
  from "/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/admin_sv.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_670388e5c0e1a1_70574514',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e25ce73a889831dde670df7b38872a9b42a84e33' => 
    array (
      0 => '/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/admin_sv.tpl',
      1 => 1707251794,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_670388e5c0e1a1_70574514 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_modifier_date_format')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_html_options')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/function.html_options.php';
if ($_smarty_tpl->tpl_vars['msg']->value) {?>
  <div class="notice">
    <?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msg']->value);?>

    <?php if ($_smarty_tpl->tpl_vars['smsg']->value) {?>
      <br /><br />
        <div class="rcon_box">
          <pre><?php echo $_smarty_tpl->tpl_vars['smsg']->value;?>
</pre>
          <div class="clearer">&nbsp;</div>
        </div>
    <?php } else { ?>
      <center><div class="admin_msg"><?php echo smarty_modifier_lang("_NOACCESS");?>
</div></center>
    <?php }?>
  </div>
<?php }?>
    <td id="main" valign="top" class="admin_list">
      <?php if ($_SESSION['servers_edit'] == "yes") {?>
        <span class="title"><?php echo smarty_modifier_lang("_SERVERSETTINGS");?>
</span>
        <table>
          <tr>
            <td>
              <table>
                <tr class="title">
                  <td width="1%"><?php echo smarty_modifier_lang("_MOD");?>
</td>
                  <td width="1%"><?php echo smarty_modifier_lang("_IP");?>
</td>
                  <td><?php echo smarty_modifier_lang("_HOSTNAME");?>
</td>
                  <td width="1%" align="center"><?php echo smarty_modifier_lang("_LASTSEEN");?>
</td>
                </tr>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['servers']->value, 'server');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['server']->value) {
?>
                  <tr class="list" style="cursor:pointer;"onClick="NewToggleLayer('layer_<?php echo $_smarty_tpl->tpl_vars['server']->value['sid'];?>
');">
                    <td align="center"><img src="images/mods/<?php echo $_smarty_tpl->tpl_vars['server']->value['gametype'];?>
.gif" /></td>
                    <td align="center"><?php echo $_smarty_tpl->tpl_vars['server']->value['address'];?>
</td>
                    <td align="center"><?php echo $_smarty_tpl->tpl_vars['server']->value['hostname'];?>
</td>
                    <td align="center"><nobr><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['server']->value['timestamp'],"%d. %b %Y - %T");?>
</nobr></td>
                  </tr>
                  <tr id="layer_<?php echo $_smarty_tpl->tpl_vars['server']->value['sid'];?>
" <?php if ($_smarty_tpl->tpl_vars['server']->value['sid'] != $_smarty_tpl->tpl_vars['server_activ']->value) {?>style="display: none"<?php }?>>
                    <td colspan="5">
                      <div style="display:none" align="center">
                      <fieldset>
                        <legend><?php echo smarty_modifier_lang("_SERVERSETTINGS");?>
 <?php echo $_smarty_tpl->tpl_vars['server']->value['hostname'];?>
</legend>
                        <table class="details">
                          <form name="rcon_<?php echo $_smarty_tpl->tpl_vars['server']->value['sid'];?>
" method="POST">
                            <tr class="title">
                              <td colspan="5"><?php echo smarty_modifier_lang("_SERVERSETTINGS");?>
</td>
                            </tr>
                            <tr class="info">
                              <td><?php echo smarty_modifier_lang("_RCONPW");?>
:</td>
                              <td width="60%">
                                <?php if ($_SESSION['servers_edit'] == "yes") {?>
                                  <input type="password" name="rcon" value="<?php echo $_smarty_tpl->tpl_vars['server']->value['rcon'];?>
" />
                                <?php } else { ?>
                                  <i><?php echo smarty_modifier_lang("_HIDDEN");?>
</i>
                                <?php }?>
                              </td>
                              <td>&nbsp;</td>
                              <td rowspan="5" width="1%" valign="bottom">
                                <input type="hidden" name="sid" value="<?php echo $_smarty_tpl->tpl_vars['server']->value['sid'];?>
" />
                                <input type="hidden" name="sidname" value="<?php echo $_smarty_tpl->tpl_vars['server']->value['hostname'];?>
" />
                                <input type="submit" class="button" name="save" value="<?php echo smarty_modifier_lang("_SAVE");?>
" <?php if ($_SESSION['servers_edit'] !== "yes") {?>disabled<?php }?> />
                                <input type="submit" class="button" name="del" value="<?php echo smarty_modifier_lang("_DEL");?>
" onclick="return confirm('<?php echo smarty_modifier_lang("_DELSERVER");
echo smarty_modifier_lang("_DATALOSS");?>
');" <?php if ($_SESSION['servers_edit'] !== "yes") {?>disabled<?php }?> />
                              </td>
                            </tr>
                            <tr class="info">
                              <td><?php echo smarty_modifier_lang("_MOTDURL");?>
:</td>
                              <td><input type="text" size="70" name="amxban_motd" id="<?php echo $_smarty_tpl->tpl_vars['server']->value['sid'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['server']->value['amxban_motd'];?>
" /></td>
                              <td><input type="button" class="button" value="<?php echo smarty_modifier_lang("_AUTO");?>
" onclick="document.getElementById('<?php echo $_smarty_tpl->tpl_vars['server']->value['sid'];?>
').value='<?php echo $_smarty_tpl->tpl_vars['motd_url']->value;?>
';" /></td>
                            </tr>
                            <tr class="info">
                              <td><?php echo smarty_modifier_lang("_MOTDDELAY");?>
:</td>
                              <td><?php echo smarty_function_html_options(array('name'=>'motd_delay','values'=>$_smarty_tpl->tpl_vars['delay_choose']->value,'output'=>$_smarty_tpl->tpl_vars['delay_choose']->value,'selected'=>$_smarty_tpl->tpl_vars['server']->value['motd_delay']),$_smarty_tpl);?>
 <?php echo smarty_modifier_lang("_SECS");?>
</td>
                            </tr>
        <!--                    <tr class="info">
                              <td><?php echo smarty_modifier_lang("_SERVERMENU");?>
</td>
                              <td><?php echo smarty_function_html_options(array('name'=>'amxban_menu','values'=>$_smarty_tpl->tpl_vars['menu_choose']->value,'output'=>$_smarty_tpl->tpl_vars['menu_choose']->value,'selected'=>$_smarty_tpl->tpl_vars['server']->value['amxban_menu']),$_smarty_tpl);?>
</td>
                            </tr>
        -->                      <tr class="info">
                              <td><?php echo smarty_modifier_lang("_REASONSSET");?>
:</td>
                              <td><?php echo smarty_function_html_options(array('name'=>'reasons','values'=>$_smarty_tpl->tpl_vars['reasons_values']->value,'output'=>$_smarty_tpl->tpl_vars['reasons_choose']->value,'selected'=>$_smarty_tpl->tpl_vars['server']->value['reasons']),$_smarty_tpl);?>
</td>
                            </tr>
                            <tr class="info">
                              <td><?php echo smarty_modifier_lang("_TIMEZONEFIXX");?>
:</td>
                              <td><?php echo smarty_function_html_options(array('name'=>'timezone_fixx','values'=>$_smarty_tpl->tpl_vars['timezone_values']->value,'output'=>$_smarty_tpl->tpl_vars['timezone_output']->value,'selected'=>$_smarty_tpl->tpl_vars['server']->value['timezone_fixx']),$_smarty_tpl);?>
 <?php echo smarty_modifier_lang("_HOURS");?>
</td>
                            </tr>
                            <?php if ($_smarty_tpl->tpl_vars['server']->value['rcon']) {?>
                              <tr class="info"><td colspan="4">&nbsp;</td></tr>
                              <tr class="title">
                                <td colspan="5"><?php echo smarty_modifier_lang("_SERVERRCON");?>
</td>
                              </tr>
                              <?php if ($_SESSION['servers_edit'] == "yes") {?>
                              <tr class="info">
                                <td valign="top"><?php echo smarty_modifier_lang("_RCON_PREDEFINED");?>
:</td>
                                <td>
        <!--                        <select name="command" size="3">
                                    <?php
$__section_rcon_cmds_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']) ? $_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds'] : false;
$__section_rcon_cmds_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['rcon_cmds']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_rcon_cmds_0_total = $__section_rcon_cmds_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds'] = new Smarty_Variable(array());
if ($__section_rcon_cmds_0_total != 0) {
for ($__section_rcon_cmds_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index'] = 0; $__section_rcon_cmds_0_iteration <= $__section_rcon_cmds_0_total; $__section_rcon_cmds_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index']++){
?>
                                      <option value="<?php echo $_smarty_tpl->tpl_vars['rcon_cmds']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index'] : null)];?>
" <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index'] : null) == 0) {?>selected<?php }?>><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['rcon_cmdkeys']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index'] : null)]);?>
</option>
                                    <?php
}
}
if ($__section_rcon_cmds_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds'] = $__section_rcon_cmds_0_saved;
}
?>
                                  </select>
        -->
                                  <select name="command" size="1">
                                    <?php
$__section_rcon_cmds_1_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']) ? $_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds'] : false;
$__section_rcon_cmds_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['rcon_cmds']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_rcon_cmds_1_total = $__section_rcon_cmds_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds'] = new Smarty_Variable(array());
if ($__section_rcon_cmds_1_total != 0) {
for ($__section_rcon_cmds_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index'] = 0; $__section_rcon_cmds_1_iteration <= $__section_rcon_cmds_1_total; $__section_rcon_cmds_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index']++){
?>
                                      <option value="<?php echo $_smarty_tpl->tpl_vars['rcon_cmds']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index'] : null)];?>
" <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index'] : null) == 0) {?>selected<?php }?>><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['rcon_cmdkeys']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds']->value['index'] : null)]);?>
</option>
                                    <?php
}
}
if ($__section_rcon_cmds_1_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_rcon_cmds'] = $__section_rcon_cmds_1_saved;
}
?>
                                  </select>
                                </td>
                                <td>
                                  <input type="submit" class="button" name="rconcommand" value="<?php echo smarty_modifier_lang("_RCON_SEND");?>
" />
                                </td>
                                <td></td>
                              </tr>
                              <tr class="info">
                                <td><?php echo smarty_modifier_lang("_RCON_USERDEFINED");?>
:</td>
                                <td><input type="test" size="70" name="rconuser" onkeyup="document.rcon_<?php echo $_smarty_tpl->tpl_vars['server']->value['sid'];?>
.rconuserstart_<?php echo $_smarty_tpl->tpl_vars['server']->value['sid'];?>
.disabled=(this.value=='');" /></td>
                                <td><input type="submit" class="button" name="rconuserstart_<?php echo $_smarty_tpl->tpl_vars['server']->value['sid'];?>
" value="<?php echo smarty_modifier_lang("_RCON_SEND");?>
" disabled /></td>
                              </tr>
                              <?php } else { ?>
                                <tr class="info"><td class="admin_msg"><?php echo smarty_modifier_lang("_NOACCESS");?>
 !!</td></tr>
                              <?php }?>  
                            <?php }?>
                          </form>
                        </table>
                      </fieldset></div>
                    </td>
                  </tr>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

              </table>
            </td>
          </tr>
        </table>
        <div class="clearer">&nbsp;</div>
      <?php }?>
    </td>
  </tr>
</table>
</div><?php }
}
