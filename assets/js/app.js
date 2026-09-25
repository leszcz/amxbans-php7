/**
 * @file AMXBans UI components for Alpine.js (CSP build).
 *
 * The CSP build of Alpine cannot evaluate arbitrary JavaScript written in HTML
 * attributes, so every component with logic is registered here with
 * `Alpine.data()` and used in templates as `x-data="name"` or
 * `x-data="name(arg)"`. Simple state such as `x-data="{ open: false }"` still
 * works inline.
 *
 * Security rule: never interpolate user data into an Alpine expression in a
 * template. Pass it through `data-*` attributes and read it from `this.$el.dataset`
 * (see {@link flagPicker}).
 *
 * Load order in the layout (all `defer`): app.js → alpine-focus → alpine-csp.
 *
 * @see docs/frontend.md
 */

/**
 * Asks for confirmation before submitting dangerous forms.
 *
 * Usage: `<button name="action" value="delete" data-confirm="Really delete?">`
 * (the attribute may also be placed on the `<form>`).
 *
 * @param {SubmitEvent} e
 */
document.addEventListener('submit', function (e) {
  var el = e.submitter && e.submitter.dataset.confirm ? e.submitter : e.target.closest('[data-confirm]');
  if (el && el.dataset.confirm && !window.confirm(el.dataset.confirm)) {
    e.preventDefault();
    e.stopImmediatePropagation();
  }
}, true);

