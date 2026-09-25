{extends file="admin/layout.tpl"}
{block name=admin}
<div class="mb-6">
  <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_WELCOME_BACK"|lang|sprintf:$auth.username}</h1>
  <p class="muted mt-1 text-sm">{"_MENUINFO"|lang}</p>
</div>

{if $system['setup.php'] == 'present'}
  <div class="alert alert-warning mb-6">{icon name="exclamation-triangle" class="size-5 shrink-0"}<p>{"_SETUP_STILL_PRESENT"|lang}</p></div>
{/if}
{if $system.HTTPS == 'no'}
  <div class="alert alert-info mb-6">{icon name="lock-closed" class="size-5 shrink-0"}<p>{"_HTTPS_RECOMMENDED"|lang}</p></div>
{/if}

<div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
  <div class="stat"><span class="stat-icon bg-red-100 text-red-600 dark:bg-red-500/15 dark:text-red-400">{icon name="no-symbol" class="size-6"}</span><div><p class="text-2xl font-bold tabular-nums">{$stats.active}</p><p class="muted text-sm">{"_ACTIVEBANS"|lang}</p></div></div>
  <div class="stat"><span class="stat-icon bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">{icon name="circle-stack" class="size-6"}</span><div><p class="text-2xl font-bold tabular-nums">{$stats.bans}</p><p class="muted text-sm">{"_BANSINDB"|lang}</p></div></div>
  <div class="stat"><span class="stat-icon bg-brand-100 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">{icon name="fire" class="size-6"}</span><div><p class="text-2xl font-bold tabular-nums">{$stats.today}</p><p class="muted text-sm">{"_BANS_TODAY"|lang}</p></div></div>
  <div class="stat"><span class="stat-icon bg-sky-100 text-sky-600 dark:bg-sky-500/15 dark:text-sky-400">{icon name="calendar" class="size-6"}</span><div><p class="text-2xl font-bold tabular-nums">{$stats.week}</p><p class="muted text-sm">{"_BANS_WEEK"|lang}</p></div></div>
</div>

<div class="grid gap-6 xl:grid-cols-3">
  <section class="card xl:col-span-2">
    <div class="card-header"><h2 class="card-title">{icon name="clock" class="size-5 text-brand-600"}{"_RECENT_BANS"|lang}</h2><a class="link text-sm" href="ban_list.php">{"_BANLIST"|lang} →</a></div>
    {include file="partials/ban_rows.tpl" list=$recent}
  </section>

  <div class="space-y-6">
    <section class="card">
      <div class="card-header"><h2 class="card-title">{icon name="wrench-screwdriver" class="size-5 text-brand-600"}{"_DBPRUNE"|lang}</h2></div>
      <div class="card-body space-y-3 text-sm">
        <div class="flex justify-between"><span class="muted">{"_DBSIZE"|lang}</span><span class="font-medium">{$stats.db_size|filesize}</span></div>
        <div class="flex justify-between"><span class="muted">{"_BL_COMMENTS"|lang}</span><span class="font-medium">{$stats.comments}{if $stats.comment_orphans} <span class="badge badge-amber">{$stats.comment_orphans} {"_ORPHANED"|lang}</span>{/if}</span></div>
        <div class="flex justify-between"><span class="muted">{"_BL_FILES"|lang}</span><span class="font-medium">{$stats.files}{if $stats.file_orphans} <span class="badge badge-amber">{$stats.file_orphans} {"_ORPHANED"|lang}</span>{/if}</span></div>
        <div class="flex justify-between"><span class="muted">{"_AUTOPRUNE"|lang}</span>{if $auto_prune}<span class="badge badge-green">{"_ON"|lang}</span>{else}<span class="badge badge-zinc">{"_OFF"|lang}</span>{/if}</div>
      </div>
      {if $perms.prune_db == 'yes'}
        <form method="post" class="grid grid-cols-2 gap-2 border-t border-zinc-200 p-4 dark:border-zinc-800">
          {csrf}
          <button name="action" value="prune" class="btn btn-secondary btn-sm">{icon name="funnel" class="size-4"}{"_PRUNEDB"|lang}</button>
          <button name="action" value="optimize" class="btn btn-secondary btn-sm">{icon name="bolt" class="size-4"}{"_OPTIMIZE"|lang}</button>
          <button name="action" value="clear_cache" class="btn btn-secondary btn-sm">{icon name="arrow-path" class="size-4"}{"_CLEARCACHE"|lang}</button>
          <button name="action" value="repair_files" class="btn btn-secondary btn-sm" {if !$stats.file_orphans}disabled{/if}>{icon name="paper-clip" class="size-4"}{"_REPAIR"|lang} {"_BL_FILES"|lang|lower}</button>
          <button name="action" value="repair_comments" class="btn btn-secondary btn-sm col-span-2" {if !$stats.comment_orphans}disabled{/if}>{icon name="chat-bubble-left-right" class="size-4"}{"_REPAIR"|lang} {"_BL_COMMENTS"|lang|lower}</button>
        </form>
      {/if}
    </section>

    <section class="card">
      <div class="card-header"><h2 class="card-title">{icon name="computer-desktop" class="size-5 text-brand-600"}{"_SYSTEMSETTINGS"|lang}</h2></div>
      <dl class="card-body space-y-2 text-sm">
        {foreach $system as $k => $v}
          <div class="flex justify-between gap-4"><dt class="muted">{$k}</dt><dd class="truncate font-medium">{$v}</dd></div>
        {/foreach}
      </dl>
    </section>
  </div>
</div>
{/block}
