/* Maytrix Education — public site interactions (no framework). */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    /* ---------- mobile menu ---------- */
    var hamburger = document.getElementById('hamburgerBtn');
    if (hamburger) {
      hamburger.addEventListener('click', function () {
        document.getElementById('primaryNav').classList.toggle('open');
        document.querySelectorAll('.dropdown.open').forEach(function (d) { d.classList.remove('open'); });
      });
    }

    /* ---------- nav dropdowns ---------- */
    document.querySelectorAll('[data-dropdown]').forEach(function (dd) {
      var btn = dd.querySelector('button');
      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        var isOpen = dd.classList.contains('open');
        document.querySelectorAll('.dropdown.open').forEach(function (d) {
          d.classList.remove('open');
          d.querySelector('button').setAttribute('aria-expanded', 'false');
        });
        if (!isOpen) { dd.classList.add('open'); btn.setAttribute('aria-expanded', 'true'); }
      });
    });
    document.addEventListener('click', function () {
      document.querySelectorAll('.dropdown.open').forEach(function (d) {
        d.classList.remove('open');
        d.querySelector('button').setAttribute('aria-expanded', 'false');
      });
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        document.querySelectorAll('.dropdown.open').forEach(function (d) { d.classList.remove('open'); });
      }
    });

    /* ---------- generic tabs (curricula / subjects) ---------- */
    document.querySelectorAll('.tabbar').forEach(function (bar) {
      bar.querySelectorAll('button').forEach(function (btn) {
        btn.addEventListener('click', function () {
          bar.querySelectorAll('button').forEach(function (b) { b.classList.remove('active'); });
          btn.classList.add('active');
          var container = bar.parentElement;
          container.querySelectorAll('.tabpanel').forEach(function (p) {
            p.classList.toggle('active', p.getAttribute('data-panel') === btn.getAttribute('data-tab'));
          });
        });
      });
    });

    /* ---------- home graphs toggle (Maths / Physics) ---------- */
    var graphButtons = document.querySelectorAll('[data-graph]');
    if (graphButtons.length) {
      graphButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
          var key = btn.getAttribute('data-graph');
          graphButtons.forEach(function (b) { b.classList.remove('active'); });
          btn.classList.add('active');
          document.querySelectorAll('[data-graph-panel]').forEach(function (p) {
            p.classList.toggle('active', p.getAttribute('data-graph-panel') === key);
          });
        });
      });
    }

    /* ---------- animated stat counters ---------- */
    var counterEls = document.querySelectorAll('.counter-grid .n, .mini-counters .n');
    if (counterEls.length && 'IntersectionObserver' in window) {
      var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      var runCount = function (el) {
        var node = el.querySelector('em') || el;          // keep coloured <em> wrapper if present
        var raw = node.textContent.trim();
        var m = raw.match(/^(\D*)(\d+(?:\.\d+)?)(.*)$/);   // prefix / number / suffix (e.g. +1.8, 500+)
        if (!m) { return; }
        var prefix = m[1], numStr = m[2], suffix = m[3];
        var target = parseFloat(numStr);
        var decimals = (numStr.split('.')[1] || '').length;
        if (reduceMotion || target === 0) { node.textContent = prefix + numStr + suffix; return; }
        var duration = 1400, startTs = null;
        var tick = function (ts) {
          if (startTs === null) { startTs = ts; }
          var p = Math.min((ts - startTs) / duration, 1);
          var eased = 1 - Math.pow(1 - p, 3);             // easeOutCubic
          if (p < 1) {
            node.textContent = prefix + (target * eased).toFixed(decimals) + suffix;
            requestAnimationFrame(tick);
          } else {
            node.textContent = prefix + numStr + suffix;   // land exactly on the source value
          }
        };
        requestAnimationFrame(tick);
      };
      var counterObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          if (en.isIntersecting) { runCount(en.target); counterObserver.unobserve(en.target); }
        });
      }, { threshold: 0.4 });
      counterEls.forEach(function (c) { counterObserver.observe(c); });
    }

    /* ---------- booking wizard: subject → level → details ---------- */
    var wizard = document.querySelector('.wizard');
    if (wizard) {
      var wizStep = 1;
      var steps = wizard.querySelectorAll('.wizard-step');
      var totalSteps = steps.length;
      var subjHidden = document.getElementById('wiz_subject_id');
      var curricHidden = document.getElementById('wiz_curriculum_id');
      var levelHidden = document.getElementById('wiz_level');
      var guard = document.getElementById('wizGuard');

      function showGuard(msg) {
        if (!guard) return;
        guard.textContent = msg;
        guard.hidden = false;
      }
      function hideGuard() { if (guard) guard.hidden = true; }

      // Show only the level set for the currently-chosen subject.
      function syncLevelSets() {
        var sid = subjHidden ? subjHidden.value : '';
        wizard.querySelectorAll('.level-set').forEach(function (set) {
          set.hidden = set.getAttribute('data-subject') !== sid;
        });
      }
      function refresh() {
        wizard.querySelectorAll('.wizard-progress i').forEach(function (i) {
          i.classList.toggle('done', parseInt(i.getAttribute('data-p')) <= wizStep);
        });
        steps.forEach(function (s) {
          s.classList.toggle('active', parseInt(s.getAttribute('data-step')) === wizStep);
        });
        if (wizStep === 2) { syncLevelSets(); }
        var back = document.getElementById('wizBack');
        var next = document.getElementById('wizNext');
        back.style.visibility = wizStep === 1 ? 'hidden' : 'visible';
        next.textContent = wizStep === totalSteps ? 'Submit request' : 'Continue';
        next.setAttribute('type', wizStep === totalSteps ? 'submit' : 'button');
      }

      // Single-select groups that map straight to a hidden field (subject_id, class_type…).
      wizard.querySelectorAll('.choice').forEach(function (btn) {
        var grid = btn.parentElement;
        var group = grid.getAttribute('data-choice-group');
        if (!group) { return; } // level buttons handled below
        btn.addEventListener('click', function () {
          grid.querySelectorAll('.choice').forEach(function (c) { c.classList.remove('selected'); });
          btn.classList.add('selected');
          var hidden = document.getElementById('wiz_' + group);
          if (hidden) { hidden.value = btn.getAttribute('data-value'); }
          if (group === 'subject_id') {
            // Changing subject invalidates a previously-picked level.
            if (curricHidden) { curricHidden.value = ''; }
            if (levelHidden) { levelHidden.value = ''; }
            wizard.querySelectorAll('.level-btn.selected').forEach(function (c) { c.classList.remove('selected'); });
          }
          hideGuard();
        });
      });

      // Level buttons set BOTH curriculum + level, single-select across the subject set.
      wizard.querySelectorAll('.level-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var set = btn.closest('.level-set');
          if (set) { set.querySelectorAll('.level-btn').forEach(function (c) { c.classList.remove('selected'); }); }
          btn.classList.add('selected');
          if (curricHidden) { curricHidden.value = btn.getAttribute('data-curriculum'); }
          if (levelHidden) { levelHidden.value = btn.getAttribute('data-level'); }
          hideGuard();
        });
      });

      document.getElementById('wizNext').addEventListener('click', function (e) {
        if (wizStep < totalSteps) {
          if (wizStep === 1 && subjHidden && !subjHidden.value) { e.preventDefault(); showGuard('Please choose a subject to continue.'); return; }
          if (wizStep === 2 && curricHidden && !curricHidden.value) { e.preventDefault(); showGuard('Please choose a level to continue.'); return; }
          e.preventDefault(); hideGuard(); wizStep++; refresh();
        }
      });
      document.getElementById('wizBack').addEventListener('click', function () {
        if (wizStep > 1) { hideGuard(); wizStep--; refresh(); }
      });
      refresh();
    }
  });
})();
