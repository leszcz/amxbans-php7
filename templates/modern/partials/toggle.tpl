{* On/off switch (checkbox). Parameters: name, label (language key), checked. *}
<label class="flex items-center justify-between gap-4 py-2.5">
  <span class="text-sm">{$label|lang}</span>
  <span class="relative inline-flex shrink-0">
    <input type="checkbox" name="{$name}" value="1" class="peer sr-only" {if $checked}checked{/if}>
    <span class="h-6 w-11 rounded-full bg-zinc-300 transition peer-checked:bg-brand-600 peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-brand-500 dark:bg-zinc-700"></span>
    <span class="absolute top-0.5 left-0.5 size-5 rounded-full bg-white shadow transition peer-checked:translate-x-5"></span>
  </span>
</label>
