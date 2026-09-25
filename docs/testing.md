# Testowanie

## Środowisko lokalne

```bash
# 1. Baza testowa (MariaDB/MySQL)
mysql -uroot -e "CREATE DATABASE amxbans CHARACTER SET utf8mb4;
                 CREATE USER 'amx'@'localhost' IDENTIFIED BY 'amxpass';
                 GRANT ALL ON amxbans.* TO 'amx'@'localhost';"

# 2. Konfiguracja - przez setup.php albo ręcznie:
cat > include/db.config.inc.php <<'PHP'
<?php
$config->db_host = 'localhost';
$config->db_user = 'amx';
$config->db_pass = 'amxpass';
$config->db_db = 'amxbans';
$config->db_prefix = 'amx';
PHP

# 3. Dane testowe (KASUJE tabele z prefiksem!) i serwer
php tests/seed.php
php -S 127.0.0.1:8080
```

`tests/seed.php` tworzy tabele ze schematu instalatora, dwa konta (`admin`/`admin123` z pełnymi uprawnieniami, `mod`/`mod123` na poziomie 2 z hasłem MD5), serwery, adminów AMXX i 45 banów – część z próbami XSS w nickach, powodach i komentarzach.

Wbudowany serwer PHP **ignoruje `.htaccess`** – blokady katalogów sprawdzaj na Apache/nginx.

## Test funkcjonalny

```bash
pip install requests
php tests/seed.php && python3 tests/functional_test.py
```

`tests/functional_test.py` (47 sprawdzeń) testuje działającą stronę przez HTTP:

* CSRF (brak/zły token ⇒ 419),
* logowanie, blokada po 5 próbach, konwersja MD5 → bcrypt, uprawnienia poziomu 2, przekierowanie gości,
* „zapamiętaj mnie” (format, podrobione i stare ciasteczka),
* dodawanie/edycja/odbanowanie/usuwanie bana, walidacja, escapowanie,
* komentarze gości (XSS, BBCode `javascript:`), ochrona przed floodem,
* upload (`.php` odrzucony, losowa nazwa, pobieranie), path traversal w kopiach,
* walidacja ustawień, menu, RCON (hasło niewysyłane, blokada komend), import `banned.cfg` i `users.ini`,
* kaskadowe usuwanie bana i wylogowanie.

Zmienne środowiskowe: `AMXB_URL`, `AMXB_MYSQL` (np. `"mysql -uamx -pamxpass amxbans"`), `AMXB_PREFIX`. Kod wyjścia ≠ 0 przy błędach – nadaje się do CI.

## Analiza statyczna (PHPStan)

Konfiguracja `phpstan.neon.dist` (poziom 6) obejmuje klasy rdzenia i funkcje pomocnicze – sprawdza m.in. zgodność typów w PHPDoc z kodem.

```bash
# phpstan.phar z https://github.com/phpstan/phpstan (lub composer require --dev phpstan/phpstan)
php phpstan.phar analyse
```

## Szybkie kontrole

```bash
# składnia wszystkich plików projektu
git ls-files '*.php' | grep -v '^vendor/\|^language/\|steamprofile\|templates/default' | xargs -n1 php -l | grep -v 'No syntax'

# CSS po zmianach w szablonach
npm run build:css
```

Zrzuty ekranu (np. do sprawdzania motywów) można robić Playwrightem – przykład logowania i nawigacji jest w historii repozytorium (PR z przebudową UI).
