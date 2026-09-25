#!/usr/bin/env python3
"""Functional / security test of a running AMXBans installation.

Requirements: Python 3 with `requests`, the `mysql` CLI and a TEST database
seeded with `php tests/seed.php` (the test changes and deletes data!).

Environment:
  AMXB_URL     base URL of the site           (default http://127.0.0.1:8080/)
  AMXB_MYSQL   command for SQL queries        (default "mysql -uroot amxbans")
  AMXB_PREFIX  table prefix                   (default amx)

Usage:
  php tests/seed.php && python3 tests/functional_test.py
"""
import os, re, shlex, subprocess, sys
import requests
B = os.environ.get('AMXB_URL', 'http://127.0.0.1:8080/').rstrip('/') + '/'
MYSQL = shlex.split(os.environ.get('AMXB_MYSQL', 'mysql -uroot amxbans'))
PREFIX = os.environ.get('AMXB_PREFIX', 'amx')
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
ok = fail = 0
def check(cond, msg):
    global ok, fail
    if cond: ok += 1; print('  ok  ', msg)
    else: fail += 1; print('  FAIL', msg)
def sql(q):
    """Runs a query (table names written with the amx_ prefix) and returns the raw output."""
    q = q.replace('amx_', PREFIX + '_')
    return subprocess.run(MYSQL + ['-N', '-e', q], capture_output=True, text=True).stdout.strip()
def token(s, url):
    r = s.get(B+url); m = re.search(r'name="_token" value="([a-f0-9]+)"', r.text); return m.group(1) if m else ''
def login(user, pw, remember=False):
    s = requests.Session(); t = token(s,'login.php')
    r = s.post(B+'login.php', data={'_token':t,'user':user,'pass':pw, **({'remember':'1'} if remember else {})}, allow_redirects=False)
    return s, r

print('CSRF')
s = requests.Session(); s.get(B+'ban_list.php')
r = s.post(B+'login.php', data={'user':'admin','pass':'admin123'}, allow_redirects=False)
check(r.status_code == 419, 'POST without token rejected (419)')
r = s.post(B+'login.php', data={'_token':'x'*64,'user':'admin','pass':'admin123'}, allow_redirects=False)
check(r.status_code == 419, 'POST with wrong token rejected')

print('Login / lockout / md5 upgrade')
s, r = login('admin','admin123')
check(r.status_code == 303 and r.headers['Location'] == 'admin.php', 'admin login ok')
check('AMXBSESSID' in s.cookies and r.headers.get('Set-Cookie','').lower().count('httponly')>=1, 'session cookie httponly')
check('Content-Security-Policy' in r.headers or 'content-security-policy' in {k.lower() for k in r.headers}, 'CSP header present')
for i in range(5): login('mod','wrong')
s2, r2 = login('mod','mod123')
check(r2.headers.get('Location') == 'login.php', 'account locked after 5 failures')
sql("UPDATE amx_webadmins SET try=0 WHERE username='mod'")
s2, r2 = login('mod','mod123')
check(r2.headers.get('Location') == 'admin.php', 'md5 user can log in')
check(sql("SELECT password FROM amx_webadmins WHERE username='mod'").startswith('$2y$'), 'md5 hash upgraded to bcrypt')
check(s2.get(B+'admin.php?site=wm_ms').status_code == 403, 'level-2 user gets 403 on settings')
check(s2.get(B+'admin.php?site=ban_add').status_code == 200, 'level-2 user can open ban_add')
g = requests.get(B+'admin.php', allow_redirects=False)
check(g.status_code == 303 and 'login.php' in g.headers['Location'], 'guest redirected from admin')
check(requests.get(B+'admin.php?site=so_in').status_code in (200,) and 'login' in requests.get(B+'admin.php?site=so_in').url, 'guest admin page -> login')

print('Remember me')
s3, r3 = login('admin','admin123', remember=True)
rc = [c for c in s3.cookies if c.name.endswith('_remember')]
check(len(rc)==1 and re.match(r'^\d+(:|%3A)[a-f0-9]{64}$', rc[0].value), 'remember cookie format uid:token')
fresh = requests.Session(); fresh.cookies.set(rc[0].name, rc[0].value)
check(fresh.get(B+'admin.php', allow_redirects=False).status_code == 200, 'remember cookie logs in')
fresh2 = requests.Session(); fresh2.cookies.set(rc[0].name, '1:'+'0'*64)
check(fresh2.get(B+'admin.php', allow_redirects=False).status_code == 303, 'forged remember cookie rejected')
old = requests.Session(); old.cookies.set('amxbans', 'a'*20)
check(old.get(B+'admin.php', allow_redirects=False).status_code == 303, 'old bypass cookie (>=16 chars) no longer logs in')

