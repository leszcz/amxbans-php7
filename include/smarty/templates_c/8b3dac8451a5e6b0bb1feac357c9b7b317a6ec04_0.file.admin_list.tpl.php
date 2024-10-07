<?php
/* Smarty version 3.1.30, created on 2024-10-07 07:08:38
  from "/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/admin_list.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_670388f6357158_38322532',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8b3dac8451a5e6b0bb1feac357c9b7b317a6ec04' => 
    array (
      0 => '/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/admin_list.tpl',
      1 => 1707251794,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_670388f6357158_38322532 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_modifier_date_format')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.date_format.php';
echo '<script'; ?>
 src="templates/<?php echo $_smarty_tpl->tpl_vars['design']->value;?>
/js/loading.js"><?php echo '</script'; ?>
>
<div class="main">
  <div class="admins">
    <div class="_right">
      <h1><a href="#" id="serv">Servers</a></h1>
    </div>
    <table>
      <tr class="title">
        <td style="width:5px;">&nbsp;</td>
        <td style="width:60px;"><?php echo smarty_modifier_lang("_NICKNAME");?>
</td>
        <td style="width:30px;">ICQ</td> 
        <!-- <td style="width:150px;"><?php echo smarty_modifier_lang("_STEAMIDIPNAME");?>
</td> -->
        <td style="width:150px;"><?php echo smarty_modifier_lang("_ACCESS");?>
</td>
        <td style="width:150px;"><?php echo smarty_modifier_lang("_ADMINSINCE");?>
</td>
        <td style="width:150px;"><?php echo smarty_modifier_lang("_ADMINTO");?>
</td>
        <td style="width:18px;">&nbsp;</td>
      </tr>
      <!-- Start Loop -->
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['admins']->value, 'admin');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['admin']->value) {
?>
        <tr class="list"> 
          <td><a href="http://steamcommunity.com/profiles/<?php echo $_smarty_tpl->tpl_vars['admins']->value['comid'];?>
" target="_blank"><img src="images/Steam.png" /></a></td>
          <td><?php echo $_smarty_tpl->tpl_vars['admin']->value['nickname'];?>
</td>
          <td>
            <?php if ($_smarty_tpl->tpl_vars['admin']->value['icq']) {?>
              <?php echo $_smarty_tpl->tpl_vars['admin']->value['icq'];?>

            <?php } else { ?>
              <i><?php echo smarty_modifier_lang("_NOTAVAILABLE");?>
</i>
            <?php }?>
          </td>
          <!-- <td><?php echo $_smarty_tpl->tpl_vars['admins']->value['steamid'];?>
</td> -->
          <td><?php echo $_smarty_tpl->tpl_vars['admin']->value['access'];?>
</td> 
          <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['admin']->value['created'],"%d.%m.%Y - %T");?>
</td> 
          <td><em>
            <?php if ($_smarty_tpl->tpl_vars['admin']->value['expired'] == "0") {?>
              <i><?php echo smarty_modifier_lang("_UNLIMITED");?>
</i>
            <?php } else { ?>
              <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['admin']->value['expired'],"%d.%m.%Y - %T");?>

            <?php }?>
          </em></td>
          <td><a href="admin_ajax.php?id=<?php echo $_smarty_tpl->tpl_vars['admins']->value['aid'];?>
" rel="facebox"><img src="images/page.png" border="0" title="<?php echo smarty_modifier_lang("_DETAILS");?>
"/></a></td> 
        </tr>
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

      <!-- Stop Loop -->
    </table> 
  </div>

  <div class="servers" style="display: none">
    <div class="_right">
      <h1><a id="adm" href="#">Admins</a></h1>
    </div>
    <table>
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['admin_list']->value, 'server');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['server']->value) {
?>
        <tr onclick="NewToggleLayer('info_<?php echo $_smarty_tpl->tpl_vars['server']->value['id'];?>
');" class="list">
          <td style="width:20px;"><img alt="<?php echo $_smarty_tpl->tpl_vars['server']->value['gametype'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['server']->value['gametype'];?>
" src="images/games/<?php echo $_smarty_tpl->tpl_vars['server']->value['gametype'];?>
.gif" /></td>
          <td><?php echo $_smarty_tpl->tpl_vars['server']->value['hostname'];?>
</td>
        </tr>
        <tr id="info_<?php echo $_smarty_tpl->tpl_vars['server']->value['id'];?>
" style="display: none">
          <td colspan="2">
            <div style="display: none;" align="center">
              <table class="details">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['server']->value['admins'], 'admin');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['admin']->value) {
?>
                  <tr class="list"> 
                    <td style="width:20px;"><a href="http://steamcommunity.com/profiles/<?php echo $_smarty_tpl->tpl_vars['admin']->value['comid'];?>
" target="_blank"><img src="images/Steam.png" /></a></td>
                    <td style="width:60px;"><?php echo $_smarty_tpl->tpl_vars['admin']->value['nickname'];?>
</td>
                    <td style="width:30px;">
                      <?php if ($_smarty_tpl->tpl_vars['admin']->value['icq']) {?>
                        <?php echo $_smarty_tpl->tpl_vars['admin']->value['icq'];?>

                      <?php } else { ?>
                        <i><?php echo smarty_modifier_lang("_NOTAVAILABLE");?>
</i>
                      <?php }?>
                    </td>
                    <!-- <td style="width:150px;"><?php echo $_smarty_tpl->tpl_vars['admin']->value['steamid'];?>
</td> -->
                    <td style="width:150px;"><?php echo $_smarty_tpl->tpl_vars['admin']->value['access'];?>
</td> 
                    <td style="width:150px;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['admin']->value['created'],"%d.%m.%Y - %T");?>
</td> 
                    <td style="width:150px;"><em>
                      <?php if ($_smarty_tpl->tpl_vars['admin']->value['expired'] == "0") {?>
                        <i><?php echo smarty_modifier_lang("_UNLIMITED");?>
</i>
                      <?php } else { ?>
                        <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['admin']->value['expired'],"%d.%m.%Y - %T");?>

                      <?php }?>
                    </em></td>
                    <td style="width:18px;"><a href="admin_ajax.php?id=<?php echo $_smarty_tpl->tpl_vars['admin']->value['aid'];?>
" rel="facebox"><img src="images/page.png" border="0" title="<?php echo smarty_modifier_lang("_DETAILS");?>
"/></a></td> 
                  </tr>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

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
  </div>

  <div class="post _center">
    <form metdod="post">
      <input type="button" class="button" name="showflags" value="<?php echo smarty_modifier_lang("_INFO_ACCESS");?>
" onclick="$('#info_access').slideToggle('slow');"/>
    </form>
  </div>
  <div id="info_access" class="post" style="display:none;">
    <br />
    <table> 
      <tr class="title"> 
        <td><?php echo smarty_modifier_lang("_ACCESSPERMS");?>
</td> 
        <td style="width:350px;"><?php echo smarty_modifier_lang("_ACCESSFLAGS");?>
</td>
      </tr> 
      <tr class="smallfont">
        <td>
          <?php echo smarty_modifier_lang("_ACCESS_FLAGS");?>

        </td> 
        <td class="vtop">
          <?php echo smarty_modifier_lang("_FLAG_FLAGS");?>

        </td> 
      </tr>
    </table> 
  </div>
  <div class="clearer">&nbsp;</div>
</div><?php }
}
