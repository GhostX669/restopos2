<?php
$orders = $orders ?? [];
$orderStatusConfig = $orderStatusConfig ?? [];
?>

<div class="flex-between mb-4" style="align-items:center;flex-wrap:wrap;gap:12px;">
  <div class="filter-row">
    <button type="button" class="filter-btn active" data-filter="all" onclick="filterOrders('all')">Toutes</button>
    <button type="button" class="filter-btn" data-filter="pending" onclick="filterOrders('pending')">En attente</button>
    <button type="button" class="filter-btn" data-filter="preparing" onclick="filterOrders('preparing')">En préparation</button>
    <button type="button" class="filter-btn" data-filter="ready" onclick="filterOrders('ready')">Prêtes</button>
    <button type="button" class="filter-btn" data-filter="served" onclick="filterOrders('served')">Servies</button>
  </div>
  <a href="index.php?view=prise-commande" class="btn btn-accent"><?= icon('plus', 14) ?> Nouvelle commande</a>
</div>

<div class="stack">
  <?php if (empty($orders)): ?>
  <div class="card text-muted">Aucune commande pour le moment.</div>
  <?php else: ?>
  <?php foreach ($orders as $o): $sc = $orderStatusConfig[$o['status']] ?? ['bg' => '#f3f4f6', 'text' => '#6b7280', 'label' => 'Inconnu']; ?>
  <div class="card order-card" data-status="<?= htmlspecialchars($o['status'] ?? 'pending') ?>">
    <button onclick="toggleOrder('<?= substr($o['id'],1) ?>')" class="flex gap-3 flex-wrap" style="width:100%;padding:14px 16px;background:none;border:none;text-align:left;align-items:center;">
      <span class="mono font-semibold" style="width:56px;flex-shrink:0;"><?= $o['id'] ?></span>
      <span class="font-semibold" style="width:40px;flex-shrink:0;"><?= $o['table'] ?></span>
      <span class="text-xs text-muted hidden-mobile" style="flex:1;"><?= $o['waiter'] ?></span>
      <span class="badge" style="background:<?= $sc['bg'] ?>;color:<?= $sc['text'] ?>;"><?= $sc['label'] ?></span>
      <span class="font-semibold mono" style="margin-left:auto;"><?= fmt($o['total']) ?></span>
      <span><?= icon('chevron-down', 14) ?></span>
    </button>
    <div id="order-detail-<?= substr($o['id'],1) ?>" style="display:none;border-top:1px solid var(--border);padding:12px 16px;background:rgba(0,0,0,.015);">
      <div class="flex gap-3 flex-wrap" style="align-items:flex-start;">
        <ul style="flex:1;margin:0;padding:0;list-style:none;min-width:180px;">
          <?php foreach ($o['items_list'] as $item): ?>
          <li class="text-xs flex gap-2" style="align-items:center;margin-bottom:4px;"><span class="dot" style="background:var(--accent);width:6px;height:6px;"></span><?= htmlspecialchars($item) ?></li>
          <?php endforeach; ?>
        </ul>
        <div class="flex gap-2 flex-wrap" style="align-items:center;">
          <form method="post" action="traitement/traitement_commande_statut.php" style="margin:0;" onclick="event.stopPropagation();">
            <input type="hidden" name="id_commande" value="<?= (int)($o['id_commande'] ?? 0) ?>">
            <select name="statut" class="field-input" style="font-size:12px;padding:6px 8px;" onchange="this.form.submit()" onclick="event.stopPropagation();">
              <?php foreach ($orderStatusConfig as $code => $cfg): ?>
              <option value="<?= htmlspecialchars($code) ?>" <?= $o['status'] === $code ? 'selected' : '' ?>><?= htmlspecialchars($cfg['label']) ?></option>
              <?php endforeach; ?>
            </select>
          </form>
          <button type="button" class="btn btn-muted btn-sm" onclick="printDoc('commande', <?= (int)($o['id_commande'] ?? 0) ?>, false)"><?= icon('eye', 13) ?> Voir</button>
          <button type="button" class="btn btn-muted btn-sm" onclick="printDoc('commande', <?= (int)($o['id_commande'] ?? 0) ?>, true)"><?= icon('printer', 13) ?> Imprimer</button>
          <?php if ($o['status'] === 'ready'): ?>
          <a href="index.php?view=encaissement" class="btn btn-accent btn-sm"><?= icon('credit-card', 13) ?> Encaisser</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>