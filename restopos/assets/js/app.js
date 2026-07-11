
function toggleSidebar() {
  const sb = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  if (!sb) return;
  if (window.innerWidth < 1024) {
    const open = sb.classList.toggle('open');
    if (overlay) {
      overlay.classList.toggle('active', open);
      document.body.style.overflow = open ? 'hidden' : '';
    }
  } else {
    sb.classList.toggle('collapsed');
    if (overlay) overlay.classList.remove('active');
    document.body.style.overflow = '';
  }
}

function closeSidebar() {
  const sb = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  if (sb) sb.classList.remove('open');
  if (overlay) overlay.classList.remove('active');
  document.body.style.overflow = '';
}

function closePanels() {
  document.getElementById('checklist-panel')?.style?.setProperty('display', 'none');
  document.getElementById('notifications-panel')?.style?.setProperty('display', 'none');
}

function toggleChecklist() {
  const panel = document.getElementById('checklist-panel');
  if (!panel) return;
  const open = panel.style.display === 'block';
  closePanels();
  panel.style.display = open ? 'none' : 'block';
}

function toggleNotifications() {
  const panel = document.getElementById('notifications-panel');
  if (!panel) return;
  const open = panel.style.display === 'block';
  closePanels();
  panel.style.display = open ? 'none' : 'block';
}

function renderChecklist() {
  const panel = document.getElementById('checklist-panel');
  const count = document.getElementById('checklist-count');
  if (!panel || !count) return;
  const items = panel.querySelectorAll('input[type="checkbox"]');
  const checked = [...items].filter((i) => i.checked).length;
  count.textContent = `${checked}/${items.length}`;
}

function toggleChecklistItem(id) {
  const input = document.querySelector(`[data-task="${id}"]`);
  if (input) input.checked = !input.checked;
  renderChecklist();
}

function renderNotifications() {
  const list = document.getElementById('notifications-list');
  if (!list) return;
  let items = [];
  try {
    items = JSON.parse(list.dataset.items || '[]');
  } catch (e) {
    items = [];
  }
  if (!Array.isArray(items) || items.length === 0) {
    list.innerHTML = '<div class="notif-item"><div class="font-semibold text-sm">Aucune notification</div><div class="text-xs text-muted">Le service est calme pour le moment.</div></div>';
    return;
  }
  list.innerHTML = items.map((item) => `
    <div class="notif-item">
      <div class="font-semibold text-sm">${item.title}</div>
      <div class="text-xs text-muted">${item.desc}</div>
    </div>
  `).join('');
}

function filterTables(status) {
  document.querySelectorAll('.table-card').forEach((card) => {
    card.style.display = (status === 'all' || card.dataset.status === status) ? '' : 'none';
  });
  document.querySelectorAll('.filter-btn').forEach((btn) => {
    btn.classList.toggle('active', btn.dataset.filter === status);
  });
}

function filterOrders(status) {
  document.querySelectorAll('.order-card').forEach((card) => {
    card.style.display = (status === 'all' || card.dataset.status === status) ? '' : 'none';
  });
  document.querySelectorAll('.filter-btn').forEach((btn) => {
    btn.classList.toggle('active', btn.dataset.filter === status);
  });
}

function toggleOrder(id) {
  const row = document.getElementById('order-detail-' + id);
  if (row) row.style.display = (row.style.display === 'none' || !row.style.display) ? 'block' : 'none';
}

function filterMenu(term) {
  term = term.toLowerCase();
  document.querySelectorAll('.menu-item-card').forEach((card) => {
    const name = card.dataset.name.toLowerCase();
    card.style.display = name.includes(term) ? '' : 'none';
  });
}

function filterMenuCategory(cat) {
  document.querySelectorAll('.menu-item-card').forEach((card) => {
    card.style.display = (cat === 'Tous' || card.dataset.category === cat) ? '' : 'none';
  });
  document.querySelectorAll('.cat-btn').forEach((btn) => {
    btn.classList.toggle('active', btn.dataset.cat === cat);
  });
}

// ─── Panier (prise de commande serveur) ─────────────────────────────────────
let cart = [];

function addToCart(id, name, price) {
  const existing = cart.find((c) => c.id === id);
  if (existing) existing.qty += 1;
  else cart.push({ id, name, price, qty: 1 });
  renderCart();
}

function removeFromCart(id) {
  cart = cart.filter((c) => c.id !== id);
  renderCart();
}

