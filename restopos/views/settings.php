<?php
$tables = $tables ?? [];
$tableStatusConfig = $tableStatusConfig ?? [];
$currentUserEmail = '';
try {
  $stmtEmail = $pdo->prepare('SELECT email FROM utilisateur WHERE id_utilisateur = :id');
  $stmtEmail->execute(['id' => $_SESSION['id_utilisateur'] ?? 0]);
  $currentUserEmail = $stmtEmail->fetchColumn() ?: '';
} catch (PDOException $e) {
  $currentUserEmail = '';
}
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
      <form method="post" action="traitement/traitement_parametres.php" class="grid grid-2" style="gap:12px;">
        <div><label class="field-label">Nom du restaurant</label><input type="text" name="restaurant_nom" class="field-input" value="<?= htmlspecialchars(get_parametre($pdo, 'restaurant_nom', 'Le Wouri Palace')) ?>"></div>
        <div><label class="field-label">Téléphone</label><input type="text" name="restaurant_telephone" class="field-input" value="<?= htmlspecialchars(get_parametre($pdo, 'restaurant_telephone', '+225 07 12 34 56 78')) ?>"></div>
        <div><label class="field-label">Email</label><input type="text" name="restaurant_email" class="field-input" value="<?= htmlspecialchars(get_parametre($pdo, 'restaurant_email', 'contact@wouri-palace.ci')) ?>"></div>
        <div><label class="field-label">Ville</label><input type="text" name="restaurant_ville" class="field-input" value="<?= htmlspecialchars(get_parametre($pdo, 'restaurant_ville', "Abidjan, Côte d'Ivoire")) ?>"></div>
        <div><label class="field-label">Devise</label><input type="text" name="restaurant_devise" class="field-input" value="<?= htmlspecialchars(get_parametre($pdo, 'restaurant_devise', 'FC (XOF)')) ?>"></div>
        <div><label class="field-label">Fond de caisse par défaut</label><input type="number" name="fond_de_caisse" class="field-input" value="<?= (float)get_parametre($pdo, 'fond_de_caisse', 50000) ?>"></div>
        <div style="grid-column:1 / -1;"><label class="field-label">Adresse</label><textarea name="restaurant_adresse" class="field-input" rows="2"><?= htmlspecialchars(get_parametre($pdo, 'restaurant_adresse', 'Boulevard Lagunaire, Plateau, Abidjan')) ?></textarea></div>
        <div style="grid-column:1 / -1;"><button type="submit" class="btn btn-accent"><?= icon('save', 13) ?> Enregistrer</button></div>
      </form>
    </div>

    <div id="sec-account" class="settings-section card card-pad" style="display:none;">
      <p class="section-title">Mon compte</p>
      <div class="flex gap-3 mb-3" style="align-items:center;padding-bottom:16px;border-bottom:1px solid var(--border);">
        <span style="width:52px;height:52px;border-radius:50%;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;"><?= $initials ?></span>
        <div>
          <p class="font-semibold" style="margin:0;"><?= htmlspecialchars($name) ?></p>
          <span class="badge" style="background:<?= $rc['bg'] ?>;color:<?= $rc['text'] ?>;margin-top:4px;display:inline-block;"><?= $role ?></span>
        </div>
      </div>
      <form method="post" action="traitement/traitement_compte.php" class="grid grid-2" style="gap:12px;">
        <div><label class="field-label">Nom complet</label><input type="text" name="nom" class="field-input" value="<?= htmlspecialchars($name) ?>" required></div>
        <div><label class="field-label">Email</label><input type="email" name="email" class="field-input" value="<?= htmlspecialchars($currentUserEmail) ?>"></div>
        <div style="grid-column:1 / -1;"><button type="submit" class="btn btn-accent"><?= icon('save', 13) ?> Enregistrer</button></div>
      </form>
    </div>

    <div id="sec-security" class="settings-section card card-pad" style="display:none;">
      <p class="section-title">Changer le mot de passe</p>
      <form method="post" action="traitement/traitement_password.php" class="stack" style="max-width:320px;">
        <div>
          <label class="field-label">Mot de passe actuel</label>
          <div class="password-field"><input type="password" name="current_password" class="field-input" placeholder="••••••••" required><button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility(this)"><?= icon('eye', 16) ?></button></div>
        </div>
        <div>
          <label class="field-label">Nouveau mot de passe</label>
          <div class="password-field"><input type="password" name="new_password" class="field-input" placeholder="••••••••" required><button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility(this)"><?= icon('eye', 16) ?></button></div>
        </div>
        <div>
          <label class="field-label">Confirmer</label>
          <div class="password-field"><input type="password" name="confirm_password" class="field-input" placeholder="••••••••" required><button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility(this)"><?= icon('eye', 16) ?></button></div>
        </div>
        <button type="submit" class="btn btn-accent"><?= icon('lock', 13) ?> Mettre à jour</button>
      </form>
    </div>

    <div id="sec-notifications" class="settings-section card card-pad" style="display:none;">
  <p class="section-title">Notifications</p>
  <form method="post" action="traitement/traitement_parametres.php">
    <input type="hidden" name="notif_section" value="1">
    <div class="divide">
      <?php $notifs = [
        ['notif_commande', 'Nouvelle commande reçue', 'Alerte à chaque prise de commande'],
        ['notif_paiement', 'Paiement encaissé', 'Confirmation après chaque encaissement'],
        ['notif_stock', 'Alerte stock bas', 'Quand un produit atteint le seuil critique'],
      ];
      foreach ($notifs as $n): ?>
      <div class="flex-between" style="padding:14px 0;">
        <div>
          <p class="font-semibold text-sm" style="margin:0;"><?= $n[1] ?></p>
          <p class="text-xs text-muted" style="margin:2px 0 0;"><?= $n[2] ?></p>
        </div>
        <label class="switch">
          <input type="checkbox" name="<?= $n[0] ?>" value="1" <?= get_parametre($pdo, $n[0], '1') === '1' ? 'checked' : '' ?>>
          <span class="switch-slider"></span>
        </label>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="flex mt-3" style="justify-content:flex-end;"><button type="submit" class="btn btn-accent"><?= icon('save', 13) ?> Enregistrer</button></div>
  </form>
