{extends file="layout.tpl"}
{block name=content}
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
  <div class="flex items-center gap-3">
    <a href="ban_list.php" class="btn btn-ghost btn-icon" title="{"_BACK"|lang}">{icon name="chevron-left"}</a>
    <div>
      <h1 class="flex items-center gap-2 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">
        <img src="{$ban.cc|flag}" alt="{$ban.cc}" title="{$ban.cn}" class="h-4 w-6 pixel"> {$ban.player_nick}
      </h1>
      <p class="muted text-sm">{"_BANDETAILS"|lang} #{$ban.bid}</p>
    </div>
  </div>
  <div class="flex items-center gap-2">
    {if $ban.active}<span class="badge badge-red">{icon name="no-symbol" class="size-3.5"}{"_ACTIVEBAN"|lang}</span>
    {elseif $ban.unbanned}<span class="badge badge-green">{"_UNBANNED"|lang}</span>
    {else}<span class="badge badge-zinc">{"_EXPIREDBAN"|lang}</span>{/if}

    {if $can.edit}
      <div x-data="modal">
        <button type="button" class="btn btn-secondary" x-on:click="show">{icon name="pencil-square" class="size-4"}{"_EDITBAN"|lang}</button>
        {include file="partials/ban_edit_modal.tpl"}
      </div>
    {/if}
    {if $can.delete}
      <form method="post">
        {csrf}
        <button name="action" value="delete_ban" class="btn btn-danger" data-confirm="{"_DELBAN"|lang} {"_DATALOSS"|lang}">{icon name="trash" class="size-4"}{"_DELETE"|lang}</button>
      </form>
    {/if}
  </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
  <section class="card lg:col-span-2">
    <div class="card-header"><h2 class="card-title">{icon name="identification" class="size-5 text-brand-600"}{"_BANDETAILS"|lang}</h2></div>
    <dl class="dl-grid card-body">
      <dt>{"_NICKNAME"|lang}</dt><dd class="font-medium">{$ban.player_nick}</dd>
      {if $ban.has_steamid}
        <dt>{"_STEAMID"|lang}</dt>
        <dd class="flex flex-wrap items-center gap-2" x-data="clipboard">
          <span class="font-mono">{$ban.player_id}</span>
          <button type="button" class="btn btn-ghost btn-sm" data-value="{$ban.player_id}" x-on:click="copy($el.dataset.value)">
            <span x-show="!copied">{icon name="clipboard" class="size-4"}</span><span x-show="copied" x-cloak>{icon name="check" class="size-4"}</span>
          </button>
          {if $ban.steam_url}<a class="link" href="{$ban.steam_url}" target="_blank" rel="noopener noreferrer">{"_STEAMPROFILE"|lang} {icon name="arrow-top-right-on-square" class="inline size-3.5"}</a>{/if}
        </dd>
      {/if}
      <dt>{"_IP"|lang}</dt>
      <dd>{if $perms.ip_view == 'yes'}<span class="font-mono">{$ban.player_ip|default:"—"}</span>{else}<span class="muted italic">{"_HIDDEN"|lang}</span>{/if}{if $ban.cn} · {$ban.cn}{/if}</dd>
      <dt>{"_BANTYPE"|lang}</dt><dd>{if $ban.ban_type == 'SI'}{"_STEAMID&IP"|lang}{else}{"_STEAMID"|lang}{/if}</dd>
      <dt>{"_REASON"|lang}</dt><dd>{$ban.ban_reason}</dd>
      <dt>{"_INVOKED"|lang}</dt><dd>{$ban.created|datetime:'full'}</dd>
      <dt>{"_BANLENGHT"|lang}</dt><dd>{$ban.ban_length|banlength}{if $ban.ban_length > 0} <span class="muted">({$ban.ban_length} {"_MINS"|lang|lower})</span>{/if}</dd>
      <dt>{"_EXPIRES"|lang}</dt>
      <dd>
        {if $ban.ban_length <= 0}<span class="muted italic">{"_NOTAPPLICABLE"|lang}</span>
        {else}{$ban.ban_end|datetime:'full'} <span class="muted">({$ban.ban_end|relative})</span>{/if}
      </dd>
      <dt>{"_BANBY"|lang}</dt><dd>{$ban.admin_nick}{if $ban.nickname && $ban.nickname != $ban.admin_nick} <span class="muted">({$ban.nickname})</span>{/if}</dd>
      {if $perms.ip_view == 'yes'}<dt>{"_ADMINID"|lang}</dt><dd class="font-mono">{$ban.admin_id|default:"—"}</dd>{/if}
      <dt>{"_BANON"|lang}</dt><dd class="flex items-center gap-2"><img src="{$ban.mod|gameicon}" alt="" class="size-4 pixel">{if $ban.website}{"_WEB"|lang}{else}{$ban.server_name}{/if}</dd>
      {if $ban.ban_kicks}<dt>{"_BL_KICKS"|lang}</dt><dd>{$ban.ban_kicks}</dd>{/if}
    </dl>
  </section>

  <section class="card">
    <div class="card-header"><h2 class="card-title">{icon name="clock" class="size-5 text-brand-600"}{"_BANHISTORY"|lang}</h2><span class="badge badge-zinc">{$history|count}</span></div>
    <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
      {foreach $history as $h}
        <li>
          <a href="ban_list.php?bid={$h.bid}" class="flex items-center justify-between gap-3 px-5 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
            <div class="min-w-0">
              <p class="truncate text-sm font-medium">{$h.ban_reason}</p>
              <p class="muted text-xs">{$h.created|datetime:'date'} · {$h.player_nick}</p>
            </div>
            {if $h.active}<span class="badge badge-red">{$h.ban_length|banlength}</span>{else}<span class="badge badge-zinc">{$h.ban_length|banlength}</span>{/if}
          </a>
        </li>
      {foreachelse}
        <li class="px-5 py-8 text-center text-sm muted">{"_NOEXPIREDBANS"|lang}</li>
      {/foreach}
    </ul>
    {if $edits}
      <div class="border-t border-zinc-200 px-5 py-4 dark:border-zinc-800">
        <h3 class="mb-3 text-sm font-semibold">{"_BANDETAILSEDITS"|lang}</h3>
        <ol class="space-y-3 border-l border-zinc-200 pl-4 dark:border-zinc-700">
          {foreach $edits as $e}
            <li class="text-sm">
              <p class="muted text-xs">{$e.edit_time|datetime} · {$e.admin_nick}</p>
              <p>{$e.edit_reason}</p>
            </li>
          {/foreach}
        </ol>
      </div>
    {/if}
  </section>
