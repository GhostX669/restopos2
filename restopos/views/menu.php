<?php
$menuItems = $menuItems ?? [];
$categoriesList = $categoriesList ?? [];
?>
<style>
.product-menu-wrap { position: relative; display: inline-block; }
.product-menu {
  display: none;
  position: absolute;
  top: calc(100% + 4px);
  right: 0;
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 10px;
  box-shadow: 0 10px 30px rgba(15,23,42,.18);
  z-index: 50;
  min-width: 180px;
  overflow: hidden;
}
.product-menu form { margin: 0; }
.product-menu button {
  display: flex;
  align-items: center;
  gap: 8px;
  width: 100%;
  padding: 10px 12px;
  background: none;
  border: none;
  text-align: left;
  font-size: 13px;
  color: var(--foreground);
  cursor: pointer;
  white-space: nowrap;
}
.product-menu button:hover { background: var(--muted); }
.product-menu button.danger { color: #dc2626; }
.menu-item-card { overflow: visible !important; }
</style>

<?php if (in_array($role, ['Administrateur', 'Gérant'], true)): ?>
<div class="card mb-4" style="padding:20px;">
  <p class="section-title" style="margin:0 0 12px;">Catégories</p>
  <form method="post" action="traitement/traitement_categorie.php" class="flex gap-2 mb-3" style="align-items:flex-end;flex-wrap:wrap;">
    <input type="hidden" name="action" value="ajouter">
    <div style="flex:1;min-width:200px;">
      <label class="field-label">Nouvelle catégorie</label>
      <input type="text" name="libelle" class="field-input" placeholder="Ex: Desserts" required>
    </div>
    <button type="submit" class="btn btn-accent"><?= icon('plus', 13) ?> Ajouter</button>
  </form>
  <div class="flex gap-2" style="flex-wrap:wrap;">
    <?php if (empty($categoriesList)): ?>
    <span class="text-xs text-muted">Aucune catégorie pour le moment.</span>
    <?php else: foreach ($categoriesList as $cat): ?>
    <form method="post" action="traitement/traitement_categorie.php" style="margin:0;" onsubmit="return confirm('Supprimer la catégorie « <?= htmlspecialchars(addslashes($cat['libelle'])) ?> » ?');">
      <input type="hidden" name="action" value="supprimer">
      <input type="hidden" name="id_categorie" value="<?= (int)$cat['id_categorie'] ?>">
      <button type="submit" class="badge" style="background:var(--muted);color:var(--foreground);border:none;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
        <?= htmlspecialchars($cat['libelle']) ?> <?= icon('x', 11) ?>
      </button>
    </form>
    <?php endforeach; endif; ?>
  </div>
</div>
<?php endif; ?>

<div class="card mb-4" style="padding:20px;">
  <p class="section-title" style="margin:0 0 12px;">Ajouter un produit</p>
  <form method="post" action="traitement/traitement_produit.php" enctype="multipart/form-data" class="grid grid-2" style="gap:12px;">
    <input type="hidden" name="action" value="ajouter">
    <div>
      <label class="field-label">Nom du produit</label>
      <input type="text" name="nom" class="field-input" required>
    </div>
    <div>
      <label class="field-label">Prix (FC)</label>
      <input type="number" name="prix" class="field-input" step="100" min="100" required>
    </div>
    <div>
      <label class="field-label">Catégorie</label>
      <select name="id_categorie" class="field-input" <?= empty($categoriesList) ? 'disabled' : '' ?>>
        <?php if (empty($categoriesList)): ?>
        <option value="">Aucune catégorie — ajoutez-en une d'abord</option>
        <?php else: foreach ($categoriesList as $cat): ?>
        <option value="<?= (int)$cat['id_categorie'] ?>"><?= htmlspecialchars($cat['libelle']) ?></option>
        <?php endforeach; endif; ?>
      </select>
    </div>
    <div>
      <label class="field-label">Image</label>
      <input type="file" name="image" class="field-input" accept="image/jpeg,image/png,image/webp,image/avif,image/gif">
    </div>
    <div style="grid-column:1 / -1;">
      <label class="field-label">Disponible</label>
      <input type="checkbox" name="disponible" checked>
    </div>
    <div style="grid-column:1 / -1;">
      <button type="submit" class="btn btn-accent" <?= empty($categoriesList) ? 'disabled' : '' ?>>Ajouter</button>
    </div>
  </form>
</div>

<div class="flex gap-3 mb-4" style="align-items:center;">
  <div style="position:relative;max-width:260px;width:100%;">
    <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted-foreground);"><?= icon('search', 13) ?></span>
    <input type="text" placeholder="Rechercher…" class="field-input" style="padding-left:32px;" oninput="filterMenu(this.value)">
  </div>
