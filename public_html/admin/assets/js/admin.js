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

    /* ---------- rich-text editor (self-hosted, no dependencies) ----------
       Progressive enhancement: turns <textarea data-richtext> into a WYSIWYG.
       The textarea stays in the DOM (hidden) and remains the posted field, so
       if JS is unavailable the raw HTML textarea still works. Server-side the
       HTML is sanitised through an allowlist before it is stored. */
    var RTE_TOOLBAR = [
      { cmd: 'formatBlock', val: 'H2', label: 'H2', title: 'Heading' },
      { cmd: 'formatBlock', val: 'H3', label: 'H3', title: 'Subheading' },
      { cmd: 'formatBlock', val: 'P', label: '¶', title: 'Paragraph' },
      { sep: true },
      { cmd: 'bold', label: 'B', title: 'Bold', style: 'font-weight:800' },
      { cmd: 'italic', label: 'I', title: 'Italic', style: 'font-style:italic' },
      { cmd: 'underline', label: 'U', title: 'Underline', style: 'text-decoration:underline' },
      { sep: true },
      { cmd: 'insertUnorderedList', label: '• List', title: 'Bulleted list' },
      { cmd: 'insertOrderedList', label: '1. List', title: 'Numbered list' },
      { cmd: 'formatBlock', val: 'BLOCKQUOTE', label: '❝', title: 'Quote' },
      { sep: true },
      { cmd: 'createLink', label: 'Link', title: 'Insert link' },
      { cmd: 'unlink', label: 'Unlink', title: 'Remove link' },
      { sep: true },
      { cmd: 'removeFormat', label: 'Clear', title: 'Clear formatting' },
      { cmd: 'undo', label: '↶', title: 'Undo' },
      { cmd: 'redo', label: '↷', title: 'Redo' }
    ];

    function safeLinkUrl(raw) {
      var u = (raw || '').trim();
      if (!u) return null;
      if (/^(https?:|mailto:|tel:)/i.test(u)) return u;
      if (/^(\/|#|\.\/|\.\.\/)/.test(u)) return u;
      if (/^[a-z][a-z0-9+.\-]*:/i.test(u)) return null; // unknown/blocked scheme
      return 'https://' + u; // bare domain → default to https
    }

    function buildEditor(textarea) {
      if (textarea.dataset.rteReady === '1') return;
      textarea.dataset.rteReady = '1';

      var wrap = document.createElement('div');
      wrap.className = 'mx-rte';
      var bar = document.createElement('div');
      bar.className = 'mx-rte-bar';
      var area = document.createElement('div');
      area.className = 'mx-rte-area';
      area.setAttribute('contenteditable', 'true');
      area.setAttribute('role', 'textbox');
      area.setAttribute('aria-multiline', 'true');
      area.innerHTML = textarea.value || '';

      RTE_TOOLBAR.forEach(function (item) {
        if (item.sep) {
          var s = document.createElement('span');
          s.className = 'mx-rte-sep';
          bar.appendChild(s);
          return;
        }
        var b = document.createElement('button');
        b.type = 'button';
        b.className = 'mx-rte-btn';
        b.textContent = item.label;
        if (item.style) b.setAttribute('style', item.style);
        b.title = item.title;
        b.setAttribute('aria-label', item.title);
        b.addEventListener('mousedown', function (e) { e.preventDefault(); }); // keep selection
        b.addEventListener('click', function () {
          area.focus();
          if (item.cmd === 'createLink') {
            var url = safeLinkUrl(window.prompt('Link URL (https://…, mailto:, tel: or /relative):', 'https://'));
            if (url) { document.execCommand('createLink', false, url); }
          } else if (item.cmd === 'formatBlock') {
            document.execCommand('formatBlock', false, item.val);
          } else {
            document.execCommand(item.cmd, false, null);
          }
          sync();
        });
        bar.appendChild(b);
      });

      function sync() { textarea.value = area.innerHTML.trim() === '<br>' ? '' : area.innerHTML; }
      area.addEventListener('input', sync);
      area.addEventListener('blur', sync);
      // Paste as plain-ish text: let the browser insert, then sync (server sanitises).
      area.addEventListener('paste', function () { setTimeout(sync, 0); });

      var form = textarea.form || textarea.closest('form');
      if (form) { form.addEventListener('submit', sync); }

      textarea.classList.add('mx-rte-source');
      textarea.setAttribute('tabindex', '-1');
      textarea.setAttribute('aria-hidden', 'true');
      textarea.parentNode.insertBefore(wrap, textarea);
      wrap.appendChild(bar);
      wrap.appendChild(area);
      wrap.appendChild(textarea); // keep the textarea inside the wrapper (hidden via CSS)
    }

    if (document.queryCommandSupported && document.queryCommandSupported('bold')) {
      document.querySelectorAll('textarea[data-richtext]').forEach(buildEditor);
    }
  });
})();
