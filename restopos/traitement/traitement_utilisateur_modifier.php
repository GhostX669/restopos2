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
$nom = trim($_POST['nom'] ?? '');
$email = trim($_POST['email'] ?? '');
$idRole = (int)($_POST['id_role'] ?? 0);
$newPassword = $_POST['password'] ?? '';

if (!$id || $nom === '' || !$idRole) {
    header('Location: ../index.php?view=users&erreur=' . urlencode('Champs invalides.'));
    exit;
}

$sql = 'UPDATE utilisateur SET nom = :nom, email = :email, id_role = :role';
$params = ['nom' => $nom, 'email' => $email, 'role' => $idRole, 'id' => $id];

if ($newPassword !== '') {
    $sql .= ', mot_passe = :mdp';
    $params['mdp'] = hash('sha256', $newPassword);
}

$sql .= ' WHERE id_utilisateur = :id';
$pdo->prepare($sql)->execute($params);

if ($id === (int)$_SESSION['id_utilisateur']) {
    $_SESSION['name'] = $nom;
}

header('Location: ../index.php?view=users&succes=' . urlencode('Utilisateur modifié avec succès.'));
exit;