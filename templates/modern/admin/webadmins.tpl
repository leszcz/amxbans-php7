{extends file="admin/layout.tpl"}
{* Web admins (admin_wm_wa.php). Variables: $users, $levels, $min_pw. *}
{block name=admin}
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
  <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_WEBADMINSSETTINGS"|lang}</h1>
  {if $perms.webadmins_edit == 'yes'}
    <div x-data="modal">
      <button type="button" class="btn btn-primary" x-on:click="show">{icon name="user-plus" class="size-4"}{"_WEBADMINADD"|lang}</button>
      <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-on:keydown.escape.window="hide">
          <div class="modal-backdrop" x-on:click="hide"></div>
          <form method="post" class="modal-panel" x-trap.noscroll="open">
            {csrf}
            <div class="card-header"><h3 class="card-title">{"_WEBADMINADD"|lang}</h3></div>
            <div class="grid gap-4 p-5 sm:grid-cols-2">
              <div><label class="label" for="n-user">{"_USERNAME"|lang}</label><input id="n-user" name="username" class="input" maxlength="32" required autocomplete="off"></div>
              <div><label class="label" for="n-level">{"_LEVEL"|lang}</label><select id="n-level" name="level" class="input">{foreach $levels as $l}<option value="{$l}">{$l}</option>{/foreach}</select></div>
              <div class="sm:col-span-2"><label class="label" for="n-mail">{"_EMAIL"|lang}</label><input id="n-mail" type="email" name="email" class="input" maxlength="64"></div>
              <div><label class="label" for="n-pw">{"_PASSWORD"|lang}</label><input id="n-pw" type="password" name="password" class="input" minlength="{$min_pw}" required autocomplete="new-password"></div>
              <div><label class="label" for="n-pw2">{"_PASSWORD2"|lang}</label><input id="n-pw2" type="password" name="password2" class="input" minlength="{$min_pw}" required autocomplete="new-password"></div>
            </div>
            <div class="flex justify-end gap-2 border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
              <button type="button" class="btn btn-ghost" x-on:click="hide">{"_CANCEL"|lang}</button>
              <button name="action" value="add" class="btn btn-primary">{"_ADD"|lang}</button>
            </div>
          </form>
        </div>
      </template>
    </div>
  {/if}
</div>

<section class="card">
  <div class="table-wrap">
    <table class="table table-hover">
      <thead><tr><th>{"_USERNAME"|lang}</th><th class="hidden md:table-cell">{"_EMAIL"|lang}</th><th>{"_LEVEL"|lang}</th><th class="hidden sm:table-cell">{"_LASTLOGIN"|lang}</th><th></th></tr></thead>
      <tbody>
        {foreach $users as $u}
          <tr>
            <td class="font-medium">{$u.username}{if $u.id == $auth.id} <span class="badge badge-blue">{"_YOU"|lang}</span>{/if}{if $u.try >= 5} <span class="badge badge-red">{"_LOGINBLOCKED"|lang}</span>{/if}</td>
            <td class="hidden md:table-cell">{$u.email}</td>
            <td><span class="badge badge-zinc">{$u.level}</span></td>
            <td class="hidden sm:table-cell muted">{if $u.last_action}{$u.last_action|relative}{else}{"_NEVER"|lang}{/if}</td>
            <td>
              <div class="flex justify-end gap-1">
                {if $perms.webadmins_edit == 'yes' || $u.id == $auth.id}
                  <div x-data="modal">
                    <button type="button" class="btn btn-ghost btn-sm" x-on:click="show" title="{"_EDIT"|lang}">{icon name="pencil-square" class="size-4"}</button>
                    <template x-teleport="body">
                      <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-on:keydown.escape.window="hide">
                        <div class="modal-backdrop" x-on:click="hide"></div>
                        <div class="modal-panel" x-trap.noscroll="open">
                          <div class="card-header"><h3 class="card-title">{$u.username}</h3><button type="button" class="btn btn-ghost btn-icon" x-on:click="hide">{icon name="x-mark" class="size-5"}</button></div>
                          {if $perms.webadmins_edit == 'yes'}
                            <form method="post" class="grid gap-4 border-b border-zinc-200 p-5 sm:grid-cols-2 dark:border-zinc-800">
                              {csrf}<input type="hidden" name="uid" value="{$u.id}">
                              <div><label class="label">{"_USERNAME"|lang}</label><input name="username" value="{$u.username}" class="input" maxlength="32" required></div>
                              <div><label class="label">{"_LEVEL"|lang}</label><select name="level" class="input" {if $u.id == $auth.id}disabled{/if}>{foreach $levels as $l}<option value="{$l}" {if $l == $u.level}selected{/if}>{$l}</option>{/foreach}</select>{if $u.id == $auth.id}<input type="hidden" name="level" value="{$u.level}">{/if}</div>
                              <div class="sm:col-span-2"><label class="label">{"_EMAIL"|lang}</label><input type="email" name="email" value="{$u.email}" class="input" maxlength="64"></div>
                              <div class="sm:col-span-2 flex justify-end"><button name="action" value="save" class="btn btn-primary">{icon name="check" class="size-4"}{"_SAVE"|lang}</button></div>
                            </form>
                          {/if}
                          <form method="post" class="grid gap-4 p-5 sm:grid-cols-2">
                            {csrf}<input type="hidden" name="uid" value="{$u.id}">
                            <p class="text-sm font-semibold sm:col-span-2">{"_NEWPASSWORD"|lang}</p>
                            <div><label class="label">{"_PASSWORD"|lang}</label><input type="password" name="password" class="input" minlength="{$min_pw}" required autocomplete="new-password"></div>
                            <div><label class="label">{"_PASSWORD2"|lang}</label><input type="password" name="password2" class="input" minlength="{$min_pw}" required autocomplete="new-password"></div>
                            <div class="sm:col-span-2 flex justify-end"><button name="action" value="password" class="btn btn-secondary">{icon name="key" class="size-4"}{"_NEWPASSWORD"|lang}</button></div>
                          </form>
                        </div>
                      </div>
                    </template>
                  </div>
                {/if}
                {if $perms.webadmins_edit == 'yes' && $u.id != $auth.id}
                  <form method="post">{csrf}<input type="hidden" name="uid" value="{$u.id}">
                    <button name="action" value="delete" class="btn btn-danger-ghost btn-sm" data-confirm="{"_DELADMIN"|lang}" title="{"_DELETE"|lang}">{icon name="trash" class="size-4"}</button></form>
                {/if}
              </div>
            </td>
          </tr>
        {/foreach}
      </tbody>
    </table>
  </div>
</section>
{/block}
