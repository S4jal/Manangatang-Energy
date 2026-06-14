/* ============================================================
   Manangatang Energy — interactions & animations
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  /* ---------- Lucide icons ---------- */
  if (window.lucide) lucide.createIcons();

  /* ---------- Preloader ---------- */
  const preloader = document.getElementById('preloader');
  if (preloader) {
    const ring = document.getElementById('preRing');
    const num = document.getElementById('preNum');
    const CIRC = 289;               // 2πr (r=46)
    let pct = 0, finished = false;

    const setPct = (p) => {
      pct = Math.min(100, p);
      if (ring) ring.style.strokeDashoffset = String(CIRC * (1 - pct / 100));
      if (num) num.textContent = String(Math.round(pct));
    };

    // simulate progress, easing toward 90% until the page is ready
    const tick = setInterval(() => {
      if (finished) return;
      const target = 90;
      setPct(pct + Math.max(0.5, (target - pct) * 0.08));
      if (pct >= 89.5) clearInterval(tick);
    }, 60);

    const finish = () => {
      if (finished) return;
      finished = true;
      clearInterval(tick);
      setPct(100);
      setTimeout(() => {
        preloader.classList.add('done');
        document.body.classList.remove('loading');
        setTimeout(() => preloader.remove(), 700);
      }, 350);
    };

    // hide once everything (incl. images) has loaded, with a safety cap.
    // The cap (ms) is configurable from the Customizer via window.ME_PRELOADER_MS.
    var capMs = (typeof window.ME_PRELOADER_MS === 'number' && window.ME_PRELOADER_MS > 0) ? window.ME_PRELOADER_MS : 4000;
    if (document.readyState === 'complete') finish();
    else window.addEventListener('load', finish, { once: true });
    setTimeout(finish, capMs); // fallback so it never hangs
  }

  /* ---------- Sticky nav: shadow + shrink on scroll ---------- */
  const navBar = document.querySelector('#nav nav');
  const onScroll = () => {
    if (window.scrollY > 20) {
      navBar.classList.add('bg-white/90', 'shadow-xl', 'py-2');
      navBar.classList.remove('bg-white/70', 'py-3');
    } else {
      navBar.classList.remove('bg-white/90', 'shadow-xl', 'py-2');
      navBar.classList.add('bg-white/70', 'py-3');
    }
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ---------- Mobile menu ---------- */
  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  menuBtn?.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
  mobileMenu?.querySelectorAll('a').forEach(a =>
    a.addEventListener('click', () => mobileMenu.classList.add('hidden'))
  );

  /* ---------- Hero parallax (subtle) ---------- */
  const heroImg = document.querySelector('.hero-img');
  if (heroImg) {
    window.addEventListener('scroll', () => {
      const y = window.scrollY;
      if (y < window.innerHeight) heroImg.style.transform = `translateY(${y * 0.18}px) scale(1.08)`;
    }, { passive: true });
  }

  /* ---------- Scroll reveal (with stagger) ---------- */
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('in');
        io.unobserve(e.target);
      }
    });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal, .draw-line').forEach((el, i) => {
    if (el.classList.contains('reveal')) el.style.transitionDelay = (i % 4) * 80 + 'ms';
    io.observe(el);
  });

  /* ---------- Animated number counters ---------- */
  const counterIO = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      const el = e.target;
      const target = parseFloat(el.dataset.count);
      const dec = (el.dataset.dec | 0);
      const dur = 1400;
      const start = performance.now();
      const step = (now) => {
        const p = Math.min((now - start) / dur, 1);
        const eased = 1 - Math.pow(1 - p, 3);          // ease-out cubic
        el.textContent = (target * eased).toFixed(dec);
        if (p < 1) requestAnimationFrame(step);
        else el.textContent = target.toFixed(dec);
      };
      requestAnimationFrame(step);
      counterIO.unobserve(el);
    });
  }, { threshold: 0.6 });
  document.querySelectorAll('[data-count]').forEach(el => counterIO.observe(el));

  /* ---------- Carousel ---------- */
  const slides = document.getElementById('slides');
  if (slides) {
    const total = slides.children.length;
    const dotsWrap = document.getElementById('dots');
    let idx = 0, timer;

    for (let i = 0; i < total; i++) {
      const d = document.createElement('button');
      d.setAttribute('aria-label', `Go to slide ${i + 1}`);
      d.addEventListener('click', () => { idx = i; render(); restart(); });
      dotsWrap.appendChild(d);
    }
    const dots = [...dotsWrap.children];

    const render = () => {
      slides.style.transform = `translateX(-${idx * 100}%)`;
      dots.forEach((d, i) => {
        d.className = 'h-2.5 rounded-full transition-all duration-300 ' +
          (i === idx ? 'w-7 bg-grass-600' : 'w-2.5 bg-ink-100 hover:bg-ink-100/70');
      });
    };
    const next = () => { idx = (idx + 1) % total; render(); };
    const prev = () => { idx = (idx - 1 + total) % total; render(); };
    const restart = () => { clearInterval(timer); timer = setInterval(next, 5000); };

    document.getElementById('nextBtn')?.addEventListener('click', () => { next(); restart(); });
    document.getElementById('prevBtn')?.addEventListener('click', () => { prev(); restart(); });

    // pause on hover
    slides.closest('.relative')?.addEventListener('mouseenter', () => clearInterval(timer));
    slides.closest('.relative')?.addEventListener('mouseleave', restart);

    render();
    restart();
  }

  /* ---------- Active nav link on scroll (scroll-spy) ---------- */
  const sections = ['about', 'site', 'community', 'contact', 'news']
    .map(id => document.getElementById(id)).filter(Boolean);
  const links = [...document.querySelectorAll('#nav nav a[href^="#"]')];
  const spy = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      links.forEach(l => {
        const on = l.getAttribute('href') === '#' + e.target.id;
        l.classList.toggle('text-grass-600', on);
        l.classList.toggle('bg-ink-50', on);
      });
    });
  }, { rootMargin: '-45% 0px -50% 0px' });
  sections.forEach(s => spy.observe(s));

  /* ---------- Contact form (demo) ---------- */
  const form = document.getElementById('contactForm');
  const formText = document.getElementById('formText');
  form?.addEventListener('submit', (e) => {
    e.preventDefault();
    formText.textContent = 'Message Sent ✓';
    form.querySelector('button').classList.add('bg-grass-600');
    form.reset();
    setTimeout(() => {
      formText.textContent = 'Send Message';
      form.querySelector('button').classList.remove('bg-grass-600');
    }, 2600);
  });

  /* ---------- Scroll progress bar + parallax (rAF batched) ---------- */
  const progress = document.getElementById('progress');
  const parallaxEls = [...document.querySelectorAll('[data-parallax]')];
  let ticking = false;

  const runScrollFx = () => {
    const sc = window.scrollY;
    const docH = document.documentElement.scrollHeight - window.innerHeight;
    if (progress) progress.style.width = (docH > 0 ? (sc / docH) * 100 : 0) + '%';

    const vh = window.innerHeight;
    for (const el of parallaxEls) {
      const speed = parseFloat(el.dataset.parallax) || 0;
      const r = el.getBoundingClientRect();
      const center = r.top + r.height / 2 - vh / 2;
      el.style.transform = `translate3d(0, ${(center * speed).toFixed(1)}px, 0)`;
    }
    ticking = false;
  };
  window.addEventListener('scroll', () => {
    if (!ticking) { ticking = true; requestAnimationFrame(runScrollFx); }
  }, { passive: true });
  runScrollFx();

  /* ---------- 3D tilt on cards ---------- */
  const isFinePointer = window.matchMedia('(pointer: fine)').matches;
  if (isFinePointer) {
    document.querySelectorAll('.tilt').forEach(card => {
      const S = 9; // tilt strength (deg)
      card.addEventListener('mousemove', (e) => {
        const r = card.getBoundingClientRect();
        const px = (e.clientX - r.left) / r.width - 0.5;
        const py = (e.clientY - r.top) / r.height - 0.5;
        card.style.transform =
          `perspective(900px) rotateX(${(-py * S).toFixed(2)}deg) rotateY(${(px * S).toFixed(2)}deg) translateY(-6px)`;
      });
      card.addEventListener('mouseleave', () => { card.style.transform = ''; });
    });

    /* ---------- Magnetic buttons ---------- */
    document.querySelectorAll('.magnetic').forEach(btn => {
      btn.addEventListener('mousemove', (e) => {
        const r = btn.getBoundingClientRect();
        const x = e.clientX - r.left - r.width / 2;
        const y = e.clientY - r.top - r.height / 2;
        btn.style.transform = `translate(${(x * 0.25).toFixed(1)}px, ${(y * 0.4).toFixed(1)}px)`;
      });
      btn.addEventListener('mouseleave', () => { btn.style.transform = ''; });
    });
  }

  /* ---------- Scroll-to-top button ---------- */
  const toTop = document.getElementById('toTop');
  if (toTop) {
    const toggleTop = () => {
      const show = window.scrollY > 500;
      toTop.classList.toggle('opacity-0', !show);
      toTop.classList.toggle('translate-y-4', !show);
      toTop.classList.toggle('pointer-events-none', !show);
    };
    window.addEventListener('scroll', toggleTop, { passive: true });
    toTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    toggleTop();
  }

  /* ---------- Cookie consent ---------- */
  const cookie = document.getElementById('cookie');
  if (cookie) {
    const KEY = 'me_cookie_consent';
    let stored = null;
    try { stored = localStorage.getItem(KEY); } catch (_) {}

    if (!stored) {
      // reveal after a short delay
      setTimeout(() => {
        cookie.classList.remove('hidden');
        cookie.style.animation = 'fadeUp .5s cubic-bezier(.2,.7,.2,1) both';
      }, 900);
    }

    const dismiss = (value) => {
      try { localStorage.setItem(KEY, value); } catch (_) {}
      cookie.style.animation = 'fadeIn .3s reverse forwards';
      setTimeout(() => cookie.classList.add('hidden'), 280);
    };
    document.getElementById('cookieAccept')?.addEventListener('click', () => dismiss('accepted'));
    document.getElementById('cookieDecline')?.addEventListener('click', () => dismiss('declined'));
  }

  /* ---------- Pointer parallax (depth layers) ---------- */
  const depthEls = [...document.querySelectorAll('[data-depth]')];
  if (depthEls.length && window.matchMedia('(pointer: fine)').matches) {
    let px = 0, py = 0, tx = 0, ty = 0, raf = null;
    const loop = () => {
      tx += (px - tx) * 0.08; ty += (py - ty) * 0.08;
      for (const el of depthEls) {
        const d = parseFloat(el.dataset.depth) || 0;
        el.style.transform = `translate3d(${(tx * d * 40).toFixed(1)}px, ${(ty * d * 40).toFixed(1)}px, 0)`;
      }
      raf = Math.abs(px - tx) > 0.001 || Math.abs(py - ty) > 0.001 ? requestAnimationFrame(loop) : null;
    };
    window.addEventListener('mousemove', (e) => {
      px = (e.clientX / window.innerWidth) - 0.5;
      py = (e.clientY / window.innerHeight) - 0.5;
      if (!raf) raf = requestAnimationFrame(loop);
    }, { passive: true });
  }

  /* ---------- Marquee: clone children for a seamless loop ---------- */
  document.querySelectorAll('.marquee').forEach(m => {
    m.innerHTML += m.innerHTML;
  });

  /* ---------- Hero scroll cue: fade out once scrolling ---------- */
  const cues = [...document.querySelectorAll('.scroll-cue')];
  if (cues.length) {
    const onCue = () => {
      const hide = window.scrollY > 120;
      cues.forEach(c => { c.style.opacity = hide ? '0' : ''; c.style.pointerEvents = hide ? 'none' : ''; });
    };
    window.addEventListener('scroll', onCue, { passive: true });
    onCue();
  }

  /* ---------- Word-by-word reveal for [data-words] headings ---------- */
  document.querySelectorAll('[data-words]').forEach(el => {
    const words = el.textContent.trim().split(/\s+/);
    el.innerHTML = words.map((w, i) =>
      `<span class="word" style="animation-delay:${0.15 + i * 0.07}s">${w}</span>`
    ).join(' ');
  });
});
