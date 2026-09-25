# Wdrożenie (GitHub Actions) i wydania

W repozytorium są dwa workflowy:

| Plik | Kiedy | Co robi |
| --- | --- | --- |
| `.github/workflows/deploy.yml` | każdy PR i push; wdrożenie tylko dla `main` | testy (lint PHP 8.1–8.4, PHPStan, aktualność CSS, testy funkcjonalne na MariaDB), potem rsync przez SSH na produkcję |
| `.github/workflows/release.yml` | push tagu `v*` | paczka ZIP + SHA-256 i release na GitHubie z opisem z CHANGELOG |

Obie korzystają z `build/package.sh` (zawartość paczki określa `build/package-filter.txt`: bez plików deweloperskich, konfiguracji i danych).

## Konfiguracja wdrożenia

*Settings → Secrets and variables → Actions* (albo w środowisku **production**: *Settings → Environments*, co pozwala dodać wymagane zatwierdzenie przed wdrożeniem):

| Nazwa | Rodzaj | Opis |
| --- | --- | --- |
| `DEPLOY_HOST` | secret | host lub IP serwera |
| `DEPLOY_USER` | secret | użytkownik SSH |
| `DEPLOY_KEY` | secret | prywatny klucz SSH (OpenSSH, bez hasła) |
| `DEPLOY_PATH` | secret | bezwzględna ścieżka strony, np. `/home/user/domains/bans.example.com/public_html` |
| `DEPLOY_PORT` | secret, opcjonalny | port SSH (domyślnie 22) |
| `DEPLOY_KNOWN_HOSTS` | secret, zalecany | wynik `ssh-keyscan -p PORT HOST` – bez niego klucz hosta jest przyjmowany „na ślepo” |
| `DEPLOY_URL` | zmienna, opcjonalna | adres strony – po wdrożeniu sprawdzany jest `ban_list.php` (HTTP 200) |

Każdą wartość można też podać jako zmienną (`vars.*`) zamiast sekretu. Gdy wymaganych sekretów brak, job wdrożenia kończy się ostrzeżeniem i niczego nie zmienia.

### Klucz SSH

```bash
ssh-keygen -t ed25519 -N "" -C "github-deploy" -f deploy_key
# deploy_key.pub  -> ~/.ssh/authorized_keys na serwerze
# deploy_key      -> sekret DEPLOY_KEY
ssh-keyscan -p 22 bans.example.com   # -> sekret DEPLOY_KNOWN_HOSTS
```

Na serwerze musi być dostępny `rsync` (większość hostingów z SSH go ma).

## Co dzieje się na serwerze

1. `rsync --delete` synchronizuje katalog strony z paczką.
2. **Chronione** (nigdy nie nadpisywane ani usuwane): `include/db.config.inc.php`, `include/files/`, `include/backup/`, `templates_c/`, `temp/`, `images/banner/`, `templates/` (własne designy), `.well-known/`, `.user.ini`.
3. **Usuwane**: wszystkie inne pliki, których nie ma w repozytorium – np. pozostałości AMXBans 6, ale też własne pliki wgrane ręcznie do katalogu strony. Przechowuj je w repozytorium albo dopisz regułę `--filter='P /plik'` w `deploy.yml`.
4. Po wgraniu: czyszczony jest `templates_c/*.php`, a `setup.php` jest usuwany, jeśli strona jest już skonfigurowana (przy pierwszej instalacji zostaje).

**Pierwsze wdrożenie** uruchom ręcznie z zaznaczonym *dry_run* (*Actions → CI & Deploy → Run workflow*): log kroku „Upload files” pokaże, co zostałoby zmienione i usunięte (`*deleting`).

## Wydanie nowej wersji

1. Zmień `AMXB_VERSION` w `include/bootstrap.php` (i `setup.php`), dopisz sekcję `## X.Y.Z – data` w `CHANGELOG.md`.
2. Zmerguj do `main`.
3. Utwórz tag na `main` i wypchnij go:

```bash
git checkout main && git pull
git tag -a v7.0.1 -m "AMXBans 7.0.1"
git push origin v7.0.1
```

Workflow sprawdzi zgodność tagu z `AMXB_VERSION`, zbuduje `amxbans-7.0.1.zip` (+ `.sha256`) i opublikuje release z notatkami z CHANGELOG. Paczkę można też zbudować lokalnie: `build/package.sh --zip` (wynik w `dist/`).
