{extends file="layout.tpl"}
{* Ban search (search.php). Variables: $criteria, $searched, $results (active/expired), $admins, $servers. *}
{block name=content}
<h1 class="mb-6 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_SEARCH"|lang}</h1>

<form method="get" action="search.php" class="card mb-8">
  <div class="grid gap-4 p-5 sm:grid-cols-2 lg:grid-cols-4">
    <div><label class="label" for="s-nick">{"_NICKNAME"|lang}</label><input id="s-nick" name="nick" value="{$criteria.nick}" class="input"></div>
    <div><label class="label" for="s-sid">{"_STEAMID"|lang}</label><input id="s-sid" name="steamid" value="{$criteria.steamid}" class="input font-mono" placeholder="STEAM_0:1:…"></div>
    {if $perms.ip_view == 'yes'}<div><label class="label" for="s-ip">{"_IP"|lang}</label><input id="s-ip" name="ip" value="{$criteria.ip}" class="input font-mono"></div>{/if}
    <div><label class="label" for="s-reason">{"_REASON"|lang}</label><input id="s-reason" name="reason" value="{$criteria.reason}" class="input"></div>
    <div><label class="label" for="s-date">{"_DATE"|lang}</label><input id="s-date" type="date" name="date" value="{$criteria.date}" class="input"></div>
    <div><label class="label" for="s-admin">{"_ADMIN"|lang}</label>
      <select id="s-admin" name="admin" class="input">
        <option value="">—</option>
        {foreach $admins as $a}<option value="{$a.admin_id}" {if $criteria.admin == $a.admin_id}selected{/if}>{if $a.nickname}{$a.nickname}{else}{$a.admin_nick}{/if}</option>{/foreach}
      </select></div>
    <div><label class="label" for="s-server">{"_SERVER"|lang}</label>
      <select id="s-server" name="server" class="input">
        <option value="">—</option>
        <option value="website" {if $criteria.server == 'website'}selected{/if}>{"_WEB"|lang}</option>
        {foreach $servers as $s}<option value="{$s.server_ip}" {if $criteria.server == $s.server_ip}selected{/if}>{$s.server_name}</option>{/foreach}
      </select></div>
    <div><label class="label" for="s-times">{"_PLAYERSWITH"|lang} … {"_MOREBANSHIS"|lang}</label><input id="s-times" type="number" min="0" name="times" value="{if $criteria.times}{$criteria.times}{/if}" class="input"></div>
  </div>
  <div class="flex justify-end gap-2 border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
    <a href="search.php" class="btn btn-ghost">{"_CLEAR"|lang}</a>
    <button class="btn btn-primary">{icon name="magnifying-glass" class="size-4"}{"_SEARCH"|lang}</button>
  </div>
</form>

{if $searched}
  <section class="card mb-6">
    <div class="card-header"><h2 class="card-title">{icon name="no-symbol" class="size-5 text-red-500"}{"_ACTIVEBANS"|lang} <span class="badge badge-red">{$results.active|count}</span></h2></div>
    {include file="partials/ban_rows.tpl" list=$results.active}
  </section>
  <section class="card">
    <div class="card-header"><h2 class="card-title">{icon name="clock" class="size-5 text-zinc-400"}{"_EXPIREDBANS"|lang} <span class="badge badge-zinc">{$results.expired|count}</span></h2></div>
    {include file="partials/ban_rows.tpl" list=$results.expired}
  </section>
{/if}
{/block}
