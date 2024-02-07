
		<td id="main" valign="top">
		{if $smarty.session.amxadmins_view == "yes"}
			<span class="title">{"_MENUUPDATE"|lang}</span>
			<table width="95%"><tr><td>
				<table border="1" width="100%">
				<form method="POST">
				<table border="1" width="100%">
					<tr class="settings_line">
						{if $version_web<$version_db_web.release} 
							<div class="error"><b>{"_WEBVERSIONINFO"|lang}:</b><br />Your website version is outdated. ({$version_web})<br />Please run this script to update your AMXBans Version.</div>
							<br />Hellow!
						{else}
							<div class="success"><b>{"_WEBVERSIONINFO"|lang}:</b><br />Your website version is up-to-date. ({$version_web})<br />There's no need to run this script.</div>
						{/if}
					</tr>
				</table>
			</table>

			{foreach from=$error item=error}
				<div class="error">{$error|lang}</div>
			{/foreach}
		{else}
			<center><div class="admin_msg">{"_NOACCESS"|lang}</div></center>
		{/if}
		</td>
	</tr>
</table>