# Baza danych

Schemat jest zgodny z AMXBans 6 – **plugin AMXX na serwerach gry czyta i zapisuje te same tabele**, więc nie zmieniaj nazw tabel ani kolumn. Definicje: `install/schema.php`. Wszystkie tabele mają wspólny prefiks (domyślnie `amx`, ustawiany w instalatorze i w pluginie).

## Klasa `Database`

| Metoda | Zwraca | Przykład |
| --- | --- | --- |
| `table('bans')` | `` `amx_bans` `` | nazwy tabel zawsze przez tę metodę |
| `run($sql, $params)` | `PDOStatement` | gdy potrzebny `rowCount()` |
| `one($sql, $params)` | wiersz lub `null` | `Database::one('SELECT … WHERE `bid` = :b', ['b' => 5])` |
| `all($sql, $params)` | lista wierszy | |
| `column($sql, $params)` | lista wartości 1. kolumny | |
| `value($sql, $params)` | pojedyncza wartość | `COUNT(*)` |
| `insert('bans', [...])` | nowe id | |
| `update('bans', [...], ['bid' => 5])` | liczba zmienionych | WHERE jest wymagany |
| `delete('comments', ['bid' => 5])` | liczba usuniętych | WHERE jest wymagany |
| `transaction(fn () => …)` | wynik callbacka | rollback przy wyjątku |

Uwagi:
* Liczby całkowite są wiązane jako `PARAM_INT` – `LIMIT :offset, :limit` działa bez rzutowania.
* Przy natywnych prepared statements ten sam placeholder nie może wystąpić dwa razy – użyj `:ip1`, `:ip2`.
* `getPDO()` zwraca surowe PDO (dla starych modułów).

## Tabele

| Tabela | Kto zapisuje | Opis |
| --- | --- | --- |
| `bans` | plugin, WWW | Bany (szczegóły niżej) |
| `bans_edit` | WWW, pruning | Historia zmian bana: `bid`, `edit_time`, `admin_nick`, `edit_reason` |
| `serverinfo` | plugin (rejestracja), WWW (ustawienia) | Serwery: `address` (ip:port), `hostname`, `gametype` (mod), `rcon`, `amxban_version`, `amxban_motd` (URL MOTD), `motd_delay`, `amxban_menu`, `reasons` (id zestawu powodów), `timezone_fixx` (godziny) |
| `amxadmins` | WWW | Admini AMXX: `username` (SteamID/IP/nick), `password` (MD5), `access` (flagi dostępu), `flags` (flagi konta), `steamid`, `nickname`, `ashow` (widoczny na liście), `created`, `expired` (0 = bez końca), `days` |
| `admins_servers` | WWW | Przypisanie adminów do serwerów: `admin_id`, `server_id`, `custom_flags`, `use_static_bantime` (yes/no) |
| `reasons`, `reasons_set`, `reasons_to_set` | WWW | Powody banów (z opcjonalnym stałym czasem) i ich zestawy dla serwerów |
| `webadmins` | WWW | Konta panelu: `username`, `password` (bcrypt), `level`, `email`, `logcode` (SHA-256 tokenu „zapamiętaj mnie”), `last_action`, `try` (nieudane logowania) |
| `levels` | WWW | Poziomy uprawnień – kolumny = uprawnienia (`Auth::PERMISSIONS`) |
| `webconfig` | WWW | Ustawienia strony (jeden wiersz), ładowane przez `settings_load()` |
| `usermenu` | WWW | Menu publiczne: `url`/`lang_key` dla gości, `url2`/`lang_key2` dla zalogowanych |
| `modulconfig` | WWW | Moduły: `name` (plik `modul_<name>.php`), `menuname`, `activ` |
| `comments`, `files` | WWW | Komentarze i pliki przy banach (`bid`) |
| `logs` | WWW | Dziennik działań (`log_to_db()`) |
| `smilies`, `bbcode` | instalator | Emotikony i przyciski BBCode |
| `flagged` | plugin | Gracze oznaczeni przez adminów (nieużywane przez WWW) |

## Semantyka banów (`bans`)

| Kolumna | Znaczenie |
| --- | --- |
| `player_id`, `player_ip`, `player_nick` | SteamID (`STEAM_0:X:Y`), IP, nick gracza |
| `admin_id`, `admin_ip`, `admin_nick` | kto zbanował (dla banów z WWW: nazwa web admina) |
| `ban_type` | `S` = po SteamID, `SI` = po IP |
| `ban_reason` (`cs_ban_reason`) | powód (kopia dla pluginu) |
| `ban_created` | znacznik czasu **czasu serwera gry**; do wyświetlania dodaj `serverinfo.timezone_fixx * 3600` |
| `ban_length` | minuty; `0` = permanentny, `-1` = odbanowany |
| `expired` | `1` = nieaktywny (upłynął czas lub odbanowany) |
| `server_ip`, `server_name` | serwer; `server_name = 'website'` dla banów z panelu |
| `ban_kicks` | ile razy plugin wyrzucił gracza z tym banem |
| `imported` | `1` = zaimportowany z `banned.cfg` |

Funkcja `ban_present()` dodaje do wiersza pola wyliczone (`created`, `ban_end`, `active`, `permanent`, `unbanned`, `website`, `steam_url`, `cc`/`cn` – kraj z GeoIP, `admin_name`) – szablony korzystają z nich zamiast liczyć same. Bany, którym minął czas, oznacza jako `expired` funkcja `bans_prune()` (automatycznie przy włączonym `auto_prune`).

## Migracje

Nie ma systemu migracji – schemat musi pozostać zgodny z pluginem. Nowe tabele dla własnych funkcji dodawaj w `install_schema()` (instalator tworzy tylko brakujące tabele: `CREATE TABLE IF NOT EXISTS`), a dla istniejących instalacji opisz SQL w CHANGELOG.
