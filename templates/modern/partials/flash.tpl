{if $flashes}
  <div class="mb-6 space-y-2" aria-live="polite">
    {foreach $flashes as $f}
      <div class="alert alert-{$f.type}" x-data="toast({if $f.type == 'success'}6000{else}0{/if})" x-show="visible" x-transition>
        {if $f.type == 'success'}{icon name="check-circle" class="size-5 shrink-0"}
        {elseif $f.type == 'error'}{icon name="x-circle" class="size-5 shrink-0"}
        {elseif $f.type == 'warning'}{icon name="exclamation-triangle" class="size-5 shrink-0"}
        {else}{icon name="information-circle" class="size-5 shrink-0"}{/if}
        <div class="flex-1">
          <p class="whitespace-pre-line">{$f.message|lang}</p>
          {if $f.details}
            <ul class="mt-1 list-disc pl-5">
              {foreach $f.details as $d}<li>{$d|lang}</li>{/foreach}
            </ul>
          {/if}
        </div>
        <button type="button" class="opacity-60 hover:opacity-100" x-on:click="dismiss" aria-label="{"_CLOSE"|lang}">{icon name="x-mark" class="size-4"}</button>
      </div>
    {/foreach}
  </div>
{/if}
