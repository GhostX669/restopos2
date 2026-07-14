<?php
require __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_utilisateur'])) {
    header('Location: ../login.php');
    exit;
}

$idCommande = (int)($_POST['id_commande'] ?? 0);
$codeStatut = $_POST['statut'] ?? '';

if (!$idCommande || $codeStatut === '') {
    header('Location: ../index.php?view=orders&erreur=' . urlencode('Statut invalide.'));
    exit;
}

$stmt = $pdo->prepare('SELECT id_statut_commande FROM statut_commande WHERE code = :code');
$stmt->execute(['code' => $codeStatut]);
$statutId = $stmt->fetchColumn();

if (!$statutId) {
    header('Location: ../index.php?view=orders&erreur=' . urlencode('Statut invalide.'));
    exit;
}

$pdo->prepare('UPDATE commande SET id_statut_commande = :s WHERE id_commande = :id')
    ->execute(['s' => $statutId, 'id' => $idCommande]);

// Si la commande est annulée ou servie, on libère la table (nettoyage/disponible selon le cas)
if (in_array($codeStatut, ['cancelled', 'served'], true)) {
    $idTableStmt = $pdo->prepare('SELECT id_table FROM commande WHERE id_commande = :id');
    $idTableStmt->execute(['id' => $idCommande]);
    $idTable = $idTableStmt->fetchColumn();

    if ($idTable) {
        $codeTable = $codeStatut === 'cancelled' ? 'available' : 'dirty';
        $statutTableId = $pdo->prepare('SELECT id_statut_table FROM statut_table WHERE code = :code');
        $statutTableId->execute(['code' => $codeTable]);
        $tId = $statutTableId->fetchColumn();
        if ($tId) {
            $pdo->prepare('UPDATE table_restaurant SET id_statut_table = :s, id_utilisateur = NULL, depuis = NULL WHERE id_table = :t')
                ->execute(['s' => $tId, 't' => $idTable]);
        }
    }
}

header('Location: ../index.php?view=orders&succes=' . urlencode('Statut de la commande mis à jour.'));
exit;