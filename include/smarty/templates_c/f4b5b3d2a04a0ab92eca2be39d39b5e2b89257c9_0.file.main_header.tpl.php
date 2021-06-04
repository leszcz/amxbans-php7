<?php
/* Smarty version 3.1.30, created on 2017-12-09 06:41:39
  from "/home/crooket/cs-nypd.pl/public_html/forum/bans/templates/default/main_header.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a2b85a307e337_52666411',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f4b5b3d2a04a0ab92eca2be39d39b5e2b89257c9' => 
    array (
      0 => '/home/crooket/cs-nypd.pl/public_html/forum/bans/templates/default/main_header.tpl',
      1 => 1512800718,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a2b85a307e337_52666411 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_getlanguage')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/modifier.getlanguage.php';
if (!is_callable('smarty_modifier_selectlang')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/modifier.selectlang.php';
if (!is_callable('smarty_modifier_lang')) require_once '/home/crooket/cs-nypd.pl/public_html/forum/bans/include/smarty/plugins/modifier.lang.php';
if (!$_smarty_tpl->tpl_vars['ajaxlist']->value) {?>
<!DOCTYPE html>
<?php $_smarty_tpl->_assignInScope('lang', smarty_modifier_getlanguage($_smarty_tpl->tpl_vars['true']->value));
$_smarty_tpl->_assignInScope('select_lang', $_SESSION['lang']);
$_smarty_tpl->_assignInScope('default_lang', smarty_modifier_selectlang($_smarty_tpl->tpl_vars['true']->value,"config"));
?>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>

  <meta charset="UTF-8" />
  <meta http-equiv="pragma" content="no-cache" /> 
  <meta http-equiv="cache-control" content="no-cache" />
  
  <link rel="stylesheet" href="templates/<?php echo $_smarty_tpl->tpl_vars['design']->value;?>
/css/style.css" />
  <link rel="stylesheet" href="templates/<?php echo $_smarty_tpl->tpl_vars['design']->value;?>
/css/facebox.css" />
  <link rel="stylesheet" href="templates/<?php echo $_smarty_tpl->tpl_vars['design']->value;?>
/css/paginator.css" />

  <?php echo '<script'; ?>
 src="templates/<?php echo $_smarty_tpl->tpl_vars['design']->value;?>
/js/jquery.js"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="templates/<?php echo $_smarty_tpl->tpl_vars['design']->value;?>
/js/amxbans.js.php"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="templates/<?php echo $_smarty_tpl->tpl_vars['design']->value;?>
/js/tooltip.js"><?php echo '</script'; ?>
>

  <?php echo '<script'; ?>
 src="templates/<?php echo $_smarty_tpl->tpl_vars['design']->value;?>
/js/facebox.js"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="templates/<?php echo $_smarty_tpl->tpl_vars['design']->value;?>
/js/facebox_ext.js"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="include/layer.js"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="include/steamprofile/steamprofile.js"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
 src="templates/<?php echo $_smarty_tpl->tpl_vars['design']->value;?>
/js/paginator3000.js"><?php echo '</script'; ?>
>

  <title>AMXBans: Gm <?php echo $_smarty_tpl->tpl_vars['version_web']->value;?>
 - <?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['title']->value);
if ($_smarty_tpl->tpl_vars['title2']->value) {?> - <?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['title2']->value);
}?></title>  
</head>

<body id="top" <?php if ($_SESSION['loginfailed']) {?>onLoad="javascript: countdown(<?php echo $_SESSION['loginfailed'];?>
);"<?php }?>>
<div id="network">
  <div class="center-wrapper">
    <div class="left">
      <ul class="tabbed" id="menu-tabs">
        <li>
          <form method="post" action="" style="padding:5px 8px 0 0;margin:0;">
            <select name="newlang" style="padding:0;margin:0;" onchange="this.form.submit()">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lang']->value, 'langs');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['langs']->value) {
?>
              <option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['langs']->value, ENT_QUOTES, 'ISO-8859-1', true);?>
" <?php if (empty($_smarty_tpl->tpl_vars['select_lang']->value) && $_smarty_tpl->tpl_vars['default_lang']->value == $_smarty_tpl->tpl_vars['langs']->value) {?>selected="selected"<?php }?> <?php if ($_smarty_tpl->tpl_vars['select_lang']->value == $_smarty_tpl->tpl_vars['langs']->value) {?>selected="selected"<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['langs']->value, ENT_QUOTES, 'ISO-8859-1', true);?>
</option>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

        <?php echo var_dump($_smarty_tpl->tpl_vars['select_lang']->value);?>

            </select>
          </form>
          
        </li>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['menu']->value, 'menus');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['menus']->value) {
?>
          <?php if ($_SESSION['loggedin'] == "true") {?>
            <?php if ($_smarty_tpl->tpl_vars['menus']->value['lang_key2']) {?><li><a href="<?php echo $_smarty_tpl->tpl_vars['menus']->value['url2'];?>
"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['menus']->value['lang_key2']);?>
</a></li><?php }?>
          <?php } else { ?>
            <?php if ($_smarty_tpl->tpl_vars['menus']->value['lang_key']) {?><li><a href="<?php echo $_smarty_tpl->tpl_vars['menus']->value['url'];?>
"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['menus']->value['lang_key']);?>
</a></li><?php }?>
          <?php }?>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

          </ul>
    </div>
    <div class="right">
      
      <ul class="tabbed" id="admin-tabs">
        <?php if ($_SESSION['loggedin'] == "true") {?>
          <li><a href="admin.php"><?php echo smarty_modifier_lang("_ADMINAREA");?>
</a></li>
          <li><a href="logout.php"><?php echo smarty_modifier_lang("_LOGOUT");?>
 <?php echo $_SESSION['uname'];?>
</a></li>
        <?php } else { ?>
          <li>
            <form name="loginform" action="login.php" method="post">
              <?php echo '<script'; ?>
 type="text/javascript">
                var languser = '<?php echo smarty_modifier_lang("_USERNAME");?>
';
                var langpass = '<?php echo smarty_modifier_lang("_PASSWORD");?>
';
              <?php echo '</script'; ?>
>
              <input
                type="text" 
                size="17"
                name="user" 
                value="<?php echo smarty_modifier_lang("_USERNAME");?>
" 
                onfocus="javascript:if(this.value=languser)this.value='';" 
                
                />
              <input 
                type="password"
                size="12" 
                name="pass" 
                value="<?php echo smarty_modifier_lang("_PASSWORD");?>
" 
                onfocus="javascript:if(this.value=langpass)this.value='';" 
                
                />
              <input type='checkbox' checked="checked" name='remember' value="yes" title='<?php echo smarty_modifier_lang("_REMEMBERME");?>
' />
              <button type="submit" name="action" id="action" value="Login"><?php echo smarty_modifier_lang("_LOGIN");?>
</button>
            </form>
          </li>
        <?php }?>
      </ul>
      
    </div>
    <div class="clearer">&nbsp;</div>
  </div>
</div>
<br />
<div id="site">
  <div class="center-wrapper">
    <?php if ($_smarty_tpl->tpl_vars['banner']->value <> '') {?>
      <div id="header">
        <div id="site-title" style="text-align: center;">
          <a href="<?php echo $_smarty_tpl->tpl_vars['banner_url']->value;?>
" target="_blank"><img src="images/banner/<?php echo $_smarty_tpl->tpl_vars['banner']->value;?>
" alt="<?php echo $_smarty_tpl->tpl_vars['banner_url']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['banner_url']->value;?>
" /></a>
        </div>
      </div>
    <?php } else { ?>
      <div class="spacer">&nbsp;</div>
    <?php }?>
    <?php }
}
}
