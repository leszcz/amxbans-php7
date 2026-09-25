{extends file="layout.tpl"}
{block name=content}
<div class="grid gap-8 lg:grid-cols-[15rem_1fr]" x-data="dropdown">
  <aside>
    <button type="button" class="btn btn-secondary mb-4 w-full justify-between lg:hidden" x-on:click="toggle">{icon name="bars-3" class="size-4"}{"_MENU"|lang}<span class="ml-auto">{icon name="chevron-down" class="size-4"}</span></button>
    <nav class="hidden space-y-6 lg:block" x-bind:class="open ? 'block!' : ''" aria-label="Admin">
      {foreach $admin_nav as $group}
        <div>
          <p class="mb-2 px-3 text-xs font-semibold tracking-wider text-zinc-400 uppercase dark:text-zinc-500">{$group.label|lang}</p>
          <ul class="space-y-0.5">
            {foreach $group.items as $item}
              <li><a href="{$item.url}" class="nav-link {if $item.site == $admin_site}nav-link-active{/if}">{icon name=$item.icon class="size-4 shrink-0"}{$item.title|lang}</a></li>
            {/foreach}
          </ul>
        </div>
      {/foreach}
    </nav>
  </aside>
  <div class="min-w-0">
    {block name=admin}{/block}
  </div>
</div>
{/block}
