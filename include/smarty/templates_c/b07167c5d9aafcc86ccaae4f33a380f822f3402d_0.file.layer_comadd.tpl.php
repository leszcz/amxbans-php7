<?php
/* Smarty version 3.1.30, created on 2017-12-08 09:11:49
  from "/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/templates/default/layer_comadd.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a2a494514e687_06761353',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b07167c5d9aafcc86ccaae4f33a380f822f3402d' => 
    array (
      0 => '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/templates/default/layer_comadd.tpl',
      1 => 1512720708,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a2a494514e687_06761353 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/include/smarty/plugins/modifier.lang.php';
?>
<td colspan=10>
  <table>
    <tr>
      <td colspan="2">
        <table>
          <form method="post">
            <input type="hidden" name="bid" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['bid'];?>
" />
            <input type="hidden" name="site" value="<?php echo $_smarty_tpl->tpl_vars['site']->value;?>
" />
            <input type="hidden" name="details_x" value="1" />
            <div id="add_comment" class="post">
              <form method="post">
                <table> 
                    <tr class="title"> 
                      <td colspan="2" class="fat"><?php echo smarty_modifier_lang("_NEWCOMMENT");?>
</th> 
                    </tr>
                    <tr class="info"> 
                      <td style="width: 150px;"><?php echo smarty_modifier_lang("_NAME");?>
</td> 
                      <td class="vtop">
                        <input type="text" size="30" name="name" value="<?php if ($_SESSION['loggedin'] == "true") {
echo $_SESSION['uname'];?>
" disabled<?php } else { ?>"<?php }?>/>
                        <?php if ($_SESSION['loggedin'] == "true") {?><input type="hidden" name="name" value="<?php echo $_SESSION['uname'];?>
" /><?php }?>
                      </td>
                    </tr>
                    <tr class="info"> 
                      <td><?php echo smarty_modifier_lang("_EMAIL");?>
</td> 
                      <td class="vtop">
                        <input type="text" size="30" name="email" value="<?php if ($_SESSION['loggedin'] == "true" && $_SESSION['email'] != '') {
echo $_SESSION['email'];?>
" disabled<?php } else { ?>"<?php }?>/>
                        <?php if ($_SESSION['loggedin'] == "true" && $_SESSION['email'] != '') {?><input type="hidden" name="email" value="<?php echo $_SESSION['email'];?>
" /><?php }?> 
                      </td>
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
                          <a href="javascript:insertAtCaret('commentc', '<?php echo $_smarty_tpl->tpl_vars['bbcode']->value[0];?>
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
                          <textarea name="comment" id="commentc" cols="50" rows="3" wrap="soft"></textarea>
                        <br />
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smilies']->value, 'smilie');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['smilie']->value) {
?>
                          <a href="javascript:insertAtCaret('commentc', ' <?php echo $_smarty_tpl->tpl_vars['smilie']->value[0];?>
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
                    <tr> 
                      <td colspan="2" class="_center"><input class="button" type="submit" name="add_comment" value="<?php echo smarty_modifier_lang("_ADD");?>
" /></th> 
                    </tr>

                </table> 
              </form>
            </div>
          </form>
        </table>
      </td>
    </tr>
  </table>
</td><?php }
}
