(function () {
  'use strict';

  /* =========================================================================
     Mobile Menu Toggle
     ========================================================================= */
  var menuToggle = document.querySelector('.menu-toggle');
  var siteNav    = document.getElementById('site-navigation');

  function closeNav() {
    siteNav.classList.remove('is-open');
    menuToggle.classList.remove('is-active');
    menuToggle.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('nav-open');
  }

  if (menuToggle && siteNav) {
    menuToggle.addEventListener('click', function () {
      var isOpen = siteNav.classList.toggle('is-open');
      menuToggle.classList.toggle('is-active', isOpen);
      menuToggle.setAttribute('aria-expanded', String(isOpen));
      document.body.classList.toggle('nav-open', isOpen);
    });

    // Close when clicking outside the nav (desktop dropdown / clicking behind overlay)
    document.addEventListener('click', function (e) {
      if (!siteNav.contains(e.target) && !menuToggle.contains(e.target)) {
        closeNav();
      }
    });

    // Close overlay when a leaf nav link is clicked (not sub-menu parent toggles)
    siteNav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        var isParent = link.parentElement && link.parentElement.classList.contains('menu-item-has-children');
        if (siteNav.classList.contains('is-open') && !isParent) {
          closeNav();
        }
      });
    });

    // Mobile sub-menu toggles (click parent link to reveal children)
    var parentLinks = siteNav.querySelectorAll('.menu-item-has-children > a');
    parentLinks.forEach(function (link) {
      link.addEventListener('click', function (e) {
        if (!siteNav.classList.contains('is-open')) return; // desktop — let CSS handle it
        var sub = link.nextElementSibling;
        if (sub && sub.classList.contains('sub-menu')) {
          e.preventDefault();
          sub.classList.toggle('is-open');
        }
      });
    });
  }

  /* =========================================================================
     Hero Slider / Random — reusable init for any hero element
     ========================================================================= */
  function initHeroSlider(heroEl, config) {
    var slides  = Array.prototype.slice.call(heroEl.querySelectorAll('.hero-slide'));
    var dots    = Array.prototype.slice.call(heroEl.querySelectorAll('.hero-dot'));
    var mode    = config.mode;
    var speed   = config.speed || 5000;
    var current = 0;
    var timer   = null;

    if (slides.length === 0) return;

    function goTo(index) {
      slides[current].classList.remove('active');
      slides[current].setAttribute('aria-hidden', 'true');
      if (dots[current]) dots[current].classList.remove('active');

      current = ((index % slides.length) + slides.length) % slides.length;

      slides[current].classList.add('active');
      slides[current].setAttribute('aria-hidden', 'false');
      if (dots[current]) dots[current].classList.add('active');
    }

    function startTimer() {
      timer = setInterval(function () { goTo(current + 1); }, speed);
    }

    if (mode === 'random' && slides.length > 1) {
      goTo(Math.floor(Math.random() * slides.length));

    } else if (mode === 'slider' && slides.length > 1) {
      startTimer();

      dots.forEach(function (dot, i) {
        dot.addEventListener('click', function () {
          clearInterval(timer);
          goTo(i);
          startTimer();
        });
      });

      heroEl.addEventListener('mouseenter', function () { clearInterval(timer); });
      heroEl.addEventListener('mouseleave', startTimer);

      var touchStartX = 0;
      heroEl.addEventListener('touchstart', function (e) {
        touchStartX = e.changedTouches[0].clientX;
        clearInterval(timer);
      }, { passive: true });
      heroEl.addEventListener('touchend', function (e) {
        var dx = e.changedTouches[0].clientX - touchStartX;
        if (Math.abs(dx) > 40) { goTo(dx < 0 ? current + 1 : current - 1); }
        startTimer();
      }, { passive: true });
    }
    // mode === 'static': first slide stays active (already set in PHP)
  }

  var hero = document.getElementById('site-hero');
  if (hero && typeof trpHero !== 'undefined') {
    initHeroSlider(hero, trpHero);
  }

  var bottomHero = document.getElementById('bottom-hero');
  if (bottomHero && typeof trpBottomHero !== 'undefined') {
    initHeroSlider(bottomHero, trpBottomHero);
  }

  /* =========================================================================
     Back to Top
     ========================================================================= */
  var backToTop = document.getElementById('back-to-top');
  if (backToTop) {
    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

})();
