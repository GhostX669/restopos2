<?php
$staffList = $staffList ?? [];
$roleConfig = $roleConfig ?? [];
$rolesList = $rolesList ?? [];
?>

<div class="card mb-4" style="padding:20px;">
  <p class="section-title" style="margin:0 0 12px;">Nouvel utilisateur</p>
  <form method="post" action="traitement/traitement_utilisateur.php" class="grid grid-2" style="gap:12px;">
    <div>
      <label class="field-label">Nom complet</label>
      <input type="text" name="nom" class="field-input" required>
    </div>
    <div>
      <label class="field-label">Identifiant</label>
      <input type="text" name="login" class="field-input" required>
    </div>
    <div>
      <label class="field-label">Mot de passe</label>
      <input type="password" name="password" class="field-input" required>
    </div>
    <div>
      <label class="field-label">Email</label>
      <input type="email" name="email" class="field-input">
    </div>
    <div style="grid-column:1 / -1;">
      <label class="field-label">Rôle</label>
      <select name="id_role" class="field-input">
        <?php foreach ($rolesList as $role): ?>
        <option value="<?= (int)$role['id_role'] ?>"><?= htmlspecialchars($role['libelle']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div style="grid-column:1 / -1;">
      <button type="submit" class="btn btn-accent">Créer</button>
    </div>
  </form>
</div>

<div class="card">
  <div style="overflow-x:auto;">
  <?php if (empty($staffList)): ?>
  <div class="text-muted">Aucun utilisateur enregistré.</div>
  <?php else: ?>
  <table class="data-table">
    <thead><tr><th>Utilisateur</th><th>Rôle</th><th>Email</th><th>Dernière connexion</th><th>Statut</th></tr></thead>
    <tbody>
      <?php foreach ($staffList as $u): $rc2 = $roleConfig[$u['role']] ?? ['bg' => '#f3f4f6', 'text' => '#6b7280']; ?>
      <tr>
        <td class="flex gap-2" style="align-items:center;border:none;">
          <span style="width:26px;height:26px;border-radius:50%;background:var(--accent);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;"><?= initials_from_name($u['name']) ?></span>
          <span class="font-semibold"><?= $u['name'] ?></span>
        </td>
        <td><span class="badge" style="background:<?= $rc2['bg'] ?>;color:<?= $rc2['text'] ?>;"><?= $u['role'] ?></span></td>
        <td class="text-xs text-muted"><?= $u['email'] ?></td>
        <td class="text-xs mono text-muted"><?= $u['lastLogin'] ?></td>
        <td>
          <span class="text-xs font-semibold" style="color:<?= $u['active'] ? '#059669' : '#9ca3af' ?>;">
            <span class="dot" style="width:6px;height:6px;background:<?= $u['active'] ? '#10b981' : '#9ca3af' ?>;display:inline-block;margin-right:4px;"></span>
            <?= $u['active'] ? 'Actif' : 'Inactif' ?>
          </span>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
  </div>
</div>