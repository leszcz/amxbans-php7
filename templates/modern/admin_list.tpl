{extends file="layout.tpl"}
{block name=content}
<h1 class="mb-6 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_ADMLIST"|lang}</h1>
<div class="grid gap-6 lg:grid-cols-2">
  {foreach $servers as $server}
    <section class="card overflow-hidden">
      <div class="card-header">
        <h2 class="card-title"><img src="{$server.gametype|gameicon}" alt="" class="size-5 pixel">{$server.hostname}</h2>
        <span class="badge badge-zinc">{$server.admins|count}</span>
      </div>
      <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
        {foreach $server.admins as $a}
          <li x-data="{ open: false }">
            <button type="button" class="flex w-full items-center gap-3 px-5 py-3 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/40" x-on:click="open = !open">
              <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700 dark:bg-brand-500/15 dark:text-brand-300">{$a.display|initial}</span>
              <span class="min-w-0 flex-1">
                <span class="block truncate font-medium text-zinc-900 dark:text-white">{$a.display}</span>
                <span class="block truncate font-mono text-xs muted">{$a.steamid}</span>
              </span>
              {if $a.expired > 0}<span class="badge badge-amber" title="{"_ADMINEXPIRATION"|lang}">{$a.expired|datetime:'date'}</span>{else}<span class="badge badge-green">{"_UNLIMITED"|lang}</span>{/if}
              <span class="text-zinc-400 transition" x-bind:class="open ? 'rotate-180' : ''">{icon name="chevron-down" class="size-4"}</span>
            </button>
            <dl class="dl-grid bg-zinc-50 px-5 py-4 dark:bg-zinc-950/40" x-show="open" x-cloak>
              <dt>{"_ACCESS"|lang}</dt><dd class="font-mono">{if $a.custom_flags}{$a.custom_flags}{else}{$a.access}{/if}</dd>
              <dt>{"_ADMINSINCE"|lang}</dt><dd>{$a.created|datetime:'date'}</dd>
              <dt>{"_ADMINTO"|lang}</dt><dd>{if $a.expired > 0}{$a.expired|datetime:'date'}{else}{"_UNLIMITED"|lang}{/if}</dd>
              {if $a.steam_url}<dt>Steam</dt><dd><a class="link" href="{$a.steam_url}" target="_blank" rel="noopener noreferrer">{"_STEAMPROFILE"|lang}</a></dd>{/if}
            </dl>
          </li>
        {/foreach}
      </ul>
    </section>
  {foreachelse}
    <div class="card p-12 text-center muted lg:col-span-2">{"_NOADMINS"|lang}</div>
  {/foreach}
</div>
{/block}
