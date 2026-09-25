/*
 * AMXBans UI components (Alpine.js CSP build).
 * Components are registered here because the CSP build cannot evaluate
 * arbitrary JavaScript in HTML attributes.
 */

// Confirmation for dangerous actions: <button data-confirm="Are you sure?">
document.addEventListener('submit', function (e) {
  var el = e.submitter && e.submitter.dataset.confirm ? e.submitter : e.target.closest('[data-confirm]');
  if (el && el.dataset.confirm && !window.confirm(el.dataset.confirm)) {
    e.preventDefault();
    e.stopImmediatePropagation();
  }
}, true);

document.addEventListener('alpine:init', function () {
  var Alpine = window.Alpine;

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

  Alpine.data('dropdown', function () {
    return {
      open: false,
      toggle: function () { this.open = !this.open; },
      close: function () { this.open = false; }
    };
  });

  Alpine.data('modal', function (startOpen) {
    return {
      open: !!startOpen,
      show: function () { this.open = true; },
      hide: function () { this.open = false; }
    };
  });

  // Auto-hiding flash messages
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

  // Language switcher <select>
  Alpine.data('langSwitch', function () {
    return {
      change: function (event) {
        var url = new URL(window.location.href);
        url.searchParams.set('setlang', event.target.value);
        window.location.href = url.toString();
      }
    };
  });

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

  // Ban form: permanent checkbox disables the length field, custom reason toggle
  Alpine.data('banForm', function (permanent, customReason) {
    return {
      permanent: !!permanent,
      custom: !!customReason,
      unban: false
    };
  });

  // AMX Mod X flag editor bound to a text input.
  // Values come from data-value / data-letters (never interpolated into the expression).
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

  // Live server card on the "Servers" page
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

  // Tabs
  Alpine.data('tabs', function (initial) {
    return {
      tab: initial,
      select: function (name) { this.tab = name; },
      is: function (name) { return this.tab === name; }
    };
  });
});
