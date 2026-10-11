/* =========================================================
   GlowBeauty — Global Script
   ========================================================= */
(function () {
  'use strict';

  const $  = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

  /* ---------- Flash auto-dismiss ---------- */
  setTimeout(() => {
    $$('[data-flash]').forEach(el => el.style.opacity = '0');
  }, 4000);

  /* ---------- Beauty Finder ---------- */
  $$('.finder__options').forEach(group => {
    group.addEventListener('click', e => {
      const btn = e.target.closest('.finder-btn');
      if (!btn) return;
      $$('.finder-btn', group).forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const key = group.dataset.group;
      const hidden = key === 'skin' ? $('#finderSkin') : $('#finderConcern');
      if (hidden) hidden.value = btn.dataset.value;
    });
  });

  /* ---------- Countdown ---------- */
  const cd = $('#countdown');
  if (cd) {
    let t = 2 * 3600 + 15 * 60 + 43;
    const tick = () => {
      if (t <= 0) return;
      t--;
      const h = String(Math.floor(t / 3600)).padStart(2, '0');
      const m = String(Math.floor((t % 3600) / 60)).padStart(2, '0');
      const s = String(t % 60).padStart(2, '0');
      cd.textContent = `02 : ${h} : ${m} : ${s}`;
    };
    tick(); setInterval(tick, 1000);
  }

  /* ---------- Mobile nav ---------- */
  const mobileNav     = $('#mobileNav');
  const mobileBtn     = $('#mobileMenuBtn');
  const mobileClose   = $('#mobileMenuClose');
  const mobileOverlay = $('#mobileOverlay');

  const openDrawer = () => {
    if (!mobileNav) return;
    mobileNav.classList.add('open');
    mobileNav.setAttribute('aria-hidden', 'false');
    if (mobileOverlay) {
      mobileOverlay.hidden = false;
      requestAnimationFrame(() => mobileOverlay.classList.add('show'));
    }
    document.body.style.overflow = 'hidden';
    mobileBtn?.setAttribute('aria-expanded', 'true');
  };

  const closeDrawer = () => {
    if (!mobileNav) return;
    mobileNav.classList.remove('open');
    mobileNav.setAttribute('aria-hidden', 'true');
    if (mobileOverlay) {
      mobileOverlay.classList.remove('show');
      setTimeout(() => { mobileOverlay.hidden = true; }, 300);
    }
    document.body.style.overflow = '';
    mobileBtn?.setAttribute('aria-expanded', 'false');
  };

  mobileBtn?.addEventListener('click', openDrawer);
  mobileClose?.addEventListener('click', closeDrawer);
  mobileOverlay?.addEventListener('click', closeDrawer);
  mobileNav?.querySelectorAll('a').forEach(a => a.addEventListener('click', closeDrawer));
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

  /* ---------- Header scroll shadow ---------- */
  const header = $('#siteHeader');
  if (header) {
    const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 8);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---------- Language Switcher ---------- */
  const langSwitcher = $('#langSwitcher');
  if (langSwitcher) {
    const btn  = langSwitcher.querySelector('.lang-switcher__btn');
    const menu = langSwitcher.querySelector('.lang-switcher__menu');

    if (btn && menu) {
      btn.addEventListener('click', e => {
        e.stopPropagation();
        const isOpen = langSwitcher.classList.toggle('is-open');
        btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      });

      document.addEventListener('click', e => {
        if (!langSwitcher.contains(e.target)) {
          langSwitcher.classList.remove('is-open');
          btn.setAttribute('aria-expanded', 'false');
        }
      });

      document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
          langSwitcher.classList.remove('is-open');
          btn.setAttribute('aria-expanded', 'false');
        }
      });
    }
  }

  /* ---------- Hero Slideshow (homepage) ---------- */
  const slides = $$('.hero__slide');
  const dots   = $$('.hero__dot');

  if (slides.length > 0) {
    let currentIndex = 0;
    let slideshowTimer = null;
    const SLIDE_INTERVAL = 3000;

    const showSlide = (index) => {
      slides.forEach((s, i) => s.classList.toggle('is-active', i === index));
      dots.forEach((d, i)   => d.classList.toggle('is-active', i === index));
      currentIndex = index;
    };

    const nextSlide = () => {
      showSlide((currentIndex + 1) % slides.length);
    };

    const startSlideshow = () => {
      stopSlideshow();
      slideshowTimer = setInterval(nextSlide, SLIDE_INTERVAL);
    };

    const stopSlideshow = () => {
      if (slideshowTimer) {
        clearInterval(slideshowTimer);
        slideshowTimer = null;
      }
    };

    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => {
        showSlide(i);
        startSlideshow();
      });
    });

    document.addEventListener('visibilitychange', () => {
      if (document.hidden) stopSlideshow();
      else startSlideshow();
    });

    startSlideshow();
  }

  /* ---------- About Hero Slideshow ---------- */
  const aboutSlides = $$('.about-hero__slide');
  const aboutDots   = $$('.about-hero__dot');

  if (aboutSlides.length > 0) {
    let aboutIndex = 0;
    let aboutTimer = null;
    const ABOUT_INTERVAL = 3000; /* 3 seconds */

    const showAboutSlide = (i) => {
      aboutSlides.forEach((s, idx) => s.classList.toggle('is-active', idx === i));
      aboutDots.forEach((d, idx)   => d.classList.toggle('is-active', idx === i));
      aboutIndex = i;
    };

    const nextAboutSlide = () => {
      showAboutSlide((aboutIndex + 1) % aboutSlides.length);
    };

    const startAboutSlides = () => {
      stopAboutSlides();
      aboutTimer = setInterval(nextAboutSlide, ABOUT_INTERVAL);
    };

    const stopAboutSlides = () => {
      if (aboutTimer) {
        clearInterval(aboutTimer);
        aboutTimer = null;
      }
    };

    aboutDots.forEach((dot, i) => {
      dot.addEventListener('click', () => {
        showAboutSlide(i);
        startAboutSlides();
      });
    });

    document.addEventListener('visibilitychange', () => {
      if (document.hidden) stopAboutSlides();
      else startAboutSlides();
    });

    startAboutSlides();
  }

  /* ---------- Order Tracker + Cancel Window ---------- */
  const trackBtn      = $('#trackOrderBtn');
  const cancelBtn     = $('#cancelOrderBtn');
  const cancelWindow  = $('#cancelWindow');
  const cancelCount   = $('#cancelCount');
  const cancelBar     = $('#cancelProgress');
  const cancelledBox  = $('#cancelledBox');
  const tracker       = $('#orderTracker');
  const trackerBar    = $('#trackerProgress');
  const trackerMsg    = $('#trackerMessage');
  const trackerSteps  = $$('.order-step');

  if (trackBtn && tracker && trackerBar && trackerMsg && trackerSteps.length) {
    const CANCEL_SECONDS = 10;
    const HOUR_WAIT_MS   = 3600000; /* 1 hour — change to 8000 for testing */

    const firstTwoStages = [
      {
        pct: 25,
        color: 'linear-gradient(90deg, #e91e63, #ff5c8d)',
        text: 'Your order has been queued. Sit tight!',
        delay: 4000,
      },
      {
        pct: 50,
        color: 'linear-gradient(90deg, #9c27b0, #e91e63)',
        text: 'Preparing your order in our warehouse...',
        delay: 4000,
      },
    ];

    let currentStage    = 0;
    let deliveryTimer   = null;
    let cancelCountdownTimer = null;
    let orderCancelled  = false;

    const applyStage = (index, pct, color, text) => {
      trackerBar.style.width = pct + '%';
      trackerBar.style.backgroundImage = color;
      trackerMsg.textContent = text;

      trackerSteps.forEach((el, i) => {
        el.classList.remove('active');
        if (i < index) el.classList.add('done');
        if (i === index) el.classList.add('active');
      });
    };

    const startOutForDelivery = () => {
      if (orderCancelled) return;

      applyStage(2, 75, 'linear-gradient(90deg, #673ab7, #9c27b0)',
                 'Your order is out for delivery!');

      if (!tracker.querySelector('.order-tracker__eta')) {
        const eta = document.createElement('p');
        eta.className = 'order-tracker__eta';
        eta.textContent = 'Estimated delivery in about 1 hour...';
        trackerMsg.parentElement.appendChild(eta);
      }

      deliveryTimer = setTimeout(() => {
        if (orderCancelled) return;
        applyStage(3, 100, 'linear-gradient(90deg, #2e7d32, #4caf50)',
                   'Delivered. Thank you for shopping with GlowBeauty!');
        const eta = tracker.querySelector('.order-tracker__eta');
        if (eta) eta.remove();
      }, HOUR_WAIT_MS);
    };

    const runStage = () => {
      if (orderCancelled) return;

      if (currentStage >= firstTwoStages.length) {
        startOutForDelivery();
        return;
      }

      const stage = firstTwoStages[currentStage];
      applyStage(currentStage, stage.pct, stage.color, stage.text);
      currentStage++;

      if (currentStage < firstTwoStages.length) {
        setTimeout(runStage, stage.delay);
      } else {
        setTimeout(startOutForDelivery, stage.delay);
      }
    };

    const startCancelWindow = () => {
      if (!cancelBtn || !cancelWindow || !cancelCount || !cancelBar) return;

      cancelWindow.hidden = false;
      cancelBtn.hidden = false;

      let remaining = CANCEL_SECONDS;
      cancelCount.textContent = remaining;
      cancelBar.style.width = '100%';

      cancelCountdownTimer = setInterval(() => {
        remaining--;
        cancelCount.textContent = remaining;
        cancelBar.style.width = ((remaining / CANCEL_SECONDS) * 100) + '%';

        if (remaining <= 0) {
          clearInterval(cancelCountdownTimer);
          cancelCountdownTimer = null;
          cancelWindow.hidden = true;
          cancelBtn.hidden = true;
        }
      }, 1000);
    };

    const cancelOrder = () => {
      orderCancelled = true;

      if (deliveryTimer) clearTimeout(deliveryTimer);
      if (cancelCountdownTimer) clearInterval(cancelCountdownTimer);

      tracker.hidden = true;
      cancelWindow.hidden = true;
      cancelBtn.hidden = true;

      if (cancelledBox) cancelledBox.hidden = false;
    };

    trackBtn.addEventListener('click', () => {
      if (orderCancelled) return;

      tracker.hidden = false;
      tracker.classList.add('is-visible');
      trackBtn.disabled = true;
      trackBtn.textContent = '📦 Tracking...';
      tracker.scrollIntoView({ behavior: 'smooth', block: 'center' });

      startCancelWindow();
      setTimeout(runStage, 500);
    });

    if (cancelBtn) cancelBtn.addEventListener('click', cancelOrder);
  }
})();

