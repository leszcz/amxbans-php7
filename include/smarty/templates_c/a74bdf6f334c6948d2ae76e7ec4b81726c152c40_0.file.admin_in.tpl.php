<?php
/* Smarty version 3.1.30, created on 2024-10-07 07:06:52
  from "/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/admin_in.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6703888c7de649_69755758',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a74bdf6f334c6948d2ae76e7ec4b81726c152c40' => 
    array (
      0 => '/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/admin_in.tpl',
      1 => 1707251794,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6703888c7de649_69755758 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.lang.php';
if ($_smarty_tpl->tpl_vars['msg']->value <> '') {?>
  <div class="success"><?php echo smarty_modifier_lang(((string)$_smarty_tpl->tpl_vars['msg']->value));?>
</div>
<?php }?>
<div class="main" id="main-two-columns">
  <div class="left" id="main-left">
    <table> 
      <tr class="title">
        <td colspan="2" class="fat"><?php echo smarty_modifier_lang("_SERVERSETUP");?>
</th> 
      </tr> 
      <tr class="info">
        <td class="b" style="width:200px;">PHP <?php echo smarty_modifier_lang("_VERSION");?>
</td> 
        <td><?php echo $_smarty_tpl->tpl_vars['php_settings']->value['version_php'];?>
</td>
      </tr>
      <tr class="info">
        <td class="b" style="width:200px;">MySQL <?php echo smarty_modifier_lang("_VERSION");?>
</td> 
        <td><?php echo $_smarty_tpl->tpl_vars['php_settings']->value['mysql_version'];?>
 <img alt="" src="images/generic/<?php if ($_smarty_tpl->tpl_vars['php_settings']->value['mysql_version'] >= 5) {?>accept<?php } else { ?>accept<?php }?>.png" /></td>
      </tr>
      <tr class="info">
        <td class="b" style="width:200px;">safe_mode</td> 
        <td><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['php_settings']->value['safe_mode']);?>
 <img alt="" src="images/generic/accept.png" /></td>
      </tr>
      <tr class="info">
        <td class="b" style="width:200px;">register_globals</td> 
        <td><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['php_settings']->value['register_globals']);?>
 <img src="images/generic/<?php if ($_smarty_tpl->tpl_vars['php_settings']->value['register_globals'] == "_OFF") {?>accept<?php } else { ?>exclamation<?php }?>.png" /></td>
      </tr>
      <tr class="info">
        <td class="b" style="width:200px;">magic_quotes_gpc</td> 
        <td><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['php_settings']->value['magic_quotes_gpc']);?>
 <img src="images/generic/<?php if ($_smarty_tpl->tpl_vars['php_settings']->value['magic_quotes_gpc'] == "_OFF") {?>accept<?php } else { ?>exclamation<?php }?>.png" /></td>
      </tr>
      <tr class="info">
        <td class="b" style="width:200px;">display_errors</td> 
        <td><?php echo $_smarty_tpl->tpl_vars['php_settings']->value['display_errors'];?>
</td>
      </tr>
      <tr class="info">
        <td class="b" style="width:200px;">post_max_size</td> 
        <td><?php echo $_smarty_tpl->tpl_vars['php_settings']->value['post_max_size'];?>
</td>
      </tr>
      <tr class="info">
        <td class="b" style="width:200px;">upload_max_filesize</td> 
        <td><?php echo $_smarty_tpl->tpl_vars['php_settings']->value['upload_max_filesize'];?>
</td>
      </tr>
      <tr class="info">
        <td class="b" style="width:200px;">max_execution_time</td> 
        <td><?php echo $_smarty_tpl->tpl_vars['php_settings']->value['max_execution_time'];?>
</td>
      </tr>
    </table>
    <div class="clearer">&nbsp;</div>
  </div>
  <div class="right sidebar" id="sidebar">
    <div class="section">
      <div class="section-title">
        <div class="left"><?php echo smarty_modifier_lang("_STATS");?>
</div>
        <div class="right"><span title="<?php echo smarty_modifier_lang("_STATS");?>
" class="icons-stats icon-stats"></span></div>
        
        <div class="clearer">&nbsp;</div>

      </div>
      <div class="section-content">
        <ul class="nice-list">
          <li>
            <div class="left"><?php echo smarty_modifier_lang("_DBSIZE");?>
</div>
            <div class="right"><?php echo $_smarty_tpl->tpl_vars['db_size']->value;?>
</div>
            <div class="clearer">&nbsp;</div>
          </li>
          <li>
            <div class="left"><?php echo smarty_modifier_lang("_BANSINDB");?>
</div>
            <div class="right"><?php echo $_smarty_tpl->tpl_vars['bans']->value['count'];?>
</div>
            <div class="clearer">&nbsp;</div>
          </li>
          <li>
            <div class="left"><?php echo smarty_modifier_lang("_ACTIVEBANS");?>
</div>
            <div class="right"><?php echo $_smarty_tpl->tpl_vars['bans']->value['activ'];?>
</div>
            <div class="clearer">&nbsp;</div>
          </li>
          <li>
            <div class="left"><?php echo smarty_modifier_lang("_COMMENTS");?>
</div>
            <div class="right">
              <?php echo $_smarty_tpl->tpl_vars['comment_count']->value['count'];?>

              <?php if ($_smarty_tpl->tpl_vars['comment_count']->value['fail'] != 0) {?> (<?php echo $_smarty_tpl->tpl_vars['comment_count']->value['fail'];?>
 <?php echo smarty_modifier_lang("_ERROR");?>
)
                <img src="images/generic/exclamation.png" />
                &nbsp;<input type="submit" name="comment_repair" value="<?php echo smarty_modifier_lang("_REPAIR");?>
" />
              <?php }?></div>
            <div class="clearer">&nbsp;</div>
          </li>
          <li>
            <div class="left"><?php echo smarty_modifier_lang("_BL_FILES");?>
</div>
            <div class="right">
              <?php echo $_smarty_tpl->tpl_vars['file_count']->value['count'];?>

              <?php if ($_smarty_tpl->tpl_vars['file_count']->value['fail'] != 0) {?> (<?php echo $_smarty_tpl->tpl_vars['file_count']->value['fail'];?>
 <?php echo smarty_modifier_lang("_ERROR");?>
)
                <img src="images/generic/exclamation.png" />
                &nbsp;<input type="submit" name="file_repair" value="<?php echo smarty_modifier_lang("_REPAIR");?>
" />
              <?php }?>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <div class="section">
      <div class="section-title">
        <?php echo smarty_modifier_lang("_OTHERFUNCTIONS");?>

      </div>
      <div class="section-content">
        <form method="post">
          <ul class="nice-list">
            <li>
              <div class="left"><?php echo smarty_modifier_lang("_CLEARCACHE");?>
</div>
              <div class="right">
                <input type="submit" class="button" name="clear" value="<?php echo smarty_modifier_lang("_DELETE");?>
" <?php if ($_SESSION['websettings_edit'] != "yes") {?>disabled<?php }?>/>
              </div>
              <div class="clearer">&nbsp;</div>
            </li>
            <li>
              <div class="left"><?php echo smarty_modifier_lang("_DBOPTIMIZE");?>
</div>
              <div class="right">
                <input type="submit" class="button" name="optimize" value="OK" <?php if ($_SESSION['websettings_edit'] != "yes") {?>disabled<?php }?>/>
              </div>
              <div class="clearer">&nbsp;</div>
            </li>
            <li>
              <div class="left"><?php echo smarty_modifier_lang("_PRUNEDB");?>
</div>
              <div class="right">
                <input type="submit" class="button" name="prunedb" value="OK" <?php if ($_SESSION['websettings_edit'] != "yes") {?>disabled<?php }?>/>
              </div>
              <div class="clearer">&nbsp;</div>
            </li>
          </ul>
        </form>
      </div>
    </div>
  </div>
  <div class="clearer">&nbsp;</div>
</div>
<?php }
}
