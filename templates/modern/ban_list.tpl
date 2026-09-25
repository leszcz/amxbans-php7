{extends file="layout.tpl"}
{block name=content}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
  <div>
    <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_BANLIST"|lang}</h1>
    <p class="muted mt-1 text-sm">{$stats.active} {"_ACTIVEBANS"|lang|lower} · {$stats.total} {"_BANSINDB"|lang|lower}</p>
  </div>
  <form method="get" action="ban_list.php" class="flex w-full gap-2 sm:w-auto" role="search">
    {if $show == 'expired'}<input type="hidden" name="show" value="expired">{/if}
    <div class="relative flex-1 sm:w-72">
      <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400">{icon name="magnifying-glass" class="size-4"}</span>
      <input type="search" name="q" value="{$q}" class="input pl-9" placeholder="{"_QUICKSEARCH"|lang}" aria-label="{"_SEARCH"|lang}">
    </div>
    <button class="btn btn-secondary">{"_SEARCH"|lang}</button>
  </form>
</div>

{if $visitor_ban}
  <div class="alert alert-error mb-6">
    {icon name="no-symbol" class="size-5 shrink-0"}
    <p><strong>{"_YOUAREBANNED"|lang}</strong> <a class="link" href="ban_list.php?bid={$visitor_ban}">{"_DETAILS"|lang}</a></p>
  </div>
{/if}

