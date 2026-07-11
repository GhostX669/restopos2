<?php
require __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_utilisateur'])) {
    header('Location: ../login.php');
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="factures-restopos.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Facture', 'Table', 'Montant', 'Mode', 'Heure']);

$stmt = $pdo->query(
    "SELECT c.numero, tr.nom AS table_name, tp.montant, mp.libelle AS mode, tp.date_heure FROM transaction_paiement tp JOIN commande c ON c.id_commande = tp.id_commande JOIN table_restaurant tr ON tr.id_table = c.id_table JOIN mode_paiement mp ON mp.id_mode_paiement = tp.id_mode_paiement ORDER BY tp.id_transaction DESC"
);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [$row['numero'], $row['table_name'], number_format((float)$row['montant'], 2, '.', ''), $row['mode'], $row['date_heure']]);
}

fclose($output);
exit;
