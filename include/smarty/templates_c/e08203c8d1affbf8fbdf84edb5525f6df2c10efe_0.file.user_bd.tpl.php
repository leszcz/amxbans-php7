<?php
/* Smarty version 3.1.30, created on 2017-12-08 12:31:12
  from "/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/templates/default/user_bd.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a2a7800996c17_49347189',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e08203c8d1affbf8fbdf84edb5525f6df2c10efe' => 
    array (
      0 => '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/templates/default/user_bd.tpl',
      1 => 1512732671,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:layer_banedit.tpl' => 1,
    'file:layer_banhistory.tpl' => 1,
    'file:layer_demedit.tpl' => 1,
    'file:layer_demadd.tpl' => 1,
    'file:layer_comadd.tpl' => 1,
    'file:layer_comedit.tpl' => 1,
  ),
),false)) {
function content_5a2a7800996c17_49347189 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_modifier_date_format')) require_once '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/include/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_date2word')) require_once '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/include/smarty/plugins/modifier.date2word.php';
if (!is_callable('smarty_modifier_file_size')) require_once '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/include/smarty/plugins/modifier.file_size.php';
if (!is_callable('smarty_modifier_bbcode2html')) require_once '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/include/smarty/plugins/modifier.bbcode2html.php';
?>
<div class="main">
  <div class="post">
    <table>
      <tr class="title">
        <td style="width:200px;" class="fat"><?php echo smarty_modifier_lang("_BANDETAILS");?>
</td> 
        <td class="_right">
            <form method="POST" style="display:inline;">
            <?php if ($_SESSION['bans_edit'] == "yes" || ($_SESSION['bans_edit'] == "own" && $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_detail']->value['nickname'] || $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_detail']->value['admin_nick'])) {?>
              <img src="images/page_edit.png" border="0" onclick="NewToggleLayer('banedit_<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['bid'];?>
')" title="<?php echo smarty_modifier_lang("_TIP_EDIT");?>
" style="cursor:pointer;border:0;" />
            <?php }?>
            <?php if ($_SESSION['bans_delete'] == "yes") {?>
              <input name="del_ban" type="image" src="images/page_delete.png" onclick="return confirm('<?php echo smarty_modifier_lang("_DELBAN");
echo smarty_modifier_lang("_DATALOSS");?>
');" border="0" title="<?php echo smarty_modifier_lang("_TIP_DEL");?>
" />
              <input type="hidden" name="site" value="<?php echo $_smarty_tpl->tpl_vars['site']->value;?>
" />
              <input type="hidden" name="bid" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['bid'];?>
" />
              <input type="hidden" name="details_x" value="1" />
            <?php }?>
            </form>
        </td> 
      </tr>
      <tr class="info">
        <td class="b"><?php echo smarty_modifier_lang("_NICKNAME");?>
</td><td><?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['player_nick'];?>
</td>
      </tr>
      <?php if (!in_array($_smarty_tpl->tpl_vars['ban_detail']->value['player_id'],array('STEAM_ID_LAN','VALVE_ID_LAN',''))) {?>
        <tr class="info">
          <td class="b"><?php echo smarty_modifier_lang("_STEAMID");?>
</td>
          <td><?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['player_id'];?>
</td>
        </tr>
        <tr class="info">
          <td class="b"><?php echo smarty_modifier_lang("_STEAMCOMID");?>
</td>
          <td><a target="_blank" href="http://steamcommunity.com/profiles/<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['player_comid'];?>
"><?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['player_comid'];?>
</a></td>
        </tr>
      <?php }?>
      <tr class="info">
        <td class="b"><?php echo smarty_modifier_lang("_IP");?>
</td>
        <td>
          <?php if ($_SESSION['ip_view'] == "yes") {?>
            <?php if ($_smarty_tpl->tpl_vars['ban_detail']->value['player_ip']) {?>
              <?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['player_ip'];?>

            <?php } else { ?>
              <?php echo smarty_modifier_lang("_NOTAVAILABLE");?>

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
          <?php if ($_smarty_tpl->tpl_vars['ban_detail']->value['ban_type'] == "S") {?>
            <?php echo smarty_modifier_lang("_STEAMID");?>

          <?php } elseif ($_smarty_tpl->tpl_vars['ban_detail']->value['ban_type'] == "SI") {?>
            <?php echo smarty_modifier_lang("_STEAMID&IP");?>

          <?php } else { ?>
            <?php echo smarty_modifier_lang("_NOTAVAILABLE");?>

          <?php }?>
        </td>
      </tr>
      <tr class="info">
        <td class="b"><?php echo smarty_modifier_lang("_REASON");?>
</td><td><?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['ban_reason'];?>
</td>
      </tr>
      <tr class="info">
        <td class="b"><?php echo smarty_modifier_lang("_INVOKED");?>
</td><td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ban_detail']->value['ban_created'],"%d.%m.%Y - %T");?>
</td>
      </tr>
      <tr class="info">
        <td class="b"><?php echo smarty_modifier_lang("_BANLENGHT");?>
</td>
        <td>
          <?php if ($_smarty_tpl->tpl_vars['ban_detail']->value['ban_length'] == 0) {?>
            <?php echo smarty_modifier_lang("_PERMANENT");?>

          <?php } elseif ($_smarty_tpl->tpl_vars['ban_detail']->value['ban_length'] == -1) {?>
            <?php echo smarty_modifier_lang("_UNBANNED");?>

          <?php } else { ?>
            <?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['ban_length']*smarty_modifier_date2word(60,true);?>
 <span style="font-size: 12px">(<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['ban_length'];?>
 <?php echo smarty_modifier_lang("_MINS");?>
)</span>
          <?php }?>
        </td>
      </tr>
      <tr class="info">
        <td class="b"><?php echo smarty_modifier_lang("_EXPIRES");?>
</td>
        <td>
          <?php if ($_smarty_tpl->tpl_vars['ban_detail']->value['ban_length'] <= 0) {?>
            <i><?php echo smarty_modifier_lang("_NOTAPPLICABLE");?>
</i>
          <?php } else { ?>
            <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ban_detail']->value['ban_end'],"%d.%m.%Y - %T");?>

            <?php if ($_smarty_tpl->tpl_vars['ban_detail']->value['ban_end'] < time()) {?>
              <span style="font-size: 12px">(<?php echo smarty_modifier_lang("_ALREADYEXP");?>
)</span>
            <?php } else { ?>
              <span style="font-size: 12px">(<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['ban_end']-smarty_modifier_date2word(time());?>
 <?php echo smarty_modifier_lang("_REMAINING");?>
)</span>
            <?php }?>
          <?php }?>
        </td>
      </tr>
      <tr class="info">
        <td class="b"><?php echo smarty_modifier_lang("_BANBY");?>
</td><td><?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['admin_nick'];
if ($_smarty_tpl->tpl_vars['ban_detail']->value['nickname']) {?> <span style="font-size: 12px">(<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['nickname'];?>
)</span><?php }?></td>
      </tr>
      <tr class="info">
        <td class="b"><?php echo smarty_modifier_lang("_ADMINID");?>
</td>
        <td>
          <?php if ($_SESSION['ip_view'] == "yes") {?>
            <?php if ($_smarty_tpl->tpl_vars['ban_detail']->value['admin_id']) {?>
              <?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['admin_id'];?>

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
        <td class="b"><?php echo smarty_modifier_lang("_BANON");?>
</td><td><?php if ($_smarty_tpl->tpl_vars['ban_detail']->value['server_name'] == "website") {
echo smarty_modifier_lang("_WEB");
} else {
echo $_smarty_tpl->tpl_vars['ban_detail']->value['server_name'];
}?></td>
      </tr>
      <?php if ($_SESSION['bans_edit'] == "yes" || ($_SESSION['bans_edit'] == "own" && $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_detail']->value['nickname'] || $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_detail']->value['admin_nick'])) {?>
        <tr id="banedit_<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['bid'];?>
" style="display:none;">
          <?php $_smarty_tpl->_subTemplateRender("file:layer_banedit.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

        </tr>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['banedit_error']->value) {?><br /><div class="_center"><span class="error"><?php echo smarty_modifier_lang("_ERROR");?>
: <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['banedit_error']->value, 'banedit_errors');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['banedit_errors']->value) {
echo smarty_modifier_lang($_smarty_tpl->tpl_vars['banedit_errors']->value);
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>
</span></div><br /><?php }?>
    </table> 
    <div class="clearer">&nbsp;</div>
  </div>


  <?php if ($_smarty_tpl->tpl_vars['activ_count']->value > 0 || $_smarty_tpl->tpl_vars['exp_count']->value > 0) {?>
  <div class="post">
    <div class="clearer">&nbsp;</div>
    <table width="90%" cellspacing="0">
      <tr class="htable" style="cursor:pointer;" onClick="NewToggleLayer('layer_banhistory');" class="list">
        <td class="table_details_head" colspan="3"><b><?php echo smarty_modifier_lang("_BANHISTORY");?>
 (<?php echo $_smarty_tpl->tpl_vars['activ_count']->value+$_smarty_tpl->tpl_vars['exp_count']->value;?>
)</b></td>
      </tr>
      <tr id="layer_banhistory" style="display: none">
        <?php $_smarty_tpl->_subTemplateRender("file:layer_banhistory.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

      </tr>
    </table>
  </div>
  <?php }?>
  
<?php if ($_smarty_tpl->tpl_vars['vars']->value['use_demo'] == 1) {?>
    <?php if ($_smarty_tpl->tpl_vars['upload_error']->value) {?><div class="_center"><span class="error"><?php echo smarty_modifier_lang("_ERROR");?>
: <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['upload_error']->value, 'upload_errors');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['upload_errors']->value) {
echo smarty_modifier_lang($_smarty_tpl->tpl_vars['upload_errors']->value);
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>
</span></div><br /><?php }?>
    <?php if ($_smarty_tpl->tpl_vars['msg_demo']->value) {?><div class="_center"><span class="success"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msg_demo']->value);?>
</span></div><br /><?php }?>
  <div class="spacer">&nbsp;</div>
  <div class="post">
    <table> 
      <tr class="title">
          <td colspan="8" class="fat"><?php echo smarty_modifier_lang("_BL_FILES");?>
</td> 
        </tr>
        <tr class="title">
          <td style="width:130px;"><?php echo smarty_modifier_lang("_DATE");?>
</td> 
          <td style="width:100px;"><?php echo smarty_modifier_lang("_FILE");?>
</td> 
          <td style="width:50px;"><?php echo smarty_modifier_lang("_SIZE");?>
</td> 
          <td><?php echo smarty_modifier_lang("_COMMENT");?>
</td> 
          <td style="width:100px;"><?php echo smarty_modifier_lang("_BY");?>
</td> 
          <td style="width:150px;"><?php echo smarty_modifier_lang("_IP");?>
</td> 
          <td style="width:120px;"><?php echo smarty_modifier_lang("_DOWNLOADS");?>
</td> 
          <td style="width:80px;">&nbsp;</td> 
        </tr>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['demos']->value, 'demo');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['demo']->value) {
?>
          <form method="post">
            <input type="hidden" name="bid" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['bid'];?>
" />
            <input type="hidden" name="site" value="<?php echo $_smarty_tpl->tpl_vars['site']->value;?>
" />
            <input type="hidden" name="did" value="<?php echo $_smarty_tpl->tpl_vars['demo']->value['id'];?>
" />
            <input type="hidden" name="details_x" value="1" />
            <tr>
              <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['demo']->value['upload_time'],"%d.%m.%Y");?>
</td> 
              <td><?php echo $_smarty_tpl->tpl_vars['demo']->value['demo_real'];?>
</td> 
              <td><?php echo smarty_modifier_file_size($_smarty_tpl->tpl_vars['demo']->value['file_size']);?>
</td> 
              <td><?php if ($_smarty_tpl->tpl_vars['demo']->value['comment']) {
echo smarty_modifier_bbcode2html($_smarty_tpl->tpl_vars['demo']->value['comment']);
} else {
echo smarty_modifier_lang("_NOCOMMENT");
}?></td> 
              <td><?php echo $_smarty_tpl->tpl_vars['demo']->value['name'];?>
</td> 
              <td><?php if ($_SESSION['ip_view'] == "yes") {
echo $_smarty_tpl->tpl_vars['demo']->value['addr'];
}?></td> 
              <td class="_center"><?php echo $_smarty_tpl->tpl_vars['demo']->value['down_count'];?>
</td> 
              <td class="_right">
                <form method="POST" style="display:inline;">
                  <a href="mailto:<?php echo $_smarty_tpl->tpl_vars['demo']->value['email'];?>
"><img src="images/email_go.png" border="0" title="<?php echo smarty_modifier_lang("_TIP_SENDMAIL");?>
" /></a>
                  <input name="down_demo" type="image" src="images/disk.png" border="0" title="<?php echo smarty_modifier_lang("_TIP_DOWNLOAD");?>
" />
                  <?php if ($_SESSION['bans_edit'] == "yes" || ($_SESSION['bans_edit'] == "own" && $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_detail']->value['nickname'])) {?>
                    <img src="images/page_edit.png" border="0" onClick="NewToggleLayer('demedit_<?php echo $_smarty_tpl->tpl_vars['demo']->value['id'];?>
');" title="<?php echo smarty_modifier_lang("_TIP_EDIT");?>
" style="cursor:pointer;"/>
                    <input name="del_demo" type="image" src="images/page_delete.png" border="0" onclick="return confirm('<?php echo smarty_modifier_lang("_DELDEMO");
echo smarty_modifier_lang("_DATALOSS");?>
');" title="<?php echo smarty_modifier_lang("_TIP_DEL");?>
" />
                  <?php }?>
                  <input type="hidden" name="site" value="<?php echo $_smarty_tpl->tpl_vars['site']->value;?>
" />
                  <input type="hidden" name="bid" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['bid'];?>
" />
                  <input type="hidden" name="details_x" value="1" />
                </form>
              </td> 
            </tr>
            <tr id="demedit_<?php echo $_smarty_tpl->tpl_vars['demo']->value['id'];?>
" style="display: none">
              <?php $_smarty_tpl->_subTemplateRender("file:layer_demedit.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

            </tr>
          </form>
        <?php
}
} else {
?>

          <td class="_center" colspan="8"><?php echo smarty_modifier_lang("_NOFILES");?>
</td> 
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

    </table> 
    <div class="clearer">&nbsp;</div>
  </div>
  <?php if ($_smarty_tpl->tpl_vars['vars']->value['demo_all'] == "1" || $_SESSION['loggedin'] == "true") {?>
    <div class="post _center">
      <form method="post" action="">
        <input type="button" class="button" name="newfile" value="<?php echo smarty_modifier_lang("_NEWFILE");?>
" onclick="$('#add_file').slideToggle('slow');"/><br/><br/>
      </form>
    </div>

    <div id="add_file" class="post" style="display:none;">
      <?php $_smarty_tpl->_subTemplateRender("file:layer_demadd.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

    </div>
  <?php }
}?>

  <?php if ($_smarty_tpl->tpl_vars['vars']->value['use_comment'] == 1) {?>
    <?php if ($_smarty_tpl->tpl_vars['comment_error']->value) {?><div class="_center"><span class="error"><?php echo smarty_modifier_lang("_ERROR");?>
: <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['comment_error']->value, 'comment_errors');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['comment_errors']->value) {
echo smarty_modifier_lang($_smarty_tpl->tpl_vars['comment_errors']->value);
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>
</span></div><br /><?php }?>
    <?php if ($_smarty_tpl->tpl_vars['msg_comment']->value) {?><div class="_center"><span class="success"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msg_comment']->value);?>
</span></div><br /><?php }?>
    <div class="spacer">&nbsp;</div>
  <div class="post">
    <table>
        <tr class="title">
          <td colspan="5" class="fat"><?php echo smarty_modifier_lang("_BL_COMMENTS");?>
</td> 
        </tr>
        <tr class="title">
          <td style="width:130px;"><?php echo smarty_modifier_lang("_DATE");?>
</td> 
          <td><?php echo smarty_modifier_lang("_COMMENT");?>
</td> 
          <td style="width:100px;"><?php echo smarty_modifier_lang("_BY");?>
</td> 
          <td style="width:150px;"><?php echo smarty_modifier_lang("_IP");?>
</td> 
          <td style="width:80px;">&nbsp;</td> 
        </tr>
    </table>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['comments']->value, 'comment');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['comment']->value) {
?>
      <form method="POST">
        <input type="hidden" name="bid" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['bid'];?>
" />
        <input type="hidden" name="site" value="<?php echo $_smarty_tpl->tpl_vars['site']->value;?>
" />
        <input type="hidden" name="cid" value="<?php echo $_smarty_tpl->tpl_vars['comment']->value['id'];?>
" />
        <input type="hidden" name="details_x" value="1" />
          <table>
              <!-- Comment List -->
              <tr> 
                <td style="width:130px;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['comment']->value['date'],"%d.%m.%Y");?>
</td> 
                <td><?php echo smarty_modifier_bbcode2html($_smarty_tpl->tpl_vars['comment']->value['comment']);?>
</td> 
                <td style="width:100px;"><?php echo $_smarty_tpl->tpl_vars['comment']->value['name'];?>
</td> 
                <td style="width:150px;"><?php if ($_SESSION['ip_view'] == "yes") {
echo $_smarty_tpl->tpl_vars['comment']->value['addr'];
} else { ?><span style='font-style:italic;font-weight:bold'><?php echo smarty_modifier_lang("_HIDDEN");?>
</span><?php }?></td> 
                <td class="_right" style="width:80px;">
                  <?php if ($_SESSION['bans_edit'] == "yes" || ($_SESSION['bans_edit'] == "own" && $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_detail']->value['nickname'] || $_SESSION['uname'] == $_smarty_tpl->tpl_vars['ban_detail']->value['admin_nick'])) {?>
                    <img src="images/page_edit.png" border="0" style="cursor:pointer;" onClick="NewToggleLayer('comedit_<?php echo $_smarty_tpl->tpl_vars['comment']->value['id'];?>
');" />
                    <input name="del_comment" type="image" src="images/cross.png" border="0" onclick="return confirm('<?php echo smarty_modifier_lang("_DELCOMMENT");
echo smarty_modifier_lang("_DATALOSS");?>
');" title="<?php echo smarty_modifier_lang("_DELETE");?>
" />
                  <?php }?>
                </td>
              <!-- Comment List -->
      </form>
    <?php
}
} else {
?>

                <div class="_center"><?php echo smarty_modifier_lang("_NOCOMMENTS");?>
</div> 
              </tr>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

          </table> 
        <div class="clearer">&nbsp;</div>
  </div>
    <?php if ($_smarty_tpl->tpl_vars['vars']->value['comment_all'] == "1" || $_SESSION['loggedin'] == "true") {?>
      <div class="post _center">
        <form method="post" action="">
          <input type="button" class="button" name="newcomment" value="<?php echo smarty_modifier_lang("_NEWCOMMENT");?>
" onclick="$('#add_comment').slideToggle('slow');"/><br/><br/>
        </form>
      </div>
      <div id="add_comment" class="post" style="display:none;">
        <tr id="comadd_<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['bid'];?>
" <?php if ($_smarty_tpl->tpl_vars['comment_layer']->value != "1") {?>style="display: none"<?php }?>>
          <?php $_smarty_tpl->_subTemplateRender("file:layer_comadd.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

        </tr>
      </div>
      <tr id="comedit_<?php echo $_smarty_tpl->tpl_vars['comment']->value['id'];?>
" style="display: none">
        <?php $_smarty_tpl->_subTemplateRender("file:layer_comedit.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

      </tr>
    <?php }
}?>
  <div class="clearer">&nbsp;</div>
</div><?php }
}
