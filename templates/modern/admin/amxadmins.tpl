{extends file="admin/layout.tpl"}
{block name=admin}
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
  <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_MANAGEAMXADMINS"|lang}</h1>
  {if $perms.amxadmins_edit == 'yes'}
    <div x-data="modal">
      <button type="button" class="btn btn-primary" x-on:click="show">{icon name="user-plus" class="size-4"}{"_ADDAMXADMINS"|lang}</button>
      <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-on:keydown.escape.window="hide">
          <div class="modal-backdrop" x-on:click="hide"></div>
          <form method="post" class="modal-panel max-w-2xl" x-trap.noscroll="open">
            {csrf}
            <div class="card-header"><h3 class="card-title">{"_ADDAMXADMINS"|lang}</h3><button type="button" class="btn btn-ghost btn-icon" x-on:click="hide">{icon name="x-mark" class="size-5"}</button></div>
            <div class="max-h-[70vh] space-y-4 overflow-y-auto p-5">
              {include file="partials/amxadmin_fields.tpl" a=[]}
              {if $servers}
                <div>
                  <span class="label">{"_ADDADMINTOSERVERS"|lang}</span>
                  <div class="grid gap-1.5 sm:grid-cols-2">{foreach $servers as $s}<label class="flex items-center gap-2 text-sm"><input type="checkbox" name="servers[]" value="{$s.id}" class="checkbox"> {$s.hostname}</label>{/foreach}</div>
                  <label class="mt-3 flex items-center gap-2 text-sm">{"_WITHSTATICBANTIME"|lang}
                    <select name="static_bantime" class="input w-24 py-1"><option value="yes">{"_YES"|lang}</option><option value="no">{"_NO"|lang}</option></select></label>
                </div>
              {/if}
            </div>
            <div class="flex justify-end gap-2 border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
              <button type="button" class="btn btn-ghost" x-on:click="hide">{"_CANCEL"|lang}</button>
              <button name="action" value="add" class="btn btn-primary">{icon name="plus" class="size-4"}{"_ADD"|lang}</button>
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
      <thead><tr><th>{"_NICKNAME"|lang}</th><th>{"_STEAMIDIPNAME"|lang}</th><th class="hidden md:table-cell">{"_ACCESS"|lang}</th><th class="hidden md:table-cell">{"_FLAGS"|lang}</th><th>{"_ADMINEXPIRATION"|lang}</th><th class="hidden lg:table-cell">{"_SERVER"|lang}</th><th></th></tr></thead>
      <tbody>
        {foreach $admins as $a}
          <tr>
            <td class="font-medium">{$a.nickname|default:"—"}{if !$a.ashow} <span class="badge badge-zinc" title="{"_SHOWINADMINLIST"|lang}">{icon name="eye-slash" class="size-3"}</span>{/if}</td>
            <td class="font-mono text-xs">{$a.username}</td>
            <td class="hidden font-mono text-xs md:table-cell">{$a.access}</td>
            <td class="hidden font-mono text-xs md:table-cell">{$a.flags}</td>
            <td>{if $a.expired == 0}<span class="badge badge-green">{"_UNLIMITED"|lang}</span>{elseif $a.is_expired}<span class="badge badge-red">{$a.expired|datetime:'date'}</span>{else}<span class="badge badge-amber">{$a.expired|datetime:'date'}</span>{/if}</td>
            <td class="hidden tabular-nums lg:table-cell">{$a.servers}</td>
            <td class="text-right">
              {if $perms.amxadmins_edit == 'yes'}
                <div class="flex justify-end gap-1">
                  <div x-data="modal">
                    <button type="button" class="btn btn-ghost btn-sm" x-on:click="show" title="{"_EDIT"|lang}">{icon name="pencil-square" class="size-4"}</button>
                    <template x-teleport="body">
                      <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-on:keydown.escape.window="hide">
                        <div class="modal-backdrop" x-on:click="hide"></div>
                        <form method="post" class="modal-panel max-w-2xl text-left" x-trap.noscroll="open">
                          {csrf}<input type="hidden" name="aid" value="{$a.id}">
                          <div class="card-header"><h3 class="card-title">{$a.nickname|default:$a.username}</h3><button type="button" class="btn btn-ghost btn-icon" x-on:click="hide">{icon name="x-mark" class="size-5"}</button></div>
                          <div class="max-h-[70vh] overflow-y-auto p-5">{include file="partials/amxadmin_fields.tpl"}</div>
                          <div class="flex justify-end gap-2 border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
                            <button type="button" class="btn btn-ghost" x-on:click="hide">{"_CANCEL"|lang}</button>
                            <button name="action" value="save" class="btn btn-primary">{icon name="check" class="size-4"}{"_SAVE"|lang}</button>
                          </div>
                        </form>
                      </div>
                    </template>
                  </div>
                  <form method="post">{csrf}<input type="hidden" name="aid" value="{$a.id}">
                    <button name="action" value="delete" class="btn btn-danger-ghost btn-sm" data-confirm="{"_DELADMIN"|lang}" title="{"_DELETE"|lang}">{icon name="trash" class="size-4"}</button></form>
                </div>
              {/if}
            </td>
          </tr>
        {foreachelse}
          <tr><td colspan="7" class="py-10 text-center muted">{"_NOADMINS"|lang}</td></tr>
        {/foreach}
      </tbody>
    </table>
  </div>
</section>
{/block}
