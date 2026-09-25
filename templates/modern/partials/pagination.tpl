{* expects $pager = [page, pages, base] ; base ends with "?...&" or "?" *}
{if $pager.pages > 1}
  <nav class="flex items-center justify-between gap-3 border-t border-zinc-200 px-5 py-3 text-sm dark:border-zinc-800" aria-label="Pagination">
    <p class="muted">{"_SITE"|lang} {$pager.page} / {$pager.pages}</p>
    <div class="flex items-center gap-1">
      {if $pager.page > 1}
        <a class="btn btn-secondary btn-sm" href="{$pager.base}page={$pager.page-1}">{icon name="chevron-left" class="size-4"}</a>
      {/if}
      {foreach $pager.links as $p}
        {if $p == 0}
          <span class="px-1 muted">…</span>
        {elseif $p == $pager.page}
          <span class="btn btn-primary btn-sm" aria-current="page">{$p}</span>
        {else}
          <a class="btn btn-ghost btn-sm" href="{$pager.base}page={$p}">{$p}</a>
        {/if}
      {/foreach}
      {if $pager.page < $pager.pages}
        <a class="btn btn-secondary btn-sm" href="{$pager.base}page={$pager.page+1}">{icon name="chevron-right" class="size-4"}</a>
      {/if}
    </div>
  </nav>
{/if}