<div class="card overflow-hidden">
  <div class="card-header">
    <div class="flex gap-1 rounded-lg bg-zinc-100 p-1 dark:bg-zinc-800">
      <a href="ban_list.php{if $q}?q={$q|escape:'url'}{/if}" class="rounded-md px-3 py-1.5 text-sm font-medium {if $show == 'active'}bg-white text-zinc-900 shadow-sm dark:bg-zinc-950 dark:text-white{else}text-zinc-500 hover:text-zinc-900 dark:hover:text-white{/if}">{"_ACTIVEBANS"|lang}</a>
      <a href="ban_list.php?show=expired{if $q}&amp;q={$q|escape:'url'}{/if}" class="rounded-md px-3 py-1.5 text-sm font-medium {if $show == 'expired'}bg-white text-zinc-900 shadow-sm dark:bg-zinc-950 dark:text-white{else}text-zinc-500 hover:text-zinc-900 dark:hover:text-white{/if}">{"_EXPIREDBANS"|lang}</a>
    </div>
    <p class="muted text-sm">{$pager.total} {"_BANS"|lang|lower}</p>
  </div>

  <div class="table-wrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th class="w-10"><span class="sr-only">{"_MOD"|lang}</span></th>
          <th class="hidden sm:table-cell">{"_DATE"|lang}</th>
          <th>{"_PLAYER"|lang}</th>
          <th class="hidden lg:table-cell">{"_ADMIN"|lang}</th>
          <th class="hidden md:table-cell">{"_REASON"|lang}</th>
          <th>{"_LENGHT"|lang}</th>
          {if $cols.comments}<th class="hidden text-center xl:table-cell" title="{"_BL_COMMENTS"|lang}">{icon name="chat-bubble-left-right" class="mx-auto size-4"}</th>{/if}
          {if $cols.files}<th class="hidden text-center xl:table-cell" title="{"_BL_FILES"|lang}">{icon name="paper-clip" class="mx-auto size-4"}</th>{/if}
          {if $cols.kicks}<th class="hidden text-center xl:table-cell" title="{"_BL_KICKS"|lang}">{icon name="bolt" class="mx-auto size-4"}</th>{/if}
          <th class="w-10"></th>
        </tr>
      </thead>
      {foreach $bans as $ban}
        <tbody x-data="{ open: false }" class="border-b border-zinc-100 last:border-0 dark:border-zinc-800/70">
          <tr class="cursor-pointer" x-on:click="open = !open">
            <td><img src="{$ban.mod|gameicon}" alt="{$ban.mod}" title="{if $ban.website}{"_WEB"|lang}{else}{$ban.mod}{/if}" class="size-5 pixel"></td>
            <td class="hidden whitespace-nowrap tabular-nums muted sm:table-cell">{$ban.created|datetime:'date'}</td>
            <td>
              <div class="flex items-center gap-2">
                <img src="{$ban.cc|flag}" alt="{$ban.cc}" title="{$ban.cn}" class="h-3 w-4 shrink-0 pixel">
                <span class="max-w-[11rem] truncate font-medium text-zinc-900 sm:max-w-none dark:text-white">{$ban.player_nick}</span>
                {if $ban.previous > 0}<span class="badge badge-amber" title="{"_TOTALEXPBANS"|lang}">+{$ban.previous}</span>{/if}
              </div>
            </td>
            <td class="hidden lg:table-cell">{$ban.admin_name}</td>
            <td class="hidden max-w-xs truncate md:table-cell" title="{$ban.ban_reason}">{$ban.ban_reason}</td>
            <td class="whitespace-nowrap">
              {if $ban.permanent}<span class="badge badge-red">{"_PERMANENT"|lang}</span>
              {elseif $ban.unbanned}<span class="badge badge-green">{"_UNBANNED"|lang}</span>
              {else}<span class="badge badge-zinc">{$ban.ban_length|banlength}</span>{/if}
            </td>
            {if $cols.comments}<td class="hidden text-center tabular-nums xl:table-cell muted">{$ban.comment_count}</td>{/if}
            {if $cols.files}<td class="hidden text-center tabular-nums xl:table-cell muted">{$ban.file_count}</td>{/if}
            {if $cols.kicks}<td class="hidden text-center tabular-nums xl:table-cell muted">{$ban.ban_kicks}</td>{/if}
            <td class="text-zinc-400"><span class="block transition" x-bind:class="open ? 'rotate-180' : ''">{icon name="chevron-down" class="size-4"}</span></td>
          </tr>
          <tr x-show="open" x-cloak>
            <td colspan="10" class="bg-zinc-50 dark:bg-zinc-950/40">
              <div class="grid gap-6 p-2 md:grid-cols-[1fr_auto]">
                <dl class="dl-grid">
                  <dt>{"_NICKNAME"|lang}</dt><dd>{$ban.player_nick}</dd>
                  {if $ban.has_steamid}
                    <dt>{"_STEAMID"|lang}</dt>
                    <dd class="font-mono">{$ban.player_id}{if $ban.steam_url} · <a class="link" href="{$ban.steam_url}" target="_blank" rel="noopener noreferrer">{"_STEAMPROFILE"|lang}</a>{/if}</dd>
                  {/if}
                  <dt>{"_IP"|lang}</dt>
                  <dd>{if $perms.ip_view == 'yes'}<span class="font-mono">{$ban.player_ip|default:"—"}</span>{else}<span class="muted italic">{"_HIDDEN"|lang}</span>{/if}{if $ban.cn} · {$ban.cn}{/if}</dd>
                  <dt>{"_BANTYPE"|lang}</dt><dd>{if $ban.ban_type == 'SI'}{"_STEAMID&IP"|lang}{else}{"_STEAMID"|lang}{/if}</dd>
                  <dt>{"_REASON"|lang}</dt><dd>{$ban.ban_reason}</dd>
                  <dt>{"_INVOKED"|lang}</dt><dd>{$ban.created|datetime}</dd>
                  <dt>{"_EXPIRES"|lang}</dt>
                  <dd>
                    {if $ban.permanent}<span class="font-semibold text-red-600 dark:text-red-400">{"_NOTAPPLICABLE"|lang}</span>
                    {elseif $ban.unbanned}{"_UNBANNED"|lang}
                    {else}{$ban.ban_end|datetime} <span class="muted">({$ban.ban_end|relative})</span>{/if}
                  </dd>
                  <dt>{"_BANBY"|lang}</dt><dd>{$ban.admin_nick}{if $ban.nickname && $ban.nickname != $ban.admin_nick} <span class="muted">({$ban.nickname})</span>{/if}</dd>
                  <dt>{"_BANON"|lang}</dt><dd>{if $ban.website}{"_WEB"|lang}{else}{$ban.server_name}{/if}</dd>
                  <dt>{"_TOTALEXPBANS"|lang}</dt><dd>{$ban.previous}</dd>
                </dl>
                <div class="flex items-start">
                  <a href="ban_list.php?bid={$ban.bid}" class="btn btn-primary btn-sm">{icon name="eye" class="size-4"}{"_DETAILS"|lang}</a>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      {foreachelse}
        <tbody><tr><td colspan="10" class="py-16 text-center muted">{icon name="shield-check" class="mx-auto mb-2 size-10 text-zinc-300 dark:text-zinc-700"}{"_NO_BANS"|lang}</td></tr></tbody>
      {/foreach}
    </table>
  </div>
  {include file="partials/pagination.tpl"}
</div>
{/block}
