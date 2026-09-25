{extends file="layout.tpl"}
{block name=content}
  <div class="mx-auto max-w-lg py-16 text-center">
    <p class="text-6xl font-bold text-brand-600 dark:text-brand-400">{$code}</p>
    <h1 class="mt-4 text-xl font-semibold text-zinc-900 dark:text-white">{$message|lang}</h1>
    <a href="index.php" class="btn btn-primary mt-8">{icon name="home" class="size-4"}{"_HOME"|lang}</a>
  </div>
{/block}
