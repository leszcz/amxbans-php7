
		<td id="main" valign="top">
		{if $smarty.session.amxadmins_view == "yes"}
			<span class="title">{"_VERSION"|lang}</span>
			<table width="95%"><tr><td>
				<table border="1" width="100%">
				<form method="POST">
				<table border="1" width="100%">
					<tr class="htabletop">
						<td colspan="4"><b>{"_WEBVERSIONINFO"|lang}</b></td>
					</tr>
					<tr class="htablebottom">
						<td>{"_WEB"|lang}</td>
						<td width="20%">{"_VERSION_CURRENT"|lang}</td>
						<td width="20%">{"_VERSION_RELEASE"|lang}</td>
						<td width="20%">{"_VERSION_BETA"|lang}</td>
					</tr>
					<tr class="settings_line">
						<td><b>{"_YOURWEB"|lang}</b></td>
						<td>
							{if $version_web<$version_db_web.release && $version_db_web.recommended_to>=$version_web}
								<span style="color:orange;font-weight:bold">{$version_web}</span>
								{assign var="web" value=true}
								<img src="images/warning.gif" title="{"_UPDATE_RECOMMENDED"|lang}" />
							{else}
								<span style="color:green;font-weight:bold">{$version_web}</span>
								<img src="images/success.gif" title="{"_UPDATE_NOTNEEDED"|lang}" />
							{/if}
						</td>
						<td>{$version_db_web.release}</td>
						<td>{$version_db_web.beta}</td>
					</tr>
				</table>
				<br />
			</table>
			<table width="95%"><tr><td width="49%" valign="top">
				<form method="POST">
				<table border="1" width="100%">
					<tr class="htabletop">
						<td colspan="4"><b>{"_PLUGINVERSIONINFO"|lang}</b></td>
					</tr>
					<tr class="htablebottom">
						<td>{"_SERVER"|lang}</td>
						<td width="20%">{"_VERSION_CURRENT"|lang}</td>
						<td width="20%">{"_VERSION_RELEASE"|lang}</td>
						<td width="20%">{"_VERSION_BETA"|lang}</td>
					</tr>
					{if $server_count == 0}
						<tr>
							<td align="center" colspan="2">&nbsp;</td>
							<td>{$version_db_plugin.release}</td>
							<td>{$version_db_plugin.beta}</td>
						</tr>
					{else}
						{foreach from=$version_server item=version_server}
						<tr>
							<td title="{$version_server.address}">{$version_server.hostname}</td>
							<td>
								{if $version_server.version<$version_db_plugin.release && $version_db_plugin.recommended_to>=$version_server.version}
									<span style="color:orange;font-weight:bold">{$version_server.version}</span>
									{assign var="plugin" value=true}
									<img src="images/warning.gif" title="{"_UPDATE_RECOMMENDED"|lang}" />
								{else}
									<span style="color:green;font-weight:bold">{$version_server.version}</span>
									<img src="images/success.gif" title="{"_UPDATE_NOTNEEDED"|lang}" />
								{/if}
							</td>
							<td>{$version_db_plugin.release}</td>
							<td>{$version_db_plugin.beta}</td>
						</tr>
						{/foreach}
					{/if}
				</table>
				<br />
			</table>
			<table width="95%" style="margin:0;"><tr><td width="49%" valign="top">
				<form method="POST">
				<table border="1" width="100%" style="margin:0;">
					<tr class="htable">
						<td colspan="4">{"_LASTCHANGELOG"|lang}</td>
					</tr>
					<tr class="settings_line">
						<td width="15%" valign="top"><b>{"_WEB"|lang}</b></td>
						<td>{$version_db_web.changelog|bbcode2html}</td>
					</tr>
					<tr class="settings_line">
						<td width="15%" valign="top"><b>{"_PLUGIN"|lang}</b></td>
						<td>{$version_db_plugin.changelog|bbcode2html}</td>
					</tr>
				</table>
				<br />
			</table>
			{if $web==true}<center><div class="notice"><img src="images/warning.gif" alt="" border="0" /> <a href="{$version_db_web.url}" target="_blank">{"_WEBUPDATE_RECOMMENDED"|lang}</a></div></center>{/if}
			{if $plugin==true}<center><div class="notice"><img src="images/warning.gif" alt="" border="0" /> <a href="{$version_db_plugin.url}" target="_blank">{"_PLUGINUPDATE_RECOMMENDED"|lang}</a></div></center>{/if}
			{foreach from=$error item=error}
				<div class="error">{$error|lang}</div>
			{/foreach}
			<br />
		{else}
			<center><div class="admin_msg">{"_NOACCESS"|lang}</div></center>
		{/if}
		</td>
	</tr>
</table>