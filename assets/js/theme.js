// Applies the saved (or system) color scheme before the page is painted.
(function () {
  var theme = null;
  try { theme = localStorage.getItem('amxb-theme'); } catch (e) {}
  if (theme === 'dark' || (theme === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
  }
})();
