{extends file="admin/layout.tpl"}
{* Ban reasons and sets (admin_sm_bg.php). Variables: $reasons, $sets (with reasons = list of reason ids). *}
{block name=admin}
<h1 class="mb-6 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_REASONSSETTINGS"|lang}</h1>
<div class="grid gap-6 xl:grid-cols-2">
  <section class="card">
    <div class="card-header"><h2 class="card-title">{icon name="clipboard-document-list" class="size-5 text-brand-600"}{"_REASONS"|lang}</h2><span class="badge badge-zinc">{$reasons|count}</span></div>
    <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
      {foreach $reasons as $r}
        <li class="px-5 py-3">
          <form method="post" class="flex flex-wrap items-center gap-2">
            {csrf}<input type="hidden" name="rid" value="{$r.id}">
            <input name="reason" value="{$r.reason}" class="input min-w-40 flex-1" maxlength="100" aria-label="{"_REASON"|lang}">
            <div class="flex items-center gap-1"><input type="number" name="static_bantime" value="{$r.static_bantime}" min="0" class="input w-24" aria-label="{"_STATICBANTIME"|lang}" title="{"_STATICBANTIME"|lang}"><span class="text-xs muted">min</span></div>
            <button name="action" value="save_reason" class="btn btn-ghost btn-icon" title="{"_SAVE"|lang}">{icon name="check" class="size-4"}</button>
            <button name="action" value="delete_reason" class="btn btn-danger-ghost btn-icon" title="{"_DELETE"|lang}" data-confirm="{"_DELETE"|lang}?">{icon name="trash" class="size-4"}</button>
          </form>
        </li>
      {/foreach}
    </ul>
    <form method="post" class="flex flex-wrap items-center gap-2 border-t border-zinc-200 bg-zinc-50 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-950/40">
      {csrf}
      <input name="reason" class="input min-w-40 flex-1" placeholder="{"_NEWREASON"|lang}" maxlength="100" required>
      <div class="flex items-center gap-1"><input type="number" name="static_bantime" value="0" min="0" class="input w-24" title="{"_STATICBANTIME"|lang}"><span class="text-xs muted">min</span></div>
      <button name="action" value="add_reason" class="btn btn-primary">{icon name="plus" class="size-4"}{"_ADD"|lang}</button>
    </form>
  </section>

  <section class="card">
    <div class="card-header"><h2 class="card-title">{icon name="archive-box" class="size-5 text-brand-600"}{"_REASONSSETS"|lang}</h2></div>
    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
      {foreach $sets as $set}
        <form method="post" class="space-y-3 px-5 py-4" x-data="{ open: false }">
          {csrf}<input type="hidden" name="rsid" value="{$set.id}">
          <div class="flex items-center gap-2">
            <input name="setname" value="{$set.setname}" class="input flex-1" maxlength="32" aria-label="{"_NAME"|lang}">
            <button type="button" class="btn btn-ghost btn-sm" x-on:click="open = !open">{$set.reasons|count} {"_REASONS"|lang|lower}{icon name="chevron-down" class="size-4"}</button>
          </div>
          <div class="grid gap-1.5 sm:grid-cols-2" x-show="open" x-cloak>
            {foreach $reasons as $r}
              <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="reasons[]" value="{$r.id}" class="checkbox" {if in_array($r.id, $set.reasons)}checked{/if}> {$r.reason}</label>
            {/foreach}
          </div>
          <div class="flex justify-end gap-2">
            <button name="action" value="delete_set" class="btn btn-danger-ghost btn-sm" data-confirm="{"_DELETE"|lang}?">{icon name="trash" class="size-4"}{"_DELETE"|lang}</button>
            <button name="action" value="save_set" class="btn btn-secondary btn-sm">{icon name="check" class="size-4"}{"_SAVESET"|lang}</button>
          </div>
        </form>
      {/foreach}
    </div>
    <form method="post" class="space-y-3 border-t border-zinc-200 bg-zinc-50 px-5 py-4 dark:border-zinc-800 dark:bg-zinc-950/40">
      {csrf}
      <div class="flex gap-2"><input name="setname" class="input flex-1" placeholder="{"_NEWSET"|lang}" maxlength="32" required>
        <button name="action" value="add_set" class="btn btn-primary">{icon name="plus" class="size-4"}{"_ADD"|lang}</button></div>
      <div class="grid gap-1.5 sm:grid-cols-2">
        {foreach $reasons as $r}<label class="flex items-center gap-2 text-sm"><input type="checkbox" name="reasons[]" value="{$r.id}" class="checkbox"> {$r.reason}</label>{/foreach}
      </div>
    </form>
  </section>
</div>
{/block}
