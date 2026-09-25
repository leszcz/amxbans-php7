# Szablony (Smarty 5)

## Designy i dziedziczenie

Design wybiera się w *Web → Ustawienia* (`_webconfig.design`). Katalog `templates/<design>/` jest przeszukiwany jako pierwszy, `templates/modern/` jako drugi (`View::__construct()`), więc design zawiera tylko szablony, które zmienia. Warunek: musi mieć `layout.tpl`.

* `modern` – komplet szablonów, jasny/ciemny motyw.
* `classic` – wygląd starego AMXBans GM: nadpisuje tylko `layout.tpl` i `admin/layout.tpl`, a kolory zmienia klasa `.theme-classic` w `assets/src/app.css` (patrz [frontend.md](frontend.md)).
* Stare szablony AMXBans 6 (`default`) zostały usunięte – były niekompatybilne ze Smarty 5. Instalacje z ustawionym designem `default` automatycznie używają `modern`.

Nowy design: skopiuj `templates/classic/`, zmień layout, dodaj klasę `.theme-<nazwa>` z własnymi zmiennymi kolorów i przebuduj CSS.

## Struktura

```
layout.tpl                 szkielet strony, blok {block name=content}
admin/layout.tpl           panel admina (menu), blok {block name=admin}
<strona>.tpl               {extends file="layout.tpl"} + {block name=content}
admin/<strona>.tpl         {extends file="admin/layout.tpl"} + {block name=admin}
modules/<moduł>.tpl        moduły panelu
partials/*.tpl             fragmenty: flash, pagination, ban_rows, ban_fields, captcha, toggle, …
install/setup.tpl          instalator (samodzielna strona)
motd.tpl                   okno MOTD w grze (samodzielna strona)
```

Każdy szablon zaczyna się (po `{extends}`) komentarzem z listą zmiennych.

## Zmienne globalne (`View::assignCommon()`)

| Zmienna | Zawartość |
| --- | --- |
| `$app` | `version`, `banner`, `banner_url`, `design`, `script` (bieżący plik), `lang`, `html_lang`, `languages`, `use_comment`, `use_demo` |
| `$auth` | zalogowany admin (bez hasła) lub `null` |
| `$perms` | uprawnienia: `{if $perms.ip_view == 'yes'}` |
| `$nav` | menu publiczne: `url`, `label`, `file` |
| `$flashes` | komunikaty do wyświetlenia (`partials/flash.tpl`) |
| `$page_title` | klucz językowy tytułu |
| `$asset_ver` | wersja zasobów (cache busting) |

## Escapowanie

Każde `{$x}` jest escapowane (`escape_html`). `nofilter` tylko dla bezpiecznego HTML:

```smarty
{$comment.comment|bbcode nofilter}   {* bbcode escapuje tekst przed konwersją *}
<a href="{$url|safeurl}">            {* blokuje javascript: *}
<img src="images/banner/{$file|escape:'url'}">
```

## Modyfikatory i funkcje

| Składnia | Wynik |
| --- | --- |
| `{"_BANLIST"\|lang}` | tłumaczenie (tekst bez `_` na początku zwraca bez zmian) |
| `{"_WELCOME_BACK"\|lang\|sprintf:$auth.username}` | tłumaczenie z `%s` |
| `{$ts\|datetime}` / `:'date'` / `:'full'` / `:'time'` | data z timestampu |
| `{$ts\|relative}` | „3 dni temu” / „za 2 godziny” |
| `{$seconds\|duration}` | „2 tygodnie 3 dni” |
| `{$ban.ban_length\|banlength}` | długość bana w minutach → tekst (0 = permanentny) |
| `{$bytes\|filesize}` | „1.5 MB” |
| `{$steamid\|steamprofile}` | URL profilu Steam |
| `{$cc\|flag}`, `{$mod\|gameicon}` | ścieżki obrazków |
| `{$text\|bbcode nofilter}` | BBCode → HTML |
| `{$x\|contains:'del'}`, `{$name\|initial}` | pomocnicze |
| `{csrf}` | ukryte pole `_token` – **w każdym formularzu POST** |
| `{icon name="trash" class="size-4"}` | ikona SVG (Heroicons, lista w `include/icons.php`) |

Wbudowane modyfikatory Smarty 5 (`default`, `count`, `lower`, `escape`, `date_format`, `in_array`, …) też działają. Funkcje PHP nie są dostępne w szablonach, dopóki nie zarejestrujesz ich w `View`.

## Wzorce UI

* Formularze akcji: `<button name="action" value="save">` – kontroler robi `switch (action())`.
* Potwierdzenie: `data-confirm="…"` na przycisku.
* Okno modalne: `x-data="modal"` + `<template x-teleport="body">` + `x-trap.noscroll="open"` (przykład w `partials/ban_edit_modal.tpl`).
* Komponenty CSS: `card`, `card-header`, `btn btn-primary|secondary|ghost|danger`, `input`, `label`, `badge badge-red|green|…`, `table`, `alert alert-…` (definicje w `assets/src/app.css`).

Skompilowane szablony trafiają do `templates_c/` (czyszczenie: *Panel → Wyczyść cache*).
