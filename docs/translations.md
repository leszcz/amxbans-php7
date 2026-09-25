# Tłumaczenia

## Pliki

`language/lang.<język>[.<część>].php`, np. `lang.polish.php`, `lang.polish.iexport.php`, `lang.polish.ui.php`. Język jest dostępny, jeśli istnieje plik główny `lang.<język>.php`.

Pliki mają format AMXBans 6 i są **parsowane, nie wykonywane** (`Lang::load()`):

```php
define("_BANLIST","Lista banów");
```

* Wszystkie pliki danego języka są czytane alfabetycznie i łączone.
* Encje HTML są dekodowane, `<br>` zamieniane na nową linię, pozostałe znaczniki usuwane – w szablonach teksty są zwykłym tekstem (escapowanym automatycznie). Wieloliniowe teksty wyświetlaj z klasą `whitespace-pre-line`.
* Brakujący klucz → tekst angielski → sam klucz.

## Dodawanie tekstów

Nowe klucze dopisuj do `lang.english.ui.php` **i** `lang.polish.ui.php` (pozostałe języki dostaną tekst angielski). Klucze zaczynają się od `_`, wielkie litery.

```php
// PHP
flash('success', '_SAVED');          // klucz tłumaczy szablon
$text = __('_BANLIST');              // lub Lang::get()
```

```smarty
{"_BANLIST"|lang}
{"_WELCOME_BACK"|lang|sprintf:$auth.username}   {* %s / %d w tekście *}
```

Sprawdzenie brakujących kluczy (klucze użyte w kodzie, których nie ma w plikach angielskich):

```bash
(grep -rhoE '"_[A-Z0-9_&]+"' templates/modern; grep -rhoE "'_[A-Z0-9_&]+'" templates include *.php) | tr -d "\"'" | sort -u > /tmp/used
grep -ohE 'define\s*\(\s*"_[A-Z0-9_&]+"' language/lang.english*.php | sed 's/.*"\(_[^"]*\)"/\1/' | sort -u > /tmp/have
comm -23 /tmp/used /tmp/have
```

## Język MOTD

`motd.php` dostaje kod języka AMXX (`lang=pl`); mapowanie kodów na nazwy plików jest w `include/amxx_langs.inc.php`.