</div>

{if $show_files}
<section class="card mt-6" id="files">
  <div class="card-header">
    <h2 class="card-title">{icon name="paper-clip" class="size-5 text-brand-600"}{"_BL_FILES"|lang} <span class="badge badge-zinc">{$files|count}</span></h2>
  </div>
  <div class="table-wrap">
    <table class="table">
      <thead><tr><th>{"_FILE"|lang}</th><th class="hidden md:table-cell">{"_COMMENT"|lang}</th><th class="hidden sm:table-cell">{"_BY"|lang}</th><th class="hidden lg:table-cell">{"_DATE"|lang}</th><th class="text-center">{icon name="arrow-down-tray" class="mx-auto size-4"}</th><th></th></tr></thead>
      <tbody>
        {foreach $files as $f}
          <tr>
            <td>
              <div class="flex items-center gap-3">
                {if $f.thumb}<img src="ban_list.php?bid={$ban.bid}&amp;download={$f.id}&amp;thumb=1" alt="" class="size-10 rounded object-cover">{else}<span class="flex size-10 items-center justify-center rounded bg-zinc-100 text-zinc-400 dark:bg-zinc-800">{icon name="document" class="size-5"}</span>{/if}
                <div class="min-w-0"><p class="truncate font-medium">{$f.demo_real}</p><p class="muted text-xs">{$f.file_size|filesize}</p></div>
              </div>
            </td>
            <td class="hidden max-w-sm md:table-cell">{if $f.comment}{$f.comment|bbcode nofilter}{else}<span class="muted italic">{"_NOCOMMENT"|lang}</span>{/if}</td>
            <td class="hidden sm:table-cell">{$f.name}{if $perms.ip_view == 'yes'}<br><span class="muted font-mono text-xs">{$f.addr}</span>{/if}</td>
            <td class="hidden whitespace-nowrap lg:table-cell muted">{$f.upload_time|datetime}</td>
            <td class="text-center tabular-nums muted">{$f.down_count}</td>
            <td>
              <div class="flex justify-end gap-1">
                <a class="btn btn-secondary btn-sm" href="ban_list.php?bid={$ban.bid}&amp;download={$f.id}">{icon name="arrow-down-tray" class="size-4"}<span class="hidden sm:inline">{"_DOWNLOAD"|lang}</span></a>
                {if $can.edit}
                  <div x-data="modal">
                    <button type="button" class="btn btn-ghost btn-sm" x-on:click="show" title="{"_EDIT"|lang}">{icon name="pencil-square" class="size-4"}</button>
                    <template x-teleport="body">
                      <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-on:keydown.escape.window="hide">
                        <div class="modal-backdrop" x-on:click="hide"></div>
                        <form method="post" class="modal-panel" x-trap.noscroll="open">
                          {csrf}<input type="hidden" name="did" value="{$f.id}">
                          <div class="card-header"><h3 class="card-title">{"_ENTRYEDIT"|lang}</h3></div>
                          <div class="card-body"><label class="label" for="fc{$f.id}">{"_COMMENT"|lang}</label><textarea id="fc{$f.id}" name="comment" rows="4" class="input">{$f.comment}</textarea></div>
                          <div class="flex justify-end gap-2 border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
                            <button type="button" class="btn btn-ghost" x-on:click="hide">{"_CANCEL"|lang}</button>
                            <button name="action" value="edit_file" class="btn btn-primary">{"_SAVE"|lang}</button>
                          </div>
                        </form>
                      </div>
                    </template>
                  </div>
                {/if}
                {if $can.delete}
                  <form method="post">{csrf}<input type="hidden" name="did" value="{$f.id}">
                    <button name="action" value="delete_file" class="btn btn-danger-ghost btn-sm" data-confirm="{"_DELDEMO"|lang} {"_DATALOSS"|lang}" title="{"_DELETE"|lang}">{icon name="trash" class="size-4"}</button>
                  </form>
                {/if}
              </div>
            </td>
          </tr>
        {foreachelse}
          <tr><td colspan="6" class="py-8 text-center muted">{"_NOFILES"|lang}</td></tr>
        {/foreach}
      </tbody>
    </table>
  </div>
  {if $can.upload}
    <div x-data="{ open: false }" class="border-t border-zinc-200 dark:border-zinc-800">
      <button type="button" class="flex w-full items-center gap-2 px-5 py-3 text-sm font-medium text-brand-600 hover:bg-zinc-50 dark:text-brand-400 dark:hover:bg-zinc-800/40" x-on:click="open = !open">{icon name="arrow-up-tray" class="size-4"}{"_FILEUPLOAD"|lang}</button>
      <form method="post" enctype="multipart/form-data" class="grid gap-4 px-5 pb-5 sm:grid-cols-2" x-show="open" x-cloak>
        {csrf}
        {if !$auth}
          <div><label class="label" for="up-name">{"_NAME"|lang}</label><input id="up-name" name="name" class="input" maxlength="64" required></div>
          <div><label class="label" for="up-email">{"_EMAIL"|lang}</label><input id="up-email" type="email" name="email" class="input" maxlength="64"></div>
        {/if}
        <div class="sm:col-span-2">
          <label class="label" for="up-file">{"_FILE"|lang}</label>
          <input id="up-file" type="file" name="file" required class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-brand-600 file:px-3 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-brand-500">
          <p class="hint">{$upload.types} · max. {$upload.max} MB</p>
        </div>
        <div class="sm:col-span-2"><label class="label" for="up-comment">{"_COMMENT"|lang}</label><textarea id="up-comment" name="comment" rows="3" class="input"></textarea></div>
        {if $can.captcha}{include file="partials/captcha.tpl" id="up"}{/if}
        <div class="sm:col-span-2"><button name="action" value="upload_file" class="btn btn-primary">{icon name="arrow-up-tray" class="size-4"}{"_UPLOAD"|lang}</button></div>
      </form>
    </div>
  {/if}
