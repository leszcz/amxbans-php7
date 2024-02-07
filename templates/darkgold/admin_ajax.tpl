<script src="include/steamprofile/steamprofile.js"></script>
<link rel="stylesheet" href="templates/darkgold/css/style.css" />

<table>
	<tr style="border-bottom: 1px solid #333;"> 
		<td><h1>Info</h1> </td>
	</tr> 
	<tr><td colspan="2"><br /></td></tr>
	<tr> 
		<td style="font-weight: bold">{"_NICKNAME"|lang}</td>
		<td>{$admin.nickname}</td>
	</tr>
	<tr> 
		<td style="font-weight: bold">ICQ</td>
		<td>
			{if $admin.icq}
				<img src="http://status.icq.com/online.gif?icq={$admin.icq}&img=27"> {$admin.icq}
			{else}
				{"_NOTAVAILABLE"|lang}
			{/if}
		</td>
	</tr>
<!-- 	<tr>
		<td style="font-weight: bold">{"_STEAMIDIPNAME"|lang}</td>
		<td>{$admin.steamid}</td>
	</tr> -->
	<tr> 
		<td style="font-weight: bold">{"_ACCESS"|lang}</td>
		<td>{$admin.access}</td>
	</tr>
	<tr> 
		<td style="font-weight: bold">{"_ADMINSINCE"|lang}</td>
		<td>{$admin.created|date_format:"%d.%m.%Y - %T"}</td>
	</tr>
	<tr> 
		<td style="font-weight: bold">{"_ADMINTO"|lang}</td>
		<td>{if $admin.expired=="0"}<i>{"_UNLIMITED"|lang}</i>{else}{$admin.expired|date_format:"%d.%m.%Y - %T"}{/if}</td>
	</tr>
</table>
{if $admin.steamid}
	<table>
		<tr style="border-bottom: 1px solid #333;"> 
			<td>
				<h1>Steam</h1>
			</td>
		</tr> 
		<tr> 
			<td>
				<br />
				<div class="steamprofile" title="{$admin.steamid}"></div>
			</td>
		</tr>
	</table>
{/if}
<table>
	<tr style="border-bottom: 1px solid #333;"> 
		<td>
			<h1>Servers</h1>
		</td>
	</tr> 
	<tr> 
		<td>
			<br />
			{foreach from=$servers item=server}
				{$server}<br />
			{/foreach}
		</td>
	</tr>
</table>