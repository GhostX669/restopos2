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

// ---- Upload d'image (facultatif) ----
$imageUrl = null; // null = on ne touche pas à l'image existante en cas de modification
$allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'avif', 'gif'];
$allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/avif', 'image/gif'];
$maxSize = 5 * 1024 * 1024; // 5 Mo

if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $mime = mime_content_type($_FILES['image']['tmp_name']);
    $size = $_FILES['image']['size'];

    if (!in_array($ext, $allowedExt, true) || !in_array($mime, $allowedMime, true)) {
        header('Location: ../index.php?view=menu&erreur=' . urlencode("Format d'image non autorisé (jpg, png, webp, avif, gif uniquement)."));
        exit;
    }
    if ($size > $maxSize) {
        header('Location: ../index.php?view=menu&erreur=' . urlencode('Image trop volumineuse (5 Mo max).'));
        exit;
    }

    $imagesDir = __DIR__ . '/../assets/images';
    if (!is_dir($imagesDir)) {
        mkdir($imagesDir, 0775, true);
    }

    $filename = uniqid('prod_', true) . '.' . $ext;
    $dest = $imagesDir . '/' . $filename;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        $imageUrl = 'assets/images/' . $filename;
    } else {
        header('Location: ../index.php?view=menu&erreur=' . urlencode("Échec de l'envoi de l'image."));
        exit;
    }
} elseif (!empty($_FILES['image']['name']) && ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    header('Location: ../index.php?view=menu&erreur=' . urlencode('Erreur lors du téléversement de l\'image.'));
    exit;
}


if ($action === 'modifier') {
    $id = (int)($_POST['id_produit'] ?? 0);
    $sql = 'UPDATE produit SET nom=:nom, prix=:prix, disponible=:dispo, id_categorie=:cat';
    $params = ['nom' => $nom, 'prix' => $prix, 'dispo' => $disponible, 'cat' => $idCategorie, 'id' => $id];
    if ($imageUrl !== null) {
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
        'img' => $imageUrl ?? 'assets/images/default-plat.jpg',
        'cat' => $idCategorie,
    ]);
    $msg = 'Produit ajouté avec succès.';
}

header('Location: ../index.php?view=menu&succes=' . urlencode($msg));
exit;