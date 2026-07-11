<?php
$tables = $tables ?? [];
$tableStatusConfig = $tableStatusConfig ?? [];
$sections = [
  ['id' => 'restaurant', 'label' => 'Restaurant', 'icon' => 'store', 'roles' => ['Administrateur', 'Gérant']],
  ['id' => 'account', 'label' => 'Mon compte', 'icon' => 'user', 'roles' => ['Administrateur', 'Gérant', 'Caissier', 'Serveur']],
  ['id' => 'security', 'label' => 'Sécurité', 'icon' => 'shield', 'roles' => ['Administrateur', 'Gérant', 'Caissier', 'Serveur']],
  ['id' => 'notifications', 'label' => 'Notifications', 'icon' => 'bell-ring', 'roles' => ['Administrateur', 'Gérant']],
  ['id' => 'printer', 'label' => 'Imprimante & Caisse', 'icon' => 'printer', 'roles' => ['Administrateur', 'Caissier']],
  ['id' => 'appearance', 'label' => 'Apparence', 'icon' => 'palette', 'roles' => ['Administrateur', 'Gérant']],
];
$sections = array_values(array_filter($sections, fn($s) => in_array($role, $s['roles'], true)));
?>
<div class="flex gap-4" style="align-items:flex-start;">
  <div class="card" style="width:200px;flex-shrink:0;overflow:hidden;">
    <?php foreach ($sections as $i => $s): ?>
      <button class="settings-nav-btn <?= $i === 0 ? 'active' : '' ?>" onclick="showSection('sec-<?= $s['id'] ?>', this)"
        style="width:100%;text-align:left;padding:12px 16px;font-size:13px;font-weight:500;background:none;border:none;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;<?= $i === 0 ? 'background:rgba(249,115,22,.08);color:var(--accent);border-left:2px solid var(--accent);' : '' ?>">
        <?= icon($s['icon'], 14) ?> <?= $s['label'] ?>
      </button>
    <?php endforeach; ?>
  </div>

  <div style="flex:1;min-width:0;">
    <div id="sec-restaurant" class="settings-section card card-pad" style="<?= $sections[0]['id'] !== 'restaurant' ? 'display:none;' : '' ?>">
      <p class="section-title">Informations du restaurant</p>
      <div class="flex gap-4 mb-3" style="align-items:center;padding-bottom:16px;border-bottom:1px solid var(--border);">
        <div style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,#fb923c,#ea580c);display:flex;align-items:center;justify-content:center;">
          <?= icon('utensils-crossed', 28, 'color:#fff;') ?>
        </div>
        <div>
          <p class="font-semibold text-sm mb-2">Logo du restaurant</p>
          <button class="btn btn-muted btn-sm"><?= icon('camera', 12) ?> Changer</button>
        </div>
      </div>
      <div class="grid grid-2 gap-3">
        <?php $fields = [['Nom du restaurant', 'Le Wouri Palace'], ['Téléphone', '+225 07 12 34 56 78'], ['Email', 'contact@wouri-palace.ci'], ['Ville', 'Abidjan, Côte d\'Ivoire'], ['Nombre de tables', '15'], ['Devise', 'FC (XOF)']];
        foreach ($fields as $f): ?>
          <div><label class="field-label"><?= $f[0] ?></label><input type="text" class="field-input" value="<?= htmlspecialchars($f[1]) ?>"></div>
        <?php endforeach; ?>
      </div>
      <div class="mt-3"><label class="field-label">Adresse</label><textarea class="field-input" rows="2">Boulevard Lagunaire, Plateau, Abidjan</textarea></div>
      <div class="flex mt-3" style="justify-content:flex-end;"><button class="btn btn-accent"><?= icon('save', 13) ?> Enregistrer</button></div>
    </div>

    <div id="sec-account" class="settings-section card card-pad" style="display:none;">
      <p class="section-title">Mon compte</p>
      <div class="flex gap-3 mb-3" style="align-items:center;padding-bottom:16px;border-bottom:1px solid var(--border);">
        <span style="width:52px;height:52px;border-radius:50%;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;"><?= $initials ?></span>
        <div>
          <p class="font-semibold" style="margin:0;"><?= htmlspecialchars($name) ?></p>
          <span class="badge" style="background:<?= $rc['bg'] ?>;color:<?= $rc['text'] ?>;margin-top:4px;display:inline-block;"><?= $role ?></span>
        </div>
        <button class="btn btn-muted btn-sm" style="margin-left:auto;"><?= icon('edit-3', 12) ?> Modifier photo</button>
      </div>
      <div class="grid grid-2 gap-3">
        <div><label class="field-label">Prénom</label><input type="text" class="field-input" value="<?= explode(' ', $name)[0] ?>"></div>
        <div><label class="field-label">Nom</label><input type="text" class="field-input" value="<?= explode(' ', $name)[1] ?? '' ?>"></div>
        <div><label class="field-label">Email</label><input type="text" class="field-input" value="<?= strtolower(str_replace(' ', '.', $name)) ?>@restaurant.ci"></div>
        <div><label class="field-label">Téléphone</label><input type="text" class="field-input" value="+225 07 XX XX XX XX"></div>
      </div>
      <div class="flex mt-3" style="justify-content:flex-end;"><button class="btn btn-accent"><?= icon('save', 13) ?> Enregistrer</button></div>
    </div>

    <div id="sec-security" class="settings-section card card-pad" style="display:none;">
      <p class="section-title">Changer le mot de passe</p>
      <div class="stack" style="max-width:320px;">
        <div><label class="field-label">Mot de passe actuel</label><input type="password" class="field-input" placeholder="••••••••"></div>
        <div><label class="field-label">Nouveau mot de passe</label><input type="password" class="field-input" placeholder="••••••••"></div>
        <div><label class="field-label">Confirmer</label><input type="password" class="field-input" placeholder="••••••••"></div>
        <button class="btn btn-accent"><?= icon('lock', 13) ?> Mettre à jour</button>
      </div>
    </div>

    <div id="sec-notifications" class="settings-section card card-pad" style="display:none;">
      <p class="section-title">Notifications</p>
      <div class="divide">
        <?php $notifs = [['Nouvelle commande reçue', 'Alerte à chaque prise de commande'], ['Paiement encaissé', 'Confirmation après chaque encaissement'], ['Alerte stock bas', 'Quand un produit atteint le seuil critique']];
        foreach ($notifs as $n): ?>
          <div class="flex-between" style="padding:14px 0;">
            <div>
              <p class="font-semibold text-sm" style="margin:0;"><?= $n[0] ?></p>
              <p class="text-xs text-muted" style="margin:2px 0 0;"><?= $n[1] ?></p>
            </div>
            <button onclick="toggleSwitch(this)" style="border:none;background:none;color:var(--accent);" class="on"><?= icon('toggle-right', 24) ?></button>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div id="sec-printer" class="settings-section card card-pad" style="display:none;">
      <p class="section-title">Imprimante thermique</p>
      <div class="flex gap-2 mb-3" style="align-items:center;padding:12px;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:10px;">
        <?= icon('wifi', 14, 'color:#059669;') ?>
        <div>
          <p class="font-semibold text-sm" style="margin:0;color:#065f46;">Imprimante connectée</p>
          <p class="text-xs" style="margin:0;color:#059669;">Epson TM-T88VI · 192.168.1.50</p>
        </div>
      </div>
      <div class="grid grid-2 gap-3 mb-3">
        <?php foreach ([['Adresse IP', '192.168.1.50'], ['Port', '9100'], ['Largeur ticket (mm)', '80'], ['Copies', '1']] as $f): ?>
          <div><label class="field-label"><?= $f[0] ?></label><input type="text" class="field-input" value="<?= $f[1] ?>"></div>
        <?php endforeach; ?>
      </div>
      <div class="flex gap-3 flex-wrap">
        <button class="btn btn-accent"><?= icon('save', 13) ?> Enregistrer</button>
        <button class="btn btn-muted"><?= icon('printer', 13) ?> Test impression</button>
      </div>
    </div>

    <div id="sec-appearance" class="settings-section card card-pad" style="display:none;">
      <p class="section-title">Apparence</p>
      <p class="font-semibold text-sm mb-2">Couleur d'accentuation</p>
      <div class="flex gap-2 flex-wrap">
        <?php foreach (['#f97316', '#3b82f6', '#10b981', '#8b5cf6', '#ef4444', '#06b6d4'] as $c): ?>
          <button style="width:30px;height:30px;border-radius:50%;background:<?= $c ?>;border:2px solid #fff;box-shadow:0 0 0 1px var(--border);"></button>
        <?php endforeach; ?>
      </div>
      <p class="font-semibold text-sm mt-3 mb-2">Langue de l'interface</p>
      <select class="field-input" style="width:auto;">
        <option>Français</option>
        <option>English</option>
      </select>
    </div>

    <div id="sec-restaurant" class="settings-section card card-pad" style="display:none;">
      <p class="section-title">Gestion des tables</p>
      <form method="post" action="traitement/traitement_table.php" class="card card-pad" style="margin-bottom:12px;">
        <input type="hidden" name="action" value="create">
        <input type="hidden" name="redirect_view" value="settings">
        <div class="grid grid-2 gap-3">
          <div>
            <label class="field-label">Nom de la table</label>
            <input type="text" name="nom" class="field-input" placeholder="T16" required>
          </div>
          <div>
            <label class="field-label">Capacité</label>
            <input type="number" name="capacite" class="field-input" min="1" max="12" value="4" required>
          </div>
          <div>
            <label class="field-label">Statut initial</label>
            <select name="statut" class="field-input">
              <?php foreach ($tableStatusConfig as $code => $cfg): ?>
              <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($cfg['label']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="field-label">Note</label>
            <input type="text" name="note" class="field-input" placeholder="Réservation VIP">
          </div>
        </div>
        <div class="flex mt-3" style="justify-content:flex-end;"><button class="btn btn-accent">Ajouter la table</button></div>
      </form>

      <div class="stack">
        <?php foreach ($tables as $t): ?>
        <form method="post" action="traitement/traitement_table.php" class="flex gap-3 flex-wrap" style="padding:12px 0;border-bottom:1px solid var(--border);align-items:center;">
          <input type="hidden" name="action" value="update_status">
          <input type="hidden" name="id_table" value="<?= (int)$t['id'] ?>">
          <input type="hidden" name="redirect_view" value="settings">
          <div style="flex:1;min-width:180px;">
            <p class="font-semibold text-sm" style="margin:0;"><?= htmlspecialchars($t['name']) ?></p>
            <p class="text-xs text-muted" style="margin:2px 0 0;">Capacité : <?= (int)$t['capacity'] ?> pers.</p>
          </div>
          <select name="statut" class="field-input" style="width:auto;min-width:140px;">
            <?php foreach ($tableStatusConfig as $code => $cfg): ?>
            <option value="<?= htmlspecialchars($code) ?>" <?= ($t['status'] ?? '') === $code ? 'selected' : '' ?>><?= htmlspecialchars($cfg['label']) ?></option>
            <?php endforeach; ?>
          </select>
          <button class="btn btn-muted btn-sm">Mettre à jour</button>
        </form>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>