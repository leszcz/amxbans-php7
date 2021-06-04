<?php
/* Smarty version 3.1.30, created on 2018-09-02 03:11:26
  from "/home/nypd/public_html/forum/bans/dd2/templates/default/layer_banedit_banlist.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5b8b8d1e0f42d3_82548774',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '240772e8438fb1046892388f43c3cd4d4c9bdb01' => 
    array (
      0 => '/home/nypd/public_html/forum/bans/dd2/templates/default/layer_banedit_banlist.tpl',
      1 => 1532941654,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b8b8d1e0f42d3_82548774 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/nypd/public_html/forum/bans/dd2/include/smarty/plugins/modifier.lang.php';
if ($_SESSION['bans_edit'] == "yes" || ($_SESSION['bans_edit'] == "own" && $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_list']->value['nickname'] || $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_list']->value['admin_nick'])) {?>
<td colspan="10">
  <div style="display:none;">
    <table>
      <tr class="title">
        <td><?php echo smarty_modifier_lang("_EDITBAN");?>
</td>
      </tr>
      <tr>
        <td colspan="2">
          <table width="100%">
            <form method="post" action="ban_list.php">
              <input type="hidden" name="site" value="<?php echo $_smarty_tpl->tpl_vars['site']->value;?>
" />
              <input type="hidden" name="bid" value="<?php echo $_smarty_tpl->tpl_vars['ban_list']->value['bid'];?>
" />
              <input type="hidden" name="details_x" value="1" />
              <input type="hidden" name="ban_length_old" value="<?php echo $_smarty_tpl->tpl_vars['ban_list']->value['ban_length'];?>
" />
              <input type="hidden" name="ban_created" value="<?php echo $_smarty_tpl->tpl_vars['ban_list']->value['ban_created'];?>
" />
              <input type="hidden" name="ban_type" value="<?php echo $_smarty_tpl->tpl_vars['ban_list']->value['ban_type'];?>
" />

              <tr class="info">
                <td class="b"><?php echo smarty_modifier_lang("_NICKNAME");?>
:</td>
                <td>
                  <input type="text" size="40" id="id0" name="player_nick" value="<?php echo $_smarty_tpl->tpl_vars['ban_list']->value['player_nick'];?>
" <?php if ($_smarty_tpl->tpl_vars['ban_list']->value['ban_length'] == -1) {?>disabled<?php }?> />
                </td>
              </tr>
              <tr class="info">
                <td class="b"><?php echo smarty_modifier_lang("_STEAMID");?>
:</td>
                <td>
                  <input type="text" size="40" id="id1" name="player_id" value="<?php echo $_smarty_tpl->tpl_vars['ban_list']->value['player_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['ban_list']->value['ban_length'] == -1) {?>disabled<?php }?> />
                </td>
              </tr>
              <tr class="info"><td class="b"><?php echo smarty_modifier_lang("_IP");?>
:</td>
                <td>
                  <?php if ($_SESSION['ip_view'] == "yes") {?>
                    <input type="text" size="40" id="id2" name="player_ip" value="<?php echo $_smarty_tpl->tpl_vars['ban_list']->value['player_ip'];?>
" <?php if ($_smarty_tpl->tpl_vars['ban_list']->value['ban_length'] == -1) {?>disabled<?php }?> /><?php } else { ?><i><?php echo smarty_modifier_lang("_HIDDEN");?>
</i>
                  <?php }?>
                </td>
              </tr>
              <tr class="info">                <td class="b"><?php echo smarty_modifier_lang("_REASON");?>
:</td>
                <td>
                  <input type="text" size="40" id="id4" name="ban_reason" value="<?php echo $_smarty_tpl->tpl_vars['ban_list']->value['ban_reason'];?>
" <?php if ($_smarty_tpl->tpl_vars['ban_list']->value['ban_length'] == -1) {?>disabled<?php }?>/>
                </td>
              </tr>
              <tr class="info">                <td class="b"><?php echo smarty_modifier_lang("_BANLENGHT");?>
:</td>
                <td>
                  <?php if ($_SESSION['bans_edit'] == "yes" || ($_SESSION['bans_edit'] == "own" && $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_list']->value['nickname'] || $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_list']->value['admin_nick'])) {?>
                    <input type="text" size="10" id="id5" name="ban_length" value="<?php if ($_smarty_tpl->tpl_vars['ban_list']->value['ban_length'] != -1) {
echo $_smarty_tpl->tpl_vars['ban_list']->value['ban_length'];
}?>"  <?php if ($_smarty_tpl->tpl_vars['ban_list']->value['ban_length'] == -1) {?>disabled<?php }?>/> <?php echo smarty_modifier_lang("_MINS");?>

                    <b><input type="checkbox" 
                      onclick="this.form.id0.disabled=this.checked;
                          this.form.id1.disabled=this.checked;
                          this.form.id2.disabled=this.checked;
                          this.form.id3.disabled=this.checked;
                          this.form.id4.disabled=this.checked;
                          this.form.id5.disabled=this.checked" name="unban"
                          /> <?php echo smarty_modifier_lang("_UNBANPLAYER");?>

                  <?php } else { ?>
                    <?php echo $_smarty_tpl->tpl_vars['ban_list']->value['ban_length'];?>

                  <?php }?> 
                </td>
              </tr>
              <tr class="info">                <td class="b"><?php echo smarty_modifier_lang("_EDITREASON");?>
:</td>
                <td>
                  <textarea name="edit_reason" id="edit_reason" cols="50" rows="3" wrap="soft"></textarea>
                </td>
              </tr>
            </table>
            <div class="_right"><input type="submit" class="button" name="edit_ban" onclick="return confirm('<?php echo smarty_modifier_lang("_SAVEEDIT");?>
');" value="<?php echo smarty_modifier_lang("_SAVE");?>
" /></div>
          </form>
        </td>
      </tr>
    </table>
  </div>
</td>
<?php }
}
}
