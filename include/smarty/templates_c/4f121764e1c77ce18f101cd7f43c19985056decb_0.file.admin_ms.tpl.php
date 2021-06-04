<?php
/* Smarty version 3.1.30, created on 2017-12-10 06:14:14
  from "/home/crooket/cs-nypd.pl/public_html/forum/bans/templates/default/admin_ms.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a2cd0b66dcbf6_19027816',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4f121764e1c77ce18f101cd7f43c19985056decb' => 
    array (
      0 => '/home/crooket/cs-nypd.pl/public_html/forum/bans/templates/default/admin_ms.tpl',
      1 => 1512800717,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a2cd0b66dcbf6_19027816 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_modifier_getlanguage')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/modifier.getlanguage.php';
if (!is_callable('smarty_function_html_options')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/function.html_options.php';
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['yesno_select']->value, 'foo');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['foo']->value) {
if ($_smarty_tpl->tpl_vars['foo']->value == "_YES") {
$_tmp_array = isset($_smarty_tpl->tpl_vars['yesno_select']) ? $_smarty_tpl->tpl_vars['yesno_select']->value : array();
if (!is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess) {
settype($_tmp_array, 'array');
}
$_tmp_array[0] = smarty_modifier_lang($_smarty_tpl->tpl_vars['foo']->value);
$_smarty_tpl->_assignInScope('yesno_select', $_tmp_array);
} elseif ($_smarty_tpl->tpl_vars['foo']->value == "_NO") {?> 
<?php $_tmp_array = isset($_smarty_tpl->tpl_vars['yesno_select']) ? $_smarty_tpl->tpl_vars['yesno_select']->value : array();
if (!is_array($_tmp_array) || $_tmp_array instanceof ArrayAccess) {
settype($_tmp_array, 'array');
}
$_tmp_array[1] = smarty_modifier_lang($_smarty_tpl->tpl_vars['foo']->value);
$_smarty_tpl->_assignInScope('yesno_select', $_tmp_array);
}
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>



<?php if ($_smarty_tpl->tpl_vars['msg']->value <> '') {?>
  <div class="notice">
    <?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msg']->value);?>

  </div>
<?php }?>
    <td id="main" valign="top" >
    <?php if ($_SESSION['amxadmins_view'] == "yes") {?>
      <span class="title"><?php echo smarty_modifier_lang("_SITESETTINGS");?>
</span>
      <?php $_smarty_tpl->_assignInScope('lang', smarty_modifier_getlanguage($_smarty_tpl->tpl_vars['true']->value));
?>

      <table>
        <tr>
          <td>
            <table>
              <form method="POST">
                <table>
                  <tr class="title">
                    <td colspan="2" class="fat"><?php echo smarty_modifier_lang("_SYSTEMSETTINGS");?>
</td>
                  </tr>
                  <tr class="info">
                    <td width="50%"><?php echo smarty_modifier_lang("_DEFAULTLANG");?>
</td>
                    <td><?php echo smarty_function_html_options(array('name'=>"language",'values'=>$_smarty_tpl->tpl_vars['lang']->value,'output'=>$_smarty_tpl->tpl_vars['lang']->value,'selected'=>$_smarty_tpl->tpl_vars['current_lang']->value),$_smarty_tpl);?>
</td>
                  </tr>
                  <tr class="info">
                    <td><?php echo smarty_modifier_lang("_USECAPTURE");?>
</td>
                    <td><select name="use_capture" width="200"><?php echo smarty_function_html_options(array('output'=>$_smarty_tpl->tpl_vars['yesno_select']->value,'values'=>$_smarty_tpl->tpl_vars['yesno_values']->value,'selected'=>$_smarty_tpl->tpl_vars['vars']->value['use_capture']),$_smarty_tpl);?>
</select></td>
                  </tr>
                  <tr class="info">
                    <td><?php echo smarty_modifier_lang("_AUTOPRUNE");?>
</td>
                    <td><select name="auto_prune" width="200" 
                      onchange="document.getElementById('max_offences').disabled=(this.value==0);document.getElementById('max_offences_reason').disabled=(this.value==0);">
                      <?php echo smarty_function_html_options(array('output'=>$_smarty_tpl->tpl_vars['yesno_select']->value,'values'=>$_smarty_tpl->tpl_vars['yesno_values']->value,'selected'=>$_smarty_tpl->tpl_vars['vars']->value['auto_prune']),$_smarty_tpl);?>

                    </select></td>
                  </tr>
                <?php if ($_smarty_tpl->tpl_vars['vars']->value['auto_prune'] == 1) {?>
                  <tr class="info">
                    <td><?php echo smarty_modifier_lang("_AUTOPRUNE_MAXOFFENCES");?>
</td>
                    <td><input type="text" size="5" name="max_offences" id="max_offences" value="<?php echo $_smarty_tpl->tpl_vars['vars']->value['max_offences'];?>
" <?php if ($_smarty_tpl->tpl_vars['vars']->value['auto_prune'] == 0) {?>disabled="disabled"<?php }?>/>
                    </td>
                  </tr>
                  <tr class="info">
                    <td><?php echo smarty_modifier_lang("_AUTOPRUNE_MAXOFFENCES_REASON");?>
</td>
                    <td><input type="text" size="30" name="max_offences_reason" id="max_offences_reason" value="<?php echo $_smarty_tpl->tpl_vars['vars']->value['max_offences_reason'];?>
" <?php if ($_smarty_tpl->tpl_vars['vars']->value['auto_prune'] == 0) {?>disabled="disabled"<?php }?>/>
                    </td>
                  </tr>
                <?php }?>
                  <tr class="info">
                    <td><?php echo smarty_modifier_lang("_COOKIENAME");?>
</td>
                    <td><input type="text" size="30" name="cookie" value="<?php echo $_smarty_tpl->tpl_vars['vars']->value['cookie'];?>
" /></td>
                  </tr>
                </table>
                <br />
                <table>
                  <tr class="title">
                    <td colspan="2" class="fat"><?php echo smarty_modifier_lang("_VIEWSETTINGS");?>
</td>
                  </tr>
                  <tr class="info">
                    <td width="50%"><?php echo smarty_modifier_lang("_BANNER");?>
</td>
                    <td><select name="banner" width="200"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['banners']->value,'selected'=>$_smarty_tpl->tpl_vars['vars']->value['banner']),$_smarty_tpl);?>
</select></td>
                  </tr>
                  <tr class="info">
                    <td width="50%"><?php echo smarty_modifier_lang("_BANNERURL");?>
</td>
                    <td><input type="text" name="banner_url" size="30" value="<?php echo $_smarty_tpl->tpl_vars['vars']->value['banner_url'];?>
"></select></td>
                  </tr>
                  <tr class="info">
                    <td><?php echo smarty_modifier_lang("_DESIGN");?>
</td>
                    <td><?php echo smarty_function_html_options(array('name'=>'design','options'=>$_smarty_tpl->tpl_vars['designs']->value,'selected'=>$_smarty_tpl->tpl_vars['vars']->value['design']),$_smarty_tpl);?>
</td>
                  </tr>
                  <tr class="info">
                    <td><?php echo smarty_modifier_lang("_STARTPAGE");?>
</td>
                    <td><select name="start_page" width="200"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['start_pages']->value,'selected'=>$_smarty_tpl->tpl_vars['vars']->value['start_page']),$_smarty_tpl);?>
</select></td>
                  </tr>
                </table>
                <br />
                <table>
                  <tr class="title">
                    <td colspan="2" class="fat"><?php echo smarty_modifier_lang("_COMMENTSETTINGS");?>
</td>
                  </tr>
                  <tr class="info">
                    <td width="50%"><?php echo smarty_modifier_lang("_USECOMMENTSYSTEM");?>
</td>
                    <td><select name="use_comment" width="200"><?php echo smarty_function_html_options(array('output'=>$_smarty_tpl->tpl_vars['yesno_select']->value,'values'=>$_smarty_tpl->tpl_vars['yesno_values']->value,'selected'=>$_smarty_tpl->tpl_vars['vars']->value['use_comment']),$_smarty_tpl);?>
</select></td>
                  </tr>
                  <?php if ($_smarty_tpl->tpl_vars['vars']->value['use_comment'] == 1) {?>
                  <tr class="info">
                    <td width="50%"><?php echo smarty_modifier_lang("_COMMENTUSERALLOWEDWRITE");?>
</td>
                    <td><select name="comment_all" width="200"><?php echo smarty_function_html_options(array('output'=>$_smarty_tpl->tpl_vars['yesno_select']->value,'values'=>$_smarty_tpl->tpl_vars['yesno_values']->value,'selected'=>$_smarty_tpl->tpl_vars['vars']->value['comment_all']),$_smarty_tpl);?>
</select></td>
                  </tr>
                  <?php } else { ?>
                    <input type="hidden" name="comment_all" value="<?php echo $_smarty_tpl->tpl_vars['vars']->value['comment_all'];?>
" />
                  <?php }?>
                </table>
                <br />
                <table>
                  <tr class="title">
                    <td colspan="2" class="fat"><?php echo smarty_modifier_lang("_FILESETTINGS");?>
</td>
                  </tr>
                  <tr class="info">
                    <td width="50%"><?php echo smarty_modifier_lang("_USEFILESYSTEM");?>
</td>
                    <td><select name="use_demo" width="200"><?php echo smarty_function_html_options(array('output'=>$_smarty_tpl->tpl_vars['yesno_select']->value,'values'=>$_smarty_tpl->tpl_vars['yesno_values']->value,'selected'=>$_smarty_tpl->tpl_vars['vars']->value['use_demo']),$_smarty_tpl);?>
</select></td>
                  </tr>
                  <?php if ($_smarty_tpl->tpl_vars['vars']->value['use_demo'] == 1) {?>
                  <tr class="info">
                    <td width="50%"><?php echo smarty_modifier_lang("_FILE_USERUPLOADALLOWED");?>
</td>
                    <td><select name="demo_all" width="200"><?php echo smarty_function_html_options(array('output'=>$_smarty_tpl->tpl_vars['yesno_select']->value,'values'=>$_smarty_tpl->tpl_vars['yesno_values']->value,'selected'=>$_smarty_tpl->tpl_vars['vars']->value['demo_all']),$_smarty_tpl);?>
</select></td>
                  </tr>
                  <tr class="info">
                    <td><?php echo smarty_modifier_lang("_MAXFILESIZE");?>
</td>
                    <td><input type="text" size="5" name="max_file_size" value="<?php echo $_smarty_tpl->tpl_vars['vars']->value['max_file_size'];?>
" /> MB</td>
                  </tr>
                  <tr class="info">
                    <td><?php echo smarty_modifier_lang("_FILE_ALLOWEDTYPES");?>
</td>
                    <td><input type="text" size="30" name="file_type" value="<?php echo $_smarty_tpl->tpl_vars['vars']->value['file_type'];?>
" /></td>
                  </tr>
                  <?php } else { ?>
                    <input type="hidden" name="demo_all" value="<?php echo $_smarty_tpl->tpl_vars['vars']->value['demo_all'];?>
" />
                    <input type="hidden" name="max_file_size" value="<?php echo $_smarty_tpl->tpl_vars['vars']->value['max_file_size'];?>
" />
                    <input type="hidden" name="file_type" value="<?php echo $_smarty_tpl->tpl_vars['vars']->value['file_type'];?>
" />
                  <?php }?>
                </table>
                <br />
                <table>
                  <tr class="title" colspan="20">
                    <td colspan="2" class="fat"><?php echo smarty_modifier_lang("_BANLISTSETTINGS");?>
</td>
                  </tr>
                  <tr class="info">
                    <td width="50%"><?php echo smarty_modifier_lang("_BANSPERPAGE");?>
</td>
                    <td><input type="text" size="5" name="bans_per_page" value="<?php echo $_smarty_tpl->tpl_vars['vars']->value['bans_per_page'];?>
" /></td>
                  </tr>
                  <?php if ($_smarty_tpl->tpl_vars['vars']->value['use_comment'] == 1) {?>
                  <tr class="info">
                    <td><?php echo smarty_modifier_lang("_SHOWCOMMENTSCOUNT");?>
</td>
                    <td><select name="show_comment_count" width="200"><?php echo smarty_function_html_options(array('output'=>$_smarty_tpl->tpl_vars['yesno_select']->value,'values'=>$_smarty_tpl->tpl_vars['yesno_values']->value,'selected'=>$_smarty_tpl->tpl_vars['vars']->value['show_comment_count']),$_smarty_tpl);?>
</select></td>
                  </tr>
                  <?php } else { ?>
                    <input type="hidden" name="show_comment_count" value="<?php echo $_smarty_tpl->tpl_vars['vars']->value['show_comment_count'];?>
" />
                  <?php }?>
                  <?php if ($_smarty_tpl->tpl_vars['vars']->value['use_demo'] == 1) {?>
                  <tr class="info">
                    <td><?php echo smarty_modifier_lang("_SHOWFILESCOUNT");?>
</td>
                    <td><select name="show_demo_count" width="200"><?php echo smarty_function_html_options(array('output'=>$_smarty_tpl->tpl_vars['yesno_select']->value,'values'=>$_smarty_tpl->tpl_vars['yesno_values']->value,'selected'=>$_smarty_tpl->tpl_vars['vars']->value['show_demo_count']),$_smarty_tpl);?>
</select></td>
                  </tr>
                  <?php } else { ?>
                    <input type="hidden" name="show_demo_count" value="<?php echo $_smarty_tpl->tpl_vars['vars']->value['show_demo_count'];?>
" />
                  <?php }?>
                  <tr class="info">
                    <td><?php echo smarty_modifier_lang("_SHOWKICKCOUNT");?>
</td>
                    <td><select name="show_kick_count" width="200"><?php echo smarty_function_html_options(array('output'=>$_smarty_tpl->tpl_vars['yesno_select']->value,'values'=>$_smarty_tpl->tpl_vars['yesno_values']->value,'selected'=>$_smarty_tpl->tpl_vars['vars']->value['show_kick_count']),$_smarty_tpl);?>
</select></td>
                  </tr>
                </table>
                <div align="right"><input type="submit" class="button" name="save" value="<?php echo smarty_modifier_lang("_SAVE");?>
" onclick="return confirm('<?php echo smarty_modifier_lang("_SAVESETTINGS");?>
');" <?php if ($_SESSION['websettings_edit'] !== "yes") {?>disabled<?php }?> /></div>
            </form>
          </table>
    <?php } else { ?>
      <?php echo smarty_modifier_lang("_NOACCESS");?>
 !!
    <?php }?>
    </td>
  </tr>
</table><?php }
}
