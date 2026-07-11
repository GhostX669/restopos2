<?php
$menuItems = $menuItems ?? [];
$tables = $tables ?? [];
$categories = array_values(array_unique(array_column($menuItems, 'category')));
$availableTables = array_filter($tables, fn($t) => ($t['status'] ?? '') === 'available' || ($t['waiter'] ?? '') === 'Aminata D.');
?>
<form id="order-form" method="post" action="traitement/traitement_commande.php" class="flex gap-4" style="align-items:flex-start;">
  <input type="hidden" name="id_table" id="selected-table-id" value="">
  <input type="hidden" name="produits" id="cart-json" value="[]">
  <div style="flex:1;min-width:0;">
    <div class="filter-row">
      <button type="button" class="cat-btn filter-btn active" data-cat="Tous" onclick="filterMenuCategory('Tous')" style="background:#059669;color:#fff;border-color:#059669;">Tous</button>
      <?php if (empty($categories)): ?>
      <button type="button" class="cat-btn filter-btn" disabled>Aucun menu</button>
      <?php else: ?>
        <?php foreach ($categories as $cat): ?>
      <button type="button" class="cat-btn filter-btn" data-cat="<?= htmlspecialchars($cat) ?>" onclick="filterMenuCategory('<?= htmlspecialchars($cat) ?>')"><?= htmlspecialchars($cat) ?></button>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="menu-grid" style="grid-template-columns:repeat(3,1fr);">
      <?php if (empty($menuItems)): ?>
      <div class="text-muted">Aucun produit disponible.</div>
      <?php else: ?>
        <?php foreach ($menuItems as $item): ?>
      <button type="button" class="menu-item-card" data-name="<?= htmlspecialchars($item['name']) ?>" data-category="<?= htmlspecialchars($item['category']) ?>"
        onclick="addToCart(<?= $item['id'] ?>, '<?= addslashes($item['name']) ?>', <?= $item['price'] ?>)" style="text-align:left;border:1px solid var(--border);">
        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="height:90px;">
        <div class="body">
          <p class="name" style="font-size:12px;"><?= htmlspecialchars($item['name']) ?></p>
          <div class="flex-between mt-2">
            <span class="text-xs font-bold mono" style="color:#059669;"><?= fmtShort($item['price']) ?> F</span>
            <span style="color:#059669;"><?= icon('plus', 14) ?></span>
          </div>
        </div>
      </button>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="card" style="width:250px;flex-shrink:0;">
    <div style="padding:16px;border-bottom:1px solid var(--border);">
      <p class="section-title" style="margin:0 0 8px;">Table</p>
      <div class="grid" style="grid-template-columns:repeat(4,1fr);gap:6px;">
        <?php if (empty($availableTables)): ?>
        <div class="text-muted" style="font-size:11px;">Aucune table disponible.</div>
        <?php else: foreach (array_slice($availableTables, 0, 8) as $t): ?>
        <button type="button" class="table-select-btn" data-id="<?= (int)$t['id'] ?>" onclick="selectTable(this)" style="padding:6px;border-radius:8px;font-size:11px;font-weight:600;border:1px solid var(--border);background:none;color:var(--muted-foreground);"><?= $t['name'] ?></button>
        <?php endforeach; endif; ?>
      </div>
    </div>
    <div id="cart-list" style="padding:12px 16px;max-height:220px;overflow-y:auto;"></div>
    <div style="padding:16px;border-top:1px solid var(--border);">
      <div class="flex-between mb-3"><span class="font-bold">Total</span><span id="cart-total" class="font-bold mono" style="color:#059669;">0 F</span></div>
      <button type="submit" id="cart-send-btn" class="btn mt-2" style="width:100%;justify-content:center;background:#059669;color:#fff;" disabled>
        <?= icon('arrow-right', 14) ?> Envoyer en cuisine
      </button>
    </div>
  </div>
</form>

<style>
.table-select-btn.active { background:#059669 !important; color:#fff !important; border-color:#059669 !important; }
.cat-btn.active { background:#059669 !important; color:#fff !important; border-color:#059669 !important; }
</style>