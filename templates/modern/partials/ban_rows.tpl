{* compact ban table: expects $list *}
<div class="table-wrap">
  <table class="table table-hover">
    <thead><tr><th class="w-10"></th><th>{"_DATE"|lang}</th><th>{"_PLAYER"|lang}</th><th class="hidden md:table-cell">{"_STEAMID"|lang}</th><th class="hidden lg:table-cell">{"_ADMIN"|lang}</th><th class="hidden md:table-cell">{"_REASON"|lang}</th><th>{"_LENGHT"|lang}</th></tr></thead>
    <tbody>
      {foreach $list as $ban}
        <tr>
          <td><img src="{$ban.mod|gameicon}" alt="" class="size-5 rounded-sm pixel dark:bg-zinc-300 dark:p-px"></td>
          <td class="whitespace-nowrap muted tabular-nums">{$ban.created|datetime:'date'}</td>
          <td><a class="font-medium text-zinc-900 hover:text-brand-600 dark:text-white dark:hover:text-brand-400" href="ban_list.php?bid={$ban.bid}"><img src="{$ban.cc|flag}" alt="" class="mr-1.5 inline h-3 w-4 pixel">{$ban.player_nick}</a></td>
          <td class="hidden font-mono text-xs md:table-cell">{$ban.player_id}</td>
          <td class="hidden lg:table-cell">{$ban.admin_name}</td>
          <td class="hidden max-w-xs truncate md:table-cell">{$ban.ban_reason}</td>
          <td class="whitespace-nowrap">{if $ban.permanent}<span class="badge badge-red">{"_PERMANENT"|lang}</span>{elseif $ban.unbanned}<span class="badge badge-green">{"_UNBANNED"|lang}</span>{else}<span class="badge badge-zinc">{$ban.ban_length|banlength}</span>{/if}</td>
        </tr>
      {foreachelse}
        <tr><td colspan="7" class="py-8 text-center muted">{"_NO_BANS"|lang}</td></tr>
      {/foreach}
    </tbody>
  </table>
</div>
