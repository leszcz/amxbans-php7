{extends file="admin/layout.tpl"}
{* Permission levels (admin_wm_ul.php). Variables: $levels, $labels (permission => [group key, label key, options]). *}
{block name=admin}
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
  <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_ADMINLEVELSETTINGS"|lang}</h1>
  <form method="post">{csrf}<button name="action" value="add" class="btn btn-primary">{icon name="plus" class="size-4"}{"_NEWLEVEL"|lang}</button></form>
</div>
<div class="grid gap-6 xl:grid-cols-2">
  {foreach $levels as $level}
    <form method="post" class="card">
      {csrf}<input type="hidden" name="level" value="{$level.level}">
      <div class="card-header">
        <h2 class="card-title">{icon name="shield-check" class="size-5 text-brand-600"}{"_LEVEL"|lang} {$level.level}</h2>
        <span class="badge badge-zinc">{$level.users} {"_WEBADMINS"|lang|lower}</span>
      </div>
      {if $level.level == $auth.level}<p class="border-b border-zinc-200 bg-amber-50 px-5 py-2 text-xs text-amber-800 dark:border-zinc-800 dark:bg-amber-950/40 dark:text-amber-300">{"_YOURLEVEL_NOTE"|lang}</p>{/if}
      <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
        {foreach $labels as $perm => $label}
          <div class="flex items-center justify-between gap-3 px-5 py-2.5">
            <span class="text-sm"><span class="muted">{$label.0|lang} ·</span> {$label.1|lang}</span>
            <div class="flex rounded-lg bg-zinc-100 p-0.5 text-xs dark:bg-zinc-800">
              {foreach $label.2 as $opt}
                <label class="cursor-pointer rounded-md px-2.5 py-1 font-medium has-checked:bg-white has-checked:shadow-sm dark:has-checked:bg-zinc-950 {if $opt == 'yes'}has-checked:text-emerald-600{elseif $opt == 'own'}has-checked:text-amber-600{else}has-checked:text-zinc-900 dark:has-checked:text-white{/if}">
                  <input type="radio" name="{$perm}" value="{$opt}" class="sr-only" {if $level.$perm == $opt}checked{/if}>{if $opt == 'yes'}{"_YES"|lang}{elseif $opt == 'own'}{"_OWN"|lang}{else}{"_NO"|lang}{/if}
                </label>
              {/foreach}
            </div>
          </div>
        {/foreach}
      </div>
      <div class="flex justify-between border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
        <button name="action" value="delete" class="btn btn-danger-ghost" data-confirm="{"_DELLEVEL"|lang}" {if $level.users}disabled{/if}>{icon name="trash" class="size-4"}{"_DELETE"|lang}</button>
        <button name="action" value="save" class="btn btn-primary">{icon name="check" class="size-4"}{"_SAVE"|lang}</button>
      </div>
    </form>
  {/foreach}
</div>
{/block}
