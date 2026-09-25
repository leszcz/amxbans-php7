{extends file="admin/layout.tpl"}
{block name=admin}
<h1 class="mb-6 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_USERMENUSETTINGS"|lang}</h1>
<section class="card">
  <div class="table-wrap">
    <table class="table">
      <thead><tr><th class="w-20">{"_POSITION"|lang}</th><th class="w-12">{"_ACTIV"|lang}</th><th>{"_MENULOGGEDOUT"|lang}</th><th>{"_MENULOGGEDIN"|lang}</th><th></th></tr></thead>
      <tbody>
        {foreach $items as $m}
          <tr>
            <td>
              <form method="post" class="flex gap-0.5">{csrf}<input type="hidden" name="mid" value="{$m.id}">
                <button name="action" value="up" class="btn btn-ghost btn-icon" {if $m@first}disabled{/if} title="↑">{icon name="arrow-up" class="size-4"}</button>
                <button name="action" value="down" class="btn btn-ghost btn-icon" {if $m@last}disabled{/if} title="↓">{icon name="arrow-down" class="size-4"}</button>
              </form>
            </td>
            <td colspan="4" class="p-0">
              <form method="post" class="grid items-center gap-2 px-4 py-3 md:grid-cols-[3rem_1fr_1fr_auto]">
                {csrf}<input type="hidden" name="mid" value="{$m.id}">
                <input type="checkbox" name="activ" value="1" class="checkbox" {if $m.activ}checked{/if} aria-label="{"_ACTIV"|lang}">
                <div class="grid grid-cols-2 gap-2"><input name="lang_key" value="{$m.lang_key}" class="input font-mono text-xs" placeholder="{"_LANGKEY1"|lang}"><input name="url" value="{$m.url}" class="input font-mono text-xs" placeholder="{"_URL1"|lang}"></div>
                <div class="grid grid-cols-2 gap-2"><input name="lang_key2" value="{$m.lang_key2}" class="input font-mono text-xs" placeholder="{"_LANGKEY2"|lang}"><input name="url2" value="{$m.url2}" class="input font-mono text-xs" placeholder="{"_URL2"|lang}"></div>
                <div class="flex gap-1">
                  <button name="action" value="save" class="btn btn-ghost btn-icon" title="{"_SAVE"|lang}">{icon name="check" class="size-4"}</button>
                  <button name="action" value="delete" class="btn btn-danger-ghost btn-icon" title="{"_DELETE"|lang}" data-confirm="{"_DELETE"|lang}?">{icon name="trash" class="size-4"}</button>
                </div>
              </form>
            </td>
          </tr>
        {/foreach}
      </tbody>
    </table>
  </div>
  <form method="post" class="grid items-center gap-2 border-t border-zinc-200 bg-zinc-50 px-5 py-4 md:grid-cols-[1fr_1fr_auto] dark:border-zinc-800 dark:bg-zinc-950/40">
    {csrf}<input type="hidden" name="activ" value="1">
    <div class="grid grid-cols-2 gap-2"><input name="lang_key" class="input font-mono text-xs" placeholder="{"_LANGKEY1"|lang} (_HOME)"><input name="url" class="input font-mono text-xs" placeholder="{"_URL1"|lang}"></div>
    <div class="grid grid-cols-2 gap-2"><input name="lang_key2" class="input font-mono text-xs" placeholder="{"_LANGKEY2"|lang}"><input name="url2" class="input font-mono text-xs" placeholder="{"_URL2"|lang}"></div>
    <button name="action" value="add" class="btn btn-primary">{icon name="plus" class="size-4"}{"_ADD"|lang}</button>
  </form>
</section>
<p class="hint mt-3">{"_USERMENU_HINT"|lang}</p>
{/block}
