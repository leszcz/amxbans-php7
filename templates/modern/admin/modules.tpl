{extends file="admin/layout.tpl"}
{* Modules (admin_so_mo.php). Variables: $modules. *}
{block name=admin}
<h1 class="mb-6 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_MODULSETTINGS"|lang}</h1>
<div class="grid gap-4 md:grid-cols-2">
  {foreach $modules as $m}
    <form method="post" class="card p-5">
      {csrf}<input type="hidden" name="mid" value="{$m.id}">
      <div class="mb-4 flex items-center justify-between">
        <h2 class="card-title">{icon name="puzzle-piece" class="size-5 text-brand-600"}{$m.menuname|lang}</h2>
        {if $m.installed}<span class="badge badge-green">{"_INSTALLED"|lang}</span>{else}<span class="badge badge-red">{"_FILENOTFOUND"|lang}</span>{/if}
      </div>
      <p class="mb-3 font-mono text-xs muted">include/modules/modul_{$m.name}.php</p>
      <label class="label" for="mn{$m.id}">{"_NAMELANGKEY"|lang}</label>
      <input id="mn{$m.id}" name="menuname" value="{$m.menuname}" class="input font-mono text-xs" maxlength="32">
      <div class="mt-3 border-t border-zinc-100 dark:border-zinc-800">{include file="partials/toggle.tpl" name="activ" label="_ACTIV" checked=$m.activ}</div>
      {if $perms.websettings_edit == 'yes'}<div class="mt-2 flex justify-end"><button name="action" value="save" class="btn btn-primary btn-sm">{icon name="check" class="size-4"}{"_SAVE"|lang}</button></div>{/if}
    </form>
  {foreachelse}
    <div class="card p-12 text-center muted md:col-span-2">—</div>
  {/foreach}
</div>
{/block}
