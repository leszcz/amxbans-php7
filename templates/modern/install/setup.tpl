<!DOCTYPE html>
<html lang="{$app.html_lang}" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>{"_INSTALLATION"|lang} · AMXBans</title>
  <link rel="icon" href="images/favicon.svg" type="image/svg+xml">
  <link rel="stylesheet" href="assets/dist/app.css?v={$asset_ver}">
  <script src="assets/js/theme.js?v={$asset_ver}"></script>
  <script defer src="assets/js/app.js?v={$asset_ver}"></script>
  <script defer src="assets/dist/alpine-csp.min.js?v={$asset_ver}"></script>
</head>
<body class="min-h-full bg-gradient-to-b from-zinc-100 to-zinc-200 py-10 dark:from-zinc-950 dark:to-zinc-900">
<div class="mx-auto max-w-2xl px-4">
  <div class="mb-8 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <span class="flex size-10 items-center justify-center rounded-lg bg-brand-600 text-white shadow-sm">{icon name="shield-check" class="size-6"}</span>
      <div><p class="text-lg font-semibold">AMX<span class="text-brand-600">Bans</span> {$app.version}</p><p class="muted text-sm">{"_INSTALLATION"|lang}</p></div>
    </div>
    <form method="post" class="flex items-center gap-2">
      {csrf}
      <select name="lang" class="input w-auto py-1.5 text-sm capitalize" aria-label="Language">{foreach $app.languages as $l}<option {if $l == $app.lang}selected{/if}>{$l}</option>{/foreach}</select>
      <button name="action" value="language" class="btn btn-secondary btn-sm">OK</button>
    </form>
  </div>

  {if $step != 'locked' && $step != 'done'}
    <ol class="mb-6 grid grid-cols-4 gap-2 text-xs font-medium">
      {foreach $steps as $s}
        <li class="rounded-lg px-3 py-2 text-center {if $s == $step}bg-brand-600 text-white{elseif $s@index < $step_index}bg-emerald-600/15 text-emerald-700 dark:text-emerald-400{else}bg-white text-zinc-500 dark:bg-zinc-900{/if}">{$s@iteration}. {$step_labels[$s]|lang}</li>
      {/foreach}
    </ol>
  {/if}

  {include file="partials/flash.tpl"}

  <div class="card">
  {if $step == 'locked'}
    <div class="card-body space-y-4 text-center">
      {icon name="lock-closed" class="mx-auto size-10 text-brand-600"}
      <h1 class="text-xl font-semibold">{"_SETUP_LOCKED"|lang}</h1>
      <p class="muted text-sm">{"_SETUP_LOCKED_TEXT"|lang}</p>
      <form method="post" class="flex justify-center gap-2">{csrf}
        <a href="index.php" class="btn btn-secondary">{"_HOME"|lang}</a>
        <button name="action" value="delete_setup" class="btn btn-danger">{icon name="trash" class="size-4"}{"_SETUP_DELETE"|lang}</button>
      </form>
    </div>

  {elseif $step == 'requirements'}
    <div class="card-header"><h1 class="card-title">{"_SETUP_STEP_REQUIREMENTS"|lang}</h1></div>
    <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
      {foreach $requirements as $r}
        <li class="flex items-center justify-between px-5 py-2.5 text-sm">
          <span>{$r.0}{if !$r.3} <span class="muted text-xs">({"_OPTIONAL"|lang})</span>{/if}</span>
          <span class="flex items-center gap-2"><span class="muted">{$r.1}</span>{if $r.2}{icon name="check-circle" class="size-5 text-emerald-500"}{elseif $r.3}{icon name="x-circle" class="size-5 text-red-500"}{else}{icon name="exclamation-triangle" class="size-5 text-amber-500"}{/if}</span>
        </li>
      {/foreach}
    </ul>
    <form method="post" class="flex justify-end border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">{csrf}
      <button name="action" value="requirements" class="btn btn-primary" {if !$requirements_ok}disabled{/if}>{"_NEXT"|lang}{icon name="chevron-right" class="size-4"}</button>
    </form>

  {elseif $step == 'database'}
    <form method="post">{csrf}
      <div class="card-header"><h1 class="card-title">{"_SETUP_STEP_DATABASE"|lang}</h1></div>
      <div class="grid gap-4 p-5 sm:grid-cols-2">
        <div><label class="label" for="host">{"_SETUP_DBHOST"|lang}</label><input id="host" name="host" value="{$setup.db.host|default:'localhost'}" class="input" required></div>
        <div><label class="label" for="name">{"_SETUP_DBNAME"|lang}</label><input id="name" name="name" value="{$setup.db.name|default:''}" class="input" required></div>
        <div><label class="label" for="user">{"_SETUP_DBUSER"|lang}</label><input id="user" name="user" value="{$setup.db.user|default:''}" class="input" required autocomplete="off"></div>
        <div><label class="label" for="pass">{"_SETUP_DBPASS"|lang}</label><input id="pass" type="password" name="pass" class="input" autocomplete="new-password"></div>
        <div><label class="label" for="prefix">{"_SETUP_DBPREFIX"|lang}</label><input id="prefix" name="prefix" value="{$setup.db.prefix|default:'amx'}" class="input font-mono" pattern="[A-Za-z0-9_]+" required><p class="hint">{"_SETUP_PREFIX_HINT"|lang}</p></div>
      </div>
      <div class="flex justify-between border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
        <button name="action" value="back:requirements" class="btn btn-ghost" formnovalidate>{icon name="chevron-left" class="size-4"}{"_BACK"|lang}</button>
        <button name="action" value="database" class="btn btn-primary">{"_NEXT"|lang}{icon name="chevron-right" class="size-4"}</button>
      </div>
    </form>

  {elseif $step == 'admin'}
    <form method="post">{csrf}
      <div class="card-header"><h1 class="card-title">{"_SETUP_STEP_ADMIN"|lang}</h1></div>
      {if $setup.existing|default:false}<p class="alert alert-info m-5 mb-0">{icon name="information-circle" class="size-5 shrink-0"}{"_SETUP_ADMIN_OPTIONAL"|lang}</p>{/if}
      <div class="grid gap-4 p-5 sm:grid-cols-2">
        <div><label class="label" for="auser">{"_USERNAME"|lang}</label><input id="auser" name="user" value="{$setup.admin.user|default:''}" class="input" maxlength="32" {if !($setup.existing|default:false)}required{/if}></div>
        <div><label class="label" for="aemail">{"_EMAIL"|lang}</label><input id="aemail" type="email" name="email" value="{$setup.admin.email|default:''}" class="input"></div>
        <div><label class="label" for="apass">{"_PASSWORD"|lang}</label><input id="apass" type="password" name="pass" class="input" minlength="8" autocomplete="new-password" {if !($setup.existing|default:false)}required{/if}><p class="hint">{"_MIN_CHARS"|lang|sprintf:8}</p></div>
        <div><label class="label" for="apass2">{"_PASSWORD2"|lang}</label><input id="apass2" type="password" name="pass2" class="input" minlength="8" autocomplete="new-password"></div>
      </div>
      <div class="flex justify-between border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
        <button name="action" value="back:database" class="btn btn-ghost" formnovalidate>{icon name="chevron-left" class="size-4"}{"_BACK"|lang}</button>
        <button name="action" value="admin" class="btn btn-primary">{"_NEXT"|lang}{icon name="chevron-right" class="size-4"}</button>
      </div>
    </form>

  {elseif $step == 'install'}
    <form method="post">{csrf}
      <div class="card-header"><h1 class="card-title">{"_SETUP_STEP_INSTALL"|lang}</h1></div>
      <dl class="dl-grid p-5">
        <dt>{"_SETUP_DBHOST"|lang}</dt><dd>{$setup.db.host}</dd>
        <dt>{"_SETUP_DBNAME"|lang}</dt><dd>{$setup.db.name}</dd>
        <dt>{"_SETUP_DBUSER"|lang}</dt><dd>{$setup.db.user}</dd>
        <dt>{"_SETUP_DBPREFIX"|lang}</dt><dd class="font-mono">{$setup.db.prefix}_</dd>
        <dt>{"_ADMIN"|lang}</dt><dd>{$setup.admin.user|default:'—'}</dd>
        <dt>{"_DEFAULTLANG"|lang}</dt><dd class="capitalize">{$setup.lang}</dd>
      </dl>
      {if $setup.existing|default:false}<p class="alert alert-info mx-5 mb-5">{icon name="information-circle" class="size-5 shrink-0"}{"_SETUP_EXISTING"|lang}</p>{/if}
      <div class="flex justify-between border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
        <button name="action" value="back:admin" class="btn btn-ghost" formnovalidate>{icon name="chevron-left" class="size-4"}{"_BACK"|lang}</button>
        <button name="action" value="install" class="btn btn-primary">{icon name="check" class="size-4"}{"_SETUP_INSTALL_NOW"|lang}</button>
      </div>
    </form>

  {elseif $step == 'done'}
    <div class="card-body space-y-4">
      <div class="flex items-center gap-3">{icon name="check-circle" class="size-8 text-emerald-500"}<h1 class="text-xl font-semibold">{"_SETUP_DONE"|lang}</h1></div>
      {if !$written}
        <div class="alert alert-warning">{icon name="exclamation-triangle" class="size-5 shrink-0"}<p>{"_SETUP_WRITE_MANUALLY"|lang}</p></div>
        <pre class="console">{$config_php}</pre>
      {/if}
      <p class="text-sm">{"_SETUP_DELETE_HINT"|lang}</p>
      <form method="post" class="flex flex-wrap gap-2">{csrf}
        <button name="action" value="delete_setup" class="btn btn-danger">{icon name="trash" class="size-4"}{"_SETUP_DELETE"|lang}</button>
        <a href="login.php" class="btn btn-primary">{icon name="arrow-right-end-on-rectangle" class="size-4"}{"_LOGIN"|lang}</a>
      </form>
    </div>
  {/if}
  </div>
</div>
</body>
</html>
