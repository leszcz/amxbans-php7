<?php
declare(strict_types=1);

/* Game servers: settings, RCON console, delete. Servers are added by the AMXBans plugin itself. */

require_once AMXB_ROOT . '/include/GameServer.php';

$sid = input_int('sid');
$rconCommands = [
    'reload'   => ['amx_reloadadmins', '_RCON_RELOADADMINS', 0],
    'restart'  => ['restart', '_RCON_RESTARTMAP', 0],
    'status'   => ['status', '_RCON_STATUS', 1],
    'plugins'  => ['amx_plugins', '_RCON_PLUGINS', 3],
    'modules'  => ['amx_modules', '_RCON_MODULES', 3],
    'metalist' => ['meta list', '_RCON_METALIST', 1],
];
$deniedCommands = ['rcon_password', 'sv_password', '_restart', 'quit', 'exit', 'changelevel', 'exec', 'writecfg', 'log ', 'logaddress', 'sv_downloadurl', 'alias'];

switch (action()) {
    case 'save':
        $server = server_find($sid) ?? abort(404);
        $data = [
            'amxban_motd'   => mb_substr(input('amxban_motd'), 0, 250),
            'motd_delay'    => max(0, min(60, input_int('motd_delay', 10))),
            'amxban_menu'   => input_bool('amxban_menu') ? 1 : 0,
            'reasons'       => input_int('reasons') ?: null,
            'timezone_fixx' => max(-12, min(12, input_int('timezone_fixx'))),
        ];
        $rcon = (string)($_POST['rcon'] ?? '');
        if ($rcon !== '') {
            if (str_contains($rcon, '"') || strlen($rcon) > 32) {
                flash('error', '_RCONPW_INVALID');
                redirect_back();
            }
            $data['rcon'] = $rcon;
        }
        if (input_bool('rcon_clear')) {
            $data['rcon'] = '';
        }
        Database::update('serverinfo', $data, ['id' => $sid]);
        log_to_db('Server config', 'Edited server: ' . $server['hostname']);
        flash('success', '_SERVERSAVED');
        redirect_back(['server' => $sid]);

    case 'delete':
        $server = server_find($sid) ?? abort(404);
        Database::transaction(function () use ($sid) {
            Database::delete('serverinfo', ['id' => $sid]);
            Database::delete('admins_servers', ['server_id' => $sid]);
        });
        log_to_db('Server config', 'Deleted server: ' . $server['hostname']);
        flash('success', '_SERVERDELETED');
        redirect_back(['server' => null]);

    case 'rcon':
        $server = server_find($sid) ?? abort(404);
        $preset = input('command');
        if (isset($rconCommands[$preset])) {
            [$command, , $pages] = $rconCommands[$preset];
        } else {
            $command = rcon_safe(input('custom'), 200);
            $pages = 1;
            foreach ($deniedCommands as $denied) {
                if ($command === '' || stripos($command, $denied) !== false) {
                    flash('error', '_RCON_CMDDENIED');
                    redirect_back(['server' => $sid]);
                }
            }
        }
        $response = (new GameServer((string)$server['address'], (string)$server['rcon']))->rcon($command, $pages);
        if ($response === null) {
            flash('error', '_RCON_TIMEDOUT');
        } elseif (GameServer::isBadPassword($response)) {
            flash('error', '_WRONGRCON');
        } else {
            $_SESSION['_rcon_output'] = ['sid' => $sid, 'command' => $command, 'response' => mb_substr($response, 0, 20000)];
        }
        log_to_db('RCON', $server['hostname'] . ': ' . $command);
        redirect_back(['server' => $sid]);
}

$servers = servers_all();
foreach ($servers as &$s) {
    unset($s['rcon']); // never send the RCON password to the browser
    $s['has_rcon'] = (string)Database::value('SELECT `rcon` FROM ' . Database::table('serverinfo') . ' WHERE `id` = :id', ['id' => $s['id']]) !== '';
}
unset($s);
$output = $_SESSION['_rcon_output'] ?? null;
unset($_SESSION['_rcon_output']);

$base = (Security::isHttps() ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(Security::cookiePath(), '/');
$view->page('admin/servers.tpl', [
    'servers'      => $servers,
    'active'       => query_int('server', (int)($servers[0]['id'] ?? 0)),
    'reason_sets'  => Database::all('SELECT * FROM ' . Database::table('reasons_set') . ' ORDER BY `setname`'),
    'rcon_presets' => $rconCommands,
    'output'       => $output,
    'motd_url'     => $base . '/motd.php?sid=%s&adm=%d&lang=%s',
], '_TITLESERVER');
