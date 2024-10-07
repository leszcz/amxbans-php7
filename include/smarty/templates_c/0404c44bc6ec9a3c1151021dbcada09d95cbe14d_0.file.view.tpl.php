<?php
/* Smarty version 3.1.30, created on 2024-10-07 07:09:31
  from "/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/view.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6703892b9510c2_51807051',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0404c44bc6ec9a3c1151021dbcada09d95cbe14d' => 
    array (
      0 => '/home/leszczu/web/amxbans.1free.eu/public_html/templates/default/view.tpl',
      1 => 1707251794,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6703892b9510c2_51807051 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_lang')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.lang.php';
if (!is_callable('smarty_modifier_date_format')) require_once '/home/leszczu/web/amxbans.1free.eu/public_html/include/smarty/plugins/modifier.date_format.php';
?>
<div class="main" id="main-two-columns">
	<div class="left" id="main-left">
		<table> 
			<tr class="title"> 
				<td style="width:20px;"><?php echo smarty_modifier_lang("_MOD");?>
</th> 
				<td style="width:20px;"><?php echo smarty_modifier_lang("_OS");?>
</th> 
				<td style="width:20px;"><?php echo smarty_modifier_lang("_VAC");?>
</th> 
				<td><?php echo smarty_modifier_lang("_HOSTNAME");?>
</th> 
				<td style="width:50px;"><?php echo smarty_modifier_lang("_PLAYER");?>
</th> 
				<td style="width:200px;"><?php echo smarty_modifier_lang("_MAP");?>
</th>
			</tr> 
			<?php if ($_smarty_tpl->tpl_vars['error']->value) {?>
				<tr> 
					<td class="_center" colspan="6"><?php echo smarty_modifier_lang($_smarty_tpl->tpl_vars['error']->value);?>
</td>
				</tr> 
			<?php } else { ?>
					
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['server']->value, 'serv');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['serv']->value) {
?>
						
						<?php if ($_smarty_tpl->tpl_vars['serv']->value['game']) {?>
							<tr onclick="NewToggleLayer('info_<?php echo $_smarty_tpl->tpl_vars['serv']->value['sid'];?>
');" class="list"> 
								<td class="_center"><img alt="<?php echo $_smarty_tpl->tpl_vars['serv']->value['game'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['serv']->value['game'];?>
" src="images/games/<?php echo $_smarty_tpl->tpl_vars['serv']->value['mod'];?>
.gif" /></td> 
								<td class="_center"><img alt="<?php echo $_smarty_tpl->tpl_vars['serv']->value['os'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['serv']->value['os'];?>
" src="images/os/<?php echo $_smarty_tpl->tpl_vars['serv']->value['os'];?>
.png" /></td> 
								<td class="_center"><img alt="<?php echo smarty_modifier_lang("_VAC_ALT");?>
" title="<?php echo smarty_modifier_lang("_VAC_ALT");?>
" src="images/acheat/vac.png" /></td> 
								<td><?php echo $_smarty_tpl->tpl_vars['serv']->value['hostname'];?>
</td> 
								<td class="_center">
									<?php if ($_smarty_tpl->tpl_vars['serv']->value['bot_players']) {?>
										<?php echo $_smarty_tpl->tpl_vars['serv']->value['cur_players']-$_smarty_tpl->tpl_vars['serv']->value['bot_players'];?>
 (<?php echo $_smarty_tpl->tpl_vars['serv']->value['cur_players'];?>
)
									<?php } else { ?>
										<?php echo $_smarty_tpl->tpl_vars['serv']->value['cur_players'];?>

									<?php }?> 
									 / <?php echo $_smarty_tpl->tpl_vars['serv']->value['max_players'];?>

								</td> 
								<td><?php echo $_smarty_tpl->tpl_vars['serv']->value['map'];?>
</td> 
							</tr> 
							<tr id="info_<?php echo $_smarty_tpl->tpl_vars['serv']->value['sid'];?>
" style="display: none"> 
								<td class="server-info" colspan="6">
									<div style="display:none">
										<table>
												<tr>
													<td style="vertical-align:top;padding-left:0px;padding-right:0px;">
														<table>
															<thead> 
																<tr class="title"> 
																	<td class="b"><?php echo smarty_modifier_lang("_NAME");?>
</th> 
																	<td class="b" style="width:30px;"><?php echo smarty_modifier_lang("_FRAGS");?>
</th> 
																	<td class="b" style="width:90px;"><?php echo smarty_modifier_lang("_ONLINE");?>
</th>
																</tr> 
																<?php if ($_smarty_tpl->tpl_vars['serv']->value['cur_players'] >= 1) {?>
																	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['serv']->value['players'], 'player');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['player']->value) {
?>
																	<tr class="info">
																		<td class="vtop"><?php if ($_smarty_tpl->tpl_vars['player']->value['name'] != '') {?> <?php echo $_smarty_tpl->tpl_vars['player']->value['name'];
} else { ?> <?php echo smarty_modifier_lang("_PLAYERCONNECTING");
}?></td> 
																		<td class="_center vtop"><?php echo $_smarty_tpl->tpl_vars['player']->value['frag'];?>
</td> 
																		<td class="vtop"><?php echo $_smarty_tpl->tpl_vars['player']->value['time'];?>
</td>
																	</tr>
																	
																	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

																<?php } else { ?>
																	<tr>
																		<td colspan="3"><?php echo smarty_modifier_lang("_NOPLAYERS");?>
</td> 
																	</tr>
																	
																<?php }?>
																<!-- Users Online -->
														</table>
													</td>
													<td>
														<table>
																<tr class="title"> 
																	<th class="_center fat" colspan="2"><?php echo $_smarty_tpl->tpl_vars['serv']->value['address'];?>
</th> 
																</tr> 
																<tr>
																	<td class="_center" colspan="2">
																		<img style="border:1px #000000 solid;" src="images/maps/<?php echo $_smarty_tpl->tpl_vars['serv']->value['mod'];?>
/<?php echo $_smarty_tpl->tpl_vars['serv']->value['mappic'];?>
.jpg" alt="<?php echo $_smarty_tpl->tpl_vars['serv']->value['map'];?>
" width="80%">
																	</td>
																</tr>
																<tr class="info">
																	<td class="_center">
																		
																				<a href="steam://connect/<?php echo $_smarty_tpl->tpl_vars['serv']->value['address'];?>
" title="<?php echo smarty_modifier_lang("_CONNECT");?>
" class="icons-connect icon-steam"></a>
																	</td>
																	<td class="_center">
																		<a href="hlsw://<?php echo $_smarty_tpl->tpl_vars['serv']->value['address'];?>
" title="<?php echo smarty_modifier_lang("_ADDHLSW");?>
">
																		<span title="<?php echo smarty_modifier_lang("_ADDHLSW");?>
" class="icons-connect icon-hlsw" ></span>
																		</a>
																	</td>
																</tr>
																<tr class="info">
																	<td class="b"><?php echo smarty_modifier_lang("_NEXTMAP");?>
</td>
																	<td><?php echo $_smarty_tpl->tpl_vars['serv']->value['nextmap'];?>
</td>
																</tr>
																<tr class="info">
																	<td class="b"><?php echo smarty_modifier_lang("_FRIENDLYFIRE");?>
</td>
																	<td><?php if ($_smarty_tpl->tpl_vars['serv']->value['friendlyfire'] == 1) {
echo smarty_modifier_lang("_YES");
} else {
echo smarty_modifier_lang("_NO");
}?></td>
																</tr>
																<tr class="info">
																	<td class="b"><?php echo smarty_modifier_lang("_TIMELEFT");?>
</td>
																	<td><?php echo $_smarty_tpl->tpl_vars['serv']->value['timeleft'];?>
 <?php echo smarty_modifier_lang("_MINS");?>
</td>
																</tr>
																<tr class="info">
																	<td class="b"><?php echo smarty_modifier_lang("_PASSWORD");?>
</td>
																	<td><?php if ($_smarty_tpl->tpl_vars['serv']->value['password'] == 1) {
echo smarty_modifier_lang("_YES");
} else {
echo smarty_modifier_lang("_NO");
}?></td>
																</tr>
														</table>
													</td>
												</tr>
											</table>
									</div>
								</td> 
							</tr> 
							<!-- Server Online -->
						<?php } else { ?>
							<tr class="offline"> 
								<td class="_center"><img alt="<?php echo $_smarty_tpl->tpl_vars['serv']->value['mod'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['serv']->value['mod'];?>
" src="images/games/<?php echo $_smarty_tpl->tpl_vars['serv']->value['mod'];?>
.gif" /></td> 
								<td class="_center"><?php echo smarty_modifier_lang("_NA");?>
</td> 
								<td class="_center"><?php echo smarty_modifier_lang("_NA");?>
</td> 
								<td><?php echo $_smarty_tpl->tpl_vars['serv']->value['hostname'];?>
</td> 
								<td colspan="2"><?php echo smarty_modifier_lang("_SERVEROFFLINE");?>
</td>
							</tr> 
						<?php }?>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

				<?php }?>
			</tbody> 
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
						<div class="left"><?php echo smarty_modifier_lang("_BANSINDB");?>
</div>
						<div class="right"><?php echo $_smarty_tpl->tpl_vars['stats']->value['total'];?>
</div>
						<div class="clearer">&nbsp;</div>
					</li>

					<li>
						<div class="left"><?php echo smarty_modifier_lang("_ACTIVEBANS");?>
</div>
						<div class="right"><?php echo $_smarty_tpl->tpl_vars['stats']->value['active'];?>
</div>
						<div class="clearer">&nbsp;</div>
					</li>
					
					<li>
						<div class="left"><?php echo smarty_modifier_lang("_PERM_BANS");?>
</div>
						<div class="right"><?php echo $_smarty_tpl->tpl_vars['stats']->value['permanent'];?>
</div>
						<div class="clearer">&nbsp;</div>
					</li>

					<li>
						<div class="left"><?php echo smarty_modifier_lang("_TEMP_BANS");?>
</div>
						<div class="right"><?php echo $_smarty_tpl->tpl_vars['stats']->value['temp'];?>
</div>
						<div class="clearer">&nbsp;</div>
					</li>
					<li>
						<div class="left"><?php echo smarty_modifier_lang("_ADMINS");?>
</div>
						<div class="right"><?php echo $_smarty_tpl->tpl_vars['stats']->value['admins'];?>
</div>
						<div class="clearer">&nbsp;</div>
					</li>
					
					<li>
						<div class="left"><?php echo smarty_modifier_lang("_ACTIVE_SERVERS");?>
</div>
						<div class="right"><?php echo $_smarty_tpl->tpl_vars['stats']->value['servers'];?>
</div>
						<div class="clearer">&nbsp;</div>
					</li>
					
				</ul>
			</div>
		</div>

		<div class="section">
			<div class="section-title">
				<div class="left"><?php echo smarty_modifier_lang("_LATEST_BAN");?>
</div>
				<div class="right"><span title="<?php echo smarty_modifier_lang("_LATEST_BAN");?>
" class="icons-stats icon-clock"></span></div>
				
				<div class="clearer">&nbsp;</div>
			</div>

			<div class="section-content">
				<ul class="nice-list">
					<li>
						<div class="left"><?php echo smarty_modifier_lang("_PLAYER");?>
</div>
						<div class="right"><?php echo $_smarty_tpl->tpl_vars['last_ban']->value['nickname'];?>
</div>
						<div class="clearer">&nbsp;</div>
					</li>
					
					<li>
						<div class="left"><?php echo smarty_modifier_lang("_STEAMID");?>
</div>
						<div class="right"><?php if ($_smarty_tpl->tpl_vars['last_ban']->value['steamid'] == "SI") {
echo smarty_modifier_lang("_NOTAVAILABLE");
} else {
echo $_smarty_tpl->tpl_vars['last_ban']->value['steamid'];
}?></div>
						<div class="clearer">&nbsp;</div>
					</li>
					
					<li>
						<div class="left"><?php echo smarty_modifier_lang("_REASON");?>
</div>
						<div class="right"><?php echo $_smarty_tpl->tpl_vars['last_ban']->value['reason'];?>
</div>
						<div class="clearer">&nbsp;</div>
					</li>
					
					<li>
						<div class="left"><?php echo smarty_modifier_lang("_DATE");?>
</div>
						<div class="right"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['last_ban']->value['created'],"%Y-%m-%d %H:%M");?>
</div>
						<div class="clearer">&nbsp;</div>
					</li>
					
					<li>
						<div class="left"><?php echo smarty_modifier_lang("_EXPIRES");?>
</div>
						<div class="right"><?php if ($_smarty_tpl->tpl_vars['last_ban']->value['length'] == 0) {
echo smarty_modifier_lang("_NEVER");
} else {
if ($_smarty_tpl->tpl_vars['last_ban']->value['time'] > $_smarty_tpl->tpl_vars['last_ban']->value['length']) {
echo smarty_modifier_lang("_ALREADYEXP");
} else {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['last_ban']->value['length'],"%Y-%m-%d %H:%M");
}
}?></div>
						<div class="clearer">&nbsp;</div>
					</li>

					<li><a href="ban_list.php"><?php echo smarty_modifier_lang("_BROWSE_ALL");?>
 &#187;</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="clearer">&nbsp;</div>
</div><?php }
}
