# Frontend: Tailwind CSS 4 + Alpine.js

Serwer produkcyjny nie potrzebuje Node – skompilowane pliki są w repozytorium (`assets/dist/`). Node jest potrzebny tylko programistom.

```bash
npm install
npm run build        # CSS + Alpine + ikony
npm run build:css    # tylko CSS (po każdej zmianie klas w szablonach)
npm run watch:css    # przebudowa w tle podczas pracy
npm run icons        # include/icons.php z pakietu heroicons
```

## Pliki

| Plik | Rola |
| --- | --- |
| `assets/src/app.css` | źródło Tailwind: `@source` (skanowane katalogi), `@theme` (kolory `brand-*`), komponenty (`card`, `btn`, `input`, …), skórka `.theme-classic` |
| `assets/dist/app.css` | wynik (commitowany) |
| `assets/js/app.js` | komponenty Alpine (`Alpine.data`) + obsługa `data-confirm` |
| `assets/js/theme.js` | ustawia klasę `dark` przed renderem (ładowany synchronicznie w `<head>`) |
| `assets/dist/alpine-csp.min.js`, `alpine-focus.min.js` | Alpine (build CSP) i plugin focus (`x-trap`), kopiowane przez `build/copy-js.mjs` |
| `build/icons.mjs` | lista ikon Heroicons → `include/icons.php` |

Tailwind skanuje `templates/`, `include/`, `install/` i `assets/js/` – klasy muszą występować w tych plikach dosłownie (nie składaj nazw klas z fragmentów w PHP).

## Tryb ciemny i skórki

* Wariant `dark:` działa na klasie `.dark` na `<html>` (`@custom-variant dark`). Przełącza ją komponent `theme`, wybór jest w `localStorage` (`amxb-theme`).
* Tailwind 4 czyta kolory i promienie z CSS custom properties. Skórka `.theme-classic` nadpisuje `--color-brand-*`, `--color-zinc-*`, `--radius-*`, więc wszystkie komponenty zmieniają wygląd bez duplikowania szablonów. Nadpisania stylów komponentów są poza `@layer`, aby wygrywały z klasami narzędziowymi.

## Alpine.js w wersji CSP

Nagłówek Content-Security-Policy zabrania `eval`, dlatego używamy `@alpinejs/csp`:

* Proste wyrażenia działają inline: `x-data="{ open: false }"`, `x-on:click="open = !open"`, `x-bind:class="open ? 'rotate-180' : ''"`.
* Brak dostępu do globali (`window`, `document`, `confirm`) w wyrażeniach – taka logika idzie do komponentu w `app.js`.
* **Nie wstawiaj danych z serwera do wyrażeń.** Liczby i `true/false` są w porządku (`serverCard({$s.id})`), teksty przekazuj przez `data-*`:

```smarty
<div x-data="flagPicker" data-value="{$admin.access}" data-letters="abcdefghijklmnopqrstuz">
```

### Dostępne komponenty

| Komponent | Zastosowanie |
| --- | --- |
| `theme` | przełącznik jasny/ciemny |
| `dropdown` | `open`, `toggle()`, `close()` – menu mobilne |
| `modal(startOpen)` | `open`, `show()`, `hide()` – okna dialogowe |
| `toast(timeout)` | znikające komunikaty flash |
| `langSwitch` | `<select>` języka (`?setlang=`) |
| `clipboard` | kopiowanie SteamID |
| `banForm(permanent, custom)` | formularze banów |
| `flagPicker` | edytor flag AMXX |
| `serverCard(id)` | karta serwera ładująca `view.php?server=ID` |
| `tabs(initial)` | zakładki (menu admina w designie classic) |

Nowy komponent: dodaj `Alpine.data('nazwa', function () { return {…}; })` w `app.js` z blokiem JSDoc i użyj `x-data="nazwa"`.
