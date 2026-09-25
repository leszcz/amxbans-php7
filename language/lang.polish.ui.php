<?php
// AMXBans 7 - teksty nowego interfejsu (polski)

define("_LANGUAGE","Język");
define("_TOGGLE_THEME","Przełącz tryb ciemny");
define("_CLOSE","Zamknij");
define("_CANCEL","Anuluj");
define("_NEXT","Dalej");
define("_OPTIONAL","opcjonalnie");
define("_YOU","ty");
define("_UNCHANGED","bez zmian");
define("_NOTSET","nie ustawiono");
define("_INSTALLED","zainstalowany");
define("_WRITABLE","zapisywalny");
define("_NOT_WRITABLE","brak prawa zapisu");
define("_IN_TIME","za %s");
define("_TIME_AGO","%s temu");
define("_MIN_CHARS","Co najmniej %d znaków.");

define("_ERR_FORBIDDEN","Nie masz uprawnień do tej operacji.");
define("_ERR_NOTFOUND","Nie znaleziono strony.");
define("_ERR_BADREQUEST","Nieprawidłowe żądanie.");

define("_QUICKSEARCH","Nick, SteamID lub powód…");
define("_STEAMPROFILE","Profil Steam");
define("_ACTIVEBAN","Aktywny ban");
define("_EXPIREDBAN","Wygasły");
define("_LATESTBAN","Ostatni ban");
define("_BANNOTFOUND","Nie znaleziono bana.");
define("_BANDELETED","Ban usunięty");
define("_NOBANSTEAMID","Dla tego typu bana wymagany jest SteamID!");
define("_BBCODE_HINT","Możesz użyć [b], [i], [u], [quote] oraz [url=https://…]tekst[/url].");
define("_TOOFAST","Odczekaj chwilę przed dodaniem kolejnego wpisu.");
define("_CUSTOM_REASON","własny");
define("_NOADMINS","Brak adminów");
define("_NOSERVERS","Brak serwerów");
define("_NOSERVERS_HINT","Brak serwerów. Serwery dodają się automatycznie, gdy plugin AMXBans połączy się z tą bazą danych.");
define("_MOTD_APPEAL","Jeśli uważasz, że ban jest pomyłką, skontaktuj się z administracją serwera.");

define("_LOGIN_INTRO","Zaloguj się, aby zarządzać banami, adminami i serwerami.");
define("_LOGINBLOCKED_FOR","Spróbuj ponownie za %d min.");
define("_LOGIN_TRIES_LEFT","Pozostało prób: %d");

define("_WELCOME_BACK","Witaj ponownie, %s");
define("_RECENT_BANS","Ostatnie bany");
define("_BANS_TODAY","Bany dzisiaj");
define("_BANS_WEEK","Bany w ciągu 7 dni");
define("_ORPHANED","osieroconych");
define("_REPAIRED","Naprawione wpisy");
define("_SETUP_STILL_PRESENT","Plik setup.php nadal istnieje. Usuń go z serwera - nie jest już potrzebny.");
define("_HTTPS_RECOMMENDED","Strona nie działa przez HTTPS. Logowanie i ciasteczka mogą zostać przechwycone - włącz HTTPS (np. darmowy certyfikat Let's Encrypt).");

define("_NORCON","Hasło RCON tego serwera nie jest ustawione.");
define("_RCON_CLEAR","Usuń zapisane hasło RCON");
define("_RCONPW_INVALID","Hasło RCON nie może zawierać cudzysłowu i może mieć maksymalnie 32 znaki.");
define("_MOTD_SUGGESTED","Sugerowany adres");
define("_URLINVALID","Dozwolone są tylko linki http(s) i względne.");
define("_USERMENU_HINT","Klucze językowe (np. _HOME) są tłumaczone automatycznie; zwykły tekst jest wyświetlany bez zmian. Przyciski logowania/wylogowania są zawsze w nagłówku.");
define("_FILETYPES_HINT","Oddzielone przecinkami, np. dem,zip,jpg,png. Pliki skryptów (php, html, js, svg…) są zawsze blokowane.");
define("_CANNOT_CHANGE_OWN_LEVEL","Nie możesz zmienić własnego poziomu.");
define("_CANNOT_DELETE_SELF","Nie możesz usunąć własnego konta.");
define("_CANNOT_REMOVE_OWN_PERMISSION","Nie możesz odebrać prawa edycji uprawnień własnemu poziomowi.");
define("_YOURLEVEL_NOTE","To twój poziom - zmiany obowiązują cię od razu.");
define("_CHECK_RELEASES","Sprawdź nowe wersje");

define("_DB_BACKUP","Kopia bazy danych");
define("_ALL_TABLES","Wszystkie tabele AMXBans");
define("_STRUCTURE_ONLY","Tylko struktura (bez danych)");
define("_DOWNLOAD_NOW","Pobierz od razu (nie zapisuj na serwerze)");
define("_CREATE_BACKUP","Utwórz kopię");
define("_ONLY_PERMANENT","Tylko bany permanentne");
define("_WITH_REASON","Dodaj powód jako komentarz");
define("_DELETE_IMPORTED","Usuń zaimportowane bany");
define("_IMPORTSUCCESS","Import zakończony");
define("_IMPORT_RESULT","Zaimportowano: %d, pominięto: %d");
define("_USERSI_RESULT","Dodano adminów: %d, przypisano do serwera: %d, pominięto linii: %d");

define("_INSTALLATION","Instalacja");
define("_INSTALLFAILED","Instalacja nie powiodła się");
define("_DBOK","Połączenie z bazą danych działa.");
define("_SETUP_STEP_REQUIREMENTS","Wymagania");
define("_SETUP_STEP_DATABASE","Baza danych");
define("_SETUP_STEP_ADMIN","Administrator");
define("_SETUP_STEP_INSTALL","Instalacja");
define("_SETUP_DBHOST","Host bazy danych");
define("_SETUP_DBNAME","Nazwa bazy danych");
define("_SETUP_DBUSER","Użytkownik bazy");
define("_SETUP_DBPASS","Hasło do bazy");
define("_SETUP_DBPREFIX","Prefiks tabel");
define("_SETUP_PREFIX_HINT","Musi być taki sam jak w pluginie AMXBans (amxbans_tableprefix bez końcowego _).");
define("_SETUP_DB_FAILED","Nie można połączyć się z bazą danych");
define("_SETUP_EXISTING","Znaleziono istniejące tabele AMXBans - zostaną zachowane, utworzone zostaną tylko brakujące.");
define("_SETUP_ADMIN_OPTIONAL","Dotychczasowi web admini nadal będą działać. Wypełnij formularz tylko, jeśli chcesz dodać kolejnego admina.");
define("_SETUP_INSTALL_NOW","Zainstaluj");
define("_SETUP_DONE","AMXBans został zainstalowany.");
define("_SETUP_WRITE_MANUALLY","Nie udało się zapisać include/db.config.inc.php. Utwórz ten plik z poniższą zawartością:");
define("_SETUP_DELETE_HINT","Ze względów bezpieczeństwa usuń teraz setup.php.");
define("_SETUP_DELETE","Usuń setup.php");
define("_SETUP_LOCKED","AMXBans jest już zainstalowany");
define("_SETUP_LOCKED_TEXT","Aby zainstalować ponownie, najpierw usuń include/db.config.inc.php. W przeciwnym razie usuń setup.php z serwera.");
