<?php
// Ajoute un utilisateur (Administrateur uniquement)
require __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_utilisateur']) || $_SESSION['role'] !== 'Administrateur') {
    header('Location: ../login.php');
    exit;
}

$nom = trim($_POST['nom'] ?? '');
$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';
$email = trim($_POST['email'] ?? '');
$idRole = (int)($_POST['id_role'] ?? 0);

if ($nom === '' || $login === '' || $password === '' || !$idRole) {
    header('Location: ../index.php?view=users&erreur=' . urlencode('Champs obligatoires manquants.'));
    exit;
}

// Vérifie l'unicité du login
$check = $pdo->prepare('SELECT COUNT(*) FROM utilisateur WHERE login = :login');
$check->execute(['login' => $login]);
if ($check->fetchColumn() > 0) {
    header('Location: ../index.php?view=users&erreur=' . urlencode('Cet identifiant existe déjà.'));
    exit;
}

$initiales = mb_strtoupper(mb_substr($nom, 0, 1) . mb_substr(strrchr($nom, ' ') ?: $nom, 1, 1));

$stmt = $pdo->prepare('
    INSERT INTO utilisateur (nom, login, mot_passe, email, initiales, actif, id_role)
    VALUES (:nom, :login, :mdp, :email, :initiales, 1, :role)
');
$stmt->execute([
    'nom' => $nom,
    'login' => $login,
    'mdp' => hash('sha256', $password),
    'email' => $email,
    'initiales' => $initiales,
    'role' => $idRole,
]);

header('Location: ../index.php?view=users&succes=' . urlencode('Utilisateur créé avec succès.'));
exit;
