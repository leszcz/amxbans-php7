<?php
/* Smarty version 3.1.30, created on 2017-12-09 07:49:51
  from "/home/crooket/cs-nypd.pl/public_html/forum/bans/templates/default/layer_banedit.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a2b959fa874a3_13775357',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e5d9ef1c36fae1152fc111f6a72b6c4c4ab7df2c' => 
    array (
      0 => '/home/crooket/cs-nypd.pl/public_html/forum/bans/templates/default/layer_banedit.tpl',
      1 => 1512800717,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a2b959fa874a3_13775357 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_modifier_date_format')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_bbcode2html')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/modifier.bbcode2html.php';
if ($_SESSION['bans_edit'] == "yes" || ($_SESSION['bans_edit'] == "own" && $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_detail']->value['nickname'] || $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_detail']->value['admin_nick'])) {?>
  <td colspan="10">
    <div style="display:none;" align="center">
      <table class="details">
        <tr class="title">
          <td><?php echo smarty_modifier_lang("_EDITBAN");?>
</td>
        </tr>
        <tr>
          <td colspan="2">
            <table width="100%">
              <form method="post">
                <input type="hidden" name="site" value="<?php echo $_smarty_tpl->tpl_vars['site']->value;?>
" />
                <input type="hidden" name="bid" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['bid'];?>
" />
                <input type="hidden" name="details_x" value="1" />
                <input type="hidden" name="ban_length_old" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['ban_length'];?>
" />
                <input type="hidden" name="ban_created" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['ban_created'];?>
" />
                <input type="hidden" name="ban_type" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['ban_type'];?>
" />
  
                <tr class="info">
                  <td class="fat"><?php echo smarty_modifier_lang("_NICKNAME");?>
:</td>
                  <td>
                    <input type="text" size="40" id="id0" name="player_nick" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['player_nick'];?>
" <?php if ($_smarty_tpl->tpl_vars['ban_detail']->value['ban_length'] == -1) {?>disabled<?php }?> />
                  </td>
                </tr>
                <tr class="info">
                  <td class="fat"><?php echo smarty_modifier_lang("_STEAMID");?>
:</td>
                  <td>
                    <input type="text" size="40" id="id1" name="player_id" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['player_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['ban_detail']->value['ban_length'] == -1) {?>disabled<?php }?> />
                  </td>
                </tr>
                <tr class="info"><td class="fat"><?php echo smarty_modifier_lang("_IP");?>
:</td>
                  <td>
                    <?php if ($_SESSION['ip_view'] == "yes") {?>
                      <input type="text" size="40" id="id2" name="player_ip" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['player_ip'];?>
" <?php if ($_smarty_tpl->tpl_vars['ban_detail']->value['ban_length'] == -1) {?>disabled<?php }?> /><?php } else { ?><i><?php echo smarty_modifier_lang("_HIDDEN");?>
</i>
                    <?php }?>
                  </td>
                </tr>
                <tr class="info">
                  <td class="fat"><?php echo smarty_modifier_lang("_REASON");?>
:</td>
                  <td>
                    <input type="text" size="40" id="id4" name="ban_reason" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['ban_reason'];?>
" <?php if ($_smarty_tpl->tpl_vars['ban_detail']->value['ban_length'] == -1) {?>disabled<?php }?>/>
                  </td>
                </tr>
                <tr class="info">
                  <td class="fat"><?php echo smarty_modifier_lang("_BANLENGHT");?>
:</td>
                  <td>
                    <?php if ($_SESSION['bans_edit'] == "yes" || ($_SESSION['bans_edit'] == "own" && $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_detail']->value['nickname'] || $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_detail']->value['admin_nick'])) {?>
                      <input type="text" size="10" id="id5" name="ban_length" value="<?php if ($_smarty_tpl->tpl_vars['ban_detail']->value['ban_length'] != -1) {
echo $_smarty_tpl->tpl_vars['ban_detail']->value['ban_length'];
}?>"  <?php if ($_smarty_tpl->tpl_vars['ban_detail']->value['ban_length'] == -1) {?>disabled<?php }?>/> <?php echo smarty_modifier_lang("_MINS");?>

                      <b><input type="checkbox" 
                        onclick="this.form.id0.disabled=this.checked;
                            this.form.id1.disabled=this.checked;
                            this.form.id2.disabled=this.checked;
                            this.form.id3.disabled=this.checked;
                            this.form.id4.disabled=this.checked;
                            this.form.id5.disabled=this.checked" name="unban"
                            /> <?php echo smarty_modifier_lang("_UNBANPLAYER");?>

                    <?php } else { ?>
                      <?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['ban_length'];?>

                    <?php }?> 
                  </td>
                </tr>
                <tr class="info">
                  <td class="fat"><?php echo smarty_modifier_lang("_EDITREASON");?>
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
            <?php if ($_smarty_tpl->tpl_vars['ban_details_edits']->value) {?>
              <br />
              <table width="100%" cellspacing="0" border="1">
                <tr class="title">
                  <td colspan="3"><?php echo smarty_modifier_lang("_BANDETAILSEDITS");?>
</td>
                </tr>
                <tr class="info">
                  <td width="1%"><b><?php echo smarty_modifier_lang("_DATE");?>
</b></td>
                  <td width="1%"><b><?php echo smarty_modifier_lang("_ADMIN");?>
</b></td>
                  <td><b><?php echo smarty_modifier_lang("_EDITREASON");?>
</b></td>
                </tr>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ban_details_edits']->value, 'ban_details_edit');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ban_details_edit']->value) {
?>
                  <tr class="info">
                    <td nowrap><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ban_details_edit']->value['edit_time'],"%Y-%m-%d");?>
</td>
                    <td nowrap><?php echo $_smarty_tpl->tpl_vars['ban_details_edit']->value['admin_nick'];?>
</td>
                    <td><?php echo smarty_modifier_bbcode2html($_smarty_tpl->tpl_vars['ban_details_edit']->value['edit_reason']);?>
</td>
                  </tr>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

              </table>
            <?php }?>
          </td>
        </tr>
      </table>
    </div>
  </td>
<?php }
}
}
