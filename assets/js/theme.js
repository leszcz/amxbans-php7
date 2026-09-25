/**
 * @file Applies the saved (or system) color scheme before the page is painted.
 * Loaded synchronously in <head> (not deferred) to avoid a flash of the wrong theme.
 */
(function () {
  var theme = null;
  try { theme = localStorage.getItem('amxb-theme'); } catch (e) {}
  if (theme === 'dark' || (theme === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
  }
})();
