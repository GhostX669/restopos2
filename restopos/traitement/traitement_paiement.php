<?php
// Enregistre un paiement (encaissement caissier)
require __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_utilisateur'])) {
    header('Location: ../login.php');
    exit;
}

$idCommande = (int)($_POST['id_commande'] ?? 0);
$idModePaiement = (int)($_POST['id_mode_paiement'] ?? 0);
$montant = (float)($_POST['montant'] ?? 0);

if (!$idCommande || !$idModePaiement || $montant <= 0) {
    header('Location: ../index.php?view=encaissement&erreur=' . urlencode('Données de paiement invalides.'));
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('
        INSERT INTO transaction_paiement (montant, id_commande, id_mode_paiement)
        VALUES (:montant, :commande, :mode)
    ');
    $stmt->execute(['montant' => $montant, 'commande' => $idCommande, 'mode' => $idModePaiement]);

    // Passe la commande en "servi"
    $statutServi = $pdo->query("SELECT id_statut_commande FROM statut_commande WHERE code = 'served'")->fetchColumn();
    $pdo->prepare('UPDATE commande SET id_statut_commande = :s WHERE id_commande = :id')
        ->execute(['s' => $statutServi, 'id' => $idCommande]);

    // Libère la table associée
    $idTable = $pdo->prepare('SELECT id_table FROM commande WHERE id_commande = :id');
    $idTable->execute(['id' => $idCommande]);
    $tableId = $idTable->fetchColumn();

    $statutDirty = $pdo->query("SELECT id_statut_table FROM statut_table WHERE code = 'dirty'")->fetchColumn();
    $pdo->prepare('UPDATE table_restaurant SET id_statut_table = :s, id_utilisateur = NULL, depuis = NULL WHERE id_table = :t')
        ->execute(['s' => $statutDirty, 't' => $tableId]);

    $pdo->commit();
    header('Location: ../index.php?view=dashboard&succes=' . urlencode('Paiement enregistré avec succès.'));
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    header('Location: ../index.php?view=encaissement&erreur=' . urlencode('Erreur : ' . $e->getMessage()));
    exit;
}
