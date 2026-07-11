<?php
$tables = $tables ?? [];
$tableStatusConfig = $tableStatusConfig ?? [];
?>

<div class="filter-row">
  <button class="filter-btn active" data-filter="all" onclick="filterTables('all')">Toutes</button>
  <?php foreach ($tableStatusConfig as $key => $cfg): ?>
  <button class="filter-btn" data-filter="<?= $key ?>" onclick="filterTables('<?= $key ?>')"><?= $cfg['label'] ?></button>
  <?php endforeach; ?>
</div>

<div class="tables-grid">
  <?php if (empty($tables)): ?>
  <div class="card text-muted">Aucune table disponible.</div>
  <?php else: ?>
  <?php foreach ($tables as $t): $sc = $tableStatusConfig[$t['status']] ?? ['bg' => '#f9fafb', 'dot' => '#9ca3af', 'text' => '#6b7280', 'border' => '#e5e7eb', 'label' => 'Inconnu']; ?>
  <div class="table-card" data-status="<?= $t['status'] ?>" style="background:<?= $sc['bg'] ?>;border-color:<?= $sc['border'] ?>;">
    <div class="flex-between"><span class="t-name"><?= $t['name'] ?></span><span class="dot" style="background:<?= $sc['dot'] ?>;"></span></div>
    <div class="t-status" style="color:<?= $sc['text'] ?>;"><?= $sc['label'] ?></div>
    <div class="t-cap"><?= $t['capacity'] ?> pers.</div>
    <?php if ($t['status'] === 'occupied'): ?>
      <div class="mt-2 text-xs">
        <div class="mono font-semibold"><?= $t['order'] ?></div>
        <div class="text-muted">Depuis <?= $t['since'] ?></div>
        <?php if ($t['total']): ?><div class="font-semibold"><?= fmtShort($t['total']) ?> F</div><?php endif; ?>
      </div>
    <?php endif; ?>
    <?php if ($t['status'] === 'reserved' && $t['note']): ?>
      <div class="mt-2 text-xs font-semibold" style="color:#1d4ed8;"><?= htmlspecialchars($t['note']) ?></div>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>