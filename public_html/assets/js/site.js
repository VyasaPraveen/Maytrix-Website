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

    /* ---------- hero worked example (home only) ---------- */
    var workedBody = document.getElementById('workedBody');
    if (workedBody) {
      var workedData = {
        math: {
          q: 'Differentiate: y = 3x² − 5x + 7',
          steps: ['dy/dx of each term, separately', 'd/dx(3x²) = 6x', 'd/dx(−5x) = −5,   d/dx(7) = 0'],
          result: 'dy/dx = 6x − 5'
        },
        physics: {
          q: 'A ball is thrown upward at 20 m/s. Find max height. (g = 10 m/s²)',
          steps: ['Use v² = u² − 2gh, with v = 0 at max height', '0 = (20)² − 2(10)h', 'h = 400 ÷ 20'],
          result: 'h = 20 m'
        }
      };
      function renderWorked(key) {
        var d = workedData[key];
        var html = '<div class="q">' + d.q + '</div><ol class="worked-steps">';
        d.steps.forEach(function (s, i) { html += '<li><span class="n">' + (i + 1) + '.</span> ' + s + '</li>'; });
        html += '<li class="result">→ ' + d.result + '</li></ol>';
        workedBody.innerHTML = html;
      }
      renderWorked('math');
      document.querySelectorAll('[data-worked]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          document.querySelectorAll('[data-worked]').forEach(function (b) { b.classList.remove('active'); });
          btn.classList.add('active');
          renderWorked(btn.getAttribute('data-worked'));
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

    /* ---------- booking wizard (book page) ---------- */
    var wizard = document.querySelector('.wizard');
    if (wizard) {
      var wizStep = 1;
      var totalSteps = wizard.querySelectorAll('.wizard-step').length;
      function refresh() {
        wizard.querySelectorAll('.wizard-progress i').forEach(function (i) {
          i.classList.toggle('done', parseInt(i.getAttribute('data-p')) <= wizStep);
        });
        wizard.querySelectorAll('.wizard-step').forEach(function (s) {
          s.classList.toggle('active', parseInt(s.getAttribute('data-step')) === wizStep);
        });
        var back = document.getElementById('wizBack');
        var next = document.getElementById('wizNext');
        back.style.visibility = wizStep === 1 ? 'hidden' : 'visible';
        next.textContent = wizStep === totalSteps ? 'Submit request' : 'Continue';
        next.setAttribute('type', wizStep === totalSteps ? 'submit' : 'button');
      }
      wizard.querySelectorAll('.choice').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var group = btn.parentElement.getAttribute('data-choice-group');
          btn.parentElement.querySelectorAll('.choice').forEach(function (c) { c.classList.remove('selected'); });
          btn.classList.add('selected');
          var hidden = document.getElementById('wiz_' + group);
          if (hidden) hidden.value = btn.getAttribute('data-value');
        });
      });
      document.getElementById('wizNext').addEventListener('click', function (e) {
        if (wizStep < totalSteps) { e.preventDefault(); wizStep++; refresh(); }
      });
      document.getElementById('wizBack').addEventListener('click', function () {
        if (wizStep > 1) { wizStep--; refresh(); }
      });
      refresh();
    }
  });
})();