function renderCart() {
  const box = document.getElementById('cart-list');
  const totalEl = document.getElementById('cart-total');
  const sendBtn = document.getElementById('cart-send-btn');
  if (!box) return;

  if (cart.length === 0) {
    box.innerHTML = '<p class="text-xs text-muted" style="text-align:center;padding:24px 0;">Ajoutez des articles</p>';
  } else {
    box.innerHTML = cart.map((c) => `
      <div class="flex" style="align-items:center;gap:8px;">
        <div style="flex:1;min-width:0;">
          <p class="font-semibold text-xs" style="margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${c.name}</p>
          <p class="text-xs text-muted mono" style="margin:0;">${c.price.toLocaleString('fr-FR')} × ${c.qty}</p>
        </div>
        <span class="text-xs font-bold mono">${(c.price * c.qty).toLocaleString('fr-FR')} F</span>
        <button onclick="removeFromCart(${c.id})" class="icon-btn" style="padding:2px;">
          <i data-lucide="x" style="width:12px;height:12px;"></i>
        </button>
      </div>
    `).join('');
  }

  const total = cart.reduce((s, c) => s + c.price * c.qty, 0);
  if (totalEl) totalEl.textContent = total.toLocaleString('fr-FR') + ' F';

  const cartInput = document.getElementById('cart-json');
  if (cartInput) cartInput.value = JSON.stringify(cart);

  const selectedTable = document.querySelector('.table-select-btn.active');
  const tableInput = document.getElementById('selected-table-id');
  if (tableInput && selectedTable) tableInput.value = selectedTable.dataset.id || '';
  if (sendBtn) sendBtn.disabled = cart.length === 0 || !selectedTable;

  if (window.lucide) lucide.createIcons();
}

function selectTable(el) {
  document.querySelectorAll('.table-select-btn').forEach((b) => b.classList.remove('active'));
  el.classList.add('active');
  renderCart();
}

// ─── Encaissement (caissier) ────────────────────────────────────────────────
function selectPaymentMethod(el, method, modeId) {
  document.querySelectorAll('.method-btn').forEach((b) => b.classList.remove('active'));
  el.classList.add('active');
  const cashBlock = document.getElementById('cash-block');
  if (cashBlock) cashBlock.style.display = method === 'Espèces' ? '' : 'none';
  const hidden = document.getElementById('id_mode_paiement');
  if (hidden) hidden.value = modeId;
}

function computeChange(total) {
  const input = document.getElementById('received-amount');
  const resultBox = document.getElementById('change-result');
  const amountHidden = document.getElementById('payment-amount');
  if (!input || !resultBox) return;
  if (amountHidden) amountHidden.value = total;
  const received = parseFloat(input.value);
  if (isNaN(received) || input.value === '') {
    resultBox.style.display = 'none';
    return;
  }
  resultBox.style.display = 'flex';
  if (received < total) {
    resultBox.className = 'error-box';
    resultBox.innerHTML = `<span class="font-semibold">Montant insuffisant</span><span class="font-bold mono" style="margin-left:auto;">${(total - received).toLocaleString('fr-FR')} FC</span>`;
  } else {
    resultBox.className = 'flex-between';
    resultBox.style.padding = '12px'; resultBox.style.borderRadius = '10px';
    resultBox.style.background = '#ecfdf5'; resultBox.style.color = '#047857';
    resultBox.innerHTML = `<span class="font-semibold">Monnaie à rendre</span><span class="font-bold mono">${(received - total).toLocaleString('fr-FR')} FC</span>`;
  }
}

// ─── Réglages : toggles on/off ───────────────────────────────────────────────
function toggleSwitch(el) {
  el.classList.toggle('on');
}

// ─── Onglets réglages ────────────────────────────────────────────────────────
function showSection(sectionId, btn) {
  document.querySelectorAll('.settings-section').forEach((s) => s.style.display = 'none');
  const target = document.getElementById(sectionId);
  if (target) target.style.display = '';
  document.querySelectorAll('.settings-nav-btn').forEach((b) => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
}

document.addEventListener('DOMContentLoaded', () => {
  renderCart();
  renderChecklist();
  renderNotifications();
  document.querySelectorAll('#checklist-panel input[type="checkbox"]').forEach((input) => {
    input.addEventListener('change', renderChecklist);
  });
  document.addEventListener('click', (event) => {
    if (event.target.closest('.topbar-right .icon-btn') || event.target.closest('.floating-panel')) return;
    closePanels();
  });
  if (window.lucide) lucide.createIcons();
});