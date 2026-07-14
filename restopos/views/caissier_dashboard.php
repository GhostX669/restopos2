<?php
$orders = $orders ?? [];
$orderStatusConfig = $orderStatusConfig ?? [];
$recentTransactions = $recentTransactions ?? [];
?>

<?php
$fondCaisse = (float)get_parametre($pdo, 'fond_de_caisse', 50000);
$heureOuverture = get_parametre($pdo, 'caisse_heure_ouverture', '09:00');
$caisseToday = $caisseToday ?? ['encaissements' => 0, 'transactions' => 0];
?>
<div class="grid grid-3 mb-4">
  <div class="banner banner-amber">
    <p style="font-size:13px;color:#fde68a;margin:0;display:flex;align-items:center;gap:6px;"><?= icon('wallet', 14) ?> Fond de caisse</p>
    <p class="mono" style="font-size:24px;font-weight:700;margin:6px 0 0;"><?= fmt($fondCaisse) ?></p>
    <p style="font-size:11px;color:#fde68a;margin:4px 0 0;">Ouverture à <?= htmlspecialchars($heureOuverture) ?></p>
  </div>
  <div class="kpi-card">
    <p class="kpi-label">Encaissements</p>
    <p class="kpi-value" style="color:#059669;"><?= fmt($caisseToday['encaissements']) ?></p>
    <p class="text-xs text-muted"><?= $caisseToday['transactions'] ?> transaction<?= $caisseToday['transactions'] > 1 ? 's' : '' ?></p>
  </div>
  <div class="kpi-card">
    <p class="kpi-label">Total en caisse</p>
    <p class="kpi-value"><?= fmt($fondCaisse + $caisseToday['encaissements']) ?></p>
    <p class="text-xs text-muted">Fond + espèces</p>
  </div>
</div>

<div class="card mb-4">
  <div class="flex-between" style="padding:16px 20px;border-bottom:1px solid var(--border);">
    <div class="flex gap-2" style="align-items:center;">
      <p class="section-title" style="margin:0;">À encaisser</p>
      <span class="badge" style="background:var(--accent);color:#fff;"><?= count(array_filter($orders, fn($o)=>($o['status'] ?? '')==='ready')) ?></span>
    </div>
  </div>
  <div class="divide">
    <?php if (empty($orders)): ?>
    <div class="text-muted" style="padding:16px 20px;">Aucune commande à encaisser.</div>
    <?php else: ?>
      <?php foreach ($orders as $o): if (!in_array($o['status'] ?? '', ['ready','served'])) continue; $sc = $orderStatusConfig[$o['status']] ?? ['bg' => '#f3f4f6', 'text' => '#6b7280', 'label' => 'Inconnu']; ?>
    <div class="flex gap-3 flex-wrap" style="padding:16px 20px;align-items:center;">
      <span class="dot" style="background:<?= $o['status']==='ready' ? '#10b981' : '#9ca3af' ?>;"></span>
      <div style="flex:1;min-width:200px;">
        <div class="flex gap-2" style="align-items:center;flex-wrap:wrap;">
          <span class="mono font-semibold"><?= $o['id'] ?></span>
          <span><?= $o['table'] ?></span>
          <span class="badge" style="background:<?= $sc['bg'] ?>;color:<?= $sc['text'] ?>;"><?= $sc['label'] ?></span>
        </div>
        <p class="text-xs text-muted" style="margin:4px 0 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= implode(', ', $o['items_list']) ?></p>
      </div>
      <div style="text-align:right;">
        <p class="font-bold mono" style="margin:0;font-size:16px;"><?= fmt($o['total']) ?></p>
        <p class="text-xs text-muted" style="margin:0;"><?= $o['time'] ?></p>
      </div>
      <?php if ($o['status'] === 'ready'): ?>
      <a href="index.php?view=encaissement" class="btn btn-accent btn-sm"><?= icon('credit-card', 13) ?> Encaisser</a>
      <?php endif; ?>
    </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<div class="card">
  <div class="flex-between" style="padding:16px 20px;border-bottom:1px solid var(--border);">
    <p class="section-title" style="margin:0;">Dernières transactions</p>
    <button class="btn btn-muted btn-sm"><?= icon('printer', 13) ?> Clôturer</button>
  </div>
  <div style="overflow-x:auto;">
  <table class="data-table">
    <thead><tr><th>Facture</th><th>Table</th><th class="text-right">Montant</th><th>Mode</th><th>Heure</th><th></th></tr></thead>
    <tbody>
      <?php if (empty($recentTransactions)): ?>
      <tr><td colspan="6" class="text-muted">Aucune transaction récente.</td></tr>
      <?php else: foreach ($recentTransactions as $tx): ?>
      <tr>
        <td class="mono font-semibold"><?= $tx['id'] ?></td>
        <td><?= $tx['table'] ?></td>
        <td class="text-right mono font-semibold"><?= fmt($tx['amount']) ?></td>
        <td><span class="badge" style="background:#fef3c7;color:#92400e;"><?= $tx['method'] ?></span></td>
        <td class="text-xs mono text-muted"><?= $tx['time'] ?></td>
        <td>
          <button type="button" class="icon-btn" title="Imprimer la facture" onclick="printDoc('facture', <?= (int)($tx['id_transaction'] ?? 0) ?>)"><?= icon('printer', 14) ?></button>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
  </div>
</div>