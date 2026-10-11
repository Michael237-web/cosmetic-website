/* =========================================================
   Product page — quantity + tabs
   ========================================================= */
(function () {
  'use strict';
  const $  = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

  let qty = 1;

  $$('[data-qty]').forEach(btn => {
    btn.addEventListener('click', () => {
      qty = btn.dataset.qty === 'inc' ? qty + 1 : Math.max(1, qty - 1);
      $('#qtyValue').textContent = qty;
      $('#qtyInput').value = qty;
    });
  });

  $$('.option-chip[data-size]').forEach(chip => {
    chip.addEventListener('click', () => {
      $$('.option-chip').forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
    });
  });

  $$('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      $$('.tab-btn').forEach(b => b.classList.remove('active'));
      $$('.tab-panel').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      $(`.tab-panel[data-panel="${btn.dataset.tab}"]`)?.classList.add('active');
    });
  });

  $('#wishBtn')?.addEventListener('click', function () {
    this.classList.toggle('active');
    this.textContent = this.classList.contains('active') ? '♥ Wishlisted' : '♡ Wishlist';
  });
})();