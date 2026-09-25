<!DOCTYPE html>
<html lang="{$app.html_lang}" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>{if $page_title}{$page_title|lang} · {/if}AMXBans</title>
  <link rel="icon" href="images/favicon.svg" type="image/svg+xml">
  <link rel="stylesheet" href="assets/dist/app.css?v={$asset_ver}">
  <script src="assets/js/theme.js?v={$asset_ver}"></script>
  <script defer src="assets/js/app.js?v={$asset_ver}"></script>
  <script defer src="assets/dist/alpine-focus.min.js?v={$asset_ver}"></script>
  <script defer src="assets/dist/alpine-csp.min.js?v={$asset_ver}"></script>
</head>
<body class="flex min-h-full flex-col">

<header class="sticky top-0 z-30 border-b border-zinc-200 bg-white/85 backdrop-blur dark:border-zinc-800 dark:bg-zinc-950/85" x-data="dropdown">
  <div class="mx-auto flex h-16 max-w-7xl items-center gap-4 px-4 sm:px-6 lg:px-8">
    <a href="index.php" class="flex items-center gap-2.5 font-semibold text-zinc-900 dark:text-white">
      <span class="flex size-9 items-center justify-center rounded-lg bg-brand-600 text-white shadow-sm">{icon name="shield-check" class="size-5"}</span>
      <span class="hidden text-lg tracking-tight sm:inline">AMX<span class="text-brand-600 dark:text-brand-400">Bans</span></span>
    </a>

    <nav class="hidden flex-1 items-center gap-1 md:flex" aria-label="Main">
      {foreach $nav as $item}
        <a href="{$item.url}" class="nav-link {if $item.file == $app.script}nav-link-active{/if}">{$item.label|lang}</a>
      {/foreach}
    </nav>

    <div class="ml-auto flex items-center gap-1.5">
      <div class="hidden sm:block" x-data="langSwitch">
        <label class="sr-only" for="lang-select">{"_LANGUAGE"|lang}</label>
        <select id="lang-select" class="input h-9 w-auto py-1 pr-8 text-xs capitalize" x-on:change="change">
          {foreach $app.languages as $l}
            <option value="{$l}" {if $l == $app.lang}selected{/if}>{$l}</option>
          {/foreach}
        </select>
      </div>

      <div x-data="theme">
        <button type="button" class="btn btn-ghost btn-icon" x-on:click="toggle" title="{"_TOGGLE_THEME"|lang}">
          <span x-show="dark" x-cloak>{icon name="sun"}</span>
          <span x-show="!dark">{icon name="moon"}</span>
          <span class="sr-only">{"_TOGGLE_THEME"|lang}</span>
        </button>
      </div>

      {if $auth}
        <a href="admin.php" class="btn btn-secondary hidden sm:inline-flex">{icon name="cog-6-tooth" class="size-4"}{"_ADMINAREA"|lang}</a>
        <form method="post" action="logout.php" class="hidden sm:block">
          {csrf}
          <button type="submit" class="btn btn-ghost" title="{"_LOGOUT"|lang}">
            {icon name="arrow-left-start-on-rectangle"}<span class="hidden lg:inline">{$auth.username}</span>
          </button>
        </form>
      {else}
        <a href="login.php" class="btn btn-primary hidden sm:inline-flex">{icon name="arrow-right-end-on-rectangle" class="size-4"}{"_LOGIN"|lang}</a>
      {/if}

      <button type="button" class="btn btn-ghost btn-icon md:hidden" x-on:click="toggle" aria-label="{"_MENU"|lang}">{icon name="bars-3"}</button>
    </div>
  </div>

  {* Mobile menu *}
  <div class="border-t border-zinc-200 px-4 py-3 md:hidden dark:border-zinc-800" x-show="open" x-cloak x-transition>
    <nav class="flex flex-col gap-1">
      {foreach $nav as $item}
        <a href="{$item.url}" class="nav-link {if $item.file == $app.script}nav-link-active{/if}">{$item.label|lang}</a>
      {/foreach}
      {if $auth}
        <a href="admin.php" class="nav-link">{icon name="cog-6-tooth" class="size-4"}{"_ADMINAREA"|lang}</a>
        <form method="post" action="logout.php">
          {csrf}
          <button type="submit" class="nav-link w-full">{icon name="arrow-left-start-on-rectangle" class="size-4"}{"_LOGOUT"|lang} ({$auth.username})</button>
        </form>
      {else}
        <a href="login.php" class="nav-link">{icon name="arrow-right-end-on-rectangle" class="size-4"}{"_LOGIN"|lang}</a>
      {/if}
      <div class="mt-2" x-data="langSwitch">
        <select class="input capitalize" x-on:change="change" aria-label="{"_LANGUAGE"|lang}">
          {foreach $app.languages as $l}
            <option value="{$l}" {if $l == $app.lang}selected{/if}>{$l}</option>
          {/foreach}
        </select>
      </div>
    </nav>
  </div>
</header>

{if $app.banner}
  <div class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900/50">
    <div class="mx-auto flex max-w-7xl justify-center px-4 py-4">
      {if $app.banner_url}<a href="{$app.banner_url}" target="_blank" rel="noopener noreferrer">{/if}
        <img src="images/banner/{$app.banner|escape:'url'}" alt="Banner" class="max-h-32 w-auto">
      {if $app.banner_url}</a>{/if}
    </div>
  </div>
{/if}

<main class="mx-auto w-full max-w-7xl flex-1 px-4 py-8 sm:px-6 lg:px-8">
  {include file="partials/flash.tpl"}
  {block name=content}{/block}
</main>

<footer class="border-t border-zinc-200 dark:border-zinc-800">
  <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-4 py-6 text-xs text-zinc-500 sm:flex-row sm:px-6 lg:px-8 dark:text-zinc-400">
    <p>AMXBans <span class="font-medium text-zinc-700 dark:text-zinc-300">{$app.version}</span> · PHP 8 edition</p>
    <p>Based on AMXBans 6 by SeToY &amp; |PJ|ShOrTy and GmStaff · <a class="link" href="https://github.com/leszcz/amxbans-php7" target="_blank" rel="noopener">GitHub</a></p>
  </div>
</footer>

</body>
</html>
