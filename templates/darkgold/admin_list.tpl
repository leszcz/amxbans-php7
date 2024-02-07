<script src="templates/{$design}/js/loading.js"></script>
<div class="main">
	<div class="admins">
		<div class="_right">
			<h1><a href="#" id="serv">Servers</a></h1>
		</div>
		<table>
			<tr class="title">
				<td style="width:5px;">&nbsp;</td>
				<td style="width:60px;">{"_NICKNAME"|lang}</td>
				<td style="width:30px;">ICQ</td> 
				<!-- <td style="width:150px;">{"_STEAMIDIPNAME"|lang}</td> -->
				<td style="width:150px;">{"_ACCESS"|lang}</td>
				<td style="width:150px;">{"_ADMINSINCE"|lang}</td>
				<td style="width:150px;">{"_ADMINTO"|lang}</td>
				<td style="width:18px;">&nbsp;</td>
			</tr>
			<!-- Start Loop -->
			{foreach from=$admins item=admins}
				<tr class="list"> 
					<td><a href="http://steamcommunity.com/profiles/{$admins.comid}" target="_blank"><img src="images/Steam.png" /></a></td>
					<td>{$admins.nickname}</td>
					<td>
						{if $admins.icq}
							{$admins.icq}
						{else}
							<i>{"_NOTAVAILABLE"|lang}</i>
						{/if}
					</td>
					<!-- <td>{$admins.steamid}</td> -->
					<td>{$admins.access}</td> 
					<td>{$admins.created|date_format:"%d.%m.%Y - %T"}</td> 
					<td><em>
						{if $admins.expired=="0"}
							<i>{"_UNLIMITED"|lang}</i>
						{else}
							{$admins.expired|date_format:"%d.%m.%Y - %T"}
						{/if}
					</em></td>
					<td><a href="admin_ajax.php?id={$admins.aid}" rel="facebox"><img src="images/page.png" border="0" title="{"_DETAILS"|lang}"/></a></td> 
				</tr>
			{/foreach}
			<!-- Stop Loop -->
		</table> 
	</div>

	<div class="servers" style="display: none">
		<div class="_right">
			<h1><a id="adm" href="#">Admins</a></h1>
		</div>
		<table>
			{foreach from=$admin_list item=server}
				<tr onclick="NewToggleLayer('info_{$server.id}');" class="list">
					<td style="width:20px;"><img alt="{$server.gametype}" title="{$server.gametype}" src="images/games/{$server.gametype}.gif" /></td>
					<td>{$server.hostname}</td>
				</tr>
				<tr id="info_{$server.id}" style="display: none">
					<td colspan="2">
						<div style="display: none;" align="center">
							<table class="details">
								{foreach from=$server.admins item=admin}
									<tr class="list"> 
										<td style="width:20px;"><a href="http://steamcommunity.com/profiles/{$admin.comid}" target="_blank"><img src="images/Steam.png" /></a></td>
										<td style="width:60px;">{$admin.nickname}</td>
										<td style="width:30px;">
											{if $admin.icq}
												{$admin.icq}
											{else}
												<i>{"_NOTAVAILABLE"|lang}</i>
											{/if}
										</td>
										<!-- <td style="width:150px;">{$admin.steamid}</td> -->
										<td style="width:150px;">{$admin.access}</td> 
										<td style="width:150px;">{$admin.created|date_format:"%d.%m.%Y - %T"}</td> 
										<td style="width:150px;"><em>
											{if $admin.expired=="0"}
												<i>{"_UNLIMITED"|lang}</i>
											{else}
												{$admin.expired|date_format:"%d.%m.%Y - %T"}
											{/if}
										</em></td>
										<td style="width:18px;"><a href="admin_ajax.php?id={$admin.aid}" rel="facebox"><img src="images/page.png" border="0" title="{"_DETAILS"|lang}"/></a></td> 
									</tr>
								{/foreach}
							</table>
						</div>
					</td>
				</tr>
			{/foreach}
		</table>
	</div>

	<div class="post _center">
		<form metdod="post">
			<input type="button" class="button" name="showflags" value="{"_INFO_ACCESS"|lang}" onclick="$('#info_access').slideToggle('slow');"/>
		</form>
	</div>
	<div id="info_access" class="post" style="display:none;">
		<br />
		<table> 
			<tr class="title"> 
				<td>{"_ACCESSPERMS"|lang}</td> 
				<td style="width:350px;">{"_ACCESSFLAGS"|lang}</td>
			</tr> 
			<tr class="smallfont">
				<td>
					{"_ACCESS_FLAGS"|lang}
				</td> 
				<td class="vtop">
					{"_FLAG_FLAGS"|lang}
				</td> 
			</tr>
		</table> 
	</div>
	<div class="clearer">&nbsp;</div>
</div>