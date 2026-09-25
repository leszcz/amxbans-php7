{extends file="layout.tpl"}
{* Login form (login.php). Variables: $username. *}
{block name=content}
<div class="mx-auto max-w-sm py-8">
  <div class="mb-8 text-center">
    <span class="mx-auto flex size-12 items-center justify-center rounded-xl bg-brand-600 text-white shadow-lg shadow-brand-600/20">{icon name="lock-closed" class="size-6"}</span>
    <h1 class="mt-4 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_ADMINAREA"|lang}</h1>
    <p class="muted mt-1 text-sm">{"_LOGIN_INTRO"|lang}</p>
  </div>
  <form method="post" action="login.php" class="card space-y-5 p-6">
    {csrf}
    <div><label class="label" for="user">{"_USERNAME"|lang}</label><input id="user" name="user" value="{$username}" class="input" autocomplete="username" required autofocus maxlength="32"></div>
    <div><label class="label" for="pass">{"_PASSWORD"|lang}</label><input id="pass" type="password" name="pass" class="input" autocomplete="current-password" required></div>
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1" class="checkbox"> {"_REMEMBERME"|lang}</label>
    <button class="btn btn-primary w-full">{icon name="arrow-right-end-on-rectangle" class="size-4"}{"_LOGIN"|lang}</button>
  </form>
</div>
{/block}
