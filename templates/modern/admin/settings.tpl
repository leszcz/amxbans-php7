{extends file="admin/layout.tpl"}
{* Web settings (admin_wm_ms.php). Variables: $vars (_webconfig row), $designs, $banners, $start_pages, $upload_limit. *}
{block name=admin}
<h1 class="mb-6 text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">{"_SITESETTINGS"|lang}</h1>
<form method="post">
  {csrf}
  <div class="grid gap-6 xl:grid-cols-2">
    <section class="card">
      <div class="card-header"><h2 class="card-title">{icon name="adjustments-horizontal" class="size-5 text-brand-600"}{"_VIEWSETTINGS"|lang}</h2></div>
      <div class="grid gap-4 p-5 sm:grid-cols-2">
        <div><label class="label" for="design">{"_DESIGN"|lang}</label><select id="design" name="design" class="input">{foreach $designs as $d}<option {if $vars.design == $d}selected{/if}>{$d}</option>{/foreach}</select></div>
        <div><label class="label" for="lang">{"_DEFAULTLANG"|lang}</label><select id="lang" name="default_lang" class="input capitalize">{foreach $app.languages as $l}<option {if $vars.default_lang == $l}selected{/if}>{$l}</option>{/foreach}</select></div>
        <div><label class="label" for="banner">{"_BANNER"|lang}</label><select id="banner" name="banner" class="input"><option value="">—</option>{foreach $banners as $b}<option {if $vars.banner == $b}selected{/if}>{$b}</option>{/foreach}</select><p class="hint">images/banner/</p></div>
        <div><label class="label" for="burl">{"_BANNERURL"|lang}</label><input id="burl" name="banner_url" value="{$vars.banner_url}" class="input" placeholder="https://"></div>
        <div><label class="label" for="start">{"_STARTPAGE"|lang}</label><select id="start" name="start_page" class="input">{foreach $start_pages as $p}<option {if $vars.start_page == $p}selected{/if}>{$p}</option>{/foreach}</select></div>
        <div><label class="label" for="bpp">{"_BANSPERPAGE"|lang}</label><input id="bpp" type="number" min="5" max="200" name="bans_per_page" value="{$vars.bans_per_page}" class="input"></div>
        <div class="sm:col-span-2"><label class="label" for="cookie">{"_COOKIENAME"|lang}</label><input id="cookie" name="cookie" value="{$vars.cookie}" class="input font-mono" pattern="[A-Za-z0-9_]+"></div>
      </div>
    </section>

    <section class="card">
      <div class="card-header"><h2 class="card-title">{icon name="list-bullet" class="size-5 text-brand-600"}{"_BANLISTSETTINGS"|lang}</h2></div>
      <div class="divide-y divide-zinc-100 px-5 dark:divide-zinc-800">
        {include file="partials/toggle.tpl" name="show_comment_count" label="_SHOWCOMMENTSCOUNT" checked=$vars.show_comment_count}
        {include file="partials/toggle.tpl" name="show_demo_count" label="_SHOWFILESCOUNT" checked=$vars.show_demo_count}
        {include file="partials/toggle.tpl" name="show_kick_count" label="_SHOWKICKCOUNT" checked=$vars.show_kick_count}
        {include file="partials/toggle.tpl" name="auto_prune" label="_AUTOPRUNE" checked=$vars.auto_prune}
      </div>
      <div class="grid gap-4 border-t border-zinc-200 p-5 sm:grid-cols-2 dark:border-zinc-800">
        <div><label class="label" for="mo">{"_AUTOPRUNE_MAXOFFENCES"|lang}</label><input id="mo" type="number" min="0" name="max_offences" value="{$vars.max_offences}" class="input"></div>
        <div><label class="label" for="mor">{"_AUTOPRUNE_MAXOFFENCES_REASON"|lang}</label><input id="mor" name="max_offences_reason" value="{$vars.max_offences_reason}" class="input" maxlength="128"></div>
      </div>
    </section>

    <section class="card">
      <div class="card-header"><h2 class="card-title">{icon name="chat-bubble-left-right" class="size-5 text-brand-600"}{"_COMMENTSETTINGS"|lang}</h2></div>
      <div class="divide-y divide-zinc-100 px-5 dark:divide-zinc-800">
        {include file="partials/toggle.tpl" name="use_comment" label="_USECOMMENTSYSTEM" checked=$vars.use_comment}
        {include file="partials/toggle.tpl" name="comment_all" label="_COMMENTUSERALLOWEDWRITE" checked=$vars.comment_all}
        {include file="partials/toggle.tpl" name="use_capture" label="_USECAPTURE" checked=$vars.use_capture}
      </div>
    </section>

    <section class="card">
      <div class="card-header"><h2 class="card-title">{icon name="paper-clip" class="size-5 text-brand-600"}{"_FILESETTINGS"|lang}</h2></div>
      <div class="divide-y divide-zinc-100 px-5 dark:divide-zinc-800">
        {include file="partials/toggle.tpl" name="use_demo" label="_USEFILESYSTEM" checked=$vars.use_demo}
        {include file="partials/toggle.tpl" name="demo_all" label="_FILE_USERUPLOADALLOWED" checked=$vars.demo_all}
      </div>
      <div class="grid gap-4 border-t border-zinc-200 p-5 sm:grid-cols-2 dark:border-zinc-800">
        <div><label class="label" for="mfs">{"_MAXFILESIZE"|lang} (MB)</label><input id="mfs" type="number" min="1" name="max_file_size" value="{$vars.max_file_size}" class="input"><p class="hint">PHP upload_max_filesize: {$upload_limit}</p></div>
        <div><label class="label" for="ft">{"_FILE_ALLOWEDTYPES"|lang}</label><input id="ft" name="file_type" value="{$vars.file_type}" class="input font-mono"><p class="hint">{"_FILETYPES_HINT"|lang}</p></div>
      </div>
    </section>
  </div>
  {if $perms.websettings_edit == 'yes'}
    <div class="sticky bottom-4 mt-6 flex justify-end"><button name="action" value="save" class="btn btn-primary shadow-lg">{icon name="check" class="size-4"}{"_SAVE"|lang}</button></div>
  {/if}
</form>
{/block}
