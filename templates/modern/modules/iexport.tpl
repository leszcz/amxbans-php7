{extends file="admin/layout.tpl"}
{* Import/Export module (modul_iexport.php). Variables: $backups, $imported, $writable. *}
{block name=admin}
<h1 class="mb-6 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_MENUIMPORTEXPORT"|lang}</h1>
<div class="grid gap-6 xl:grid-cols-2">
  {if $perms.bans_export == 'yes'}
  <section class="card">
    <div class="card-header"><h2 class="card-title">{icon name="circle-stack" class="size-5 text-brand-600"}{"_DB_BACKUP"|lang}</h2></div>
    <form method="post" class="space-y-3 p-5">
      {csrf}
      <div class="flex gap-4 text-sm">
        <label class="flex items-center gap-2"><input type="radio" name="scope" value="all" checked class="accent-brand-600"> {"_ALL_TABLES"|lang}</label>
        <label class="flex items-center gap-2"><input type="radio" name="scope" value="bans" class="accent-brand-600"> {"_BANS"|lang}</label>
      </div>
      <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="structure_only" value="1" class="checkbox"> {"_STRUCTURE_ONLY"|lang}</label>
      <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="drop_table" value="1" class="checkbox" checked> DROP TABLE IF EXISTS</label>
      <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="download" value="1" class="checkbox" checked> {"_DOWNLOAD_NOW"|lang}</label>
      <button name="action" value="backup" class="btn btn-primary">{icon name="arrow-down-tray" class="size-4"}{"_CREATE_BACKUP"|lang}</button>
      {if !$writable}<p class="hint text-amber-600">include/backup/ – {"_NOT_WRITABLE"|lang}</p>{/if}
    </form>
    <ul class="divide-y divide-zinc-100 border-t border-zinc-200 dark:divide-zinc-800 dark:border-zinc-800">
      {foreach $backups as $b}
        <li class="flex items-center justify-between gap-3 px-5 py-2.5 text-sm">
          <div class="min-w-0"><p class="truncate font-mono text-xs">{$b.name}</p><p class="muted text-xs">{$b.time|datetime} · {$b.size|filesize}</p></div>
          <div class="flex gap-1">
            <a class="btn btn-ghost btn-sm" href="admin.php?modul=iexport&amp;download={$b.name|escape:'url'}" title="{"_DOWNLOAD"|lang}">{icon name="arrow-down-tray" class="size-4"}</a>
            <form method="post">{csrf}<input type="hidden" name="file" value="{$b.name}"><button name="action" value="delete_backup" class="btn btn-danger-ghost btn-sm" data-confirm="{"_DELETE"|lang}?">{icon name="trash" class="size-4"}</button></form>
          </div>
        </li>
      {/foreach}
    </ul>
  </section>

  <section class="card">
    <div class="card-header"><h2 class="card-title">{icon name="document-text" class="size-5 text-brand-600"}banned.cfg – {"_LEVELEXPORT"|lang}</h2></div>
    <form method="post" class="space-y-3 p-5">
      {csrf}
      <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="only_permanent" value="1" class="checkbox" checked> {"_ONLY_PERMANENT"|lang}</label>
      <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="with_reason" value="1" class="checkbox"> {"_WITH_REASON"|lang}</label>
      <button name="action" value="export_cfg" class="btn btn-secondary">{icon name="arrow-down-tray" class="size-4"}banned.cfg</button>
    </form>
  </section>
  {/if}

  {if $perms.bans_import == 'yes'}
  <section class="card xl:col-span-2">
    <div class="card-header"><h2 class="card-title">{icon name="arrow-up-tray" class="size-5 text-brand-600"}banned.cfg / listip.cfg – {"_LEVELIMPORT"|lang}</h2>
      {if $imported}<form method="post">{csrf}<button name="action" value="delete_imported" class="btn btn-danger-ghost btn-sm" data-confirm="{"_DATALOSS"|lang}">{icon name="trash" class="size-4"}{"_DELETE_IMPORTED"|lang} ({$imported})</button></form>{/if}</div>
    <form method="post" enctype="multipart/form-data" class="grid gap-4 p-5 sm:grid-cols-2">
      {csrf}
      <div class="sm:col-span-2"><label class="label" for="i-file">{"_FILE"|lang} (.cfg / .txt)</label><input id="i-file" type="file" name="file" accept=".cfg,.txt" required class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-brand-600 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white"></div>
      <div><label class="label" for="i-reason">{"_REASON"|lang} *</label><input id="i-reason" name="reason" class="input" required maxlength="100"></div>
      <div><label class="label" for="i-nick">{"_NICKNAME"|lang}</label><input id="i-nick" name="player_nick" class="input" value="Unknown" maxlength="100"></div>
      <div><label class="label" for="i-srv">{"_SERVER"|lang}</label><input id="i-srv" name="server_name" class="input" value="Import" maxlength="100"></div>
      <div><label class="label" for="i-date">{"_DATE"|lang}</label><input id="i-date" type="date" name="ban_created" class="input" value="{$smarty.now|date_format:'%Y-%m-%d'}"></div>
      <div class="sm:col-span-2"><button name="action" value="import_cfg" class="btn btn-primary">{icon name="arrow-up-tray" class="size-4"}{"_LEVELIMPORT"|lang}</button></div>
    </form>
  </section>
  {/if}
</div>
{/block}
