# Changelog for AmxBans GM working in PHP 8

## 7.0.0 – 2026/09/25

### Documentation
* PHPDoc blocks for every PHP file, class, method and function (types incl. `list<…>` / `array{…}`), JSDoc for Alpine components, variable lists in all templates.
* Developer documentation in `docs/` (architecture, adding pages, security rules, database, templates, frontend, translations, testing) and `CONTRIBUTING.md`.
* `phpdoc.dist.xml` (phpDocumentor) and `phpstan.neon.dist` (PHPStan level 6, no errors).
* `tests/seed.php` (test data) and `tests/functional_test.py` (47 HTTP/security checks).

### Designs
* New `classic` design recreating the old AMXBans GM look; designs can override only some templates (fallback to `modern`).

### Security
* One `Database` class for every connection; all queries use prepared statements (no more `sql_safe()` string building).
* Fixed a login bypass in the remember-me code (any cookie with 16+ characters logged the visitor in).
* CSRF protection for every form, hardened sessions, security headers and a strict Content-Security-Policy.
* Smarty 3.1.30 (known CVEs) replaced by Smarty 5 with automatic output escaping; stored XSS in comments/BBCode fixed.
* Uploads and imports can no longer place `.php` files in web-accessible directories.
* RCON command injection via ban reasons fixed; RCON passwords are no longer sent to the browser.
* Removed the version check that connected to a third-party database with hard-coded credentials.
* Correct permission checks on all admin pages; web admin passwords are no longer sent by e-mail.
* Installer writes the configuration safely and locks itself after installation.

### Interface
* New responsive `modern` design (Tailwind CSS 4, Alpine.js) with dark mode.
* Live server status loaded asynchronously, expandable ban rows, modals for editing, AMX Mod X flag picker.
* Polish translations for all new texts.

## 2024/10/04

* Change array() to [];
* Change old mysql reporting to mysqli_connection_errno() 

* steam.inc.php 

Replaced access to characters in strings using curly braces ($Num1{$i}) with access using square brackets ($Num1[$i]).<br>
Removed @ operator from accessing characters in strings, as it is no longer necessary in PHP 8.2.<br>
Changed access to characters in strings in Add and Mul functions.

* config.inc.php 

1. Replaced check for functions using get_magic_quotes_gpc() with if( function_exists('get_magic_quotes_gpc') ).
2. Changed the htmlsafe_recursive() function to return a new value instead of modifying the original one.
3. Removed the get_magic_quotes_gpc() check, as this function is deprecated and removed in PHP 7.4+.
4. Applied htmlsafe_recursive() to the $_GET, $_POST, and $_COOKIE superglobals.
5. Updated the database connection to use a single call to mysqli_connect().
6. Changed the encoding to utf8mb4, which is recommended for full Unicode support.
7. Updated the while loop to use mysqli_fetch_assoc() instead of list() and mysqli_fetch_[].
8. Changed the dynamicPage class constructor to __construct().
9. Minor fixes in the getipbyhost() function.

* include/functions.inc.php

Replaced check for functions using get_magic_quotes_gpc() with if( function_exists('get_magic_quotes_gpc') ).

* install/functions.inc

The main change in this function is that $path{strlen($path)-1} has been replaced with substr($path, -1), which is a safe way to get the last character of a string in PHP 8.2.

* setup.php 

1. Initialize $config object as stdClass.<br>
2. Removed curly brace syntax for accessing string elements.<br>
3. Updated dynamicPage class constructor to PHP 7+ syntax.<br>
4. Use nullsafe (??) operator when assigning $msg variable to Smarty.<br>
5. General syntax and formatting fixes.