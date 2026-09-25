{extends file="admin/layout.tpl"}
{block name=admin}
<h1 class="mb-6 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_ADDBAN"|lang}</h1>
<form method="post" class="card max-w-3xl">
  {csrf}
  <div class="grid gap-4 p-5 sm:grid-cols-2">
    <div class="sm:col-span-2"><label class="label" for="f-name">{"_NICKNAME"|lang} *</label><input id="f-name" name="name" class="input" maxlength="100" value="{$old.player_nick|default:''}" required></div>
    <div><label class="label" for="f-sid">{"_STEAMID"|lang}</label><input id="f-sid" name="steamid" class="input font-mono" placeholder="STEAM_0:1:123456" value="{$old.player_id|default:''}"></div>
    <div><label class="label" for="f-ip">{"_IP"|lang}</label><input id="f-ip" name="ip" class="input font-mono" placeholder="1.2.3.4" value="{$old.player_ip|default:''}"></div>
    <div class="sm:col-span-2">
      <span class="label">{"_BANTYPE"|lang}</span>
      <div class="flex gap-3">
        <label class="flex flex-1 cursor-pointer items-center gap-2 rounded-lg border border-zinc-300 px-3 py-2.5 text-sm has-checked:border-brand-500 has-checked:bg-brand-50 dark:border-zinc-700 dark:has-checked:bg-brand-500/10"><input type="radio" name="ban_type" value="S" class="accent-brand-600" {if ($old.ban_type|default:'S') != 'SI'}checked{/if}> {"_STEAMID"|lang}</label>
        <label class="flex flex-1 cursor-pointer items-center gap-2 rounded-lg border border-zinc-300 px-3 py-2.5 text-sm has-checked:border-brand-500 has-checked:bg-brand-50 dark:border-zinc-700 dark:has-checked:bg-brand-500/10"><input type="radio" name="ban_type" value="SI" class="accent-brand-600" {if ($old.ban_type|default:'') == 'SI'}checked{/if}> {"_STEAMID&IP"|lang}</label>
      </div>
    </div>
    {include file="partials/ban_fields.tpl"}
  </div>
  <div class="flex justify-end border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
    <button name="action" value="add" class="btn btn-danger">{icon name="no-symbol" class="size-4"}{"_ADDBAN"|lang}</button>
  </div>
</form>
{/block}