</section>
{/if}

{if $show_comments}
<section class="card mt-6" id="comments">
  <div class="card-header">
    <h2 class="card-title">{icon name="chat-bubble-left-right" class="size-5 text-brand-600"}{"_BL_COMMENTS"|lang} <span class="badge badge-zinc">{$comments|count}</span></h2>
  </div>
  <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
    {foreach $comments as $c}
      <li class="flex gap-4 px-5 py-4">
        <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-sm font-semibold uppercase text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">{$c.name|initial}</span>
        <div class="min-w-0 flex-1">
          <div class="flex flex-wrap items-center gap-x-2 text-sm">
            <span class="font-semibold text-zinc-900 dark:text-white">{$c.name}</span>
            <span class="muted text-xs">{$c.date|datetime}</span>
            {if $perms.ip_view == 'yes'}<span class="muted font-mono text-xs">{$c.addr}</span>{/if}
          </div>
          <div class="mt-1 text-sm leading-relaxed">{$c.comment|bbcode nofilter}</div>
        </div>
        {if $can.edit || $can.delete}
          <div class="flex shrink-0 items-start gap-1">
            {if $can.edit}
              <div x-data="modal">
                <button type="button" class="btn btn-ghost btn-sm" x-on:click="show" title="{"_EDIT"|lang}">{icon name="pencil-square" class="size-4"}</button>
                <template x-teleport="body">
                  <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-on:keydown.escape.window="hide">
                    <div class="modal-backdrop" x-on:click="hide"></div>
                    <form method="post" class="modal-panel" x-trap.noscroll="open">
                      {csrf}<input type="hidden" name="cid" value="{$c.id}">
                      <div class="card-header"><h3 class="card-title">{"_EDITCOMMENT"|lang}</h3></div>
                      <div class="card-body"><textarea name="comment" rows="5" class="input" aria-label="{"_COMMENT"|lang}">{$c.comment}</textarea></div>
                      <div class="flex justify-end gap-2 border-t border-zinc-200 px-5 py-3 dark:border-zinc-800">
                        <button type="button" class="btn btn-ghost" x-on:click="hide">{"_CANCEL"|lang}</button>
                        <button name="action" value="edit_comment" class="btn btn-primary">{"_SAVE"|lang}</button>
                      </div>
                    </form>
                  </div>
                </template>
              </div>
            {/if}
            {if $can.delete}
              <form method="post">{csrf}<input type="hidden" name="cid" value="{$c.id}">
                <button name="action" value="delete_comment" class="btn btn-danger-ghost btn-sm" data-confirm="{"_DELCOMMENT"|lang}" title="{"_DELETE"|lang}">{icon name="trash" class="size-4"}</button>
              </form>
            {/if}
          </div>
        {/if}
      </li>
    {foreachelse}
      <li class="px-5 py-8 text-center text-sm muted">{"_NOCOMMENTS"|lang}</li>
    {/foreach}
  </ul>
  {if $can.comment}
    <form method="post" class="grid gap-4 border-t border-zinc-200 p-5 sm:grid-cols-2 dark:border-zinc-800">
      {csrf}
      <h3 class="text-sm font-semibold sm:col-span-2">{"_ADDCOMMENT"|lang}</h3>
      {if !$auth}
        <div><label class="label" for="c-name">{"_NAME"|lang}</label><input id="c-name" name="name" class="input" maxlength="35" value="{$old.name|default:''}" required></div>
        <div><label class="label" for="c-email">{"_EMAIL"|lang}</label><input id="c-email" type="email" name="email" class="input" maxlength="100" value="{$old.email|default:''}"></div>
      {/if}
      <div class="sm:col-span-2">
        <label class="label" for="c-text">{"_COMMENT"|lang}</label>
        <textarea id="c-text" name="comment" rows="4" class="input" maxlength="2000" required>{$old.comment|default:''}</textarea>
        <p class="hint">{"_BBCODE_HINT"|lang}</p>
      </div>
      {if $can.captcha}{include file="partials/captcha.tpl" id="c"}{/if}
      <div class="sm:col-span-2"><button name="action" value="add_comment" class="btn btn-primary">{icon name="chat-bubble-left-right" class="size-4"}{"_ADD"|lang}</button></div>
    </form>
  {/if}
</section>
{/if}
{/block}