/* Footer — mobile accordion */
(function () {
  const cols = document.querySelectorAll('.site-footer [data-collapsible]');
  if (!cols.length) return;
  const isMobile = () => window.matchMedia('(max-width: 560px)').matches;

  cols.forEach(col => {
    const title = col.querySelector('[data-toggle]');
    if (!title) return;
    title.setAttribute('role', 'button');
    title.setAttribute('tabindex', '0');

    const toggle = () => { if (isMobile()) col.classList.toggle('is-open'); };
    title.addEventListener('click', toggle);
    title.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); }
    });
  });
})();
/* =========================================================
   FLASH MESSAGES — Auto-dismiss after 3 seconds
   ========================================================= */
(function () {
  'use strict';

  const flashes = document.querySelectorAll('#flashContainer .flash');
  if (!flashes.length) return;

  const FADE_AFTER    = 3000; // 3 seconds
  const FADE_DURATION = 500;  // must match CSS transition

  flashes.forEach(flash => {
    // Auto-fade after 3 seconds
    const timer = setTimeout(() => {
      hideFlash(flash);
    }, FADE_AFTER);

    // Manual close button
    const closeBtn = flash.querySelector('.flash__close');
    if (closeBtn) {
      closeBtn.addEventListener('click', () => {
        clearTimeout(timer);
        hideFlash(flash);
      });
    }
  });

  function hideFlash(flash) {
    flash.classList.add('is-hiding');
    setTimeout(() => {
      flash.remove();
      // If the container is empty, remove it too
      const container = document.getElementById('flashContainer');
      if (container && !container.children.length) {
        container.remove();
      }
    }, FADE_DURATION);
  }
})();