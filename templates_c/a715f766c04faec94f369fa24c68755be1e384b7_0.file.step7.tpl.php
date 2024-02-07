<?php
/* Smarty version 3.1.30, created on 2024-02-06 20:41:07
  from "/home/kampownia/web/bans.kampownia.eu/public_html/install/step7.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65c29963bdfde2_42753031',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a715f766c04faec94f369fa24c68755be1e384b7' => 
    array (
      0 => '/home/kampownia/web/bans.kampownia.eu/public_html/install/step7.tpl',
      1 => 1707246853,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65c29963bdfde2_42753031 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/kampownia/web/bans.kampownia.eu/public_html/include/smarty/plugins/modifier.lang.php';
?>
<div style="font-size:12px;">
  <div align="center" class="notice"><?php echo smarty_modifier_lang("_STEP7DESC");?>
</div>
  <br />
  <table width="100%" cellspacing="10">
    <tr>
      <td width="50%" valign="top">
        <fieldset>
          <table width="100%" cellpadding="1" style="font-size:10px;">
              <legend><?php echo smarty_modifier_lang("_TABLECREATE");?>
</legend>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['tables']->value, 'table');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['table']->value) {
?>
                <tr>
                  <td width="40%"><?php echo $_smarty_tpl->tpl_vars['table']->value['table'];?>
</td><td><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['table']->value['success']);?>
</td>
                </tr>
              <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

          </table>
        </fieldset>
      </td>
      <td valign="top">
        <fieldset>
          <legend><?php echo smarty_modifier_lang("_DEFAULTDATACREATE");?>
</legend>
          <table width="100%" cellpadding="1" style="font-size:10px;">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['datas']->value, 'data');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['data']->value) {
?>
              <tr>
                <td width="40%"><?php echo $_smarty_tpl->tpl_vars['data']->value['data'];?>
</td><td><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['data']->value['success']);?>
</td>
              </tr>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

          </table>
        </fieldset>
        <br />
        <fieldset>
          <legend><?php echo smarty_modifier_lang("_DEFAULTWEBSETTINGSCREATE");?>
</legend>
          <table width="100%" cellpadding="1" style="font-size:10px;">
            <tr>
              <td width="40%"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['websettings_create']->value['data']);?>
</td><td><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['websettings_create']->value['success']);?>
</td>
            </tr>
          </table>
        </fieldset>
        <br />
        <fieldset>
          <legend><?php echo smarty_modifier_lang("_DEFAULTUSERMENUCREATE");?>
</legend>
          <table width="100%" cellpadding="1" style="font-size:10px;">
            <tr>
              <td width="40%"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['usermenu_create']->value['data']);?>
</td><td><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['usermenu_create']->value['success']);?>
</td>
            </tr>
          </table>
        </fieldset>
        <br />
        <fieldset>
          <legend><?php echo smarty_modifier_lang("_DEFAULTMODULESCREATE");?>
</legend>
          <table width="100%" cellpadding="1" style="font-size:10px;">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['modules']->value, 'module');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['module']->value) {
?>
            <tr>
              <td width="40%"><?php echo $_smarty_tpl->tpl_vars['module']->value['name'];?>
</td><td><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['module']->value['success']);?>
</td>
            </tr>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

          </table>
        </fieldset>
        <br />
        <fieldset>
          <legend><?php echo smarty_modifier_lang("_WEBADMINCREATE");?>
</legend>
          <table width="100%" cellpadding="1" style="font-size:10px;">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['webadmin_create']->value, 'webadmin_creates');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['webadmin_creates']->value) {
?>
              <tr>
                <td width="40%"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['webadmin_creates']->value['data']);?>
</td><td><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['webadmin_creates']->value['success']);?>
</td>
              </tr>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

          </table>
        </fieldset>
      </td>
    </tr>
  </table>
  <?php if ($_smarty_tpl->tpl_vars['msg']->value) {?>
    <div class="<?php if ($_smarty_tpl->tpl_vars['msg']->value == "_FILESUCCESS") {?>success<?php } else { ?>error<?php }?>"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['msg']->value);?>
</div>
  <?php }?>
  <?php if ($_smarty_tpl->tpl_vars['msg']->value != "_FILESUCCESS") {?>
  <br />
  <div align="center">
    <?php echo smarty_modifier_lang("_MANUALEDIT");?>
<br />
    <textarea cols="70" rows="15"><?php echo $_smarty_tpl->tpl_vars['content']->value;?>
</textarea>
    <div class="notice"><?php echo smarty_modifier_lang("_SETUPENDDESC");?>
</div>
  </div>
  <?php }
}
}
