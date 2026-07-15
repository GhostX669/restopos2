<?php
$role = $role ?? ($_SESSION['role'] ?? '');
$tables = $tables ?? [];
$tableStatusConfig = $tableStatusConfig ?? [];
?>

<div class="flex-between mb-4" style="flex-wrap:wrap;gap:12px;">
  <div class="filter-row" style="margin-bottom:0;">
    <button class="filter-btn active" data-filter="all" onclick="filterTables('all')">Toutes</button>
    <?php foreach ($tableStatusConfig as $key => $cfg): ?>
    <button class="filter-btn" data-filter="<?= $key ?>" onclick="filterTables('<?= $key ?>')"><?= $cfg['label'] ?></button>
    <?php endforeach; ?>
  </div>
  <?php if (in_array($role, ['Administrateur', 'Gérant'], true)): ?>
  <button type="button" class="btn btn-accent btn-sm" onclick="toggleNewTableForm()"><?= icon('plus', 13) ?> Nouvelle table</button>
  <?php endif; ?>
</div>

<?php if (in_array($role, ['Administrateur', 'Gérant'], true)): ?>
<form id="new-table-form" method="post" action="traitement/traitement_table.php" class="card card-pad mb-4" style="display:none;">
  <input type="hidden" name="action" value="create">
  <input type="hidden" name="redirect_view" value="tables">
  <div class="grid grid-4 gap-2" style="align-items:end;">
    <div>
      <label class="field-label">Nom</label>
      <input type="text" name="nom" class="field-input" placeholder="T16" required>
    </div>
    <div>
      <label class="field-label">Capacité</label>
      <input type="number" name="capacite" class="field-input" min="1" max="20" value="4" required>
    </div>
    <div>
      <label class="field-label">Statut initial</label>
      <select name="statut" class="field-input">
        <?php foreach ($tableStatusConfig as $code => $cfg): ?>
        <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($cfg['label']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-accent">Ajouter</button>
  </div>
</form>
<?php endif; ?>

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
        <div class="text-muted">Depuis <?= $t['since'] ?></div>
      </div>
    <?php endif; ?>
    <?php if ($t['status'] === 'reserved' && $t['note']): ?>
      <div class="mt-2 text-xs font-semibold" style="color:#1d4ed8;"><?= htmlspecialchars($t['note']) ?></div>
    <?php endif; ?>

    <form method="post" action="traitement/traitement_table.php" class="mt-2">
      <input type="hidden" name="action" value="update_status">
      <input type="hidden" name="id_table" value="<?= (int)$t['id'] ?>">
      <input type="hidden" name="redirect_view" value="tables">
      <select name="statut" class="field-input" style="font-size:11px;padding:5px 6px;" onchange="this.form.submit()">
        <?php foreach ($tableStatusConfig as $code => $cfg): ?>
        <option value="<?= htmlspecialchars($code) ?>" <?= $t['status'] === $code ? 'selected' : '' ?>><?= htmlspecialchars($cfg['label']) ?></option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>