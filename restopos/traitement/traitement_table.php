<?php
require __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_utilisateur'])) {
    header('Location: ../login.php');
    exit;
}

$action = $_POST['action'] ?? 'update_status';
$redirectView = $_POST['redirect_view'] ?? 'tables';
$redirect = '../index.php?view=' . urlencode($redirectView);

if ($action === 'create') {
    $nom = trim($_POST['nom'] ?? '');
    $capacite = (int)($_POST['capacite'] ?? 0);
    $codeStatut = $_POST['statut'] ?? 'available';
    $note = trim($_POST['note'] ?? '');

    $stmt = $pdo->prepare('SELECT id_statut_table FROM statut_table WHERE code = :code');
    $stmt->execute(['code' => $codeStatut]);
    $statutId = $stmt->fetchColumn();

    if (!$nom || $capacite < 1 || !$statutId) {
        header('Location: ' . $redirect . '&erreur=' . urlencode('Informations de table invalides.'));
        exit;
    }

    $insert = $pdo->prepare('INSERT INTO table_restaurant (nom, capacite, note, id_statut_table) VALUES (:nom, :capacite, :note, :statut)');
    $insert->execute(['nom' => $nom, 'capacite' => $capacite, 'note' => $note, 'statut' => $statutId]);

    header('Location: ' . $redirect . '&succes=' . urlencode('Table ajoutée avec succès.'));
    exit;
}

$idTable = (int)($_POST['id_table'] ?? 0);
$codeStatut = $_POST['statut'] ?? '';

$stmt = $pdo->prepare('SELECT id_statut_table FROM statut_table WHERE code = :code');
$stmt->execute(['code' => $codeStatut]);
$statutId = $stmt->fetchColumn();

if (!$idTable || !$statutId) {
    header('Location: ' . $redirect . '&erreur=' . urlencode('Statut invalide.'));
    exit;
}

$sql = 'UPDATE table_restaurant SET id_statut_table = :s';
$params = ['s' => $statutId, 't' => $idTable];

if ($codeStatut === 'available' || $codeStatut === 'dirty') {
    $sql .= ', id_utilisateur = NULL, depuis = NULL';
} elseif ($codeStatut === 'occupied') {
    $sql .= ', id_utilisateur = :u, depuis = CURTIME()';
    $params['u'] = $_SESSION['id_utilisateur'];
}
$sql .= ' WHERE id_table = :t';

$pdo->prepare($sql)->execute($params);

header('Location: ' . $redirect . '&succes=' . urlencode('Statut de la table mis à jour.'));
exit;
