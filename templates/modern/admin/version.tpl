{extends file="admin/layout.tpl"}
{* Version information (admin_so_vs.php). Variables: $servers, $releases_url. *}
{block name=admin}
<h1 class="mb-6 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_TITLEUPDATE"|lang}</h1>
<div class="grid gap-6 xl:grid-cols-3">
  <section class="card p-6">
    <p class="muted text-sm">{"_YOURWEB"|lang}</p>
    <p class="mt-1 text-3xl font-bold text-zinc-900 dark:text-white">{$app.version}</p>
    <a href="{$releases_url}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary mt-6">{icon name="arrow-top-right-on-square" class="size-4"}{"_CHECK_RELEASES"|lang}</a>
  </section>
  <section class="card xl:col-span-2">
    <div class="card-header"><h2 class="card-title">{icon name="server-stack" class="size-5 text-brand-600"}{"_PLUGINVERSIONINFO"|lang}</h2></div>
    <div class="table-wrap">
      <table class="table">
        <thead><tr><th>{"_HOSTNAME"|lang}</th><th>{"_ADDRESS"|lang}</th><th>{"_VERSION"|lang}</th><th>{"_LASTSEEN"|lang}</th></tr></thead>
        <tbody>
          {foreach $servers as $s}
            <tr><td class="font-medium">{$s.hostname}</td><td class="font-mono text-xs">{$s.address}</td><td><span class="badge badge-zinc">{$s.amxban_version|default:"?"}</span></td><td class="muted">{$s.timestamp|relative}</td></tr>
          {foreachelse}
            <tr><td colspan="4" class="py-8 text-center muted">{"_NOSERVERS"|lang}</td></tr>
          {/foreach}
        </tbody>
      </table>
    </div>
  </section>
</div>
{/block}
