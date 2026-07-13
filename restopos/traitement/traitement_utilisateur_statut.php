<?php
require __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_utilisateur']) || $_SESSION['role'] !== 'Administrateur') {
    header('Location: ../login.php');
    exit;
}

$id = (int)($_POST['id_utilisateur'] ?? 0);

if (!$id) {
    header('Location: ../index.php?view=users&erreur=' . urlencode('Utilisateur invalide.'));
    exit;
}

if ($id === (int)$_SESSION['id_utilisateur']) {
    header('Location: ../index.php?view=users&erreur=' . urlencode('Vous ne pouvez pas désactiver votre propre compte.'));
    exit;
}

$pdo->prepare('UPDATE utilisateur SET actif = NOT actif WHERE id_utilisateur = :id')->execute(['id' => $id]);

header('Location: ../index.php?view=users&succes=' . urlencode('Statut de l\'utilisateur mis à jour.'));
exit;