/* =========================================================
   Checkout — lightweight validation + quantity helper
   ========================================================= */
(function () {
  'use strict';

  const form = document.getElementById('checkoutForm');
  if (!form) return;

  /* Normalise a Kenyan phone number:
     0712345678  -> 254712345678
     0112345678  -> 254112345678
     254712345678 -> 254712345678
     +254712345678 -> 254712345678
  */
  function normalisePhone(raw) {
    let p = (raw || '').replace(/[\s\-()]/g, '');
    if (p.startsWith('+')) p = p.slice(1);
    if (p.startsWith('0'))  p = '254' + p.slice(1);
    if (p.startsWith('7') || p.startsWith('1')) p = '254' + p;
    return p;
  }

  function isValidKenyanPhone(raw) {
    const p = normalisePhone(raw);
    // 254 + 9 digits, starting with 7 or 1
    return /^254[71]\d{8}$/.test(p);
  }

  form.addEventListener('submit', (e) => {
    const phoneInput = form.querySelector('input[name="phone"]');
    const phone = phoneInput ? phoneInput.value.trim() : '';

    const payment = form.querySelector('input[name="payment"]:checked')?.value || 'mpesa';

    if (payment === 'mpesa' && !isValidKenyanPhone(phone)) {
      e.preventDefault();
      alert('Please enter a valid Kenyan phone number (e.g. 0712 345 678 or 0112 345 678).');
      phoneInput?.focus();
      return false;
    }

    // Disable the button to prevent double submission
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.textContent = 'Placing order…';
    }
  });
})();