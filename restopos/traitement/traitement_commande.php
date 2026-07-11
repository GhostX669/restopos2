<?php
// Crée une nouvelle commande (prise de commande serveur)
require __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_utilisateur'])) {
    header('Location: ../login.php');
    exit;
}

$idTable = (int)($_POST['id_table'] ?? 0);
$produits = $_POST['produits'] ?? []; // format attendu: JSON [{id_produit, quantite, prix}] ou [{id, qty, price}]
$produits = json_decode($produits, true);

if (!$idTable || empty($produits)) {
    header('Location: ../index.php?view=prise-commande&erreur=' . urlencode('Sélectionnez une table et au moins un produit.'));
    exit;
}

try {
    $pdo->beginTransaction();

    // Statut "En attente"
    $statutId = $pdo->query("SELECT id_statut_commande FROM statut_commande WHERE code = 'pending'")->fetchColumn();

    // Numéro auto (ex: #0047)
    $dernier = (int)$pdo->query("SELECT MAX(CAST(SUBSTRING(numero,2) AS UNSIGNED)) FROM commande")->fetchColumn();
    $numero = '#' . str_pad($dernier + 1, 4, '0', STR_PAD_LEFT);

    $total = 0;
    foreach ($produits as $p) {
        $idProduit = $p['id_produit'] ?? $p['id'] ?? 0;
        $qte = (int)($p['quantite'] ?? $p['qty'] ?? 0);
        $prix = (float)($p['prix'] ?? $p['price'] ?? 0);
        if ($idProduit && $qte > 0 && $prix >= 0) {
            $total += $prix * $qte;
        }
    }

    $stmt = $pdo->prepare('
        INSERT INTO commande (numero, montant_total, id_table, id_utilisateur, id_statut_commande)
        VALUES (:numero, :total, :table, :user, :statut)
    ');
    $stmt->execute([
        'numero' => $numero, 'total' => $total, 'table' => $idTable,
        'user' => $_SESSION['id_utilisateur'], 'statut' => $statutId,
    ]);
    $idCommande = $pdo->lastInsertId();

    $stmtLigne = $pdo->prepare('
        INSERT INTO ligne_commande (quantite, prix_unitaire, id_commande, id_produit)
        VALUES (:qte, :prix, :commande, :produit)
    ');
    foreach ($produits as $p) {
        $idProduit = $p['id_produit'] ?? $p['id'] ?? 0;
        $qte = (int)($p['quantite'] ?? $p['qty'] ?? 0);
        $prix = (float)($p['prix'] ?? $p['price'] ?? 0);
        if (!$idProduit || $qte <= 0) {
            continue;
        }
        $stmtLigne->execute([
            'qte' => $qte, 'prix' => $prix,
            'commande' => $idCommande, 'produit' => $idProduit,
        ]);
    }

    // Passe la table en "occupée"
    $statutOccupee = $pdo->query("SELECT id_statut_table FROM statut_table WHERE code = 'occupied'")->fetchColumn();
    $pdo->prepare('UPDATE table_restaurant SET id_statut_table = :s, id_utilisateur = :u, depuis = CURTIME() WHERE id_table = :t')
        ->execute(['s' => $statutOccupee, 'u' => $_SESSION['id_utilisateur'], 't' => $idTable]);

    $pdo->commit();
    header('Location: ../index.php?view=orders&succes=' . urlencode('Commande ' . $numero . ' envoyée en cuisine.'));
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    header('Location: ../index.php?view=prise-commande&erreur=' . urlencode('Erreur : ' . $e->getMessage()));
    exit;
}