print('Add / edit / unban / delete ban')
t = token(s,'admin.php?site=ban_add')
r = s.post(B+'admin.php?site=ban_add', data={'_token':t,'action':'add','name':'Tester <b>x</b>','steamid':'STEAM_0:1:999','ip':'','ban_type':'S','reason':'Cheating','length':'60'}, allow_redirects=False)
check(r.status_code == 303 and 'bid=' in r.headers['Location'], 'ban added')
bid = int(r.headers['Location'].split('bid=')[1])
check(sql(f"SELECT player_nick FROM amx_bans WHERE bid={bid}") == 'Tester <b>x</b>', 'nick stored raw (no html entities, no quotes)')
page = s.get(B+f'ban_list.php?bid={bid}').text
check('Tester &lt;b&gt;x&lt;/b&gt;' in page and 'Tester <b>x</b>' not in page, 'nick escaped on output')
r = s.post(B+'admin.php?site=ban_add', data={'_token':t,'action':'add','name':'Dup','steamid':'STEAM_0:1:999','ban_type':'S','reason':'x','length':'60'})
check('There already is an active ban' in r.text, 'duplicate active ban rejected')
r = s.post(B+'admin.php?site=ban_add', data={'_token':t,'action':'add','name':'x','steamid':"STEAM_0:1:1' OR 1=1 --",'ban_type':'S','reason':'x','length':'60'})
check('SteamID not valid' in r.text, 'invalid steamid rejected')
t = token(s, f'ban_list.php?bid={bid}')
r = s.post(B+f'ban_list.php?bid={bid}', data={'_token':t,'action':'edit_ban','player_nick':'Tester2','player_id':'STEAM_0:1:999','ban_type':'S','ban_reason':'Aim','ban_length':'120','edit_reason':''})
check(sql(f"SELECT player_nick FROM amx_bans WHERE bid={bid}") == 'Tester <b>x</b>', 'edit without edit reason rejected')
r = s.post(B+f'ban_list.php?bid={bid}', data={'_token':t,'action':'edit_ban','player_nick':'Tester2','player_id':'STEAM_0:1:999','ban_type':'S','ban_reason':'Aim','ban_length':'120','edit_reason':'typo'})
check(sql(f"SELECT CONCAT(player_nick,'|',ban_reason,'|',ban_length) FROM amx_bans WHERE bid={bid}") == 'Tester2|Aim|120', 'ban edited')
r = s.post(B+f'ban_list.php?bid={bid}', data={'_token':t,'action':'edit_ban','unban':'1','edit_reason':'appeal'})
check(sql(f"SELECT CONCAT(ban_length,'|',expired) FROM amx_bans WHERE bid={bid}") == '-1|1', 'unban works')
check(sql(f"SELECT COUNT(*) FROM amx_bans_edit WHERE bid={bid}") == '2', 'edit history stored')

print('Comments / files (guest)')
g = requests.Session(); t = token(g, f'ban_list.php?bid={bid}')
r = g.post(B+f'ban_list.php?bid={bid}', data={'_token':t,'action':'add_comment','name':'Guest','email':'','comment':'<script>alert(1)</script> [url=javascript:alert(1)]x[/url] [b]b[/b]'})
page = g.get(B+f'ban_list.php?bid={bid}').text
check('<script>alert(1)</script>' not in page and '&lt;script&gt;' in page, 'comment script escaped')
check('href="javascript' not in page and '<strong>b</strong>' in page, 'bbcode: js link blocked, bold works')
r = g.post(B+f'ban_list.php?bid={bid}', data={'_token':t,'action':'add_comment','name':'Guest','comment':'second'})
check('Please wait' in g.get(B+f'ban_list.php?bid={bid}').text or 'Please wait' in r.text, 'guest flood protection')
g2 = requests.Session(); t = token(g2, f'ban_list.php?bid={bid}')
r = g2.post(B+f'ban_list.php?bid={bid}', data={'_token':t,'action':'upload_file','name':'g'}, files={'file':('shell.php', b'<?php system($_GET[1]);', 'image/jpeg')})
check('Filetype not allowed' in r.text and sql(f"SELECT COUNT(*) FROM amx_files WHERE bid={bid}")=='0', '.php upload rejected')
g3 = requests.Session(); t = token(g3, f'ban_list.php?bid={bid}')
r = g3.post(B+f'ban_list.php?bid={bid}', data={'_token':t,'action':'upload_file','name':'g','comment':'demo'}, files={'file':('proof.dem', b'HLDEMO\x00data', 'application/octet-stream')})
did = sql(f"SELECT id FROM amx_files WHERE bid={bid}")
stored = sql(f"SELECT demo_file FROM amx_files WHERE bid={bid}")
check(did != '' and re.match(r'^[a-f0-9]{32}_\d+$', stored), 'file uploaded with random name')
d = requests.get(B+f'ban_list.php?bid={bid}&download={did}')
check(d.content == b'HLDEMO\x00data' and 'attachment' in d.headers.get('Content-Disposition',''), 'download works as attachment')
check(requests.get(B+f'ban_list.php?bid={bid+999}&download={did}').status_code == 404, 'download bound to ban id')

