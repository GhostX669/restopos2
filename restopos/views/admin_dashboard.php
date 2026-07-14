<?php
$hourlyRevenue = $hourlyRevenue ?? [];
$paymentMethods = $paymentMethods ?? [];
$orders = $orders ?? [];
$orderStatusConfig = $orderStatusConfig ?? [];
$kpiToday = $kpiToday ?? ['revenue'=>0,'orders'=>0,'avg_ticket'=>0,'revenue_trend'=>0,'orders_trend'=>0,'ticket_trend'=>0];
$tablesOccupiedCount = $tablesOccupiedCount ?? 0;
$tablesTotalCount = $tablesTotalCount ?? 0;
$maxRev = !empty($hourlyRevenue) ? max(array_column($hourlyRevenue, 'revenue')) : 1;
if ($maxRev <= 0) { $maxRev = 1; }

function trendArrow($val) {
    if ($val > 0) return ['trend-up', 'trending-up', '+' . $val . '%'];
    if ($val < 0) return ['trend-down', 'trending-down', $val . '%'];
    return ['trend-sub', 'minus', '0%'];
}
[$rClass, $rIcon, $rLabel] = trendArrow($kpiToday['revenue_trend']);
[$oClass, $oIcon, $oLabel] = trendArrow($kpiToday['orders_trend']);
[$tClass, $tIcon, $tLabel] = trendArrow($kpiToday['ticket_trend']);
?>
<div class="grid grid-4 mb-4">
  <div class="kpi-card">
    <p class="kpi-label">Chiffre d'affaires</p>
    <p class="kpi-value"><?= fmtShort($kpiToday['revenue']) ?></p>
    <div class="kpi-trend"><span class="<?= $rClass ?>"><?= icon($rIcon, 12) ?> <?= $rLabel ?></span><span class="trend-sub">vs hier</span></div>
  </div>
  <div class="kpi-card">
    <p class="kpi-label">Commandes</p>
    <p class="kpi-value"><?= $kpiToday['orders'] ?></p>
    <div class="kpi-trend"><span class="<?= $oClass ?>"><?= icon($oIcon, 12) ?> <?= $oLabel ?></span><span class="trend-sub">vs hier</span></div>
  </div>
  <div class="kpi-card">
    <p class="kpi-label">Tables occupées</p>
    <p class="kpi-value"><?= $tablesOccupiedCount ?> / <?= $tablesTotalCount ?></p>
    <div class="kpi-trend"><span class="trend-sub"><?= $tablesTotalCount > 0 ? round(($tablesOccupiedCount/$tablesTotalCount)*100) : 0 ?>% occupation</span></div>
  </div>
  <div class="kpi-card">
    <p class="kpi-label">Ticket moyen</p>
    <p class="kpi-value"><?= fmtShort($kpiToday['avg_ticket']) ?></p>
    <div class="kpi-trend"><span class="<?= $tClass ?>"><?= icon($tIcon, 12) ?> <?= $tLabel ?></span><span class="trend-sub">vs hier</span></div>
  </div>
</div>

<div class="grid mb-4" style="grid-template-columns:2fr 1fr;">
  <div class="card card-pad">
    <p class="section-title">Revenus par heure (aujourd'hui)</p>
    <div class="flex" style="align-items:flex-end;gap:6px;height:170px;">
      <?php if (empty($hourlyRevenue)): ?>
        <div class="text-muted">Aucune commande enregistrée aujourd'hui.</div>
      <?php else: ?>
        <?php foreach ($hourlyRevenue as $h): $pct = round(($h['revenue'] / $maxRev) * 100); ?>
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;">
          <div style="width:100%;background:var(--accent);border-radius:4px 4px 0 0;height:<?= max($pct,2) ?>%;" title="<?= fmt($h['revenue']) ?>"></div>
          <span class="text-xs text-muted"><?= $h['hour'] ?></span>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
  <div class="card card-pad">
    <p class="section-title">Modes de paiement</p>
    <div class="stack">
      <?php if (empty($paymentMethods)): ?>
        <div class="text-muted">Aucun paiement enregistré.</div>
      <?php else: ?>
        <?php foreach ($paymentMethods as $m): ?>
        <div class="flex-between">
          <div class="flex gap-2" style="align-items:center;">
            <span class="dot" style="background:<?= $m['color'] ?>;"></span>
            <span class="text-xs text-muted"><?= $m['name'] ?></span>
          </div>
          <span class="text-xs font-bold mono"><?= $m['value'] ?></span>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="card mt-4">
  <div class="flex-between" style="padding:16px 20px;border-bottom:1px solid var(--border);">
    <p class="section-title" style="margin:0;">Commandes récentes</p>
  </div>
  <div style="overflow-x:auto;">
    <table class="data-table">
      <thead><tr><th>Commande</th><th>Table</th><th>Serveur</th><th>Statut</th><th class="text-right">Montant</th></tr></thead>
      <tbody>
        <?php if (empty($orders)): ?>
          <tr><td colspan="5" class="text-muted">Aucune commande récente.</td></tr>
        <?php else: ?>
          <?php foreach (array_slice($orders, 0, 5) as $o): $sc = $orderStatusConfig[$o['status']] ?? ['bg' => '#f3f4f6', 'text' => '#6b7280', 'label' => 'Inconnu']; ?>
          <tr>
            <td class="mono font-semibold"><?= $o['id'] ?></td>
            <td><?= $o['table'] ?></td>
            <td class="text-muted"><?= $o['waiter'] ?></td>
            <td><span class="badge" style="background:<?= $sc['bg'] ?>;color:<?= $sc['text'] ?>;"><?= $sc['label'] ?></span></td>
            <td class="text-right mono font-semibold"><?= fmt($o['total']) ?></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>