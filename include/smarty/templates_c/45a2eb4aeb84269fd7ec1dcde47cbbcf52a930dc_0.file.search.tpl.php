<?php
/* Smarty version 3.1.30, created on 2024-10-07 07:08:39
  from "/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/search.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_670388f7049aa4_90941795',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '45a2eb4aeb84269fd7ec1dcde47cbbcf52a930dc' => 
    array (
      0 => '/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/search.tpl',
      1 => 1707251794,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_670388f7049aa4_90941795 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_modifier_date_format')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_html_options')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/function.html_options.php';
if (!is_callable('smarty_modifier_date2word')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.date2word.php';
?>
<div class="main">
  <table>
    <tr class="title">
      <td style="width:150px;" class="fat"><?php echo smarty_modifier_lang("_SEARCH");?>
</th> 
      <td>&nbsp;</th>
      <td style="width:50px;">&nbsp;</th>
    </tr>
    <tr>
      <form method="post" style="display:inline;">
        <td class="b"><?php echo smarty_modifier_lang("_NICKNAME");?>
</td> 
        <td><input type="text" size="40" name="nick" style="width:200px;" /></td> 
        <td><input type="submit" name="submit" class="button" value="<?php echo smarty_modifier_lang("_SEARCH");?>
" /></td>
      </form>
    </tr> 
    <tr>
      <form method="post" style="display:inline;">
        <td class="b"><?php echo smarty_modifier_lang("_STEAMID");?>
</td> 
        <td><input type="text" name="steamid" size="40" style="width:200px;"/></td> 
        <td><input type="submit" class="button" name="submit" value="<?php echo smarty_modifier_lang("_SEARCH");?>
" /></td> 
      </form>
    </tr> 
    <tr>
      <form method="post" style="display:inline;">
        <td class="b"><?php echo smarty_modifier_lang("_IP");?>
</td> 
        <td><input type="text" name="ip" size="40" style="width:200px;"/></td> 
        <td><input type="submit" class="button" name="submit" value="<?php echo smarty_modifier_lang("_SEARCH");?>
"/></td>
      </form>
    </tr> 
    <tr> 
      <form method="post" style="display:inline;">
        <td class="b"><?php echo smarty_modifier_lang("_REASON");?>
</td> 
        <td><input type="text" name="reason" size="40" style="width:200px;"/></td> 
        <td><input type="submit" name="submit" class="button" value="<?php echo smarty_modifier_lang("_SEARCH");?>
"/></td>
      </form>
    </tr> 
    <tr> 
      <form method="post" name="searchdate" style="display:inline;">
        <td class="b"><?php echo smarty_modifier_lang("_DATE");?>
</td> 
        <td>
          <input type="text" name="date" value="<?php echo smarty_modifier_date_format(time(),"%d-%m-%Y");?>
" style="width:200px;" />
          &nbsp;<?php echo '<script'; ?>
 language="javascript" src="calendar1.js"><?php echo '</script'; ?>
>
          <a href="javascript:cal1.popup();">
            <img src="images/calendar.png" width="16" height="16" border="0" alt="<?php echo smarty_modifier_lang("_PICK_DATE");?>
" title="<?php echo smarty_modifier_lang("_PICK_DATE");?>
"/>
          </a>
        </td> 
        <td><input type="submit" class="button" value="<?php echo smarty_modifier_lang("_SEARCH");?>
"/></td> 
      </form>
      <?php echo '<script'; ?>
 language="javascript" type="text/javascript">
      <!--
        var cal1 = new calendar1(document.forms['searchdate'].elements['date']);
        cal1.year_scroll = true;
        cal1.time_comp = false;
      -->
      <?php echo '</script'; ?>
>
    </tr> 
    <tr> 
      <form method="post" style="display:inline;">
        <td class="b"><?php echo smarty_modifier_lang("_PLAYERSWITH");?>
</td> 
        <td>
          <select name='timesbanned'> 
            <option value='2'>2</option> 
            <option value='3'>3</option> 
            <option value='4'>4</option> 
            <option value='5'>5</option> 
            <option value='6'>6</option> 
            <option value='7'>7</option> 
            <option value='8'>8</option> 
            <option value='9'>9</option> 
            <option value='10'>10</option> 
          </select>
          <?php echo smarty_modifier_lang("_MOREBANSHIS");?>

        </td> 
        <td><input type="submit" class="button" name="submit" value="<?php echo smarty_modifier_lang("_SEARCH");?>
"/></td> 
      </form>
    </tr> 
    <tr> 
      <td class="b"><?php echo smarty_modifier_lang("_ADMIN");?>
</td> 
      <td>
        <form method="post" name="form_admin" style="display:inline;">
          <select name="admin" size="1">
            <optgroup label="<?php echo smarty_modifier_lang("_AMXADMINS");?>
">
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['amxadmins']->value, 'amxadmin');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['amxadmin']->value) {
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['amxadmins']->value['steam'];?>
"><?php echo $_smarty_tpl->tpl_vars['amxadmin']->value['nick'];?>
</option>
              <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

            </optgroup>
          <!--  <optgroup label="<?php echo smarty_modifier_lang("_OTHER");?>
 <?php echo smarty_modifier_lang("_ADMINS");?>
">
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['admins']->value, 'admin');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['admin']->value) {
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['admin']->value['steam'];?>
"><?php echo $_smarty_tpl->tpl_vars['admin']->value['nick'];?>
</option>
              <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

            </optgroup> -->
          </select>
        </form>
      </td> 
      <td><form method="post"><input type="button" class="button" onclick="form_admin.submit();" value="<?php echo smarty_modifier_lang("_SEARCH");?>
"/></form></td> 
    </tr> 
    <tr> 
      <td class="b"><?php echo smarty_modifier_lang("_SERVER");?>
</td> 
      <td>
        <form method="post" name="form_server" style="display:inline;">
          <?php echo smarty_function_html_options(array('name'=>'server','options'=>smarty_modifier_lang($_smarty_tpl->tpl_vars['servers']->value)),$_smarty_tpl);?>

        </form> 
      </td> 
      <td><form method="post"><input type="button" class="button" onclick="form_server.submit();" value="<?php echo smarty_modifier_lang("_SEARCH");?>
"/></form></td> 
    </tr> 
  </table> 
</div>
<div class="clearer">&nbsp;</div>

<?php if ($_smarty_tpl->tpl_vars['msg']->value) {?>
  <center class="admin_msg"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msg']->value);?>
</center><br />
<?php }
if ($_smarty_tpl->tpl_vars['search_done']->value == 1) {?>
  <fieldset><legend><span class="title"><?php echo smarty_modifier_lang("_SEARCHRESULT");?>
</span></legend>
    <table width="95%" cellpadding="2">
      <tr>
        <td>
          <table cellpadding="2">
            <tr>
              <td width="100%" colspan="6"><span style="font-weight:bold;color:red"><?php echo smarty_modifier_lang("_ACTIVEBANS");?>
 (<?php echo $_smarty_tpl->tpl_vars['ban_list_aktiv_count']->value;?>
)</span></td>
            </tr>
            <tr class="title">
              <td width="80"><?php echo smarty_modifier_lang("_DATE");?>
</td>
              <td><?php echo smarty_modifier_lang("_PLAYER");?>
</td>
              <td><?php echo smarty_modifier_lang("_STEAMID");?>
</td>
              <td><?php echo smarty_modifier_lang("_ADMIN");?>
</td>
              <td><?php echo smarty_modifier_lang("_REASON");?>
</td>
              <td width="80"><?php echo smarty_modifier_lang("_LENGHT");?>
</td>
              <td></td>
            </tr>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ban_list_aktiv']->value, 'ban_list_aktivs');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ban_list_aktivs']->value) {
?>
              <tr class="list" style="cursor:pointer;" onClick="NewToggleLayer('layer_<?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['bid'];?>
');">
                <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ban_list_aktivs']->value['ban_created'],"%d.%m.%Y");?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['player_nick'];?>
</td>
                <td><?php if (!in_array($_smarty_tpl->tpl_vars['ban_list_aktivs']->value['player_id'],array('STEAM_ID_LAN','VALVE_ID_LAN','','0'))) {
echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['player_id'];
} else {
echo smarty_modifier_lang("_NOTAVAILABLE");
}?></td>
                <td><?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['admin_nick'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['ban_reason'];?>
</td>
                <td nowrap><?php if ($_smarty_tpl->tpl_vars['ban_list_aktivs']->value['ban_length'] > 0) {
echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['ban_length']*smarty_modifier_date2word(60,true);
} else {
echo smarty_modifier_lang("_PERMANENT");
}?></td>
                <td><a href="ban_list.php?bid=<?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['bid'];?>
"><img src="images/page.png" border="0" title="<?php echo smarty_modifier_lang("_DETAILS");?>
"/></a></td>
              </tr>
              <tr id="layer_<?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['bid'];?>
" style="display: none">
                <td colspan=10>
                  <div style="display: none" align="center">
                    <table class="details">
                      <tr class="title">
                        <td style="width:200px;"><?php echo smarty_modifier_lang("_BANDETAILS");?>
</td>
                        <td></td>
                      </tr>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_NICKNAME");?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['player_nick'];?>
</td>
                      </tr>
                      <?php if (!in_array($_smarty_tpl->tpl_vars['ban_list_aktivs']->value['player_id'],array('STEAM_ID_LAN','VALVE_ID_LAN','','0'))) {?>
                        <tr class="info">
                          <td class="b"><?php echo smarty_modifier_lang("_STEAMID");?>
</td>
                          <td><?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['player_id'];?>
</td>
                        </tr>
                        <tr class="info">
                          <td class="b"><?php echo smarty_modifier_lang("_STEAMCOMID");?>
</td>
                          <td>
                            <a target="_blank" href="http://steamcommunity.com/profiles/<?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['player_comid'];?>
"><?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['player_comid'];?>
</a>
                          </td>
                        </tr>
                      <?php }?>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_IP");?>
</td>
                        <td>
                          <?php if ($_SESSION['ip_view'] == "yes") {?>
                            <?php if ($_smarty_tpl->tpl_vars['ban_list_aktivs']->value['player_ip']) {?>
                              <?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['player_ip'];?>

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
                          <?php if ($_smarty_tpl->tpl_vars['ban_list_aktivs']->value['ban_type'] == "S") {?>
                            <?php echo smarty_modifier_lang("_STEAMID");?>

                          <?php } elseif ($_smarty_tpl->tpl_vars['ban_list_aktivs']->value['ban_type'] == "SI") {?>
                            <?php echo smarty_modifier_lang("_STEAMID&IP");?>

                          <?php } else { ?>
                              <?php echo smarty_modifier_lang("_NOTAVAILABLE");?>

                            <?php }?>
                          </td>
                        </tr>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_REASON");?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['ban_reason'];?>
</td>
                      </tr>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_INVOKED");?>
</td>
                        <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ban_list_aktivs']->value['ban_created'],"%d.%m.%Y - %T");?>
</td>
                      </tr>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_EXPIRES");?>
</td>
                        <td>
                          <?php if ($_smarty_tpl->tpl_vars['ban_list_aktivs']->value['ban_length'] == 0) {?>
                            <span style="font-weight:bold;color:red"><?php echo smarty_modifier_lang("_NOTAPPLICABLE");?>
</span>
                          <?php } else { ?>
                            <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ban_list_aktivs']->value['ban_end'],"%d.%m.%Y - %T");?>

                            <?php if ($_smarty_tpl->tpl_vars['ban_list_aktivs']->value['ban_end'] < time()) {?>
                              (<?php echo smarty_modifier_lang("_ALREADYEXP");?>
)
                            <?php } else { ?>
                              <i>(<?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['ban_end']-smarty_modifier_date2word(time());?>
 <?php echo smarty_modifier_lang("_REMAINING");?>
)</i>
                            <?php }?>
                          <?php }?>
                        </td>
                      </tr>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_BANBY");?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['admin_nick'];
if ($_smarty_tpl->tpl_vars['ban_list_aktivs']->value['nickname']) {?> <span style="font-size: 12px">(<?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['nickname'];?>
)</span><?php }?></td>
                      </tr>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_BANON");?>
</td>
                        <td><?php if ($_smarty_tpl->tpl_vars['ban_list_aktivs']->value['server_name'] == "website") {
echo smarty_modifier_lang("_WEB");
} else {
echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['server_name'];
}?></td>
                      </tr>
                      <tr class="info" style="border: 0">
                        <td class="b"><?php echo smarty_modifier_lang("_TOTALEXPBANS");?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['ban_list_aktivs']->value['bancount'];?>
</td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

          </table>
          <br />
          <table cellpadding="2">
            <tr>
              <td width="100%" colspan="6"><span style="font-weight:bold;color:green"><?php echo smarty_modifier_lang("_EXPIREDBANS");?>
 (<?php echo $_smarty_tpl->tpl_vars['ban_list_exp_count']->value;?>
)</span></td>
            </tr>
            <tr class="title">
              <td width="80"><?php echo smarty_modifier_lang("_DATE");?>
</td>
              <td><?php echo smarty_modifier_lang("_PLAYER");?>
</td>
              <td><?php echo smarty_modifier_lang("_STEAMID");?>
</td>
              <td><?php echo smarty_modifier_lang("_ADMIN");?>
</td>
              <td><?php echo smarty_modifier_lang("_REASON");?>
</td>
              <td width="80"><?php echo smarty_modifier_lang("_LENGHT");?>
</td>
              <td></td>
            </tr>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ban_list_exp']->value, 'ban_list_exps');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ban_list_exps']->value) {
?>
              <tr class="list" style="cursor:pointer;" onClick="NewToggleLayer('layer_<?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['bid'];?>
');">
                <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ban_list_exps']->value['ban_created'],"%d.%m.%Y");?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['player_nick'];?>
</td>
                <td><?php if (!in_array($_smarty_tpl->tpl_vars['ban_list_exps']->value['player_id'],array('STEAM_ID_LAN','VALVE_ID_LAN','','0'))) {
echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['player_id'];
} else {
echo smarty_modifier_lang("_NOTAVAILABLE");
}?></td>
                <td><?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['admin_nick'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['ban_reason'];?>
</td>
                <td nowrap><?php if ($_smarty_tpl->tpl_vars['ban_list_exps']->value['ban_length'] > 0) {
echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['ban_length']*smarty_modifier_date2word(60,true);
} else {
echo smarty_modifier_lang("_PERMANENT");
}?></td>
                <td><a href="ban_list.php?bid=<?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['bid'];?>
"><img src="images/page.png" border="0" title="<?php echo smarty_modifier_lang("_DETAILS");?>
"/></a></td>
              </tr>
              <tr id="layer_<?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['bid'];?>
" style="display: none">
                <td colspan=10>
                  <div style="display: none" align="center">
                    <table class="details">
                      <tr class="title">
                        <td style="width:200px;"><?php echo smarty_modifier_lang("_BANDETAILS");?>
</td>
                        <td></td>
                      </tr>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_NICKNAME");?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['player_nick'];?>
</td>
                      </tr>
                      <?php if (!in_array($_smarty_tpl->tpl_vars['ban_list_exps']->value['player_id'],array('STEAM_ID_LAN','VALVE_ID_LAN','','0'))) {?>
                        <tr class="info">
                          <td class="b"><?php echo smarty_modifier_lang("_STEAMID");?>
</td>
                          <td><?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['player_id'];?>
</td>
                        </tr>
                        <tr class="info">
                          <td class="b"><?php echo smarty_modifier_lang("_STEAMCOMID");?>
</td>
                          <td>
                            <a target="_blank" href="http://steamcommunity.com/profiles/<?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['player_comid'];?>
"><?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['player_comid'];?>
</a>
                          </td>
                        </tr>
                      <?php }?>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_IP");?>
</td>
                        <td>
                          <?php if ($_SESSION['ip_view'] == "yes") {?>
                            <?php if ($_smarty_tpl->tpl_vars['ban_list_exps']->value['player_ip']) {?>
                              <?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['player_ip'];?>

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
                          <?php if ($_smarty_tpl->tpl_vars['ban_list_exps']->value['ban_type'] == "S") {?>
                            <?php echo smarty_modifier_lang("_STEAMID");?>

                          <?php } elseif ($_smarty_tpl->tpl_vars['ban_list_exps']->value['ban_type'] == "SI") {?>
                            <?php echo smarty_modifier_lang("_STEAMID&IP");?>

                          <?php } else { ?>
                              <?php echo smarty_modifier_lang("_NOTAVAILABLE");?>

                            <?php }?>
                          </td>
                        </tr>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_REASON");?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['ban_reason'];?>
</td>
                      </tr>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_INVOKED");?>
</td>
                        <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ban_list_exps']->value['ban_created'],"%d.%m.%Y - %T");?>
</td>
                      </tr>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_EXPIRES");?>
</td>
                        <td>
                          <?php if ($_smarty_tpl->tpl_vars['ban_list_exps']->value['ban_length'] == 0) {?>
                            <span style="font-weight:bold;color:red"><?php echo smarty_modifier_lang("_NOTAPPLICABLE");?>
</span>
                          <?php } else { ?>
                            <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ban_list_exps']->value['ban_end'],"%d.%m.%Y - %T");?>

                            <?php if ($_smarty_tpl->tpl_vars['ban_list_exps']->value['ban_end'] < time()) {?>
                              (<?php echo smarty_modifier_lang("_ALREADYEXP");?>
)
                            <?php } else { ?>
                              <i>(<?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['ban_end']-smarty_modifier_date2word(time());?>
 <?php echo smarty_modifier_lang("_REMAINING");?>
)</i>
                            <?php }?>
                          <?php }?>
                        </td>
                      </tr>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_BANBY");?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['admin_nick'];
if ($_smarty_tpl->tpl_vars['ban_list_exps']->value['nickname']) {?> <span style="font-size: 12px">(<?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['nickname'];?>
)</span><?php }?></td>
                      </tr>
                      <tr class="info">
                        <td class="b"><?php echo smarty_modifier_lang("_BANON");?>
</td>
                        <td><?php if ($_smarty_tpl->tpl_vars['ban_list_exps']->value['server_name'] == "website") {
echo smarty_modifier_lang("_WEB");
} else {
echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['server_name'];
}?></td>
                      </tr>
                      <tr class="info" style="border: 0">
                        <td class="b"><?php echo smarty_modifier_lang("_TOTALEXPBANS");?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['ban_list_exps']->value['bancount'];?>
</td>
                      </tr>
                    </table>
                  </div>
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
  </fieldset>
<?php }
}
}
