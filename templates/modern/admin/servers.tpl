{extends file="admin/layout.tpl"}
{* Server settings + RCON console (admin_sm_sv.php). Variables: $servers (without rcon, with has_rcon), $active, $reason_sets, $rcon_presets, $output, $motd_url. *}
{block name=admin}
<h1 class="mb-6 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_SERVERSETTINGS"|lang}</h1>

{if !$servers}
  <div class="card p-12 text-center muted">{"_NOSERVERS_HINT"|lang}</div>
{else}
<div class="mb-6 flex flex-wrap gap-2">
  {foreach $servers as $s}
    <a href="admin.php?site=sm_sv&amp;server={$s.id}" class="btn {if $s.id == $active}btn-primary{else}btn-secondary{/if}"><img src="{$s.gametype|gameicon}" alt="" class="size-4 pixel">{$s.hostname}</a>
  {/foreach}
</div>

{foreach $servers as $s}{if $s.id == $active}
<div class="grid gap-6 xl:grid-cols-2">
  <form method="post" class="card">
    {csrf}<input type="hidden" name="sid" value="{$s.id}">
    <div class="card-header">
      <h2 class="card-title">{icon name="server" class="size-5 text-brand-600"}{$s.hostname}</h2>
      <span class="font-mono text-xs muted">{$s.address}</span>
    </div>
    <div class="grid gap-4 p-5 sm:grid-cols-2">
      <div><span class="label">{"_MOD"|lang}</span><p class="text-sm">{$s.gametype}</p></div>
      <div><span class="label">{"_VERSION"|lang}</span><p class="text-sm">{$s.amxban_version|default:"—"} <span class="muted">· {"_LASTSEEN"|lang} {$s.timestamp|relative}</span></p></div>
      <div class="sm:col-span-2">
        <label class="label" for="rcon">{"_RCONPW"|lang}</label>
        <input id="rcon" type="password" name="rcon" class="input" autocomplete="new-password" maxlength="32" placeholder="{if $s.has_rcon}••••••••  ({"_UNCHANGED"|lang}){else}{"_NOTSET"|lang}{/if}">
        {if $s.has_rcon}<label class="mt-2 flex items-center gap-2 text-xs muted"><input type="checkbox" name="rcon_clear" value="1" class="checkbox"> {"_RCON_CLEAR"|lang}</label>{/if}
      </div>
      <div class="sm:col-span-2">
        <label class="label" for="motd">{"_MOTDURL"|lang}</label>
        <input id="motd" name="amxban_motd" class="input font-mono text-xs" value="{$s.amxban_motd}" maxlength="250">
        <p class="hint">{"_MOTD_SUGGESTED"|lang}: <span class="font-mono break-all">{$motd_url}</span></p>
      </div>
      <div><label class="label" for="delay">{"_MOTDDELAY"|lang}</label><input id="delay" type="number" min="0" max="60" name="motd_delay" class="input" value="{$s.motd_delay}"></div>
      <div><label class="label" for="tz">{"_TIMEZONEFIXX"|lang}</label>
        <select id="tz" name="timezone_fixx" class="input">{for $h=-12 to 12}<option value="{$h}" {if $s.timezone_fixx == $h}selected{/if}>{if $h > 0}+{/if}{$h} h</option>{/for}</select></div>
      <div><label class="label" for="rs">{"_REASONSSET"|lang}</label>
        <select id="rs" name="reasons" class="input"><option value="0">—</option>{foreach $reason_sets as $set}<option value="{$set.id}" {if $s.reasons == $set.id}selected{/if}>{$set.setname}</option>{/foreach}</select></div>
      <div class="flex items-end"><label class="flex items-center gap-2 text-sm"><input type="checkbox" name="amxban_menu" value="1" class="checkbox" {if $s.amxban_menu}checked{/if}> {"_SERVERMENU"|lang}</label></div>
    </div>
    <div class="flex justify-between gap-2 border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
      <button name="action" value="delete" class="btn btn-danger-ghost" data-confirm="{"_DELSERVER"|lang}">{icon name="trash" class="size-4"}{"_DELETE"|lang}</button>
      <button name="action" value="save" class="btn btn-primary">{icon name="check" class="size-4"}{"_SAVE"|lang}</button>
    </div>
  </form>

  <section class="card">
    <div class="card-header"><h2 class="card-title">{icon name="command-line" class="size-5 text-brand-600"}{"_SERVERRCON"|lang}</h2></div>
    <div class="space-y-4 p-5">
      {if !$s.has_rcon}<div class="alert alert-warning">{icon name="key" class="size-5 shrink-0"}<p>{"_NORCON"|lang}</p></div>{/if}
      <form method="post" class="flex flex-wrap gap-2">
        {csrf}<input type="hidden" name="sid" value="{$s.id}"><input type="hidden" name="action" value="rcon">
        {foreach $rcon_presets as $key => $cmd}
          <button name="command" value="{$key}" class="btn btn-secondary btn-sm" {if !$s.has_rcon}disabled{/if}>{$cmd.1|lang}</button>
        {/foreach}
      </form>
      <form method="post" class="flex gap-2">
        {csrf}<input type="hidden" name="sid" value="{$s.id}">
        <input name="custom" class="input font-mono" placeholder="amx_who" aria-label="{"_RCON_USERDEFINED"|lang}" {if !$s.has_rcon}disabled{/if}>
        <button name="action" value="rcon" class="btn btn-primary" {if !$s.has_rcon}disabled{/if}>{"_RCON_SEND"|lang}</button>
      </form>
      {if $output && $output.sid == $s.id}
        <div>
          <p class="mb-1 font-mono text-xs muted">&gt; {$output.command}</p>
          <pre class="console">{$output.response}</pre>
        </div>
      {/if}
    </div>
  </section>
</div>
{/if}{/foreach}
{/if}
{/block}