document.addEventListener('alpine:init', function () {
  var Alpine = window.Alpine;

  /**
   * Dark / light mode switch; the choice is stored in localStorage ("amxb-theme").
   * The initial class is set by assets/js/theme.js before the page is painted.
   *
   * @example <div x-data="theme"><button x-on:click="toggle">…</button></div>
   * @returns {{dark: boolean, toggle: function(): void}}
   */
  Alpine.data('theme', function () {
    return {
      dark: document.documentElement.classList.contains('dark'),
      toggle: function () {
        this.dark = !this.dark;
        document.documentElement.classList.toggle('dark', this.dark);
        try { localStorage.setItem('amxb-theme', this.dark ? 'dark' : 'light'); } catch (e) {}
      }
    };
  });

  /**
   * Generic open/close state (mobile menu, admin sidebar).
   *
   * @example <div x-data="dropdown"><button x-on:click="toggle"/> <nav x-show="open">…</nav></div>
   * @returns {{open: boolean, toggle: function(): void, close: function(): void}}
   */
  Alpine.data('dropdown', function () {
    return {
      open: false,
      toggle: function () { this.open = !this.open; },
      close: function () { this.open = false; }
    };
  });

  /**
   * Modal dialog state. The markup lives in a `<template x-teleport="body">`
   * and uses `x-trap` (focus plugin) - see partials/ban_edit_modal.tpl.
   *
   * @param {boolean} [startOpen=false] Open immediately.
   * @returns {{open: boolean, show: function(): void, hide: function(): void}}
   */
  Alpine.data('modal', function (startOpen) {
    return {
      open: !!startOpen,
      show: function () { this.open = true; },
      hide: function () { this.open = false; }
    };
  });

  /**
   * Flash message that hides itself (partials/flash.tpl).
   *
   * @param {number} timeout Milliseconds until it hides; 0 = stays until dismissed.
   * @returns {{visible: boolean, init: function(): void, dismiss: function(): void}}
   */
  Alpine.data('toast', function (timeout) {
    return {
      visible: true,
      init: function () {
        var self = this;
        if (timeout > 0) { setTimeout(function () { self.visible = false; }, timeout); }
      },
      dismiss: function () { this.visible = false; }
    };
  });

  /**
   * Language `<select>`: reloads the page with `?setlang=<language>` (handled by Lang::init()).
   *
   * @example <div x-data="langSwitch"><select x-on:change="change">…</select></div>
   * @returns {{change: function(Event): void}}
   */
  Alpine.data('langSwitch', function () {
    return {
      change: function (event) {
        var url = new URL(window.location.href);
        url.searchParams.set('setlang', event.target.value);
        window.location.href = url.toString();
      }
    };
  });

  /**
   * Copy-to-clipboard button; `copied` is true for 1.5 s after copying.
   *
   * @example <button x-data="clipboard" data-value="STEAM_0:1:1" x-on:click="copy($el.dataset.value)">
   * @returns {{copied: boolean, copy: function(string): void}}
   */
  Alpine.data('clipboard', function () {
    return {
      copied: false,
      copy: function (text) {
        var self = this;
        if (!navigator.clipboard) { return; }
        navigator.clipboard.writeText(text).then(function () {
          self.copied = true;
          setTimeout(function () { self.copied = false; }, 1500);
        });
      }
    };
  });

  /**
   * State of ban forms (partials/ban_fields.tpl, partials/ban_edit_modal.tpl):
   * "permanent" disables the length field, "custom" switches to a free-text reason,
   * "unban" disables all fields of the edit dialog.
   *
   * @param {boolean} permanent    Initial state of the permanent checkbox.
   * @param {boolean} customReason Initial state of the custom reason checkbox.
   * @returns {{permanent: boolean, custom: boolean, unban: boolean}}
   */
  Alpine.data('banForm', function (permanent, customReason) {
    return {
      permanent: !!permanent,
      custom: !!customReason,
      unban: false
    };
  });

  /**
   * Toggle buttons for AMX Mod X flags, bound to a text input with `x-model="value"`.
   * Letters are kept in the order given by data-letters.
   *
   * @example <div x-data="flagPicker" data-value="abc" data-letters="abcdefghijklmnopqrstuz">
   * @returns {{value: string, letters: string[], has: function(string): boolean, toggle: function(string): void}}
   */
  Alpine.data('flagPicker', function () {
    return {
      value: '',
      letters: [],
      init: function () {
        this.value = this.$el.dataset.value || '';
        this.letters = (this.$el.dataset.letters || '').split('');
      },
      has: function (l) { return this.value.indexOf(l) !== -1; },
      toggle: function (l) {
        var set = this.value.split('');
        var i = set.indexOf(l);
        if (i === -1) { set.push(l); } else { set.splice(i, 1); }
        var order = this.letters;
        set.sort(function (a, b) { return order.indexOf(a) - order.indexOf(b); });
        this.value = set.join('');
      }
    };
  });

  /**
   * Live server card (view.tpl). Loads `view.php?server=<id>` (JSON) on init.
   *
   * @param {number} id Server id (_serverinfo.id).
   * @returns {{loading: boolean, online: boolean, data: Object, players: Array<{name: string, frags: number, time: string}>,
   *            showPlayers: boolean, load: function(): void, fill: string, togglePlayers: function(): void}}
   *          `fill` is the player bar width, e.g. "75%".
   */
  Alpine.data('serverCard', function (id) {
    return {
      loading: true,
      online: false,
      data: {},
      players: [],
      showPlayers: false,
      init: function () { this.load(); },
      load: function () {
        var self = this;
        self.loading = true;
        fetch('view.php?server=' + encodeURIComponent(id), { headers: { 'Accept': 'application/json' } })
          .then(function (r) { return r.json(); })
          .then(function (json) {
            self.online = !!json.online;
            self.data = json.info || {};
            self.players = json.players || [];
          })
          .catch(function () { self.online = false; })
          .finally(function () { self.loading = false; });
      },
      get fill() {
        var max = this.data.max_players || 0;
        return max ? Math.round((this.data.players || 0) / max * 100) + '%' : '0%';
      },
      togglePlayers: function () { this.showPlayers = !this.showPlayers; }
    };
  });

  /**
   * Tab switcher (classic design admin menu).
   *
   * @param {string|number} initial Initially selected tab.
   * @returns {{tab: (string|number), select: function((string|number)): void, is: function((string|number)): boolean}}
   */
  Alpine.data('tabs', function (initial) {
    return {
      tab: initial,
      select: function (name) { this.tab = name; },
      is: function (name) { return this.tab === name; }
    };
  });
});
