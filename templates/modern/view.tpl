{extends file="layout.tpl"}
{block name=content}
<h1 class="mb-6 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_TITLEVIEW"|lang}</h1>

<div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
  <div class="stat"><span class="stat-icon bg-red-100 text-red-600 dark:bg-red-500/15 dark:text-red-400">{icon name="no-symbol" class="size-6"}</span><div><p class="text-2xl font-bold tabular-nums">{$stats.active}</p><p class="muted text-sm">{"_ACTIVEBANS"|lang}</p></div></div>
  <div class="stat"><span class="stat-icon bg-amber-100 text-amber-600 dark:bg-amber-500/15 dark:text-amber-400">{icon name="lock-closed" class="size-6"}</span><div><p class="text-2xl font-bold tabular-nums">{$stats.permanent}</p><p class="muted text-sm">{"_PERMANENT"|lang}</p></div></div>
  <div class="stat"><span class="stat-icon bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">{icon name="circle-stack" class="size-6"}</span><div><p class="text-2xl font-bold tabular-nums">{$stats.total}</p><p class="muted text-sm">{"_BANSINDB"|lang}</p></div></div>
  <div class="stat"><span class="stat-icon bg-brand-100 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">{icon name="users" class="size-6"}</span><div><p class="text-2xl font-bold tabular-nums">{$stats.admins}</p><p class="muted text-sm">{"_AMXADMINS"|lang}</p></div></div>
</div>

{if $latest}
  <a href="ban_list.php?bid={$latest.bid}" class="card mb-8 flex flex-wrap items-center gap-4 p-4 hover:border-brand-400">
    <span class="badge badge-red">{"_LATESTBAN"|lang}</span>
    <span class="font-medium">{$latest.player_nick}</span>
    <span class="muted text-sm">{$latest.ban_reason}</span>
    <span class="ml-auto muted text-sm">{$latest.created|relative}</span>
  </a>
{/if}

<div class="grid gap-6 lg:grid-cols-2">
  {foreach $servers as $s}
    <section class="card overflow-hidden" x-data="serverCard({$s.id})">
      <div class="card-header">
        <div class="flex min-w-0 items-center gap-3">
          <img src="{$s.gametype|gameicon}" alt="" class="size-6 pixel">
          <div class="min-w-0">
            <h2 class="truncate font-semibold text-zinc-900 dark:text-white"><span x-show="!data.name">{$s.hostname}</span><span x-text="data.name"></span></h2>
            <p class="font-mono text-xs muted">{$s.address}</p>
          </div>
        </div>
        <span x-show="loading" class="badge badge-zinc">{icon name="arrow-path" class="size-3.5 animate-spin"}…</span>
        <span x-show="!loading && online" x-cloak class="badge badge-green">{icon name="signal" class="size-3.5"}Online</span>
        <span x-show="!loading && !online" x-cloak class="badge badge-red">{icon name="signal-slash" class="size-3.5"}{"_SERVEROFFLINE"|lang}</span>
      </div>
      <div class="card-body" x-show="online" x-cloak>
        <dl class="grid grid-cols-2 gap-4 text-sm sm:grid-cols-4">
          <div><dt class="muted text-xs">{"_MAP"|lang}</dt><dd class="font-medium" x-text="data.map"></dd></div>
          <div><dt class="muted text-xs">{"_PLAYER"|lang}</dt><dd class="font-medium tabular-nums"><span x-text="data.players"></span> / <span x-text="data.max_players"></span></dd></div>
          <div><dt class="muted text-xs">{"_NEXTMAP"|lang}</dt><dd class="font-medium" x-text="data.nextmap || '—'"></dd></div>
          <div><dt class="muted text-xs">{"_TIMELEFT"|lang}</dt><dd class="font-medium" x-text="data.timeleft || '—'"></dd></div>
        </dl>
        <div class="mt-4 h-2 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800"><div class="h-full rounded-full bg-brand-500 transition-all" x-bind:style="{ width: fill }"></div></div>
        <div class="mt-4 flex flex-wrap gap-2">
          <a class="btn btn-primary btn-sm" href="steam://connect/{$s.address}">{icon name="bolt" class="size-4"}{"_CONNECT"|lang}</a>
          <button type="button" class="btn btn-secondary btn-sm" x-on:click="togglePlayers" x-bind:disabled="!players.length">{icon name="users" class="size-4"}{"_PLAYER"|lang} (<span x-text="players.length"></span>)</button>
          <button type="button" class="btn btn-ghost btn-sm" x-on:click="load" title="{"_REFRESH"|lang}">{icon name="arrow-path" class="size-4"}</button>
        </div>
        <div class="mt-4 overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-800" x-show="showPlayers" x-cloak>
          <table class="table">
            <thead><tr><th>{"_NAME"|lang}</th><th class="text-right">{"_FRAGS"|lang}</th><th class="text-right">{"_ONLINE"|lang}</th></tr></thead>
            <tbody>
              <template x-for="p in players">
                <tr><td x-text="p.name"></td><td class="text-right tabular-nums" x-text="p.frags"></td><td class="text-right tabular-nums muted" x-text="p.time"></td></tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  {foreachelse}
    <div class="card p-12 text-center muted lg:col-span-2">{"_NOSERVERS"|lang}</div>
  {/foreach}
</div>
{/block}
