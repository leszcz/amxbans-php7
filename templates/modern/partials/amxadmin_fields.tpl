{* expects $a (admin or empty array), $access_letters, $flag_letters *}
<div class="grid gap-4 sm:grid-cols-2">
  <div><label class="label">{"_STEAMIDIPNAME"|lang} *</label><input name="username" value="{$a.username|default:''}" class="input font-mono" maxlength="32" required></div>
  <div><label class="label">{"_NICKNAME"|lang}</label><input name="nickname" value="{$a.nickname|default:''}" class="input" maxlength="32"></div>
  <div><label class="label">{"_STEAMID"|lang}</label><input name="steamid" value="{$a.steamid|default:''}" class="input font-mono" maxlength="32" placeholder="STEAM_0:1:123456"></div>
  <div><label class="label">{"_PASSWORD"|lang}</label><input type="password" name="password" class="input" autocomplete="new-password" {if $a}placeholder="{"_UNCHANGED"|lang}"{/if}></div>

  <div class="sm:col-span-2" x-data="flagPicker" data-value="{$a.access|default:'z'}" data-letters="{$access_letters}">
    <label class="label">{"_ACCESS"|lang}</label>
    <input name="access" class="input font-mono" x-model="value" maxlength="23">
    <div class="mt-2 flex flex-wrap gap-1">
      <template x-for="l in letters">
        <button type="button" class="size-8 rounded-md border text-sm font-mono transition" x-bind:class="has(l) ? 'border-brand-500 bg-brand-600 text-white' : 'border-zinc-300 text-zinc-500 hover:border-zinc-400 dark:border-zinc-700'" x-on:click="toggle(l)" x-text="l"></button>
      </template>
    </div>
    <details class="mt-2 text-xs muted"><summary class="cursor-pointer">{"_INFO_ACCESS"|lang}</summary><p class="mt-1 whitespace-pre-line">{"_ACCESS_FLAGS"|lang}</p></details>
  </div>
  <div class="sm:col-span-2" x-data="flagPicker" data-value="{$a.flags|default:'ce'}" data-letters="{$flag_letters}">
    <label class="label">{"_FLAGS"|lang}</label>
    <input name="flags" class="input font-mono" x-model="value" maxlength="4">
    <div class="mt-2 flex flex-wrap gap-1">
      <template x-for="l in letters">
        <button type="button" class="size-8 rounded-md border text-sm font-mono transition" x-bind:class="has(l) ? 'border-brand-500 bg-brand-600 text-white' : 'border-zinc-300 text-zinc-500 hover:border-zinc-400 dark:border-zinc-700'" x-on:click="toggle(l)" x-text="l"></button>
      </template>
    </div>
    <details class="mt-2 text-xs muted"><summary class="cursor-pointer">{"_INFO_ACCESS"|lang}</summary><p class="mt-1 whitespace-pre-line">{"_FLAG_FLAGS"|lang}</p></details>
  </div>

  <div x-data="{ unlimited: {if $a && $a.days == 0}true{elseif $a}false{else}false{/if} }">
    <label class="label">{"_ADMINVALIDITY"|lang} ({"_DAYS"|lang|lower})</label>
    <input type="number" name="days" min="0" value="{$a.days|default:30}" class="input" x-bind:disabled="unlimited">
    <label class="mt-2 flex items-center gap-2 text-sm"><input type="checkbox" name="unlimited" value="1" class="checkbox" x-model="unlimited"> {"_UNLIMITED"|lang}</label>
  </div>
  {if $a}
    <div><label class="label">{"_EXTENDWITH"|lang} ({"_DAYS"|lang|lower})</label><input type="number" name="extend" min="0" value="0" class="input"></div>
  {/if}
  <div class="flex items-end"><label class="flex items-center gap-2 text-sm"><input type="checkbox" name="ashow" value="1" class="checkbox" {if !$a || $a.ashow}checked{/if}> {"_SHOWINADMINLIST"|lang}</label></div>
</div>