</div>

<div class="menu-grid">
  <?php if (empty($menuItems)): ?>
  <div class="card text-muted">Aucun produit disponible pour le moment.</div>
  <?php else: ?>
  <?php foreach ($menuItems as $item): ?>
  <div class="menu-item-card" data-name="<?= htmlspecialchars($item['name']) ?>" data-category="<?= htmlspecialchars($item['category']) ?>">
    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
    <div class="body">
      <p class="name"><?= htmlspecialchars($item['name']) ?></p>
      <p class="cat"><?= htmlspecialchars($item['category']) ?> <?= !$item['available'] ? '· <span style="color:#dc2626;">Indisponible</span>' : '' ?></p>
      <div class="flex-between">
        <span class="price"><?= fmtShort($item['price']) ?> F</span>
        <?php if (in_array($role, ['Administrateur', 'Gérant'], true)): ?>
        <div class="product-menu-wrap">
          <button type="button" class="icon-btn" onclick="toggleProductMenu(<?= (int)$item['id'] ?>)"><?= icon('more-horizontal', 14) ?></button>
          <div class="product-menu" id="product-menu-<?= (int)$item['id'] ?>">
            <button type="button" onclick="toggleProductEdit(<?= (int)$item['id'] ?>)"><?= icon('pencil', 13) ?> Modifier</button>
            <form method="post" action="traitement/traitement_produit_disponibilite.php">
              <input type="hidden" name="id_produit" value="<?= (int)$item['id'] ?>">
              <button type="submit"><?= icon('eye-off', 13) ?> <?= $item['available'] ? 'Marquer indisponible' : 'Marquer disponible' ?></button>
            </form>
            <form method="post" action="traitement/traitement_produit_supprimer.php" onsubmit="return confirm('Supprimer « <?= htmlspecialchars(addslashes($item['name'])) ?> » ?');">
              <input type="hidden" name="id_produit" value="<?= (int)$item['id'] ?>">
              <button type="submit" class="danger"><?= icon('trash-2', 13) ?> Supprimer</button>
            </form>
          </div>
        </div>
        <?php else: ?>
        <button class="icon-btn"><?= icon('more-horizontal', 14) ?></button>
        <?php endif; ?>
      </div>
    </div>

    <?php if (in_array($role, ['Administrateur', 'Gérant'], true)): ?>
    <div id="product-edit-<?= (int)$item['id'] ?>" style="display:none;padding:12px;border-top:1px solid var(--border);background:rgba(0,0,0,.015);">
      <form method="post" action="traitement/traitement_produit.php" enctype="multipart/form-data" class="stack" style="gap:8px;">
        <input type="hidden" name="action" value="modifier">
        <input type="hidden" name="id_produit" value="<?= (int)$item['id'] ?>">
        <div>
          <label class="field-label">Nom</label>
          <input type="text" name="nom" class="field-input" value="<?= htmlspecialchars($item['name']) ?>" required>
        </div>
        <div>
          <label class="field-label">Prix (FC)</label>
          <input type="number" name="prix" class="field-input" step="100" min="100" value="<?= (int)$item['price'] ?>" required>
        </div>
        <div>
          <label class="field-label">Catégorie</label>
          <select name="id_categorie" class="field-input">
            <?php foreach ($categoriesList as $cat): ?>
            <option value="<?= (int)$cat['id_categorie'] ?>" <?= $cat['id_categorie'] === $item['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['libelle']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="field-label">Nouvelle image (optionnel)</label>
          <input type="file" name="image" class="field-input" accept="image/jpeg,image/png,image/webp,image/avif,image/gif">
        </div>
        <div class="flex gap-2" style="align-items:center;">
          <input type="checkbox" name="disponible" id="dispo-<?= (int)$item['id'] ?>" <?= $item['available'] ? 'checked' : '' ?>>
          <label for="dispo-<?= (int)$item['id'] ?>" class="text-xs">Disponible</label>
        </div>
        <div class="flex gap-2">
          <button type="submit" class="btn btn-accent btn-sm">Enregistrer</button>
          <button type="button" class="btn btn-muted btn-sm" onclick="toggleProductEdit(<?= (int)$item['id'] ?>)">Annuler</button>
        </div>
      </form>
    </div>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>