</div>

    <div id="sec-printer" class="settings-section card card-pad" style="display:none;">
  <p class="section-title">Imprimante thermique</p>
  <form method="post" action="traitement/traitement_parametres.php">
    <div class="grid grid-2 gap-3 mb-3">
      <div><label class="field-label">Adresse IP</label><input type="text" name="imprimante_ip" class="field-input" value="<?= htmlspecialchars(get_parametre($pdo, 'imprimante_ip', '192.168.1.50')) ?>"></div>
      <div><label class="field-label">Port</label><input type="text" name="imprimante_port" class="field-input" value="<?= htmlspecialchars(get_parametre($pdo, 'imprimante_port', '9100')) ?>"></div>
      <div><label class="field-label">Largeur ticket (mm)</label><input type="text" name="imprimante_largeur" class="field-input" value="<?= htmlspecialchars(get_parametre($pdo, 'imprimante_largeur', '80')) ?>"></div>
      <div><label class="field-label">Copies</label><input type="text" name="imprimante_copies" class="field-input" value="<?= htmlspecialchars(get_parametre($pdo, 'imprimante_copies', '1')) ?>"></div>
    </div>
    <button type="submit" class="btn btn-accent"><?= icon('save', 13) ?> Enregistrer</button>
  </form>
</div>

    <div id="sec-appearance" class="settings-section card card-pad" style="display:none;">
  <p class="section-title">Apparence</p>
  <form method="post" action="traitement/traitement_parametres.php">
    <p class="font-semibold text-sm mb-2">Couleur d'accentuation</p>
    <div class="flex gap-2 flex-wrap mb-3">
      <?php $currentColor = get_parametre($pdo, 'apparence_couleur', '#f97316'); ?>
      <?php foreach (['#f97316', '#3b82f6', '#10b981', '#8b5cf6', '#ef4444', '#06b6d4'] as $c): ?>
        <label style="cursor:pointer;">
          <input type="radio" name="apparence_couleur" value="<?= $c ?>" <?= $currentColor === $c ? 'checked' : '' ?> style="display:none;">
          <span style="display:block;width:30px;height:30px;border-radius:50%;background:<?= $c ?>;border:2px solid <?= $currentColor === $c ? '#000' : '#fff' ?>;box-shadow:0 0 0 1px var(--border);"></span>
        </label>
      <?php endforeach; ?>
    </div>
    <p class="font-semibold text-sm mb-2">Langue de l'interface</p>
    <select name="apparence_langue" class="field-input" style="width:auto;">
      <?php $currentLang = get_parametre($pdo, 'apparence_langue', 'fr'); ?>
      <option value="fr" <?= $currentLang === 'fr' ? 'selected' : '' ?>>Français</option>
      <option value="en" <?= $currentLang === 'en' ? 'selected' : '' ?>>English</option>
    </select>
    <div class="mt-3"><button type="submit" class="btn btn-accent"><?= icon('save', 13) ?> Enregistrer</button></div>
  </form>
</div>
  </div>
</div>