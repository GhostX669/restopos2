<?php
require __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_utilisateur']) || !in_array($_SESSION['role'], ['Administrateur', 'Gérant'], true)) {
    header('Location: ../login.php');
    exit;
}

$action = $_POST['action'] ?? 'ajouter';

if ($action === 'supprimer') {
    $id = (int)($_POST['id_categorie'] ?? 0);
    if (!$id) {
        header('Location: ../index.php?view=menu&erreur=' . urlencode('Catégorie invalide.'));
        exit;
    }
    try {
        $pdo->prepare('DELETE FROM categorie WHERE id_categorie = :id')->execute(['id' => $id]);
        header('Location: ../index.php?view=menu&succes=' . urlencode('Catégorie supprimée.'));
    } catch (PDOException $e) {
        header('Location: ../index.php?view=menu&erreur=' . urlencode('Impossible de supprimer : des produits utilisent encore cette catégorie.'));
    }
    exit;
}

$libelle = trim($_POST['libelle'] ?? '');

if ($libelle === '') {
    header('Location: ../index.php?view=menu&erreur=' . urlencode('Le nom de la catégorie est obligatoire.'));
    exit;
}

$check = $pdo->prepare('SELECT COUNT(*) FROM categorie WHERE libelle = :libelle');
$check->execute(['libelle' => $libelle]);
if ($check->fetchColumn() > 0) {
    header('Location: ../index.php?view=menu&erreur=' . urlencode('Cette catégorie existe déjà.'));
    exit;
}

$pdo->prepare('INSERT INTO categorie (libelle) VALUES (:libelle)')->execute(['libelle' => $libelle]);

header('Location: ../index.php?view=menu&succes=' . urlencode('Catégorie ajoutée avec succès.'));
exit;