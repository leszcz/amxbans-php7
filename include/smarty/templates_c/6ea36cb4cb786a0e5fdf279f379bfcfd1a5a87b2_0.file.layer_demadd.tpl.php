<?php
/* Smarty version 3.1.30, created on 2017-12-09 07:49:51
  from "/home/crooket/cs-nypd.pl/public_html/forum/bans/templates/default/layer_demadd.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a2b959fa98e53_19457891',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6ea36cb4cb786a0e5fdf279f379bfcfd1a5a87b2' => 
    array (
      0 => '/home/crooket/cs-nypd.pl/public_html/forum/bans/templates/default/layer_demadd.tpl',
      1 => 1512800718,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a2b959fa98e53_19457891 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/modifier.lang.php';
?>
<td colspan=10>
    <table width="100%">
      <tr class="title">
        <td class="fat"><?php echo smarty_modifier_lang("_FILEUPLOAD");?>
</td>
      </tr>
      <tr><td colspan="2">
        <form name="demoupload" method="post" enctype="multipart/form-data">
          <table>
            <input type="hidden" name="bid" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['bid'];?>
" />
            <input type="hidden" name="site" value="<?php echo $_smarty_tpl->tpl_vars['site']->value;?>
" />
            <input type="hidden" name="details_x" value="1" />
            <tr class="info">
              <td width="30%"><?php echo smarty_modifier_lang("_NAME");?>
:</td>
              <td class="vtop"><input type="test" size="30" name="name" value="<?php if ($_SESSION['loggedin'] == "true") {
echo $_SESSION['uname'];?>
" disabled<?php } else { ?>"<?php }?>/></td>
              <?php if ($_SESSION['loggedin'] == "true") {?><input type="hidden" name="name" value="<?php echo $_SESSION['uname'];?>
" /><?php }?>
            </tr>
            <tr class="info">
              <td width="30%"><?php echo smarty_modifier_lang("_EMAIL");?>
:</td>
              <td class="vtop"><input type="test" size="30" name="email" value="<?php if ($_SESSION['loggedin'] == "true" && $_SESSION['email'] != '') {
echo $_SESSION['email'];?>
" disabled<?php } else { ?>"<?php }?>/></td>
              <?php if ($_SESSION['loggedin'] == "true" && $_SESSION['email'] != '') {?><input type="hidden" name="email" value="<?php echo $_SESSION['email'];?>
" /><?php }?>
            </tr>
            <tr class="info">
              <td><?php echo smarty_modifier_lang("_FILE");?>
:</td>
              <td class="vtop"><input class="input_file" type="file" size="30" name="filename"> (<?php echo $_smarty_tpl->tpl_vars['vars']->value['file_type'];?>
, max. <?php echo $_smarty_tpl->tpl_vars['vars']->value['max_file_size'];?>
 MB)</td>
            </tr>
            <tr class="info">
              <td><?php echo smarty_modifier_lang("_COMMENT");?>
:</td>
              <td>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['bbcodes']->value, 'bbcode');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['bbcode']->value) {
?>
                  <a href="javascript:insertAtCaret('commentd', '<?php echo $_smarty_tpl->tpl_vars['bbcode']->value[0];?>
 <?php echo $_smarty_tpl->tpl_vars['bbcode']->value[1];?>
');"><img border="0" src="images/icons/bbcode/<?php echo $_smarty_tpl->tpl_vars['bbcode']->value[2];?>
" title="<?php echo $_smarty_tpl->tpl_vars['bbcode']->value[3];?>
" /></a>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                <br />
                  <textarea name="comment" id="commentd" cols="50" rows="3" wrap="soft"></textarea>
                <br />
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smilies']->value, 'smilie');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['smilie']->value) {
?>
                  <a href="javascript:insertAtCaret('commentd', ' <?php echo $_smarty_tpl->tpl_vars['smilie']->value[0];?>
 ');"><img border="0" src="images/icons/<?php echo $_smarty_tpl->tpl_vars['smilie']->value[1];?>
" title="<?php echo $_smarty_tpl->tpl_vars['smilie']->value[2];?>
" /></a>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

              </td>
            </tr>
            <?php if ($_SESSION['loggedin'] != true) {?>
            <tr class="info">
              <td><?php echo smarty_modifier_lang("_SCODE");?>
</td> 
              <td><?php echo smarty_modifier_lang("_SCODEENTER");?>
<br>
                <img src="include/captcha.php" alt="Security code" style="border: 1px #000000 solid;"><br>
                <input type='text' size="30" name='verify' id="verify_code">
              </td> 
            </tr>
            <?php }?>
          </table>
          <div class="_center">
            <input type="submit" class="button" name="add_demo" value="<?php echo smarty_modifier_lang("_UPLOAD");?>
" />
          </div>
        </form>
      </td></tr>
    </table>
</td><?php }
}
