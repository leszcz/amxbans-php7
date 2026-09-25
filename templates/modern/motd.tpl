<!DOCTYPE html>
<html lang="{$app.html_lang}" class="dark">
<head>
  <meta charset="utf-8">
  <title>AMXBans</title>
  <link rel="stylesheet" href="assets/dist/app.css?v={$asset_ver}">
</head>
<body class="p-6">
  <div class="mx-auto max-w-xl rounded-xl border border-red-900/60 bg-zinc-900 p-6 shadow-2xl">
    <div class="mb-5 flex items-center gap-3 text-red-400">
      {icon name="no-symbol" class="size-8"}
      <h1 class="text-2xl font-bold">{"_YOUAREBANNED"|lang}</h1>
    </div>
    <dl class="dl-grid">
      <dt>{"_NICKNAME"|lang}</dt><dd>{$ban.player_nick}</dd>
      {if $ban.has_steamid}<dt>{"_STEAMID"|lang}</dt><dd class="font-mono">{$ban.player_id}</dd>{/if}
      <dt>{"_REASON"|lang}</dt><dd>{$ban.ban_reason}</dd>
      <dt>{"_INVOKED"|lang}</dt><dd>{$ban.created|datetime}</dd>
      <dt>{"_BANLENGHT"|lang}</dt><dd>{$ban.ban_length|banlength}</dd>
      {if $ban.ban_end}<dt>{"_EXPIRES"|lang}</dt><dd>{$ban.ban_end|datetime}</dd>{/if}
      {if $show_admin}<dt>{"_BANBY"|lang}</dt><dd>{$ban.admin_name}</dd>{/if}
      <dt>{"_BANON"|lang}</dt><dd>{$ban.server_name}</dd>
    </dl>
    <p class="mt-6 text-sm text-zinc-400">{"_MOTD_APPEAL"|lang}</p>
  </div>
</body>
</html>
