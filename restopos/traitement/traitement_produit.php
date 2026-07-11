<?php
// Ajoute ou modifie un produit du menu
require __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_utilisateur']) || !in_array($_SESSION['role'], ['Administrateur', 'Gérant'])) {
    header('Location: ../login.php');
    exit;
}

$action = $_POST['action'] ?? 'ajouter';
$nom = trim($_POST['nom'] ?? '');
$prix = (float)($_POST['prix'] ?? 0);
$idCategorie = (int)($_POST['id_categorie'] ?? 0);
$disponible = isset($_POST['disponible']) ? 1 : 0;

if ($nom === '' || $prix <= 0 || !$idCategorie) {
    header('Location: ../index.php?view=menu&erreur=' . urlencode('Champs invalides.'));
    exit;
}

// Upload d'image (facultatif)
$imageUrl = 'assets/images/default-plat.jpg';
if (!empty($_FILES['image']['name'])) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('prod_') . '.' . $ext;
    $dest = __DIR__ . '/../assets/images/' . $filename;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        $imageUrl = 'assets/images/' . $filename;
    }
}

if ($action === 'modifier') {
    $id = (int)($_POST['id_produit'] ?? 0);
    $sql = 'UPDATE produit SET nom=:nom, prix=:prix, disponible=:dispo, id_categorie=:cat';
    $params = ['nom' => $nom, 'prix' => $prix, 'dispo' => $disponible, 'cat' => $idCategorie, 'id' => $id];
    if (!empty($_FILES['image']['name'])) {
        $sql .= ', image_url=:img';
        $params['img'] = $imageUrl;
    }
    $sql .= ' WHERE id_produit=:id';
    $pdo->prepare($sql)->execute($params);
    $msg = 'Produit modifié avec succès.';
} else {
    $stmt = $pdo->prepare('
        INSERT INTO produit (nom, prix, disponible, image_url, id_categorie)
        VALUES (:nom, :prix, :dispo, :img, :cat)
    ');
    $stmt->execute([
        'nom' => $nom,
        'prix' => $prix,
        'dispo' => $disponible,
        'img' => $imageUrl,
        'cat' => $idCategorie,
    ]);
    $msg = 'Produit ajouté avec succès.';
}

header('Location: ../index.php?view=menu&succes=' . urlencode($msg));
exit;
