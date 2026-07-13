<?php
$weeklyRevenue = $weeklyRevenue ?? [];
$staffPerf = $staffPerf ?? [];
$maxWeek = !empty($weeklyRevenue) ? max(array_column($weeklyRevenue, 'target')) : 1;
$maxStaffRevenue = !empty($staffPerf) ? max(array_column($staffPerf, 'revenue')) : 1;
if ($maxStaffRevenue <= 0) { $maxStaffRevenue = 1; }
?>
<div class="grid mb-4" style="grid-template-columns:1fr 1fr;">
  <div class="card card-pad">
    <p class="section-title">Ventes hebdomadaires</p>
    <div class="flex" style="align-items:flex-end;gap:10px;height:150px;">
      <?php if (empty($weeklyRevenue)): ?>
      <div class="text-muted">Aucune donnée de vente sur les 7 derniers jours. Passez une commande et encaissez-la pour voir le graphique se remplir.</div>
      <?php else: ?>
        <?php foreach ($weeklyRevenue as $w): $pct = round(($w['revenue']/$maxWeek)*100); $tpct = round(($w['target']/$maxWeek)*100); ?>
      <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;">
        <div style="width:100%;height:120px;display:flex;align-items:flex-end;justify-content:center;gap:2px;">
          <div style="width:45%;background:var(--accent);border-radius:3px 3px 0 0;height:<?= $pct ?>%;"></div>
          <div style="width:45%;background:#e5e7eb;border-radius:3px 3px 0 0;height:<?= $tpct ?>%;"></div>
        </div>
        <span class="text-xs text-muted"><?= $w['day'] ?></span>
      </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
  <div class="card card-pad">
    <p class="section-title">Top produits</p>
    <div class="stack">
      <?php
      $top = [['name'=>'Poulet braisé','qty'=>342,'pct'=>88],['name'=>'Thiéboudienne','qty'=>289,'pct'=>74],['name'=>'Jus de gingembre','qty'=>256,'pct'=>66],['name'=>'Attiéké poisson','qty'=>198,'pct'=>51],['name'=>'Grillades mixtes','qty'=>167,'pct'=>43]];
      foreach ($top as $i => $item): ?>
      <div class="flex gap-3" style="align-items:center;">
        <span class="text-xs mono text-muted" style="width:16px;"><?= $i+1 ?></span>
        <div style="flex:1;">
          <div class="flex-between"><span class="text-xs font-semibold"><?= $item['name'] ?></span><span class="text-xs mono text-muted"><?= $item['qty'] ?></span></div>
          <div style="height:6px;background:var(--muted);border-radius:999px;overflow:hidden;margin-top:4px;"><div style="height:100%;background:var(--accent);width:<?= $item['pct'] ?>%;"></div></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<div class="card card-pad">
  <p class="section-title">Performance du personnel</p>
  <div style="overflow-x:auto;">
  <?php if (empty($staffPerf)): ?>
  <div class="text-muted">Aucune donnée de performance disponible pour le moment.</div>
  <?php else: ?>
  <table class="data-table">
    <thead><tr><th>Serveur</th><th>Commandes</th><th>CA généré</th><th>Note</th></tr></thead>
    <tbody>
      <?php foreach ($staffPerf as $s): $pctStaff = round(($s['revenue'] / $maxStaffRevenue) * 100); ?>
      <tr>
        <td class="flex gap-2" style="align-items:center;border:none;">
          <span style="width:26px;height:26px;border-radius:50%;background:var(--accent);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;"><?= initials_from_name($s['name']) ?></span>
          <span class="font-semibold"><?= $s['name'] ?></span>
        </td>
        <td class="mono font-semibold"><?= $s['orders'] ?></td>
        <td class="mono font-semibold" style="color:var(--accent);"><?= fmtShort($s['revenue']) ?> F</td>
        <td class="font-semibold text-xs flex gap-2" style="align-items:center;border:none;"><?= $s['rating'] ?> <?= icon('star', 12, 'fill:#facc15;color:#facc15;') ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
  </div>
</div>