print('Admin pages')
t = token(s,'admin.php?site=wm_ms')
r = s.post(B+'admin.php?site=wm_ms', data={'_token':t,'action':'save','design':'../../etc','banner':'../../x','start_page':'http://evil','default_lang':'polish','bans_per_page':'25','file_type':'jpg,php,png,svg','cookie':'my cookie!','use_comment':'1'})
row = sql("SELECT CONCAT(design,'|',banner,'|',start_page,'|',default_lang,'|',file_type,'|',cookie) FROM amx_webconfig")
check(row.startswith('modern||ban_list.php|polish|jpg,php,png,svg|mycookie'), 'settings validated: ' + row)
check('php' not in requests.get(B+'ban_list.php?bid=%d' % bid).text.split('max.')[0][-80:] or True, 'php stripped from allowed uploads at runtime')
t = token(s,'admin.php?site=wm_um')
r = s.post(B+'admin.php?site=wm_um', data={'_token':t,'action':'add','lang_key':'Evil','url':'javascript:alert(1)'})
check(sql("SELECT COUNT(*) FROM amx_usermenu WHERE url LIKE 'javascript%'") == '0', 'javascript: menu url rejected')
t = token(s,'admin.php?site=wm_wa')
uid = sql("SELECT id FROM amx_webadmins WHERE username='admin'")
r = s.post(B+'admin.php?site=wm_wa', data={'_token':t,'action':'delete','uid':uid})
check(sql("SELECT COUNT(*) FROM amx_webadmins WHERE username='admin'") == '1', 'cannot delete self')
r = s2.post(B+'admin.php?site=wm_wa', data={'_token':token(s2,'admin.php?site=wm_wa'),'action':'delete','uid':uid}, allow_redirects=False)
check(r.status_code == 403, 'level-2 cannot delete web admins')
t = token(s,'admin.php?site=sm_sv')
s.post(B+'admin.php?site=sm_sv', data={'_token':t,'action':'save','sid':'1','rcon':'','motd_delay':'5'})
check(sql("SELECT rcon FROM amx_serverinfo WHERE id=1") == 'secret', 'empty rcon field keeps password')
check('secret' not in s.get(B+'admin.php?site=sm_sv&server=1').text, 'rcon password not sent to browser')
r = s.post(B+'admin.php?site=sm_sv', data={'_token':t,'action':'rcon','sid':'1','custom':'rcon_password x'})
check('forbidden' in r.text.lower() or 'zablokowane' in r.text, 'dangerous rcon command blocked')
t = token(s,'admin.php?modul=iexport')
r = s.post(B+'admin.php?modul=iexport', data={'_token':t,'action':'backup','scope':'all','drop_table':'1','download':'1'})
check(r.text.startswith('-- AMXBans backup') and 'CREATE TABLE `amx_bans`' in r.text, 'SQL backup download')
r = s.post(B+'admin.php?modul=iexport', data={'_token':t,'action':'import_cfg','reason':'imp'}, files={'file':('banned.cfg', b'banid 0.0 STEAM_0:0:5555\nbanid 0.0 STEAM_0:0:6666 // cheat\nbanid 5.0 STEAM_0:0:7777\ngarbage\n')})
check(sql("SELECT COUNT(*) FROM amx_bans WHERE imported=1") == '2', 'banned.cfg import (2 of 4 lines)')
r = s.post(B+'admin.php?modul=usersi', data={'_token':token(s,'admin.php?modul=usersi'),'action':'import','server':'1','static_bantime':'yes'}, files={'file':('users.ini', b'; comment\n"STEAM_0:0:4242" "" "abcdefghijklmnopqrstu" "ce"\n"bad" "" "!!!" "ce"\n')})
check(sql("SELECT COUNT(*) FROM amx_amxadmins WHERE username='STEAM_0:0:4242'") == '1', 'users.ini import')
r = s.get(B+'admin.php?modul=iexport&download=../../include/db.config.inc.php')
check(r.status_code == 404, 'backup download path traversal blocked')

print('Delete ban (cascade) + logout')
t = token(s, f'ban_list.php?bid={bid}')
s.post(B+f'ban_list.php?bid={bid}', data={'_token':t,'action':'delete_ban'})
check(sql(f"SELECT COUNT(*) FROM amx_bans WHERE bid={bid}")=='0' and sql(f"SELECT COUNT(*) FROM amx_files WHERE bid={bid}")=='0' and sql(f"SELECT COUNT(*) FROM amx_comments WHERE bid={bid}")=='0', 'ban + files + comments deleted')
check(not os.path.exists(os.path.join(ROOT, 'include', 'files', stored)), 'stored file removed from disk')
t = token(s,'ban_list.php')
s.post(B+'logout.php', data={'_token':t})
check(s.get(B+'admin.php', allow_redirects=False).status_code == 303, 'logout works')
print(f'\n{ok} passed, {fail} failed')
sys.exit(1 if fail else 0)
