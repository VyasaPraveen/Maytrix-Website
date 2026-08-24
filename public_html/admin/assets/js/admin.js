/* Maytrix Admin — dashboard interactions */
(function () {
  'use strict';
  document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('menuToggle');
    var sidebar = document.getElementById('sidebar');
    if (toggle && sidebar) {
      toggle.addEventListener('click', function () { sidebar.classList.toggle('open'); });
    }
    // Confirm before destructive actions.
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        if (!window.confirm(form.getAttribute('data-confirm'))) { e.preventDefault(); }
      });
    });
    // Country → Time zone auto-fill.
    document.querySelectorAll('[data-country-select]').forEach(function (sel) {
      var map = {};
      try { map = JSON.parse(sel.getAttribute('data-tz-map') || '{}'); } catch (e) { map = {}; }
      var targetName = sel.getAttribute('data-tz-target') || 'timezone';
      var form = sel.form || sel.closest('form');
      var target = form ? form.querySelector('[name="' + targetName + '"]') : null;
      if (!target) return;
      sel.addEventListener('change', function () {
        var tz = map[sel.value];
        // Fill if empty, or if the current value came from a previous country pick.
        if (tz && (target.value.trim() === '' || target.dataset.autofilled === '1')) {
          target.value = tz;
          target.dataset.autofilled = '1';
        }
      });
      // If the user edits the time zone manually, stop auto-overwriting it.
      target.addEventListener('input', function () {
        if (target.dataset.autofilled === '1' && target.value !== map[sel.value]) {
          delete target.dataset.autofilled;
        }
      });
    });

    // Auto-slug: fields with data-slug-source populate a target slug field.
    document.querySelectorAll('[data-slug-target]').forEach(function (src) {
      var target = document.querySelector(src.getAttribute('data-slug-target'));
      if (!target) return;
      src.addEventListener('blur', function () {
        if (target.value.trim() === '') {
          target.value = src.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        }
      });
    });
  });
})();
