<?php
declare(strict_types=1);

/*
 * AMXBans database schema (compatible with the AMXBans 6 game plugin) and default data.
 */

/** @return array<string,string> table name (without prefix) => column definitions */
function install_schema(): array
{
    return [
        'admins_servers' => "admin_id int(11) NOT NULL, server_id int(11) NULL, custom_flags varchar(32) NOT NULL DEFAULT '',
            use_static_bantime enum('yes','no') NOT NULL DEFAULT 'yes', KEY admin_id (admin_id), KEY server_id (server_id)",
        'amxadmins' => "id int(12) NOT NULL auto_increment, username varchar(32) NULL, password varchar(50) NULL, access varchar(32) NULL,
            flags varchar(32) NULL, steamid varchar(32) NULL, nickname varchar(32) NULL, icq int(9) NULL, ashow int(11) NULL,
            created int(11) NULL, expired int(11) NULL, days int(11) NULL, PRIMARY KEY (id), KEY steamid (steamid)",
        'bans' => "bid int(11) NOT NULL auto_increment, player_ip varchar(32) NULL, player_id varchar(35) NULL,
            player_nick varchar(100) NULL DEFAULT 'Unknown', admin_ip varchar(32) NULL, admin_id varchar(35) DEFAULT 'Unknown',
            admin_nick varchar(100) NULL DEFAULT 'Unknown', ban_type varchar(10) NULL DEFAULT 'S', ban_reason varchar(100) NULL,
            cs_ban_reason varchar(100) NULL, ban_created int(11) NULL, ban_length int(11) NULL, server_ip varchar(32) NULL,
            server_name varchar(100) NULL DEFAULT 'Unknown', ban_kicks int(11) NOT NULL DEFAULT '0', expired int(1) NOT NULL DEFAULT '0',
            imported int(1) NOT NULL DEFAULT '0', PRIMARY KEY (bid), KEY player_id (player_id), KEY player_ip (player_ip),
            KEY expired_created (expired, ban_created)",
        'bans_edit' => "id int(11) NOT NULL auto_increment, bid int(11) NOT NULL, edit_time int(11) NOT NULL,
            admin_nick varchar(32) NOT NULL DEFAULT 'unknown', edit_reason varchar(255) NOT NULL, PRIMARY KEY (id), KEY bid (bid)",
        'bbcode' => "id int(11) NOT NULL auto_increment, open_tag varchar(32) NULL, close_tag varchar(32) NULL, url varchar(32) NULL,
            name varchar(32) NULL, PRIMARY KEY (id)",
        'comments' => "id int(11) NOT NULL auto_increment, name varchar(35) NULL, comment text NULL, email varchar(100) NULL,
            addr varchar(45) NULL, date int(11) NULL, bid int(11) NULL, PRIMARY KEY (id), KEY bid (bid)",
        'files' => "id int(11) NOT NULL auto_increment, upload_time int(11) NULL, down_count int(11) NULL, bid int(11) NULL,
            demo_file varchar(100) NULL, demo_real varchar(100) NULL, file_size int(11) NULL, comment text NULL, name varchar(64) NULL,
            email varchar(64) NULL, addr varchar(45) NULL, PRIMARY KEY (id), KEY bid (bid)",
        'levels' => "level int(12) NOT NULL, bans_add enum('yes','no') NULL DEFAULT 'no', bans_edit enum('yes','no','own') NULL DEFAULT 'no',
            bans_delete enum('yes','no','own') NULL DEFAULT 'no', bans_unban enum('yes','no','own') NULL DEFAULT 'no',
            bans_import enum('yes','no') NULL DEFAULT 'no', bans_export enum('yes','no') NULL DEFAULT 'no',
            amxadmins_view enum('yes','no') NULL DEFAULT 'no', amxadmins_edit enum('yes','no') NULL DEFAULT 'no',
            webadmins_view enum('yes','no') NULL DEFAULT 'no', webadmins_edit enum('yes','no') NULL DEFAULT 'no',
            websettings_view enum('yes','no') NULL DEFAULT 'no', websettings_edit enum('yes','no') NULL DEFAULT 'no',
            permissions_edit enum('yes','no') NULL DEFAULT 'no', prune_db enum('yes','no') NULL DEFAULT 'no',
            servers_edit enum('yes','no') NULL DEFAULT 'no', ip_view enum('yes','no') NULL DEFAULT 'no', PRIMARY KEY (level)",
        'logs' => "id int(11) NOT NULL auto_increment, timestamp int(11) NULL, ip varchar(45) NULL, username varchar(32) NULL,
            action varchar(64) NULL, remarks varchar(256) NULL, PRIMARY KEY (id)",
        'modulconfig' => "id int(11) NOT NULL auto_increment, menuname varchar(32) NULL, name varchar(32) NULL, `index` varchar(32) NULL,
            activ int(1) NOT NULL DEFAULT '1', PRIMARY KEY (id)",
        'reasons' => "id int(11) NOT NULL auto_increment, reason varchar(100) NULL, static_bantime int(11) NOT NULL DEFAULT '0', PRIMARY KEY (id)",
        'reasons_set' => "id int(11) NOT NULL auto_increment, setname varchar(32) NULL, PRIMARY KEY (id)",
        'reasons_to_set' => "id int(11) NOT NULL auto_increment, setid int(11) NOT NULL, reasonid int(11) NOT NULL, PRIMARY KEY (id)",
        'serverinfo' => "id int(11) NOT NULL auto_increment, timestamp int(11) NULL, hostname varchar(100) NULL DEFAULT 'Unknown',
            address varchar(100) NULL, gametype varchar(32) NULL, rcon varchar(32) NULL, amxban_version varchar(32) NULL,
            amxban_motd varchar(250) NULL, motd_delay int(10) NULL DEFAULT '10', amxban_menu int(10) NOT NULL DEFAULT '1',
            reasons int(10) NULL, timezone_fixx int(11) NOT NULL DEFAULT '0', PRIMARY KEY (id)",
        'smilies' => "id int(5) NOT NULL auto_increment, code varchar(32) NULL, url varchar(32) NULL, name varchar(32) NULL, PRIMARY KEY (id)",
        'usermenu' => "id int(11) NOT NULL auto_increment, pos int(11) NULL, activ tinyint(1) NOT NULL DEFAULT '1', lang_key varchar(64) NULL,
            url varchar(64) NULL, lang_key2 varchar(64) NULL, url2 varchar(64) NULL, PRIMARY KEY (id)",
        'webadmins' => "id int(12) NOT NULL auto_increment, username varchar(32) NULL, password varchar(255) NULL,
            level int(11) NULL DEFAULT '99', logcode varchar(64) NULL, email varchar(64) NULL, last_action int(11) NULL,
            try int(1) NOT NULL default '0', PRIMARY KEY (id), UNIQUE (username)",
        'webconfig' => "id int(11) NOT NULL auto_increment, cookie varchar(32) NULL, bans_per_page int(11) NULL, design varchar(32) NULL,
            banner varchar(64) NULL, banner_url varchar(128) NOT NULL DEFAULT '', default_lang varchar(32) NULL, start_page varchar(64) NULL,
            show_comment_count int(1) NULL DEFAULT '1', show_demo_count int(1) NULL DEFAULT '1', show_kick_count int(1) NULL DEFAULT '1',
            demo_all int(1) NOT NULL DEFAULT '0', comment_all int(1) NOT NULL DEFAULT '0', use_capture int(1) NULL DEFAULT '1',
            max_file_size int(11) NULL DEFAULT '2', file_type varchar(64) NULL DEFAULT 'dem,zip,rar,jpg,gif,png',
            auto_prune int(1) NOT NULL DEFAULT '0', max_offences SMALLINT NOT NULL DEFAULT '10',
            max_offences_reason VARCHAR(128) NOT NULL DEFAULT 'max offences reached', use_demo int(1) NULL DEFAULT '1',
            use_comment int(1) NULL DEFAULT '1', PRIMARY KEY (id)",
        'flagged' => "fid int(11) NOT NULL auto_increment, player_ip varchar(32) default NULL, player_id varchar(35) default NULL,
            player_nick varchar(100) default 'Unknown', admin_ip varchar(32) default NULL, admin_id varchar(35) default NULL,
            admin_nick varchar(100) default 'Unknown', reason varchar(100) default NULL, created int(11) default NULL,
            length int(11) default NULL, server_ip varchar(100) default NULL, PRIMARY KEY (fid), KEY player_id (player_id)",
    ];
}

