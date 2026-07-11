<?php
$weeklyRevenue = $weeklyRevenue ?? [];
$staffPerf = $staffPerf ?? [];
$goalTarget = 300000; $goalCurrent = 246400;
$goalPct = round(($goalCurrent / $goalTarget) * 100);
$maxWeek = !empty($weeklyRevenue) ? max(array_column($weeklyRevenue, 'target')) : 1;
?>
<div class="banner banner-blue mb-4">
  <div class="flex-between flex-wrap">
    <div>
      <p style="color:#bfdbfe;font-size:13px;margin:0;">Objectif journalier</p>
      <p class="mono" style="font-size:24px;font-weight:700;margin:2px 0 0;"><?= fmtShort($goalCurrent) ?> / <?= fmtShort($goalTarget) ?> FC</p>
    </div>
    <div style="text-align:right;">
      <p style="color:#bfdbfe;font-size:13px;margin:0;">Progression</p>
      <p style="font-size:24px;font-weight:700;margin:2px 0 0;"><?= $goalPct ?>%</p>
    </div>
  </div>
  <div class="progress-track mt-3"><div class="progress-fill" style="width:<?= $goalPct ?>%;"></div></div>
  <p style="color:#bfdbfe;font-size:11px;margin:8px 0 0;">Il reste <?= fmtShort($goalTarget - $goalCurrent) ?> FC à réaliser</p>
</div>

<div class="grid grid-4 mb-4">
  <div class="kpi-card"><p class="kpi-label">CA du jour</p><p class="kpi-value">246 400</p><div class="kpi-trend"><span class="trend-up"><?= icon('trending-up', 12) ?> +12.4%</span><span class="trend-sub">vs hier</span></div></div>
  <div class="kpi-card"><p class="kpi-label">Commandes</p><p class="kpi-value">87</p><div class="kpi-trend"><span class="trend-up"><?= icon('trending-up', 12) ?> +8.1%</span><span class="trend-sub">vs hier</span></div></div>
  <div class="kpi-card"><p class="kpi-label">Ticket moyen</p><p class="kpi-value">2 833</p><div class="kpi-trend"><span class="trend-down"><?= icon('trending-down', 12) ?> -3.2%</span><span class="trend-sub">vs hier</span></div></div>
  <div class="kpi-card"><p class="kpi-label">Taux occupation</p><p class="kpi-value">33%</p><div class="kpi-trend"><span class="trend-up"><?= icon('trending-up', 12) ?> +5%</span><span class="trend-sub">tables</span></div></div>
</div>

<div class="grid" style="grid-template-columns:1fr 1fr;">
  <div class="card card-pad">
    <p class="section-title">Ventes hebdo vs Objectif</p>
    <div class="flex" style="align-items:flex-end;gap:10px;height:160px;">
      <?php if (empty($weeklyRevenue)): ?>
      <div class="text-muted">Aucune donnée hebdomadaire disponible.</div>
      <?php else: ?>
        <?php foreach ($weeklyRevenue as $w): $pct = round(($w['revenue'] / $maxWeek) * 100); $tpct = round(($w['target'] / $maxWeek) * 100); ?>
      <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;position:relative;">
        <div style="width:100%;position:relative;height:130px;display:flex;align-items:flex-end;justify-content:center;gap:2px;">
          <div style="width:45%;background:#3b82f6;border-radius:3px 3px 0 0;height:<?= $pct ?>%;"></div>
          <div style="width:45%;background:#dbeafe;border-radius:3px 3px 0 0;height:<?= $tpct ?>%;"></div>
        </div>
        <span class="text-xs text-muted"><?= $w['day'] ?></span>
      </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
  <div class="card card-pad">
    <p class="section-title">Performance serveurs</p>
    <div class="stack">
      <?php if (empty($staffPerf)): ?>
      <div class="text-muted">Aucune performance serveur à afficher.</div>
      <?php else: ?>
        <?php foreach ($staffPerf as $s): $pct = round(($s['revenue'] / 124000) * 100); ?>
      <div>
        <div class="flex-between"><span class="text-xs font-semibold"><?= $s['name'] ?></span><span class="text-xs mono font-semibold" style="color:#2563eb;"><?= fmtShort($s['revenue']) ?> F</span></div>
        <div style="height:6px;background:var(--muted);border-radius:999px;overflow:hidden;margin-top:4px;"><div style="height:100%;background:#3b82f6;border-radius:999px;width:<?= $pct ?>%;"></div></div>
      </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="card card-pad mt-4">
  <p class="section-title">Alertes</p>
  <div class="stack">
    <?php
    $alerts = [
      ['type'=>'warn','msg'=>"Tables T06 et T12 en attente de nettoyage depuis +20 min",'time'=>'13:55'],
      ['type'=>'info','msg'=>"Réservation confirmée : table T10 à 19h — Famille Koné (6 pers.)",'time'=>'13:30'],
      ['type'=>'ok','msg'=>"Livraison fournisseur reçue : boissons et condiments",'time'=>'11:00'],
    ];
    $alertColors = ['warn'=>['#fffbeb','#92400e'],'info'=>['#eff6ff','#1e40af'],'ok'=>['#ecfdf5','#065f46']];
    $alertIcons  = ['warn'=>'alert-triangle','info'=>'info','ok'=>'check-circle'];
    foreach ($alerts as $a): [$bg,$text] = $alertColors[$a['type']]; ?>
    <div class="flex gap-3" style="padding:12px;border-radius:8px;background:<?= $bg ?>;color:<?= $text ?>;align-items:flex-start;">
      <?= icon($alertIcons[$a['type']], 14) ?>
      <span class="text-xs" style="flex:1;"><?= htmlspecialchars($a['msg']) ?></span>
      <span class="text-xs mono" style="opacity:.6;"><?= $a['time'] ?></span>
    </div>
    <?php endforeach; ?>
  </div>
</div>