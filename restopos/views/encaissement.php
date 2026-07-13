<?php
$orders = $orders ?? [];
$order = null;
foreach ($orders as $o) { if (($o['status'] ?? '') === 'ready') { $order = $o; break; } }
$total = $order['total'] ?? 15200;
?>
<div style="max-width:560px;margin:0 auto;">
  <div class="card mb-4">
    <div class="flex-between" style="padding:16px 20px;background:rgba(0,0,0,.02);border-bottom:1px solid var(--border);">
      <div>
        <p class="font-semibold" style="margin:0;"><?= $order['id'] ?? '#0043' ?> · Table <?= $order['table'] ?? 'T03' ?></p>
        <p class="text-xs text-muted" style="margin:2px 0 0;"><?= $order['waiter'] ?? 'Kouassi B.' ?></p>
      </div>
      <span class="badge" style="background:#d1fae5;color:#047857;">Prêt à servir</span>
    </div>
    <div style="padding:4px 20px;">
      <?php foreach (($order['items_list'] ?? ['Attiéké poisson x2', 'Bière Castel x1']) as $i => $item): ?>
      <div class="flex-between text-sm" style="padding:12px 0;<?= $i > 0 ? 'border-top:1px solid var(--border);' : '' ?>">
        <span><?= htmlspecialchars($item) ?></span>
        <span class="font-semibold mono"><?= $i === 0 ? '11 000 FC' : '4 200 FC' ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="flex-between" style="padding:16px 20px;background:rgba(0,0,0,.015);border-top:1px solid var(--border);">
      <span class="font-bold">Total</span>
      <span class="font-bold mono" style="font-size:20px;color:var(--accent);"><?= fmt($total) ?></span>
    </div>
  </div>

  <form method="post" action="traitement/traitement_paiement.php" class="card card-pad">
    <input type="hidden" name="id_commande" value="<?= (int)($order['id_commande'] ?? 0) ?>">
    <input type="hidden" name="id_mode_paiement" id="id_mode_paiement" value="1">
    <input type="hidden" name="montant" id="payment-amount" value="<?= (float)$total ?>">
    <p class="section-title">Mode de paiement</p>
    <div class="grid grid-2 gap-2 mb-3">
      <?php foreach ([['label'=>'Espèces','id'=>1],['label'=>'Carte bancaire','id'=>2],['label'=>'Mobile Money','id'=>3],['label'=>'Chèque','id'=>4]] as $i => $m): ?>
      <button type="button" class="method-btn <?= $i === 0 ? 'active' : '' ?>" onclick="selectPaymentMethod(this, '<?= $m['label'] ?>', <?= $m['id'] ?>)"
        style="padding:12px;border-radius:8px;border:2px solid <?= $i === 0 ? 'var(--accent)' : 'var(--border)' ?>;background:<?= $i === 0 ? '#fff7ed' : 'var(--card)' ?>;color:<?= $i === 0 ? 'var(--accent)' : 'var(--muted-foreground)' ?>;font-weight:600;font-size:13px;">
        <?= $m['label'] ?>
      </button>
      <?php endforeach; ?>
    </div>

    <div id="cash-block">
      <label class="field-label">Montant reçu (FC)</label>
      <input type="number" id="received-amount" placeholder="Ex: 20000" class="field-input" style="font-size:18px;font-family:'DM Mono',monospace;" oninput="computeChange(<?= $total ?>)">
      <div id="change-result" style="display:none;margin-top:8px;"></div>
    </div>

    <button type="submit" class="btn btn-accent mt-4" style="width:100%;justify-content:center;padding:12px;">
      <?= icon('check-circle', 15) ?> Valider le paiement
    </button>
  </form>
</div>