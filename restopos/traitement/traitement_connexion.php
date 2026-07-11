<?php
// Traite le formulaire de login.php
require __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';

if ($login === '' || $password === '') {
    header('Location: ../login.php?erreur=' . urlencode('Veuillez remplir tous les champs.'));
    exit;
}

$stmt = $pdo->prepare('
    SELECT u.id_utilisateur, u.nom, u.mot_passe, u.initiales, u.actif, r.libelle AS role
    FROM utilisateur u
    JOIN role r ON r.id_role = u.id_role
    WHERE u.login = :login
    LIMIT 1
');
$stmt->execute(['login' => $login]);
$user = $stmt->fetch();

if (!$user || !hash_equals($user['mot_passe'], hash('sha256', $password))) {
    header('Location: ../login.php?erreur=' . urlencode('Identifiant ou mot de passe incorrect.'));
    exit;
}

if (!$user['actif']) {
    header('Location: ../login.php?erreur=' . urlencode('Ce compte est désactivé.'));
    exit;
}

// Met à jour la dernière connexion
$upd = $pdo->prepare('UPDATE utilisateur SET derniere_connexion = NOW() WHERE id_utilisateur = :id');
$upd->execute(['id' => $user['id_utilisateur']]);

$_SESSION['id_utilisateur'] = $user['id_utilisateur'];
$_SESSION['role'] = $user['role'];
$_SESSION['name'] = $user['nom'];
$_SESSION['initials'] = $user['initiales'];

header('Location: ../index.php');
exit;