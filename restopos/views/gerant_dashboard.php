<?php
$weeklyRevenue = $weeklyRevenue ?? [];
$staffPerf = $staffPerf ?? [];
$notifications = $notifications ?? [];
$kpiToday = $kpiToday ?? ['revenue'=>0,'orders'=>0,'avg_ticket'=>0,'revenue_trend'=>0,'orders_trend'=>0,'ticket_trend'=>0];
$tablesOccupiedCount = $tablesOccupiedCount ?? 0;
$tablesTotalCount = $tablesTotalCount ?? 0;

$goalTarget = (float)get_parametre($pdo, 'objectif_journalier', 300000);
$goalCurrent = $kpiToday['revenue'];
$goalPct = $goalTarget > 0 ? min(100, round(($goalCurrent / $goalTarget) * 100)) : 0;
$maxWeek = !empty($weeklyRevenue) ? max(array_column($weeklyRevenue, 'target')) : 1;
if ($maxWeek <= 0) { $maxWeek = 1; }
$maxStaffRevenue = !empty($staffPerf) ? max(array_column($staffPerf, 'revenue')) : 1;
if ($maxStaffRevenue <= 0) { $maxStaffRevenue = 1; }

function trendArrowG($val) {
    if ($val > 0) return ['trend-up', 'trending-up', '+' . $val . '%'];
    if ($val < 0) return ['trend-down', 'trending-down', $val . '%'];
    return ['trend-sub', 'minus', '0%'];
}
[$rClass, $rIcon, $rLabel] = trendArrowG($kpiToday['revenue_trend']);
[$oClass, $oIcon, $oLabel] = trendArrowG($kpiToday['orders_trend']);
[$tClass, $tIcon, $tLabel] = trendArrowG($kpiToday['ticket_trend']);
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
  <p style="color:#bfdbfe;font-size:11px;margin:8px 0 0;">
    <?= $goalCurrent >= $goalTarget ? 'Objectif atteint 🎉' : 'Il reste ' . fmtShort($goalTarget - $goalCurrent) . ' FC à réaliser' ?>
  </p>
</div>

<div class="grid grid-4 mb-4">
  <div class="kpi-card"><p class="kpi-label">CA du jour</p><p class="kpi-value"><?= fmtShort($kpiToday['revenue']) ?></p><div class="kpi-trend"><span class="<?= $rClass ?>"><?= icon($rIcon, 12) ?> <?= $rLabel ?></span><span class="trend-sub">vs hier</span></div></div>
  <div class="kpi-card"><p class="kpi-label">Commandes</p><p class="kpi-value"><?= $kpiToday['orders'] ?></p><div class="kpi-trend"><span class="<?= $oClass ?>"><?= icon($oIcon, 12) ?> <?= $oLabel ?></span><span class="trend-sub">vs hier</span></div></div>
  <div class="kpi-card"><p class="kpi-label">Ticket moyen</p><p class="kpi-value"><?= fmtShort($kpiToday['avg_ticket']) ?></p><div class="kpi-trend"><span class="<?= $tClass ?>"><?= icon($tIcon, 12) ?> <?= $tLabel ?></span><span class="trend-sub">vs hier</span></div></div>
  <div class="kpi-card"><p class="kpi-label">Taux occupation</p><p class="kpi-value"><?= $tablesTotalCount > 0 ? round(($tablesOccupiedCount/$tablesTotalCount)*100) : 0 ?>%</p><div class="kpi-trend"><span class="trend-sub"><?= $tablesOccupiedCount ?> / <?= $tablesTotalCount ?> tables</span></div></div>
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
          <div style="width:45%;background:#3b82f6;border-radius:3px 3px 0 0;height:<?= max($pct,2) ?>%;"></div>
          <div style="width:45%;background:#dbeafe;border-radius:3px 3px 0 0;height:<?= max($tpct,2) ?>%;"></div>
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
        <?php foreach ($staffPerf as $s): $pct = round(($s['revenue'] / $maxStaffRevenue) * 100); ?>
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
    <?php if (empty($notifications)): ?>
    <div class="text-muted">Aucune alerte pour le moment.</div>
    <?php else: foreach ($notifications as $n): ?>
    <div class="flex gap-3" style="padding:12px;border-radius:8px;background:#eff6ff;color:#1e40af;align-items:flex-start;">
      <?= icon('info', 14) ?>
      <span class="text-xs" style="flex:1;"><strong><?= htmlspecialchars($n['title']) ?></strong> — <?= htmlspecialchars($n['desc']) ?></span>
    </div>
    <?php endforeach; endif; ?>
  </div>
</div>