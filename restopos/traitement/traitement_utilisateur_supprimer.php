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
    header('Location: ../index.php?view=users&erreur=' . urlencode('Vous ne pouvez pas supprimer votre propre compte.'));
    exit;
}

try {
    $pdo->prepare('DELETE FROM utilisateur WHERE id_utilisateur = :id')->execute(['id' => $id]);
    header('Location: ../index.php?view=users&succes=' . urlencode('Utilisateur supprimé avec succès.'));
} catch (PDOException $e) {
    header('Location: ../index.php?view=users&erreur=' . urlencode('Impossible de supprimer : cet utilisateur a des commandes ou tables associées. Désactivez-le plutôt.'));
}
exit;