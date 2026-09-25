# AMXBans for PHP 8

Web interface for [AMXBans](https://github.com/leszcz/amxbans-php7) – ban management for Counter-Strike 1.6 / GoldSrc servers running AMX Mod X.
Version 7 is a security-focused rewrite of the AMXBans 6 web panel with a new, responsive interface (Tailwind CSS + Alpine.js).
The database schema is unchanged, so the AMXBans game plugin keeps working with the same tables.

## Requirements

* PHP **8.1** or newer with the extensions `pdo_mysql`, `mbstring` (and `gd` for captcha and thumbnails)
* MySQL 5.7+ / MariaDB 10.3+
* Apache 2.4 (with `AllowOverride All`) or nginx – see below
* HTTPS is strongly recommended (login cookies are marked `Secure` automatically on HTTPS)

## Installation

1. Upload all files (including `vendor/` and `assets/dist/`) to your web server.
2. Make these directories writable for PHP: `templates_c/`, `include/` (only for the installer), `include/files/`, `include/backup/`.
3. Open `setup.php` in the browser and follow the steps.
4. Delete `setup.php` (the installer offers a button for that). It refuses to run while `include/db.config.inc.php` exists.

### Upgrading from AMXBans 6 / older versions of this repository

1. Back up your database and files.
2. Replace the files with this version, but keep `include/db.config.inc.php` and `include/files/`.
3. Open the site – no database migration is required. Existing web admin passwords (MD5) are upgraded to bcrypt on the next login.
4. The old `default` templates were removed (not compatible with Smarty 5); installations that still have `default` selected automatically use `modern`. You can switch to `classic` (the old look) in *Admin area → Settings*.

If you do not have the old config file any more, run `setup.php` with the same table prefix – existing tables are detected and kept.

## Deployment

GitHub Actions run the tests on every pull request and deploy `main` to production over SSH (rsync) when the `DEPLOY_*` secrets are set.
Pushing a `v*` tag publishes a release with a ready-to-upload ZIP. See [docs/deployment.md](docs/deployment.md).

## Web server configuration

The application must only serve the PHP entry points in the root directory and `assets/`, `images/`.
The included `.htaccess` files block `include/`, `templates/`, `templates_c/`, `vendor/`, `language/`, `install/`, uploads and backups on Apache.

For **nginx** add to the `server` block:

```nginx
location ~ ^/(include|templates|templates_c|vendor|language|install|build|temp|node_modules|tests|docs)(/|$) { deny all; return 404; }
location ~ /\.(?!well-known) { deny all; }
location ~* \.(tpl|inc|sql|ini|log|md|lock)$ { deny all; }
location ~* ^/images/.*\.php$ { deny all; }
```

## Security overview

* Every database query uses prepared statements through a single `Database` class (`include/Database.php`).
* Every POST request requires a CSRF token; sessions are hardened (strict mode, HttpOnly, SameSite, id rotation).
* Templates escape all variables automatically (Smarty 5 `escape_html`); a strict Content-Security-Policy forbids inline scripts.
* Web admin passwords use `password_hash()`; accounts are locked for 15 minutes after 5 failed logins.
* Uploads get random names, cannot be script files and are only downloadable through PHP.
* RCON passwords are never sent to the browser; RCON arguments are sanitized.

AMX Mod X admin passwords (`_amxadmins.password`) are still stored as MD5 because the game plugin compares them that way.

## Development

Developer documentation (in Polish) is in [`docs/`](docs/README.md): architecture, adding pages, security rules,
database schema, templates, frontend, translations and testing.

Directory layout:

| Path | Purpose |
| --- | --- |
| `*.php` (root) | Entry points (ban list, search, admin area, installer, MOTD) |
| `include/bootstrap.php` | Loaded by every page: config, session, CSRF, auth, view |
| `include/Database.php` | The only place that creates the PDO connection |
| `include/admin/`, `include/modules/` | Admin pages and modules (`admin.php?site=…`, `admin.php?modul=…`) |
| `templates/modern/` | Smarty 5 templates |
| `assets/src/app.css`, `assets/js/app.js` | Tailwind sources and Alpine.js components |
| `language/` | Translations (`lang.<language>*.php`, parsed – never executed) |

PHP dependencies (Smarty) are managed with Composer, the frontend with npm:

```bash
composer install --no-dev      # vendor/ (committed, so FTP users do not need Composer)
npm install
npm run build                  # Tailwind CSS -> assets/dist/app.css, Alpine.js, icons
npm run watch:css              # rebuild CSS while editing templates
```

Alpine.js is used in its CSP build, so components are registered in `assets/js/app.js` and templates must not interpolate
user data into Alpine expressions – pass data through `data-*` attributes instead.
