{* Captcha image + input for guests. Parameter: id (prefix for the input id). *}
<div class="sm:col-span-2">
  <label class="label" for="{$id}-captcha">{"_SCODEENTER"|lang}</label>
  <div class="flex flex-wrap items-center gap-3">
    <img src="captcha.php?r={$smarty.now}" alt="{"_SCODE"|lang}" width="180" height="50" class="rounded-lg border border-zinc-300 dark:border-zinc-700">
    <input id="{$id}-captcha" name="captcha" class="input w-40 font-mono uppercase" autocomplete="off" maxlength="6" required>
  </div>
</div>
