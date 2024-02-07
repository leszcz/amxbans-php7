{if $msg<>""}
	<div class="success">{"$msg"|lang}</div>
{/if}
<div class="main">
	<div class="post">
		{if $smarty.session.bans_add=="yes"}
			<form method="post">
				<table> 
					<tr class="title">
						<td style="width:250px;" class="fat">{"_ADDBAN"|lang}</th> 
						<td>&nbsp;</th>
					</tr>
					<tr class="info"> 
						<td class="b">{"_NICKNAME"|lang}</td> 
						<td><input type="text" size="40" name="name" {if $inputs.name != ""}value="{$inputs.name}"{/if}/></td> 
					</tr> 
					<tr class="info"> 
						<td class="b">{"_STEAMID"|lang}</td> 
						<td><input type="text" size="40" name="steamid" /></td> 
					</tr> 
					<tr class="info"> 
						<td class="b">{"_IP"|lang}</td> 
						<td><input type="text" size="40" name="ip" {if $inputs.ip != ""}value="{$inputs.ip}"{/if}/></td>  
					</tr> 
					<tr class="info"> 
						<td class="b">{"_BANTYPE"|lang}</td> 
						<td>
							<select name="ban_type">{html_options output=$banby_output values=$banby_values selected=$inputs.type}</select>
						</td> 
					</tr> 
					<tr class="info"> 
						<td class="b">{"_REASON"|lang}</td> 
						<td>
							<select name="ban_reason">{html_options output=$reasons values=$reasons selected=$inputs.reason}</select>
								{"_OR"|lang} <br /><input type="checkbox" name="reasoncheck" {if $inputs.reason_custom == 1}checked{/if}/>
								{"_REASON"|lang}: <input type="text" size="30" name="user_reason" {if $inputs.reason_custom == 1}value="{$inputs.reason}"{/if}/>
						</td> 
					</tr> 
					<tr class="info"> 
						<td class="b">{"_BANLENGHT"|lang}</td> 
						<td>
							<input type="text" size="8" name="ban_length" {if $inputs.length > 0}value="{$inputs.length}"{/if}/> {"_MINS"|lang} 
								{"_OR"|lang} <br /><input type="checkbox" name="perm" {if $inputs.length == 0}checked{/if}/> {"_PERMANENT"|lang}
						</td> 
					</tr> 
				</table>
				<div class="_right">
					<input type="submit" class="button" name="save" value="{"_ADD"|lang}" />
				</div> 
			</form>
		{else}
			{"_NOACCESS"|lang} !!
		{/if}
	</div>
	<div class="clearer">&nbsp;</div>
</div>