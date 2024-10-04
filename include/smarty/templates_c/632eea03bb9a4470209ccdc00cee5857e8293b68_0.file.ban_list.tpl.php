<?php
/* Smarty version 3.1.30, created on 2024-10-04 19:50:51
  from "/home/bans/public_html/templates/default/ban_list.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6700471b622981_86622977',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '632eea03bb9a4470209ccdc00cee5857e8293b68' => 
    array (
      0 => '/home/bans/public_html/templates/default/ban_list.tpl',
      1 => 1707255394,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:layer_banedit_banlist.tpl' => 1,
  ),
),false)) {
function content_6700471b622981_86622977 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/bans/public_html/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_modifier_date_format')) require_once '/home/bans/public_html/include/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_date2word')) require_once '/home/bans/public_html/include/smarty/plugins/modifier.date2word.php';
?>
<div class="main">
  <div class="post">
  <span>
    <?php if (isset($_smarty_tpl->tpl_vars['check_id']->value)) {?>
      <div class='error'><a href="http://<?php echo $_SERVER['HTTP_HOST'];
echo $_SERVER['PHP_SELF'];?>
?bid=<?php echo $_smarty_tpl->tpl_vars['check_id']->value;?>
"><span style='color:white;font-weight:bold;text-shadow: #990000 0px 0px 3px;'><?php echo smarty_modifier_lang("_YOUAREBANNED");?>
</span></a></div>
    <?php } else { ?>
      <div class='success'><span style='color:white;font-weight:bold;text-shadow: #00991E 0px 0px 3px;'><?php echo smarty_modifier_lang("_IP");?>
 &mdash; <?php echo $_smarty_tpl->tpl_vars['your_ip']->value;?>
. <?php echo smarty_modifier_lang("_YOUNOTBANNED");?>
</span></div>
    <?php }?>
  </span>
  <table>
    <tr class="title">
      <td>&nbsp;</td>
      <td style="width:80px;"><?php echo smarty_modifier_lang("_DATE");?>
</td>
      <td><?php echo smarty_modifier_lang("_PLAYER");?>
</td>
      <td><?php echo smarty_modifier_lang("_ADMIN");?>
</td>
      <td><?php echo smarty_modifier_lang("_REASON");?>
</td>
      <td style="width:120px;"><?php echo smarty_modifier_lang("_LENGHT");?>
</td>
      <?php if ($_smarty_tpl->tpl_vars['ban_page']->value['show_comments'] == 1 && $_smarty_tpl->tpl_vars['vars']->value['use_comment'] == 1) {?>
        <td style="width:80px;"><?php echo smarty_modifier_lang("_BL_COMMENTS");?>
</td>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['ban_page']->value['show_demos'] == 1 && $_smarty_tpl->tpl_vars['vars']->value['use_demo'] == 1) {?>
        <td style="width:45px;"><?php echo smarty_modifier_lang("_BL_FILES");?>
</td>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['ban_page']->value['show_kicks'] == 1) {?>
        <td style="width:45px;"><?php echo smarty_modifier_lang("_BL_KICKS");?>
</td>
      <?php }?>
    </tr>
    <?php if ($_smarty_tpl->tpl_vars['error']->value) {?>
      <tr>
        <td class="_center" colspan="9"><?php echo smarty_modifier_lang("_NO_BANS");?>
</td>
      </tr>
    <?php } else { ?>
      <!-- Banlist -->
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ban_list']->value, 'ban_lists');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ban_lists']->value) {
?>
          <form name="details" method="POST">
            <tr onclick="NewToggleLayer('layer_<?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['bid'];?>
')" class="list">
              <td class="_center"><img alt="<?php if ($_smarty_tpl->tpl_vars['ban_lists']->value['mod'] == "html") {
echo smarty_modifier_lang("_WEB");
} else {
echo smarty_modifier_lang($_smarty_tpl->tpl_vars['ban_lists']->value['mod']);
}?>" title="<?php if ($_smarty_tpl->tpl_vars['ban_lists']->value['mod'] == "html") {
echo smarty_modifier_lang("_WEB");
} else {
echo smarty_modifier_lang($_smarty_tpl->tpl_vars['ban_lists']->value['mod']);
}?>" src="images/games/<?php if ($_smarty_tpl->tpl_vars['ban_lists']->value['mod'] == "html") {?>web.png<?php } else {
echo $_smarty_tpl->tpl_vars['ban_lists']->value['mod'];?>
.gif<?php }?>" /></td>
              <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ban_lists']->value['ban_created'],"%Y-%m-%d");?>
</td>
              <td><img alt="" src="images/flags/<?php if ($_smarty_tpl->tpl_vars['ban_lists']->value['cc']) {
echo mb_strtolower($_smarty_tpl->tpl_vars['ban_lists']->value['cc'], 'UTF-8');
} else { ?>clear<?php }?>.png" /> <?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['player_nick'];?>
</td>
              <td><?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['admin_nick'];?>
</td>
              <td><?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['ban_reason'];?>
</td>
              <td>
                <?php if ($_smarty_tpl->tpl_vars['ban_lists']->value['ban_length'] > 0) {?>
                  <?php echo smarty_modifier_date2word((60*$_smarty_tpl->tpl_vars['ban_lists']->value['ban_length']),true);?>
 
                <?php } else { ?>
                  <?php echo smarty_modifier_lang("_PERMANENT");?>

                <?php }?>
              </td>
              <?php if ($_smarty_tpl->tpl_vars['ban_page']->value['show_comments'] == 1 && $_smarty_tpl->tpl_vars['vars']->value['use_comment'] == 1) {?><td class="_center"><?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['comment_count'];?>
</td><?php }?>
              <?php if ($_smarty_tpl->tpl_vars['ban_page']->value['show_demos'] == 1 && $_smarty_tpl->tpl_vars['vars']->value['use_demo'] == 1) {?><td class="_center"><?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['demo_count'];?>
</td><?php }?>
              <?php if ($_smarty_tpl->tpl_vars['ban_page']->value['show_kicks'] == 1) {?><td class="_center"><?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['kick_count'];?>
</td><?php }?>
            </tr>
            <tr id="layer_<?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['bid'];?>
" style="display:none;">
              <td colspan="9">
                <div style="display:none;" align="center">
                  <input type="hidden" name="bid" value="<?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['bid'];?>
" />
                  <table class="details">
                    <tr class="title">
                      <td style="width:200px;" class="fat"><?php echo smarty_modifier_lang("_BANDETAILS");?>
</td>
                      <td class="_right">
                        <a href="ban_list.php?bid=<?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['bid'];?>
"><img src="images/page.png" border="0" title="<?php echo smarty_modifier_lang("_DETAILS");?>
" /></a>
                        <?php if ($_SESSION['bans_edit'] == "yes" || ($_SESSION['bans_edit'] == "own" && $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_lists']->value['nickname'] || $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_lists']->value['admin_nick'])) {?>
                         <!-- <img src="images/page_edit.png" border="0" onclick="NewToggleLayer('banedit_<?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['bid'];?>
')" title="<?php echo smarty_modifier_lang("_TIP_EDIT");?>
" style="cursor:pointer;border:0;" />-->
                        <?php }?>
                        <form method="POST" style="display:inline;">
                          <input class="img_input" name="del_ban" type="image" src="images/page_delete.png" onclick="return confirm('<?php echo smarty_modifier_lang("_DELBAN");
echo smarty_modifier_lang("_DATALOSS");?>
');" border="0" title="<?php echo smarty_modifier_lang("_TIP_DEL");?>
" />
                          <input type="hidden" name="site" value="<?php echo $_smarty_tpl->tpl_vars['site']->value;?>
" />
                          <input type="hidden" name="bid" value="<?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['bid'];?>
" />
                          <input type="hidden" name="details_x" value="1" />
                        </form>

                      </td>
                    </tr>
                    <tr class="info">
                      <td class="b"><?php echo smarty_modifier_lang("_NICKNAME");?>
</td>
                      <td><?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['player_nick'];?>
</td>
                    </tr>
                    <?php if (!in_array($_smarty_tpl->tpl_vars['ban_lists']->value['player_id'],array('STEAM_ID_LAN','VALVE_ID_LAN','','0'))) {?>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_STEAMID");?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['player_id'];?>
</td>
                      </tr>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_STEAMCOMID");?>
</td>
                        <td>
                          <a target="_blank" href="http://steamcommunity.com/profiles/<?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['player_comid'];?>
"><?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['player_comid'];?>
</a>
                        </td>
                      </tr>
                    <?php }?>
                    <tr class="info">
                      <td class="b"><?php echo smarty_modifier_lang("_IP");?>
</td>
                      <td>
                        <?php if ($_SESSION['ip_view'] == "yes") {?>
                          <?php if ($_smarty_tpl->tpl_vars['ban_lists']->value['player_ip']) {?>
                            <?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['player_ip'];?>

                          <?php } else { ?>
                            <i><?php echo smarty_modifier_lang("_NOTAVAILABLE");?>
</i>
                          <?php }?>
                        <?php } else { ?>
                          <span style='font-style:italic;font-weight:bold'><?php echo smarty_modifier_lang("_HIDDEN");?>
</span>
                        <?php }?>
                      </td>
                    </tr>
                    <tr class="info">
                      <td class="b"><?php echo smarty_modifier_lang("_BANTYPE");?>
</td>
                      <td>
                        <?php if ($_smarty_tpl->tpl_vars['ban_lists']->value['ban_type'] == "S") {?>
                          <?php echo smarty_modifier_lang("_STEAMID");?>

                        <?php } elseif ($_smarty_tpl->tpl_vars['ban_lists']->value['ban_type'] == "SI") {?>
                          <?php echo smarty_modifier_lang("_STEAMID&IP");?>

                        <?php } else { ?>
                            <?php echo smarty_modifier_lang("_NOTAVAILABLE");?>

                          <?php }?>
                        </td>
                      </tr>
                    <tr class="info">
                      <td class="b"><?php echo smarty_modifier_lang("_REASON");?>
</td>
                      <td><?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['ban_reason'];?>
</td>
                    </tr>
                    <tr class="info">
                      <td class="b"><?php echo smarty_modifier_lang("_INVOKED");?>
</td>
                      <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ban_lists']->value['ban_created'],"%d.%m.%Y - %T");?>
</td>
                    </tr>
                    <tr class="info">
                      <td class="b"><?php echo smarty_modifier_lang("_EXPIRES");?>
</td>
                      <td>
                        <?php if ($_smarty_tpl->tpl_vars['ban_lists']->value['ban_length'] == 0) {?>
                          <span style="font-weight:bold;color:red"><?php echo smarty_modifier_lang("_NOTAPPLICABLE");?>
</span>
                        <?php } else { ?>
                          <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ban_lists']->value['ban_end'],"%d.%m.%Y - %T");?>

                          <?php if ($_smarty_tpl->tpl_vars['ban_lists']->value['ban_end'] < time()) {?>
                            (<?php echo smarty_modifier_lang("_ALREADYEXP");?>
)
                          <?php } else { ?>
                            <i>(<?php echo smarty_modifier_date2word(($_smarty_tpl->tpl_vars['ban_lists']->value['ban_end']-time()),true);?>
 <?php echo smarty_modifier_lang("_REMAINING");?>
)</i>
                          <?php }?>
                        <?php }?>
                      </td>
                    </tr>
                    <tr class="info">
                      <td class="b"><?php echo smarty_modifier_lang("_BANBY");?>
</td>
                      <td><?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['admin_nick'];
if ($_smarty_tpl->tpl_vars['ban_lists']->value['nickname']) {?> <span style="font-size: 12px">(<?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['nickname'];?>
)</span><?php }?></td>
                    </tr>
                    <tr class="info">
                      <td class="b"><?php echo smarty_modifier_lang("_BANON");?>
</td>
                      <td><?php if ($_smarty_tpl->tpl_vars['ban_lists']->value['server_name'] == "website") {
echo smarty_modifier_lang("_WEB");
} else {
echo $_smarty_tpl->tpl_vars['ban_lists']->value['server_name'];
}?></td>
                    </tr>
                    <tr class="info" style="border: 0">
                      <td class="b"><?php echo smarty_modifier_lang("_TOTALEXPBANS");?>
</td>
                      <td><?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['bancount'];?>
</td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
            <?php if ($_SESSION['bans_edit'] == "yes" || ($_SESSION['bans_edit'] == "own" && $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_lists']->value['nickname'] || $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_lists']->value['admin_nick'])) {?>
              <tr id="banedit_<?php echo $_smarty_tpl->tpl_vars['ban_lists']->value['bid'];?>
" style="display:none;">
                <?php $_smarty_tpl->_subTemplateRender("file:layer_banedit_banlist.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

              </tr>
            <?php }?>
          </form>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

      <?php }?>
      <!-- Banlist end -->
    </table>

    <div class="clearer">&nbsp;</div>

  </div>
  <div class="paginator" id="paginator"></div>
  <?php echo '<script'; ?>
 type="text/javascript">
    paginator = new Paginator(
      "paginator",
      <?php echo $_smarty_tpl->tpl_vars['ban_page']->value['max_page'];?>
,
      20,
      <?php echo $_smarty_tpl->tpl_vars['ban_page']->value['current'];?>
,
      "?site="
    );
  <?php echo '</script'; ?>
>
</div><?php }
}
