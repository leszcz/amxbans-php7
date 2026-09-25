<template x-teleport="body">
  <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-on:keydown.escape.window="hide">
    <div class="modal-backdrop" x-on:click="hide"></div>
    <form method="post" class="modal-panel max-w-2xl" x-trap.noscroll="open" x-data="banForm(false, false)">
      {csrf}
      <div class="card-header"><h3 class="card-title">{icon name="pencil-square" class="size-5 text-brand-600"}{"_EDITBAN"|lang}</h3>
        <button type="button" class="btn btn-ghost btn-icon" x-on:click="hide" aria-label="{"_CLOSE"|lang}">{icon name="x-mark" class="size-5"}</button></div>
      <div class="grid max-h-[70vh] gap-4 overflow-y-auto p-5 sm:grid-cols-2">
        {if $can.unban && !$ban.unbanned}
          <label class="flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-sm font-medium text-emerald-800 sm:col-span-2 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300">
            <input type="checkbox" name="unban" value="1" class="checkbox" x-model="unban"> {"_UNBANPLAYER"|lang}
          </label>
        {/if}
        <fieldset class="contents" x-bind:disabled="unban">
          <div><label class="label" for="e-nick">{"_NICKNAME"|lang}</label><input id="e-nick" name="player_nick" class="input" value="{$ban.player_nick}" maxlength="100" x-bind:disabled="unban"></div>
          <div><label class="label" for="e-type">{"_BANTYPE"|lang}</label>
            <select id="e-type" name="ban_type" class="input" x-bind:disabled="unban">
              <option value="S" {if $ban.ban_type != 'SI'}selected{/if}>{"_STEAMID"|lang}</option>
              <option value="SI" {if $ban.ban_type == 'SI'}selected{/if}>{"_STEAMID&IP"|lang}</option>
            </select></div>
          <div><label class="label" for="e-sid">{"_STEAMID"|lang}</label><input id="e-sid" name="player_id" class="input font-mono" value="{$ban.player_id}" placeholder="STEAM_0:1:123456" x-bind:disabled="unban"></div>
          {if $perms.ip_view == 'yes'}<div><label class="label" for="e-ip">{"_IP"|lang}</label><input id="e-ip" name="player_ip" class="input font-mono" value="{$ban.player_ip}" x-bind:disabled="unban"></div>{/if}
          <div class="sm:col-span-2"><label class="label" for="e-reason">{"_REASON"|lang}</label><input id="e-reason" name="ban_reason" class="input" value="{$ban.ban_reason}" maxlength="100" x-bind:disabled="unban"></div>
          <div><label class="label" for="e-len">{"_BANLENGHT"|lang} ({"_MINS"|lang|lower})</label><input id="e-len" type="number" min="0" name="ban_length" class="input" value="{if $ban.ban_length > 0}{$ban.ban_length}{else}0{/if}" x-bind:disabled="unban"><p class="hint">0 = {"_PERMANENT"|lang|lower}</p></div>
        </fieldset>
        <div class="sm:col-span-2"><label class="label" for="e-why">{"_EDITREASON"|lang} *</label><textarea id="e-why" name="edit_reason" rows="2" class="input" required maxlength="255"></textarea></div>
      </div>
      <div class="flex justify-end gap-2 border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
        <button type="button" class="btn btn-ghost" x-on:click="hide">{"_CANCEL"|lang}</button>
        <button name="action" value="edit_ban" class="btn btn-primary">{icon name="check" class="size-4"}{"_SAVE"|lang}</button>
      </div>
    </form>
  </div>
</template>
