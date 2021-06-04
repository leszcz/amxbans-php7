<?php
/* Smarty version 3.1.30, created on 2017-11-21 13:10:56
  from "/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans/install/setup.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a1417d0a13e38_73807680',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c875b95b1573cd1bd9652367d3a4dca32612e988' => 
    array (
      0 => '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans/install/setup.tpl',
      1 => 1510839137,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:step".((string)$_smarty_tpl->tpl_vars[\'sitenr\']->value).".tpl' => 1,
  ),
),false)) {
function content_5a1417d0a13e38_73807680 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_getlanguage')) require_once '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans/include/smarty/plugins/modifier.getlanguage.php';
if (!is_callable('smarty_modifier_selectlang')) require_once '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans/include/smarty/plugins/modifier.selectlang.php';
if (!is_callable('smarty_modifier_lang')) require_once '/usr/home/twardy/domains/twardy.ct8.pl/public_html/build/bans/include/smarty/plugins/modifier.lang.php';
$_smarty_tpl->_assignInScope('langs', smarty_modifier_getlanguage($_smarty_tpl->tpl_vars['true']->value));
?> 
<?php $_smarty_tpl->_assignInScope('select_lang', smarty_modifier_selectlang($_smarty_tpl->tpl_vars['true']->value) == "session");
$_smarty_tpl->_assignInScope('default_lang', smarty_modifier_selectlang($_smarty_tpl->tpl_vars['true']->value) == "config");
?>
<html>
<head>
  <title>AMXBans - <?php echo smarty_modifier_lang("_INSTALLATION");?>
 - <?php echo smarty_modifier_lang("_STEP");?>
 <?php echo $_smarty_tpl->tpl_vars['sitenr']->value;?>
</title>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo smarty_modifier_lang("_ENCODING");?>
" />
  <meta name="Keywords" content="" />
  <meta name="Description" content="" />
  <meta http-equiv="pragma" content="no-cache" />
  <meta http-equiv="cache-control" content="no-cache" />
  <link rel="stylesheet" type="text/css" href="install/setup.css" />
  <?php echo '<script'; ?>
 type="text/javascript" language="javascript" src="install/func.js"><?php echo '</script'; ?>
>
</head>
<body>
<div class="center-wrapper">
  <div id="header">
    <div id="site-title" style="text-align: center;">
      <a href="http://gm-community.net/" target="_blank"><img src="images/banner/amxbans.png" title="AMXBans" border="0"></img></a>
    </div>
  </div>
  <div id="site-title" style="text-align: left;">
    <form method="POST" style="display:inline;">
      <input type="hidden" name="site" value="<?php echo $_smarty_tpl->tpl_vars['sitenr']->value;?>
" />
      <select name="newlang" onchange="this.form.submit()">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['langs']->value, 'lang');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->value) {
?>
          <option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['lang']->value, ENT_QUOTES, 'UTF-8', true);?>
" <?php if (empty($_smarty_tpl->tpl_vars['select_lang']->value) && $_smarty_tpl->tpl_vars['default_lang']->value == $_smarty_tpl->tpl_vars['lang']->value) {?>selected<?php }
if ($_smarty_tpl->tpl_vars['select_lang']->value == $_smarty_tpl->tpl_vars['lang']->value) {?>selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['lang']->value, ENT_QUOTES, 'UTF-8', true);?>
</option>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

      </select>
    </form>
  </div>
  <fieldset>
    <legend><span class='title'>AMXBans <?php echo $_smarty_tpl->tpl_vars['v_web']->value;?>
: <?php echo smarty_modifier_lang("_INSTALLATION");?>
</span></legend>
    <table width="100%" cellpadding="0" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td rowspan="2" width="40%" valign="top">
          <div align="left" class="block">
            <span class="navi">1. <?php echo smarty_modifier_lang("_STEP");?>
:</span> <?php if ($_smarty_tpl->tpl_vars['sitenr']->value == 1) {?><span class="navi_select"><?php }
echo smarty_modifier_lang("_STEP1");?>
</span><br /><br />
            <span class="navi">2. <?php echo smarty_modifier_lang("_STEP");?>
:</span> <?php if ($_smarty_tpl->tpl_vars['sitenr']->value == 2) {?><span class="navi_select"><?php }
echo smarty_modifier_lang("_STEP2");?>
</span><br /><br />
            <span class="navi">3. <?php echo smarty_modifier_lang("_STEP");?>
:</span> <?php if ($_smarty_tpl->tpl_vars['sitenr']->value == 3) {?><span class="navi_select"><?php }
echo smarty_modifier_lang("_STEP3");?>
</span><br /><br />
            <span class="navi">4. <?php echo smarty_modifier_lang("_STEP");?>
:</span> <?php if ($_smarty_tpl->tpl_vars['sitenr']->value == 4) {?><span class="navi_select"><?php }
echo smarty_modifier_lang("_STEP4");?>
</span><br /><br />
            <span class="navi">5. <?php echo smarty_modifier_lang("_STEP");?>
:</span> <?php if ($_smarty_tpl->tpl_vars['sitenr']->value == 5) {?><span class="navi_select"><?php }
echo smarty_modifier_lang("_STEP5");?>
</span><br /><br />
            <span class="navi">6. <?php echo smarty_modifier_lang("_STEP");?>
:</span> <?php if ($_smarty_tpl->tpl_vars['sitenr']->value == 6) {?><span class="navi_select"><?php }
echo smarty_modifier_lang("_STEP6");?>
</span><br /><br />
            <span class="navi">7. <?php echo smarty_modifier_lang("_STEP");?>
:</span> <?php if ($_smarty_tpl->tpl_vars['sitenr']->value == 7) {?><span class="navi_select"><?php }
echo smarty_modifier_lang("_STEP7");?>
</span>
          </div>
        </td>
        <td valign="top" height="400">
          <form method="POST" name="form" style="display:inline;">
            <?php $_smarty_tpl->_subTemplateRender("file:step".((string)$_smarty_tpl->tpl_vars['sitenr']->value).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

        </td>
      </tr>
    </table>
  <div align="right">
    <input type="hidden" name="site" value="<?php echo $_smarty_tpl->tpl_vars['sitenr']->value;?>
" />
      <?php if ($_smarty_tpl->tpl_vars['sitenr']->value != 1 && $_smarty_tpl->tpl_vars['sitenr']->value != 7) {?><input type="submit" class="button" name="back" value="<?php echo smarty_modifier_lang("_BACK");?>
" /><?php }?>
      <?php if ($_smarty_tpl->tpl_vars['checkvalue']->value) {?><input type="submit" class="button" name="check<?php echo $_smarty_tpl->tpl_vars['sitenr']->value;?>
" value="<?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['checkvalue']->value);?>
" /><?php }?>
      <?php if ($_smarty_tpl->tpl_vars['sitenr']->value < 6) {?><input type="submit" class="button" name="next" value="<?php echo smarty_modifier_lang("_NEXT");?>
" <?php if (!$_smarty_tpl->tpl_vars['next']->value || $_smarty_tpl->tpl_vars['sitenr']->value == 1) {?>disabled<?php }?>/><?php }?>
    </form>
  </div>
  </fieldset>
</div><?php }
}
