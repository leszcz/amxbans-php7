{* shared reason + length fields; expects $reasons, $lengths, $old *}
<div class="sm:col-span-2" x-data="banForm({if $old.permanent|default:false}true{else}false{/if}, {if $old.custom|default:false}true{else}false{/if})">
  <div class="grid gap-4 sm:grid-cols-2">
    <div>
      <div class="mb-1.5 flex items-center justify-between">
        <label class="label mb-0" for="f-reason">{"_REASON"|lang}</label>
        <label class="flex items-center gap-1.5 text-xs muted"><input type="checkbox" class="checkbox" x-model="custom"> {"_CUSTOM_REASON"|lang}</label>
      </div>
      <select id="f-reason" name="reason" class="input" x-show="!custom" x-bind:disabled="custom">
        {foreach $reasons as $r}<option value="{$r.reason}" {if ($old.reason|default:'') == $r.reason}selected{/if}>{$r.reason}{if $r.static_bantime} ({$r.static_bantime|banlength}){/if}</option>{/foreach}
      </select>
      <input name="custom_reason" class="input" maxlength="100" value="{if $old.custom|default:false}{$old.reason}{/if}" x-show="custom" x-cloak x-bind:disabled="!custom" aria-label="{"_REASON"|lang}">
    </div>
    <div>
      <div class="mb-1.5 flex items-center justify-between">
        <label class="label mb-0" for="f-length">{"_BANLENGHT"|lang}</label>
        <label class="flex items-center gap-1.5 text-xs muted"><input type="checkbox" name="permanent" value="1" class="checkbox" x-model="permanent"> {"_PERMANENT"|lang}</label>
      </div>
      <select id="f-length" name="length" class="input" x-bind:disabled="permanent">
        {foreach $lengths as $m => $label}<option value="{$m}" {if ($old.length|default:1440) == $m}selected{/if}>{$label}</option>{/foreach}
      </select>
    </div>
  </div>
</div>
