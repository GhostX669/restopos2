<?php
$recentTransactions = $recentTransactions ?? [];
?>

<div class="grid grid-4 mb-4">
  <div class="kpi-card"><p class="kpi-label">Total du jour</p><p class="kpi-value" style="color:#059669;font-size:17px;">246 400 FC</p></div>
  <div class="kpi-card"><p class="kpi-label">Espèces</p><p class="kpi-value" style="font-size:17px;">93 632 FC</p></div>
  <div class="kpi-card"><p class="kpi-label">Carte bancaire</p><p class="kpi-value" style="font-size:17px;">115 808 FC</p></div>
  <div class="kpi-card"><p class="kpi-label">Mobile Money</p><p class="kpi-value" style="font-size:17px;">27 104 FC</p></div>
</div>

<div class="card">
  <div class="flex-between" style="padding:16px 20px;border-bottom:1px solid var(--border);">
    <p class="section-title" style="margin:0;">Transactions</p>
    <a href="traitement/export_factures.php" class="btn btn-muted btn-sm"><?= icon('printer', 13) ?> Exporter</a>
  </div>
  <div style="overflow-x:auto;">
  <?php if (empty($recentTransactions)): ?>
  <div class="text-muted" style="padding:16px 20px;">Aucune transaction récente.</div>
  <?php else: ?>
  <table class="data-table">
    <thead><tr><th>Facture</th><th>Table</th><th class="text-right">Montant</th><th>Mode</th><th>Heure</th></tr></thead>
    <tbody>
      <?php foreach ($recentTransactions as $tx): ?>
      <tr>
        <td class="mono font-semibold"><?= $tx['id'] ?></td>
        <td><?= $tx['table'] ?></td>
        <td class="text-right mono font-semibold"><?= fmt($tx['amount']) ?></td>
        <td><span class="badge" style="background:#fef3c7;color:#92400e;"><?= $tx['method'] ?></span></td>
        <td class="text-xs mono text-muted"><?= $tx['time'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
  </div>
</div>