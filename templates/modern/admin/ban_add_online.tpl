{extends file="admin/layout.tpl"}
{block name=admin}
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
  <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_ADDBANONLINE"|lang}</h1>
  <form method="get" action="admin.php" class="flex gap-2">
    <input type="hidden" name="site" value="ban_add_online">
    <select name="server" class="input w-64" aria-label="{"_SELECTSERVER"|lang}">
      {foreach $servers as $s}<option value="{$s.id}" {if $server && $server.id == $s.id}selected{/if}>{$s.hostname}</option>{/foreach}
    </select>
    <button class="btn btn-secondary">{icon name="arrow-path" class="size-4"}{"_REFRESH"|lang}</button>
  </form>
</div>

{if !$server}
  <div class="card p-12 text-center muted">{"_NOSERVERS"|lang}</div>
{elseif $error}
  <div class="alert alert-error">{icon name="signal-slash" class="size-5 shrink-0"}<p>{$error|lang}</p></div>
{else}
  <section class="card">
    <div class="card-header"><h2 class="card-title">{icon name="users" class="size-5 text-brand-600"}{$server.hostname}</h2><span class="badge badge-zinc">{$players|count}</span></div>
    <div class="table-wrap">
      <table class="table table-hover">
        <thead><tr><th>#</th><th>{"_NAME"|lang}</th><th>{"_STEAMID"|lang}</th>{if $perms.ip_view == 'yes'}<th>{"_IP"|lang}</th>{/if}<th>{"_STATUSNAME"|lang}</th><th></th></tr></thead>
        <tbody>
          {foreach $players as $p}
            <tr>
              <td class="muted tabular-nums">{$p.userid}</td>
              <td class="font-medium">{if $p.country.code}<img src="{$p.country.code|flag}" alt="" class="mr-1.5 inline h-3 w-4 pixel">{/if}{$p.name}{if $p.immunity} <span class="badge badge-blue">immunity</span>{/if}</td>
              <td class="font-mono text-xs">{$p.steamid}</td>
              {if $perms.ip_view == 'yes'}<td class="font-mono text-xs">{$p.ip}</td>{/if}
              <td><span class="badge badge-zinc">{$p.status_name|lang}</span></td>
              <td class="text-right">
                {if $p.status == 0}
                  <div x-data="modal">
                    <button type="button" class="btn btn-danger btn-sm" x-on:click="show">{icon name="no-symbol" class="size-4"}{"_BAN"|lang} / {"_KICK"|lang}</button>
                    <template x-teleport="body">
                      <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-on:keydown.escape.window="hide">
                        <div class="modal-backdrop" x-on:click="hide"></div>
                        <form method="post" class="modal-panel max-w-2xl text-left" x-trap.noscroll="open">
                          {csrf}
                          <input type="hidden" name="userid" value="{$p.userid}"><input type="hidden" name="name" value="{$p.name}">
                          <input type="hidden" name="steamid" value="{$p.steamid}"><input type="hidden" name="ip" value="{$p.ip}">
                          <div class="card-header"><h3 class="card-title">{$p.name}</h3><span class="font-mono text-xs muted">{$p.steamid}</span></div>
                          <div class="grid gap-4 p-5 sm:grid-cols-2">
                            <div class="sm:col-span-2"><span class="label">{"_BANTYPE"|lang}</span>
                              <div class="flex gap-3">
                                <label class="flex items-center gap-2 text-sm"><input type="radio" name="ban_type" value="S" checked class="accent-brand-600"> {"_STEAMID"|lang}</label>
                                <label class="flex items-center gap-2 text-sm"><input type="radio" name="ban_type" value="SI" class="accent-brand-600"> {"_STEAMID&IP"|lang}</label>
                              </div></div>
                            {include file="partials/ban_fields.tpl"}
                          </div>
                          <div class="flex justify-end gap-2 border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
                            <button type="button" class="btn btn-ghost" x-on:click="hide">{"_CANCEL"|lang}</button>
                            <button name="action" value="kick" class="btn btn-secondary" data-confirm="{"_KICKPLAYER"|lang}">{icon name="bolt" class="size-4"}{"_KICK"|lang}</button>
                            <button name="action" value="ban" class="btn btn-danger" data-confirm="{"_BANPLAYER"|lang}">{icon name="no-symbol" class="size-4"}{"_BAN"|lang}</button>
                          </div>
                        </form>
                      </div>
                    </template>
                  </div>
                {/if}
              </td>
            </tr>
          {foreachelse}
            <tr><td colspan="6" class="py-10 text-center muted">{"_NOPLAYERS"|lang}</td></tr>
          {/foreach}
        </tbody>
      </table>
    </div>
  </section>
{/if}
{/block}
