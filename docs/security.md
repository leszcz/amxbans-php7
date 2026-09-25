# Bezpieczeństwo

Poniższe zasady obowiązują każdy nowy kod. Większość jest wymuszana przez warstwy wspólne – wystarczy z nich korzystać.

## SQL

* Połączenie tworzy wyłącznie `Database::pdo()` (natywne prepared statements, wyjątki, utf8mb4).
* Wartości zawsze jako parametry: `Database::one('… WHERE `bid` = :bid', ['bid' => $bid])`.
* Nazwy tabel tylko przez `Database::table('bans')` (dodaje prefiks, waliduje, cytuje). Nazwy kolumn w `insert/update/delete` są walidowane.
* `LIKE`: escapuj `%` i `_` (`addcslashes($q, '%_\\')`), wartość i tak przekazuj jako parametr.
* Nie istnieje już `sql_safe()` – nie przywracaj ręcznego escapowania.

## Wyjście HTML (XSS)

* Smarty ma włączone `escape_html` – każde `{$zmienna}` jest escapowane.
* `nofilter` tylko dla HTML wygenerowanego przez nas i już bezpiecznego (np. `|bbcode`, które najpierw escapuje tekst).
* Linki z danych użytkownika/bazy: `{$url|safeurl}` (blokuje `javascript:`, `data:`).
* W bazie przechowujemy surowy tekst – **nie** escapuj przy zapisie.
* Content-Security-Policy blokuje skrypty inline i `eval`. Nie dodawaj `<script>` w szablonach ani atrybutów `onclick` – logika JS idzie do `assets/js/app.js`.
* Alpine (build CSP) wykonuje wyrażenia z atrybutów `x-*`. **Nigdy nie wstawiaj danych do wyrażeń** (`x-data="f('{$nick}')"`); przekaż je przez `data-*` i odczytaj z `this.$el.dataset`.

## CSRF i metody HTTP

* `bootstrap.php` odrzuca (HTTP 419) każde żądanie POST bez poprawnego `_token`. W formularzach: `{csrf}`.
* Żądania GET nie mogą zmieniać danych (wyjątek: licznik pobrań pliku).
* Wylogowanie to POST (przycisk w nagłówku).
* Ciasteczka: `SameSite=Lax`, `HttpOnly`, `Secure` na HTTPS.

## Uwierzytelnianie

* Sesja przechowuje tylko id admina i odcisk hasha hasła; konto i uprawnienia są czytane z bazy przy każdym żądaniu.
* Zmiana hasła unieważnia inne sesje i tokeny „zapamiętaj mnie”.
* Id sesji jest odnawiane przy logowaniu, wylogowaniu i co 30 minut.
* Blokada konta: 5 nieudanych prób ⇒ 15 minut (`Auth::MAX_TRIES`, `Auth::BLOCK_MINUTES`).
* Token „zapamiętaj mnie”: losowy, w bazie tylko SHA-256 (`_webadmins.logcode`), rotowany przy każdym użyciu.
* Hasła web adminów: `password_hash()`. Hasła adminów AMXX zostają w MD5 – wymaga tego plugin.

## Uprawnienia

* `admin.php` sprawdza uprawnienie do otwarcia strony, ale **każda akcja** musi sprawdzić swoje (`Auth::require('…_edit')`).
* Bany z uprawnieniem `own`: `Auth::canOnBan()`.
* IP graczy pokazujemy tylko z uprawnieniem `ip_view` (także w wyszukiwarce i na liście graczy online).

## Pliki

* Wgrane pliki: losowa nazwa (`bin2hex(random_bytes(16)) . '_' . $bid`), bez rozszerzenia, w `include/files/` (brak dostępu HTTP), pobieranie tylko przez PHP (`send_download()` z `Content-Disposition: attachment` i `nosniff`).
* Dozwolone rozszerzenia z ustawień minus lista zawsze blokowana (`allowed_file_types()`).
* Importy (`users.ini`, `banned.cfg`) czytamy bezpośrednio z `$_FILES[...]['tmp_name']` – nigdy nie przenosimy ich do katalogu dostępnego z WWW.
* Ścieżki z bazy do plików tylko przez `stored_file_path()` (walidacja nazwy).

## RCON

* Każdy tekst wstawiany do komendy RCON: `rcon_safe()` (usuwa `"`, `;`, znaki sterujące).
* Hasło RCON nie jest wysyłane do przeglądarki (pole „bez zmian”).
* Konsola RCON blokuje niebezpieczne komendy (`$deniedCommands` w `admin_sm_sv.php`).

## Przekierowania

`redirect()` przyjmuje tylko adresy lokalne (ochrona przed open redirect). Parametr `?setlang=` jest walidowany z listą języków.

## Zgłaszanie błędów

Podatności zgłaszaj prywatnie autorowi repozytorium, nie w publicznym issue.
