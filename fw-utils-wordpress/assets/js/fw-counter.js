(function () {
  // Helper: count decimals to keep formatting consistent
  function decimalPlaces(num) {
    try {
      const s = String(num);
      if (s.includes('e-')) {
        const [, exp] = s.split('e-');
        const fractional = (+(num)).toFixed(+exp);
        return fractional.split('.')[1]?.length || 0;
      }
      const parts = s.split('.');
      return parts[1] ? parts[1].length : 0;
    } catch (_) {
      return 0;
    }
  }

  function animateValue(el, start, end, duration, prefix, suffix) {
    const startTime = performance.now();
    const decimals = Math.max(decimalPlaces(start), decimalPlaces(end));
    const dir = end >= start ? 1 : -1;
    const totalDelta = Math.abs(end - start);

    function frame(now) {
      const elapsed = Math.min(now - startTime, duration);
      const progress = duration === 0 ? 1 : (elapsed / duration);
      const current = start + dir * totalDelta * progress;
      el.textContent = `${prefix}${current.toFixed(decimals)}${suffix}`;
      if (elapsed < duration) {
        requestAnimationFrame(frame);
      } else {
        // Snap to exact end to avoid rounding drift
        el.textContent = `${prefix}${(+end).toFixed(decimals)}${suffix}`;
        el.dataset.fwDone = '1';
      }
    }
    requestAnimationFrame(frame);
  }

  function startCounter(el) {
    if (el.dataset.fwStarted === '1') return; // prevent double start
    el.dataset.fwStarted = '1';

    const start = parseFloat(el.getAttribute('data-start') || '0') || 0;
    const end = parseFloat(el.getAttribute('data-end') || '0') || 0;
    const speed = parseInt(el.getAttribute('data-speed') || '1500', 10) || 1500;
    const prefix = el.getAttribute('data-prefix') || '';
    const suffix = el.getAttribute('data-suffix') || '';

    animateValue(el, start, end, speed, prefix, suffix);
  }

  function init() {
    const nodes = Array.from(document.querySelectorAll('.fw-counter'));
    if (nodes.length === 0) return;

    // If IntersectionObserver exists, animate on first view; otherwise animate immediately.
    if ('IntersectionObserver' in window) {
      const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            startCounter(entry.target);
            io.unobserve(entry.target);
          }
        });
      }, { threshold: 0.2 });

      nodes.forEach((el) => io.observe(el));
    } else {
      nodes.forEach(startCounter);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
