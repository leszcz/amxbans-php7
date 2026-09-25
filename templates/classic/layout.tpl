{* "classic" design: the look of the old AMXBans GM "default" template, built on the same Tailwind/Alpine components. *}
<!DOCTYPE html>
<html lang="{$app.html_lang}" class="dark theme-classic h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>AMXBans{if $page_title} - {$page_title|lang}{/if}</title>
  <link rel="icon" href="images/favicon.svg" type="image/svg+xml">
  <link rel="stylesheet" href="assets/dist/app.css?v={$asset_ver}">
  <script defer src="assets/js/app.js?v={$asset_ver}"></script>
  <script defer src="assets/dist/alpine-focus.min.js?v={$asset_ver}"></script>
  <script defer src="assets/dist/alpine-csp.min.js?v={$asset_ver}"></script>
</head>
<body class="flex min-h-full flex-col">

<header class="classic-topbar" x-data="dropdown">
  <div class="mx-auto flex min-h-10 max-w-6xl flex-wrap items-center gap-x-5 gap-y-2 px-4 py-1.5">
    <div x-data="langSwitch">
      <select class="classic-select capitalize" x-on:change="change" aria-label="{"_LANGUAGE"|lang}">
        {foreach $app.languages as $l}<option value="{$l}" {if $l == $app.lang}selected{/if}>{$l}</option>{/foreach}
      </select>
    </div>
    <button type="button" class="ml-auto text-zinc-300 md:hidden" x-on:click="toggle" aria-label="{"_MENU"|lang}">{icon name="bars-3"}</button>
    <nav class="w-full flex-col gap-1 md:flex md:w-auto md:flex-row md:gap-5" x-bind:class="open ? 'flex' : 'hidden'" aria-label="Main">
      {foreach $nav as $item}
        <a href="{$item.url}" class="classic-navlink {if $item.file == $app.script}classic-navlink-active{/if}">{$item.label|lang}</a>
      {/foreach}
      {if !$auth}<a href="login.php" class="classic-navlink {if $app.script == 'login.php'}classic-navlink-active{/if}">{"_LOGIN"|lang}</a>{/if}
    </nav>
    <div class="ml-auto hidden items-center gap-3 md:flex">
      {if $auth}
        <a href="admin.php" class="classic-navlink">{"_ADMINAREA"|lang}</a>
        <form method="post" action="logout.php">{csrf}
          <button class="classic-navlink">{"_LOGOUT"|lang} {$auth.username}</button>
        </form>
      {else}
        <form method="post" action="login.php" class="flex items-center gap-1.5">
          {csrf}
          <input name="user" class="classic-input w-32" placeholder="{"_USERNAME"|lang}" aria-label="{"_USERNAME"|lang}" autocomplete="username" required>
          <input type="password" name="pass" class="classic-input w-28" placeholder="{"_PASSWORD"|lang}" aria-label="{"_PASSWORD"|lang}" autocomplete="current-password" required>
          <input type="checkbox" name="remember" value="1" class="checkbox" title="{"_REMEMBERME"|lang}">
          <button class="classic-button">{"_LOGIN"|lang}</button>
        </form>
      {/if}
    </div>
  </div>
</header>

{if $app.banner}
  <div class="mx-auto max-w-6xl px-4 pt-6 text-center">
    {if $app.banner_url}<a href="{$app.banner_url}" target="_blank" rel="noopener noreferrer">{/if}
      <img src="images/banner/{$app.banner|escape:'url'}" alt="Banner" class="mx-auto max-h-36 w-auto">
    {if $app.banner_url}</a>{/if}
  </div>
{/if}

<main class="mx-auto w-full max-w-6xl flex-1 px-4 py-6">
  {include file="partials/flash.tpl"}
  {block name=content}{/block}
</main>

<footer class="mx-auto w-full max-w-6xl px-4 pb-8">
  <div class="flex flex-col justify-between gap-1 border-t border-zinc-800 pt-3 text-xs text-zinc-400 sm:flex-row">
    <span><strong class="text-zinc-200">AMXBans <span class="text-red-600">#</span> {$app.version}</strong> · PHP 8 edition</span>
    <span>{"_DESIGN_BY"|lang}: GmStaff (classic) · <a class="link" href="https://github.com/leszcz/amxbans-php7" target="_blank" rel="noopener">GitHub</a></span>
  </div>
</footer>
</body>
</html>
