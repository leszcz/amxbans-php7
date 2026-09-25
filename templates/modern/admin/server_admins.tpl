{extends file="admin/layout.tpl"}
{* Admin assignment per server (admin_sm_sa.php). Variables: $servers, $server, $admins. *}
{block name=admin}
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
  <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_SERVERADMINSETTINGS"|lang}</h1>
  <form method="get" action="admin.php" class="flex gap-2">
    <input type="hidden" name="site" value="sm_sa">
    <select name="server" class="input w-64" aria-label="{"_SELECTSERVER"|lang}">{foreach $servers as $s}<option value="{$s.id}" {if $server && $server.id == $s.id}selected{/if}>{$s.hostname}</option>{/foreach}</select>
    <button class="btn btn-secondary">{"_SHOW"|lang}</button>
  </form>
</div>
{if !$server}
  <div class="card p-12 text-center muted">{"_NOSERVERS"|lang}</div>
{else}
<form method="post" class="card">
  {csrf}
  <div class="card-header"><h2 class="card-title"><img src="{$server.gametype|gameicon}" alt="" class="size-5 pixel">{$server.hostname}</h2></div>
  <div class="table-wrap">
    <table class="table table-hover">
      <thead><tr><th class="w-12">{"_ACTIV"|lang}</th><th>{"_NICKNAME"|lang}</th><th class="hidden md:table-cell">{"_ACCESS"|lang}</th><th>{"_CUSTOMFLAGS"|lang}</th><th>{"_STATICBANTIME"|lang}</th></tr></thead>
      <tbody>
        {foreach $admins as $a}
          <tr>
            <td><input type="checkbox" name="active[]" value="{$a.id}" class="checkbox" {if $a.active}checked{/if} aria-label="{"_ACTIV"|lang}" {if $perms.amxadmins_edit != 'yes'}disabled{/if}></td>
            <td><p class="font-medium">{$a.nickname|default:$a.username}</p><p class="font-mono text-xs muted">{$a.steamid}</p></td>
            <td class="hidden font-mono text-xs md:table-cell">{$a.access}</td>
            <td><input name="custom_flags[{$a.id}]" value="{$a.custom_flags}" class="input w-40 font-mono text-xs" placeholder="{$a.access}" maxlength="23" aria-label="{"_CUSTOMFLAGS"|lang}"></td>
            <td><select name="static_bantime[{$a.id}]" class="input w-24" aria-label="{"_STATICBANTIME"|lang}"><option value="yes" {if $a.use_static_bantime != 'no'}selected{/if}>{"_YES"|lang}</option><option value="no" {if $a.use_static_bantime == 'no'}selected{/if}>{"_NO"|lang}</option></select></td>
          </tr>
        {foreachelse}
          <tr><td colspan="5" class="py-10 text-center muted">{"_NOADMINS"|lang}</td></tr>
        {/foreach}
      </tbody>
    </table>
  </div>
  {if $perms.amxadmins_edit == 'yes'}
    <div class="flex justify-end border-t border-zinc-200 px-5 py-3 dark:border-zinc-800"><button name="action" value="save" class="btn btn-primary">{icon name="check" class="size-4"}{"_SAVE"|lang}</button></div>
  {/if}
</form>
{/if}
{/block}
