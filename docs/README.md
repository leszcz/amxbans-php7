# Dokumentacja dla programistów

Ta dokumentacja opisuje wewnętrzną budowę AMXBans 7 (PHP 8). Instrukcja instalacji dla administratorów jest w [README.md](../README.md).

| Dokument | Zawartość |
| --- | --- |
| [architecture.md](architecture.md) | Struktura katalogów, cykl życia żądania, routing, globalne obiekty |
| [adding-pages.md](adding-pages.md) | Jak dodać stronę publiczną, stronę panelu admina, akcję formularza lub moduł (z przykładami) |
| [security.md](security.md) | Zasady bezpieczeństwa, których musi przestrzegać każdy nowy kod |
| [database.md](database.md) | Tabele, znaczenie kolumn, semantyka banów, klasa `Database` |
| [templates.md](templates.md) | Smarty 5, designy i ich dziedziczenie, modyfikatory, partiale, ikony |
| [frontend.md](frontend.md) | Tailwind CSS 4, komponenty Alpine.js (CSP), budowanie zasobów |
| [translations.md](translations.md) | Pliki językowe i dodawanie tekstów |
| [testing.md](testing.md) | Środowisko lokalne, dane testowe, testy funkcjonalne |
| [deployment.md](deployment.md) | GitHub Actions: CI, wdrożenie na produkcję (rsync/SSH), wydania |

## Szybki start

```bash
composer install            # Smarty 5 (vendor/ jest też w repozytorium)
npm install && npm run build
php -S 127.0.0.1:8080       # potem http://127.0.0.1:8080/setup.php
```

## Konwencje

* PHP 8.1+, `declare(strict_types=1);` w każdym nowym pliku.
* Komentarze i dokumentacja w kodzie: PHPDoc po angielsku (`@param`, `@return`, `@throws`); dokumentacja w `docs/` po polsku.
* Każdy plik PHP ma blok PHPDoc na początku (opis, parametry GET, akcje POST, wymagane uprawnienia).
* Każda funkcja/metoda ma blok PHPDoc z typami (także generycznymi: `list<…>`, `array{…}`).
* Szablony zaczynają się komentarzem `{* … *}` z listą zmiennych.
* API można wygenerować narzędziem [phpDocumentor](https://www.phpdoc.org/): `phpdoc` (konfiguracja w `phpdoc.dist.xml`, wynik w `build/api/`).
