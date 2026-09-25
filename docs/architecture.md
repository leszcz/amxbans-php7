# Architektura

AMXBans to klasyczna aplikacja PHP bez frameworka: każdy plik w katalogu głównym jest punktem wejścia (kontrolerem), który ładuje wspólny `include/bootstrap.php`, wykonuje logikę i renderuje szablon Smarty. Dzięki temu działa na zwykłym hostingu (FTP, bez Composera i Node po stronie serwera).

## Struktura katalogów

```
├── index.php, ban_list.php, search.php,      punkty wejścia (strony publiczne)
│   admin_list.php, view.php, login.php,
│   logout.php, motd.php, captcha.php
├── admin.php                                 router panelu admina (?site= / ?modul=)
├── setup.php                                 instalator (działa bez bootstrapu)
├── include/
│   ├── bootstrap.php     start każdej strony
│   ├── Database.php      jedyne miejsce tworzące połączenie PDO
│   ├── Security.php      sesja, nagłówki, CSRF
│   ├── Auth.php          logowanie, uprawnienia, "zapamiętaj mnie"
│   ├── Lang.php          tłumaczenia
│   ├── View.php          Smarty 5 + pluginy
│   ├── GameServer.php    zapytania A2S i RCON do serwerów HLDS
│   ├── helpers.php       funkcje pomocnicze (input, redirect, flash, walidacja, formatowanie…)
│   ├── queries.php       zapytania SQL używane przez wiele stron
│   ├── icons.php         ikony Heroicons (generowane: npm run icons)
│   ├── admin/            strony panelu: admin_<site>.php
│   ├── modules/          moduły: modul_<nazwa>.php
│   ├── user/user_bd.php  szczegóły bana (ban_list.php?bid=)
│   ├── files/            wgrane dema/zrzuty (niedostępne przez HTTP)
│   ├── backup/           kopie SQL (niedostępne przez HTTP)
│   ├── db.config.inc.php konfiguracja bazy (tworzy setup.php, nie jest w repo)
│   └── *.inc.php         cienkie nakładki zgodności z AMXBans 6 (@deprecated)
├── templates/
│   ├── modern/           domyślny design (wszystkie szablony)
│   └── classic/          design w stylu starego AMXBans GM (nadpisuje tylko layouty)
├── templates_c/          skompilowane szablony (zapisywalny)
├── language/             tłumaczenia lang.<język>[.<część>].php
├── assets/src|js|dist    źródła i skompilowany CSS/JS
├── install/schema.php    schemat bazy i dane domyślne
├── images/               flagi, ikony gier, bannery
├── build/                skrypty npm (ikony, kopiowanie Alpine)
├── tests/                dane testowe i test funkcjonalny
└── vendor/               Composer (Smarty 5)
```

Katalogi `include/`, `templates/`, `templates_c/`, `vendor/`, `language/`, `install/`, `build/`, `tests/`, `temp/` są zablokowane dla HTTP przez `.htaccess` (dla nginx: patrz README).

## Cykl życia żądania

```
przeglądarka ──► ban_list.php
                   │ require include/bootstrap.php
                   │   1. autoload Composera + klasy projektu
                   │   2. include/db.config.inc.php  (brak ⇒ redirect do setup.php)
                   │   3. Security::sendHeaders(), Security::startSession()
                   │   4. settings_load($config)      ← tabela _webconfig
                   │   5. Lang::init(), Auth::init()  ← sesja / ciasteczko "zapamiętaj mnie"
                   │   6. Security::verifyCsrfOnPost()  (POST bez tokenu ⇒ 419)
                   │   7. $view = new View($config)
                   │
                   │ logika kontrolera: odczyt danych (query()/input()), akcje POST
                   │   akcja POST ⇒ zmiana w bazie ⇒ flash() ⇒ redirect_back()   (Post/Redirect/Get)
                   │
                   └ $view->page('ban_list.tpl', [...], '_TITLEBANLIST')  ⇒ HTML, exit
```

Globalne zmienne po bootstrapie: `$config` (ustawienia bazy + `_webconfig`) i `$view` (instancja `View`). Pozostały stan jest w klasach statycznych (`Database`, `Auth`, `Lang`).

## Routing panelu admina

`admin.php` wymaga zalogowania, a następnie:

* `admin.php?site=<site>` ładuje `include/admin/admin_<site>.php`, **tylko** jeżeli `<site>` jest na liście `$pages` w `admin.php`. Lista zawiera też uprawnienie potrzebne do otwarcia strony.
* `admin.php?modul=<nazwa>` ładuje `include/modules/modul_<nazwa>.php`, tylko dla modułów włączonych w `_modulconfig` (`modules_active()`).
* Menu boczne buduje `admin_navigation()`, ukrywając pozycje bez uprawnień.

Strona panelu to zwykły skrypt PHP wykonywany w zasięgu `admin.php` (ma dostęp do `$config`, `$view`). Obsługuje akcje przez `switch (action())` i kończy się `$view->page('admin/…tpl', …)`.

## Uprawnienia

Admin ma jeden poziom (`_webadmins.level` → `_levels`). Kolumny poziomu to uprawnienia (`Auth::PERMISSIONS`) o wartości `yes`/`no`; `bans_edit`, `bans_delete`, `bans_unban` mogą mieć też `own` (tylko własne bany).

```php
Auth::require();                    // tylko zalogowani
Auth::require('servers_edit');      // 403 bez uprawnienia
if (Auth::can('ip_view')) { … }
if (Auth::canOnBan('bans_edit', $ban)) { … }   // obsługuje "own"
```

W szablonach: `{if $perms.ip_view == 'yes'}`.

## Komunikacja z serwerami gry

`GameServer` rozmawia z HLDS przez UDP:

* `info()`, `players()`, `rules()` – publiczne zapytania A2S (strona „Serwery”, `view.php?server=ID` zwraca JSON),
* `rcon()` – komendy RCON (hasło z `_serverinfo.rcon`, nigdy nie trafia do przeglądarki),
* `amxList()` – komenda `amx_list` pluginu AMXBans (gracze z SteamID/IP do banowania online).

Argumenty komend wstawiane do RCON muszą przejść przez `rcon_safe()`.

## Zgodność z AMXBans 6

* Schemat bazy jest niezmieniony – korzysta z niego plugin AMXX na serwerach.
* Stare pliki `include/config.inc.php`, `sql.inc.php` itd. tylko ładują bootstrap; `getPDO()`, `has_access()`, `html_safe()` istnieją dla starych modułów.
* Hasła web adminów w MD5 są automatycznie zamieniane na bcrypt przy logowaniu.
