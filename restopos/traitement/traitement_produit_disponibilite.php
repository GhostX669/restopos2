<?php
require __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_utilisateur']) || !in_array($_SESSION['role'], ['Administrateur', 'Gérant'], true)) {
    header('Location: ../login.php');
    exit;
}

$id = (int)($_POST['id_produit'] ?? 0);
if (!$id) {
    header('Location: ../index.php?view=menu&erreur=' . urlencode('Produit invalide.'));
    exit;
}

$pdo->prepare('UPDATE produit SET disponible = NOT disponible WHERE id_produit = :id')->execute(['id' => $id]);

header('Location: ../index.php?view=menu&succes=' . urlencode('Disponibilité mise à jour.'));
exit;