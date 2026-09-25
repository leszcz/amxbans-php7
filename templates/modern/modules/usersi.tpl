{extends file="admin/layout.tpl"}
{* users.ini import module (modul_usersi.php). Variables: $servers. *}
{block name=admin}
<h1 class="mb-6 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_MENUIMPORTADMINS"|lang}</h1>
<form method="post" enctype="multipart/form-data" class="card max-w-2xl">
  {csrf}
  <div class="grid gap-4 p-5 sm:grid-cols-2">
    <div class="sm:col-span-2"><label class="label" for="u-file">users.ini</label><input id="u-file" type="file" name="file" accept=".ini,.txt" required class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-brand-600 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white">
      <p class="hint font-mono">"STEAM_0:1:123" "" "abcdefghijklmnopqrstu" "ce"</p></div>
    <div><label class="label" for="u-srv">{"_SERVER"|lang}</label><select id="u-srv" name="server" class="input" required>{foreach $servers as $s}<option value="{$s.id}">{$s.hostname}</option>{/foreach}</select></div>
    <div><label class="label" for="u-sb">{"_STATICBANTIME"|lang}</label><select id="u-sb" name="static_bantime" class="input"><option value="yes">{"_YES"|lang}</option><option value="no">{"_NO"|lang}</option></select></div>
  </div>
  <div class="flex justify-end border-t border-zinc-200 px-5 py-3 dark:border-zinc-800"><button name="action" value="import" class="btn btn-primary" {if !$servers}disabled{/if}>{icon name="arrow-up-tray" class="size-4"}{"_LEVELIMPORT"|lang}</button></div>
</form>
{/block}
