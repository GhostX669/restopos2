<?php
require __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_utilisateur'])) {
    header('Location: ../login.php');
    exit;
}

$nom = trim($_POST['nom'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($nom === '') {
    header('Location: ../index.php?view=settings&erreur=' . urlencode('Le nom est obligatoire.'));
    exit;
}

$pdo->prepare('UPDATE utilisateur SET nom = :nom, email = :email WHERE id_utilisateur = :id')
    ->execute(['nom' => $nom, 'email' => $email, 'id' => $_SESSION['id_utilisateur']]);

$_SESSION['name'] = $nom;

header('Location: ../index.php?view=settings&succes=' . urlencode('Compte mis à jour.'));
exit;