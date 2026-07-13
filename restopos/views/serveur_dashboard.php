<?php $myOrders = array_filter($orders, fn($o) => $o['waiter'] === 'Aminata D.'); ?>
<div class="banner banner-green mb-4">
  <p style="color:#a7f3d0;font-size:13px;margin:0 0 12px;">Votre journée — Vendredi 27 juin 2025</p>
  <div class="grid grid-3">
    <div><p class="mono" style="font-size:22px;font-weight:700;margin:0;">3</p><p style="font-size:11px;color:#a7f3d0;margin:0;">Mes tables</p></div>
    <div><p class="mono" style="font-size:22px;font-weight:700;margin:0;">8</p><p style="font-size:11px;color:#a7f3d0;margin:0;">Commandes</p></div>
    <div><p class="mono" style="font-size:22px;font-weight:700;margin:0;">103k FC</p><p style="font-size:11px;color:#a7f3d0;margin:0;">CA généré</p></div>
  </div>
</div>

<p class="section-title">Mes tables</p>
<div class="tables-grid mb-4">
  <?php
  $mine = array_filter($tables, fn($t) => $t['waiter'] === 'Aminata D.' || $t['status'] === 'available');
  foreach (array_slice($mine, 0, 5) as $t): $sc = $tableStatusConfig[$t['status']]; ?>
  <div class="table-card" style="background:<?= $sc['bg'] ?>;border-color:<?= $sc['border'] ?>;">
    <div class="flex-between"><span class="t-name"><?= $t['name'] ?></span><span class="dot" style="background:<?= $sc['dot'] ?>;"></span></div>
    <div class="t-status" style="color:<?= $sc['text'] ?>;"><?= $sc['label'] ?></div>
    <div class="t-cap"><?= $t['capacity'] ?> pers.</div>
  </div>
  <?php endforeach; ?>
</div>

<div class="card">
  <div class="flex-between" style="padding:16px 20px;border-bottom:1px solid var(--border);">
    <p class="section-title" style="margin:0;">Mes commandes</p>
    <a href="index.php?view=prise-commande" class="btn btn-sm" style="background:#059669;color:#fff;"><?= icon('plus', 13) ?> Nouvelle</a>
  </div>
  <div class="divide">
    <?php foreach ($myOrders as $o): $sc = $orderStatusConfig[$o['status']]; ?>
    <div class="flex gap-3 flex-wrap" style="padding:14px 20px;align-items:center;">
      <div class="flex gap-2" style="align-items:center;">
        <span class="mono font-semibold"><?= $o['id'] ?></span>
        <span class="text-xs" style="background:var(--muted);padding:2px 8px;border-radius:999px;"><?= $o['table'] ?></span>
      </div>
      <p class="text-xs text-muted hidden-mobile" style="flex:1;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= implode(', ', $o['items_list']) ?></p>
      <div class="flex gap-2" style="align-items:center;margin-left:auto;">
        <span class="badge" style="background:<?= $sc['bg'] ?>;color:<?= $sc['text'] ?>;"><?= $sc['label'] ?></span>
        <span class="font-semibold mono"><?= fmtShort($o['total']) ?> FC </span>
        <?php if ($o['status'] === 'ready'): ?>
        <span style="color:#059669;"><?= icon('check-circle', 14) ?></span>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>