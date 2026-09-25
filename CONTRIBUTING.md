# Contributing

Dziękujemy za chęć pomocy! Zanim wyślesz zmiany:

1. Przeczytaj [docs/README.md](docs/README.md) – zwłaszcza [architekturę](docs/architecture.md) i [zasady bezpieczeństwa](docs/security.md).
2. Nowe strony i akcje twórz według [docs/adding-pages.md](docs/adding-pages.md) (lista kontrolna na końcu).
3. Dokumentuj kod w PHPDoc (blok pliku + każda funkcja/metoda z `@param`/`@return`/`@throws`), szablony komentarzem `{* … *}`, komponenty JS w JSDoc.
4. Przed commitem:
   ```bash
   npm run build:css                        # jeśli zmieniały się klasy w szablonach
   php phpstan.phar analyse                 # analiza statyczna rdzenia
   php tests/seed.php && python3 tests/functional_test.py   # na bazie TESTOWEJ
   ```
5. Opisz zmianę w [CHANGELOG.md](CHANGELOG.md).

Schemat bazy musi pozostać zgodny z pluginem AMXBans na serwerach gry – nie zmieniaj nazw tabel ani kolumn.

Podatności bezpieczeństwa zgłaszaj prywatnie autorowi repozytorium, nie w publicznym issue.