/** Default rows inserted into empty tables: table => list of rows. */
function install_default_data(string $language): array
{
    $smilies = [[':D', 'big_smile.png', 'Big Grin'], ['8)', 'cool.png', 'Cool'], [':/', 'hmm.png', 'Hmm'], ['lol', 'lol.png', 'lol'],
        [':(', 'mad.png', 'Mad'], [':|', 'neutral.png', 'Neutral'], [':roll:', 'roll.png', 'RollEyes'], [':*(', 'sad.png', 'Sad'],
        [':)', 'smile.png', 'Smile'], [':P', 'tongue.png', 'Tongue'], [';)', 'wink.png', 'Wink'], [':O', 'yikes.png', 'Yikes']];
    $menu = [['_HOME', 'index.php'], ['_BANLIST', 'ban_list.php'], ['_ADMLIST', 'admin_list.php'], ['_SEARCH', 'search.php'], ['_SERVER', 'view.php']];

    $data = [
        'smilies'  => array_map(fn($s) => ['code' => $s[0], 'url' => $s[1], 'name' => $s[2]], $smilies),
        'bbcode'   => [
            ['open_tag' => '[b]', 'close_tag' => '[/b]', 'url' => 'bold.png', 'name' => 'bold'],
            ['open_tag' => '[i]', 'close_tag' => '[/i]', 'url' => 'italic.png', 'name' => 'italic'],
            ['open_tag' => '[u]', 'close_tag' => '[/u]', 'url' => 'underline.png', 'name' => 'underline'],
            ['open_tag' => '[center]', 'close_tag' => '[/center]', 'url' => 'center.png', 'name' => 'center'],
        ],
        'usermenu' => array_map(fn($m, $i) => ['pos' => $i + 1, 'activ' => 1, 'lang_key' => $m[0], 'url' => $m[1], 'lang_key2' => $m[0], 'url2' => $m[1]], $menu, array_keys($menu)),
        'webconfig' => [[
            'cookie' => 'amxbans_' . bin2hex(random_bytes(3)), 'bans_per_page' => 50, 'design' => 'modern', 'banner' => '', 'banner_url' => '',
            'default_lang' => $language, 'start_page' => 'ban_list.php', 'show_comment_count' => 1, 'show_demo_count' => 1,
            'show_kick_count' => 1, 'demo_all' => 0, 'comment_all' => 0, 'use_capture' => 1, 'max_file_size' => 2,
            'file_type' => 'dem,zip,rar,jpg,gif,png', 'auto_prune' => 1, 'use_demo' => 1, 'use_comment' => 1,
        ]],
        'levels' => [array_merge(['level' => 1], array_fill_keys([
            'bans_add', 'bans_edit', 'bans_delete', 'bans_unban', 'bans_import', 'bans_export', 'amxadmins_view', 'amxadmins_edit',
            'webadmins_view', 'webadmins_edit', 'websettings_view', 'websettings_edit', 'permissions_edit', 'prune_db',
            'servers_edit', 'ip_view'], 'yes'))],
        'modulconfig' => [
            ['menuname' => '_MENUIMPORTEXPORT', 'name' => 'iexport', 'index' => '', 'activ' => 1],
            ['menuname' => '_MENUIMPORTADMINS', 'name' => 'usersi', 'index' => '', 'activ' => 1],
        ],
    ];
    return $data;
}
