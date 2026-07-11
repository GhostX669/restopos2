<?php
$menuItems = $menuItems ?? [];
?>

<?php
$menuItems = $menuItems ?? [];
$categoriesList = $categoriesList ?? [];
?>

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
      <select name="id_categorie" class="field-input">
        <?php foreach ($categoriesList as $cat): ?>
        <option value="<?= (int)$cat['id_categorie'] ?>"><?= htmlspecialchars($cat['libelle']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="field-label">Image</label>
      <input type="file" name="image" class="field-input">
    </div>
    <div style="grid-column:1 / -1;">
      <label class="field-label">Disponible</label>
      <input type="checkbox" name="disponible" checked>
    </div>
    <div style="grid-column:1 / -1;">
      <button type="submit" class="btn btn-accent">Ajouter</button>
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
      <p class="cat"><?= htmlspecialchars($item['category']) ?></p>
      <div class="flex-between">
        <span class="price"><?= fmtShort($item['price']) ?> F</span>
        <button class="icon-btn"><?= icon('more-horizontal', 14) ?></button>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>