<?php
/* Smarty version 3.1.30, created on 2017-12-08 09:11:49
  from "/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/templates/default/layer_comedit.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a2a494515a7c1_38976959',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3620ecffade9fce5260b667a1869e9d84390b59f' => 
    array (
      0 => '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/templates/default/layer_comedit.tpl',
      1 => 1512720679,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a2a494515a7c1_38976959 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans2/include/smarty/plugins/modifier.lang.php';
?>
<td colspan=10>
  <div id="comedit_<?php echo $_smarty_tpl->tpl_vars['comments']->value['id'];?>
" style="display: none">
    <table>
      <tr class="title">
        <td class="fat"><?php echo smarty_modifier_lang("_EDITCOMMENT");?>
</td>
      </tr>
      <tr>
        <td colspan="2">
          <table>
            <form method="post">
            <input type="hidden" name="bid" value="<?php echo $_smarty_tpl->tpl_vars['ban_detail']->value['bid'];?>
" />
            <input type="hidden" name="site" value="<?php echo $_smarty_tpl->tpl_vars['site']->value;?>
" />
            <input type="hidden" name="cid" value="<?php echo $_smarty_tpl->tpl_vars['comments']->value['id'];?>
" />
            <input type="hidden" name="details_x" value="1" />
            <tr>
              <td align="right" width="30%"><?php echo smarty_modifier_lang("_NAME");?>
:</td>
              <td><input type="test" size="30" name="name" value="<?php echo $_smarty_tpl->tpl_vars['comments']->value['name'];?>
" /></td>
            </tr>
            <tr>
              <td align="right" width="30%"><?php echo smarty_modifier_lang("_EMAIL");?>
:</td>
              <td><input type="test" size="30" name="email" value="<?php echo $_smarty_tpl->tpl_vars['comments']->value['email'];?>
" /></td>
            </tr>
            <tr>
              <td align="right"><?php echo smarty_modifier_lang("_COMMENT");?>
:</td>
              <td>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['bbcodes']->value, 'bbcode');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['bbcode']->value) {
?>
                <a href="javascript:insertAtCaret('commentce<?php echo $_smarty_tpl->tpl_vars['comments']->value['id'];?>
', '<?php echo $_smarty_tpl->tpl_vars['bbcode']->value[0];?>
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
                <textarea name="comment" id="commentce<?php echo $_smarty_tpl->tpl_vars['comments']->value['id'];?>
" cols="50" rows="3" wrap="soft"><?php echo $_smarty_tpl->tpl_vars['comments']->value['comment'];?>
</textarea>
                <br />
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['smilies']->value, 'smilie');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['smilie']->value) {
?>
                <a href="javascript:insertAtCaret('commentce<?php echo $_smarty_tpl->tpl_vars['comments']->value['id'];?>
', ' <?php echo $_smarty_tpl->tpl_vars['smilie']->value[0];?>
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
            <tr>
              <td colspan="2" class="_center">
                <input type="submit" name="edit_comment" onclick="return confirm('<?php echo smarty_modifier_lang("_SAVEEDIT");?>
');" value="<?php echo smarty_modifier_lang("_SAVE");?>
" class="button" />
              </td>
            </tr>
            </form>
          </table>
      </td></tr>
    </table>
  </div>
</td><?php }
}
