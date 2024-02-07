<div class="main">
	<div class="post">
	<span>
		{if isset($check_id)}
			<div class='error'><a href="http://{$smarty.server.HTTP_HOST}{$smarty.server.PHP_SELF}?bid={$check_id}"><span style='color:white;font-weight:bold;text-shadow: #990000 0px 0px 3px;'>{"_YOUAREBANNED"|lang}</span></a></div>
		{else}
			<div class='success'><span style='color:white;font-weight:bold;text-shadow: #00991E 0px 0px 3px;'>{"_IP"|lang} &mdash; {$your_ip}. {"_YOUNOTBANNED"|lang}</span></div>
		{/if}
	</span>
	<table>
		<tr class="title">
			<td>&nbsp;</td>
			<td style="width:80px;">{"_DATE"|lang}</td>
			<td>{"_PLAYER"|lang}</td>
			<td>{"_ADMIN"|lang}</td>
			<td>{"_REASON"|lang}</td>
			<td style="width:120px;">{"_LENGHT"|lang}</td>
			{if $ban_page.show_comments == 1 && $vars.use_comment == 1}
				<td style="width:80px;">{"_BL_COMMENTS"|lang}</td>
			{/if}
			{if $ban_page.show_demos == 1 && $vars.use_demo == 1}
				<td style="width:45px;">{"_BL_FILES"|lang}</td>
			{/if}
			{if $ban_page.show_kicks == 1}
				<td style="width:45px;">{"_BL_KICKS"|lang}</td>
			{/if}
		</tr>
		{if $error}
			<tr>
				<td class="_center" colspan="9">{"_NO_BANS"|lang}</td>
			</tr>
		{else}
			<!-- Banlist -->
				{foreach from=$ban_list item=ban_list}
					<form name="details" method="POST">
						<tr onclick="NewToggleLayer('layer_{$ban_list.bid}')" class="list">
							<td class="_center"><img alt="{if $ban_list.mod == "html"}{"_WEB"|lang}{else}{$ban_list.mod|lang}{/if}" title="{if $ban_list.mod == "html"}{"_WEB"|lang}{else}{$ban_list.mod|lang}{/if}" src="images/games/{if $ban_list.mod == "html"}web.png{else}{$ban_list.mod}.gif{/if}" /></td>
							<td>{$ban_list.ban_created|date_format:"%Y-%m-%d"}</td>
							<td><img alt="" src="images/flags/{if $ban_list.cc}{$ban_list.cc|lower}{else}clear{/if}.png" /> {$ban_list.player_nick}</td>
							<td>{$ban_list.admin_nick}</td>
							<td>{$ban_list.ban_reason}</td>
							<td>
								{if $ban_list.ban_length>0}
									{$ban_list.ban_length*60|date2word:true}
								{else}
									{"_PERMANENT"|lang}
								{/if}
							</td>
							{if $ban_page.show_comments == 1 && $vars.use_comment == 1}<td class="_center">{$ban_list.comment_count}</td>{/if}
							{if $ban_page.show_demos == 1 && $vars.use_demo == 1}<td class="_center">{$ban_list.demo_count}</td>{/if}
							{if $ban_page.show_kicks == 1}<td class="_center">{$ban_list.kick_count}</td>{/if}
						</tr>
						<tr id="layer_{$ban_list.bid}" style="display:none;">
							<td colspan="9">
								<div style="display:none;" align="center">
									<input type="hidden" name="bid" value="{$ban_list.bid}" />
									<table class="details">
										<tr class="title">
											<td style="width:200px;" class="fat">{"_BANDETAILS"|lang}</td>
											<td class="_right">
												<a href="ban_list.php?bid={$ban_list.bid}"><img src="images/page.png" border="0" title="{"_DETAILS"|lang}" /></a>
												{if $smarty.session.bans_edit=="yes" || ($smarty.session.bans_edit=="own" && $smarty.session.uname == $ban_list.nickname || $smarty.session.uname == $ban_list.admin_nick)}
													<img src="images/page_edit.png" border="0" onclick="NewToggleLayer('banedit_{$ban_list.bid}')" title="{"_TIP_EDIT"|lang}" style="cursor:pointer;border:0;" />
												{/if}
												<form method="POST" style="display:inline;">
													<input class="img_input" name="del_ban" type="image" src="images/page_delete.png" onclick="return confirm('{"_DELBAN"|lang}{"_DATALOSS"|lang}');" border="0" title="{"_TIP_DEL"|lang}" />
													<input type="hidden" name="site" value="{$site}" />
													<input type="hidden" name="bid" value="{$ban_list.bid}" />
													<input type="hidden" name="details_x" value="1" />
												</form>

											</td>
										</tr>
										<tr class="info">
											<td class="b">{"_NICKNAME"|lang}</td>
											<td>{$ban_list.player_nick}</td>
										</tr>
										{if !in_array($ban_list.player_id,  array('STEAM_ID_LAN', 'VALVE_ID_LAN', '', '0'))}
											<tr class="info">
												<td class="b">{"_STEAMID"|lang}</td>
												<td>{$ban_list.player_id}</td>
											</tr>
											<tr class="info">
												<td class="b">{"_STEAMCOMID"|lang}</td>
												<td>
													<a target="_blank" href="http://steamcommunity.com/profiles/{$ban_list.player_comid}">{$ban_list.player_comid}</a>
												</td>
											</tr>
										{/if}
										<tr class="info">
											<td class="b">{"_IP"|lang}</td>
											<td>
												{if $smarty.session.ip_view=="yes"}
													{if $ban_list.player_ip}
														{$ban_list.player_ip}
													{else}
														<i>{"_NOTAVAILABLE"|lang}</i>
													{/if}
												{else}
													<span style='font-style:italic;font-weight:bold'>{"_HIDDEN"|lang}</span>
												{/if}
											</td>
										</tr>
										<tr class="info">
											<td class="b">{"_BANTYPE"|lang}</td>
											<td>
												{if $ban_list.ban_type=="S"}
													{"_STEAMID"|lang}
												{elseif $ban_list.ban_type=="SI"}
													{"_STEAMID&IP"|lang}
												{else}
														{"_NOTAVAILABLE"|lang}
													{/if}
												</td>
											</tr>
										<tr class="info">
											<td class="b">{"_REASON"|lang}</td>
											<td>{$ban_list.ban_reason}</td>
										</tr>
										<tr class="info">
											<td class="b">{"_INVOKED"|lang}</td>
											<td>{$ban_list.ban_created|date_format:"%d.%m.%Y - %T"}</td>
										</tr>
										<tr class="info">
											<td class="b">{"_EXPIRES"|lang}</td>
											<td>
												{if $ban_list.ban_length==0}
													<span style="font-weight:bold;color:red">{"_NOTAPPLICABLE"|lang}</span>
												{else}
													{$ban_list.ban_end|date_format:"%d.%m.%Y - %T"}
													{if $ban_list.ban_end < $smarty.now}
														({"_ALREADYEXP"|lang})
													{else}
														<i>({$ban_list.ban_end-$smarty.now|date2word} {"_REMAINING"|lang})</i>
													{/if}
												{/if}
											</td>
										</tr>
										<tr class="info">
											<td class="b">{"_BANBY"|lang}</td>
											<td>{$ban_list.admin_nick}{if $ban_list.nickname} <span style="font-size: 12px">({$ban_list.nickname})</span>{/if}</td>
										</tr>
										<tr class="info">
											<td class="b">{"_BANON"|lang}</td>
											<td>{if $ban_list.server_name == "website"}{"_WEB"|lang}{else}{$ban_list.server_name}{/if}</td>
										</tr>
										<tr class="info" style="border: 0">
											<td class="b">{"_TOTALEXPBANS"|lang}</td>
											<td>{$ban_list.bancount}</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
						{if $smarty.session.bans_edit=="yes" || ($smarty.session.bans_edit=="own" && $smarty.session.uname == $ban_list.nickname || $smarty.session.uname == $ban_list.admin_nick)}
							<tr id="banedit_{$ban_list.bid}" style="display:none;">
								{include file="layer_banedit_banlist.tpl"}
							</tr>
						{/if}
					</form>
				{/foreach}
			{/if}
			<!-- Banlist end -->
		</table>

		<div class="clearer">&nbsp;</div>

	</div>
	<div class="paginator" id="paginator"></div>
	<script type="text/javascript">
		paginator = new Paginator(
			"paginator",
			{$ban_page.max_page},
			20,
			{$ban_page.current},
			"?site="
		);
	</script>
</div>