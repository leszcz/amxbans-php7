# Dodawanie stron i funkcji

Przykłady pokazują pełny wzorzec: kontroler → akcje POST z CSRF i uprawnieniami → szablon.

## 1. Nowa strona w panelu admina

Przykład: strona „Notatki” (`admin.php?site=wm_notes`), widoczna dla `websettings_view`, edycja dla `websettings_edit`.

**a) Zarejestruj stronę w `admin.php`**

```php
$pages = [
    // …
    'wm_notes' => 'websettings_view',   // uprawnienie potrzebne do otwarcia strony
];
```

i dodaj pozycję menu w `admin_navigation()`:

```php
['wm_notes', '_MENUNOTES', 'document-text', 'websettings_view'],
```

(trzecia wartość to nazwa ikony z `include/icons.php`; nowe ikony dodaje się w `build/icons.mjs` i `npm run icons`).

**b) Kontroler `include/admin/admin_wm_notes.php`**

```php
<?php
declare(strict_types=1);

/**
 * Admin page "Notes" (admin.php?site=wm_notes, permission websettings_view).
 *
 * POST actions (websettings_edit): add (text), delete (id).
 *
 * @package   AMXBans
 * @license   CC-BY-NC-SA-2.0
 */

switch (action()) {                       // wartość przycisku <button name="action" value="…">
    case 'add':
        Auth::require('websettings_edit');       // uprawnienie do AKCJI sprawdzaj osobno
        $text = mb_substr(input('text'), 0, 255);
        if ($text === '') {
            flash('error', '_NOREQUIREDFIELDS');
            redirect_back();
        }
        Database::insert('notes', ['text' => $text, 'created' => time()]);
        log_to_db('Notes', 'Added note');
        flash('success', '_SAVED');
        redirect_back();                         // Post/Redirect/Get

    case 'delete':
        Auth::require('websettings_edit');
        Database::delete('notes', ['id' => input_int('id')]);
        flash('success', '_DELETED');
        redirect_back();
}

$view->page('admin/notes.tpl', [
    'notes' => Database::all('SELECT * FROM ' . Database::table('notes') . ' ORDER BY `created` DESC'),
], '_TITLENOTES');
```

Token CSRF sprawdza bootstrap – kontroler nie musi tego robić, ale **każdy formularz musi zawierać `{csrf}`**.

**c) Szablon `templates/modern/admin/notes.tpl`**

```smarty
{extends file="admin/layout.tpl"}
{* Notes (admin_wm_notes.php). Variables: $notes. *}
{block name=admin}
<h1 class="mb-6 text-2xl font-bold">{"_TITLENOTES"|lang}</h1>
<section class="card">
  <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
    {foreach $notes as $n}
      <li class="flex items-center justify-between px-5 py-3">
        <span>{$n.text}</span>                        {* automatycznie escapowane *}
        {if $perms.websettings_edit == 'yes'}
          <form method="post">{csrf}<input type="hidden" name="id" value="{$n.id}">
            <button name="action" value="delete" class="btn btn-danger-ghost btn-sm" data-confirm="{"_DELETE"|lang}?">{icon name="trash" class="size-4"}</button>
          </form>
        {/if}
      </li>
    {/foreach}
  </ul>
  {if $perms.websettings_edit == 'yes'}
    <form method="post" class="flex gap-2 border-t border-zinc-200 p-4 dark:border-zinc-800">
      {csrf}
      <input name="text" class="input" maxlength="255" required>
      <button name="action" value="add" class="btn btn-primary">{"_ADD"|lang}</button>
    </form>
  {/if}
</section>
{/block}
```

**d) Teksty** – dodaj klucze do `language/lang.english.ui.php` i `language/lang.polish.ui.php` (patrz [translations.md](translations.md)).

**e) Po zmianie klas CSS** w szablonach uruchom `npm run build:css`.

## 2. Nowa strona publiczna

Plik w katalogu głównym, np. `stats.php`:

```php
<?php
declare(strict_types=1);

/**
 * Public statistics page.
 *
 * @package AMXBans
 */

require __DIR__ . '/include/bootstrap.php';

$view->page('stats.tpl', [
    'top' => Database::all('SELECT `admin_nick`, COUNT(*) c FROM ' . Database::table('bans') . ' GROUP BY `admin_nick` ORDER BY c DESC LIMIT 10'),
], '_TITLESTATS');
```

Szablon `templates/modern/stats.tpl` zaczyna się od `{extends file="layout.tpl"}` i wypełnia `{block name=content}`. Link w menu dodaje się w panelu: *Web → Menu użytkownika* (tabela `_usermenu`). Jeśli strona ma być dostępna jako strona startowa, dopisz ją do listy `$startPages` w `admin_wm_ms.php` i `$allowed` w `index.php`.

## 3. Nowy moduł

Moduły to opcjonalne strony panelu włączane w *Moduły*:

1. Plik `include/modules/modul_<nazwa>.php` – jak strona panelu; sam sprawdza uprawnienia (`Auth::require(...)`), bo router modułów wymaga tylko zalogowania.
2. Szablon `templates/modern/modules/<nazwa>.tpl`.
3. Wpis w bazie (`_modulconfig`): `menuname` (klucz językowy), `name` = `<nazwa>`, `activ` = 1. Dla nowych instalacji dopisz go w `install_default_data()` w `install/schema.php`.

## 4. Lista kontrolna przed commitem

- [ ] Każde zapytanie przez `Database` z parametrami – żadnych zmiennych wklejonych do SQL (nazwy tabel tylko przez `Database::table()`).
- [ ] Każdy formularz POST ma `{csrf}`; GET nigdy nie zmienia danych.
- [ ] Uprawnienie sprawdzone dla strony **i** dla każdej akcji (`Auth::require()` / `Auth::canOnBan()`).
- [ ] Dane użytkownika wypisywane zwykłym `{$zmienna}` (bez `nofilter`); linki przez `|safeurl`.
- [ ] Żadnych danych użytkownika w wyrażeniach Alpine (`x-data="fn('{$x}')"`) – tylko atrybuty `data-*`.
- [ ] Po akcji: `flash()` + `redirect_back()`.
- [ ] Bloki PHPDoc dla pliku i funkcji, komentarz `{* … *}` w szablonie.
- [ ] Teksty w plikach językowych (EN + PL).
- [ ] `npm run build:css`, `php -l`, test funkcjonalny ([testing.md](testing.md)).
