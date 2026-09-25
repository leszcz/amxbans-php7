{extends file="admin/layout.tpl"}
{* Website log (admin_so_lg.php). Variables: $logs, $pager, $filters, $usernames, $actions. *}
{block name=admin}
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
  <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_LOGS"|lang}</h1>
  {if $perms.websettings_edit == 'yes'}
    <form method="post" class="flex flex-wrap items-center gap-2">
      {csrf}
      <label class="flex items-center gap-2 text-sm">{"_OLDERTHEN"|lang}<input type="number" name="days" value="30" min="1" class="input w-20"> {"_DAYS"|lang|lower}</label>
      <button name="action" value="delete_older" class="btn btn-secondary" data-confirm="{"_DELLOGS"|lang}">{icon name="trash" class="size-4"}{"_DELETE"|lang}</button>
      <button name="action" value="delete_all" class="btn btn-danger-ghost" data-confirm="{"_DELLOGSALL"|lang}">{"_ALL"|lang}</button>
    </form>
  {/if}
</div>
<section class="card">
  <form method="get" action="admin.php" class="card-header">
    <input type="hidden" name="site" value="so_lg">
    <div class="flex flex-wrap gap-2">
      <select name="username" class="input w-44" aria-label="{"_USER"|lang}"><option value="">{"_USER"|lang}: —</option>{foreach $usernames as $u}<option {if $filters.username == $u}selected{/if}>{$u}</option>{/foreach}</select>
      <select name="action" class="input w-52" aria-label="{"_ACTION"|lang}"><option value="">{"_ACTION"|lang}: —</option>{foreach $actions as $a}<option {if $filters.action == $a}selected{/if}>{$a}</option>{/foreach}</select>
      <button class="btn btn-secondary">{icon name="funnel" class="size-4"}{"_FILTER"|lang}</button>
    </div>
    <span class="muted text-sm">{$pager.total}</span>
  </form>
  <div class="table-wrap">
    <table class="table">
      <thead><tr><th>{"_DATE"|lang}</th><th>{"_USER"|lang}</th><th class="hidden md:table-cell">{"_IP"|lang}</th><th>{"_ACTION"|lang}</th><th>{"_REMARKS"|lang}</th></tr></thead>
      <tbody>
        {foreach $logs as $l}
          <tr>
            <td class="whitespace-nowrap muted tabular-nums">{$l.timestamp|datetime:'full'}</td>
            <td class="font-medium">{$l.username|default:"—"}</td>
            <td class="hidden font-mono text-xs md:table-cell">{$l.ip}</td>
            <td><span class="badge {if $l.action|contains:'fail' || $l.action|contains:'del'}badge-red{else}badge-zinc{/if}">{$l.action}</span></td>
            <td class="text-sm">{$l.remarks}</td>
          </tr>
        {foreachelse}
          <tr><td colspan="5" class="py-10 text-center muted">—</td></tr>
        {/foreach}
      </tbody>
    </table>
  </div>
  {include file="partials/pagination.tpl"}
</section>
{/block}
