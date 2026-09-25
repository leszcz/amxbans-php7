{* Classic admin area: the four tabs of the old template (Admin / Server / Web / Modules) instead of a sidebar. *}
{extends file="layout.tpl"}
{block name=content}
{assign var=current_group value=0}
{foreach $admin_nav as $group}{foreach $group.items as $item}{if $item.site == $admin_site}{assign var=current_group value=$group@index}{/if}{/foreach}{/foreach}
<div class="mb-6 overflow-hidden rounded border border-zinc-800" x-data="tabs({$current_group})">
  <div class="classic-tabs flex flex-wrap">
    {foreach $admin_nav as $group}
      <button type="button" class="classic-tab" x-bind:class="is({$group@index}) ? 'classic-tab-active' : ''" x-on:click="select({$group@index})">{$group.label|lang}</button>
    {/foreach}
  </div>
  {foreach $admin_nav as $group}
    <div class="flex flex-wrap gap-x-1 bg-zinc-900 px-2 py-1.5" x-show="is({$group@index})" {if $group@index != $current_group}x-cloak{/if}>
      {foreach $group.items as $item}
        <a href="{$item.url}" class="rounded px-3 py-1.5 text-sm {if $item.site == $admin_site}bg-zinc-800 font-semibold text-brand-400{else}text-zinc-300 hover:text-brand-400{/if}">{$item.title|lang}</a>
      {/foreach}
    </div>
  {/foreach}
</div>
{block name=admin}{/block}
{/block}
