<?php
require __DIR__ . '/includes/config.php';

if (empty($_SESSION['id_utilisateur'])) {
    header('Location: login.php');
    exit;
}

function fmtP($n) {
    return number_format((float)$n, 0, ',', ' ') . ' FC';
}

$type = $_GET['type'] ?? '';
$id = (int)($_GET['id'] ?? 0);

if (!$id || !in_array($type, ['commande', 'facture'], true)) {
    http_response_code(400);
    die('Requête invalide.');
}

if ($type === 'commande') {
    $stmt = $pdo->prepare('
        SELECT c.numero, c.heure_creation, tr.nom AS table_name, u.nom AS waiter_name
        FROM commande c
        JOIN table_restaurant tr ON tr.id_table = c.id_table
        JOIN utilisateur u ON u.id_utilisateur = c.id_utilisateur
        WHERE c.id_commande = :id
        LIMIT 1
    ');
    $stmt->execute(['id' => $id]);
    $commande = $stmt->fetch();

    if (!$commande) {
        http_response_code(404);
        die('Commande introuvable.');
    }

    $itemsStmt = $pdo->prepare('
        SELECT p.nom, lc.quantite
        FROM ligne_commande lc
        JOIN produit p ON p.id_produit = lc.id_produit
        WHERE lc.id_commande = :id
    ');
    $itemsStmt->execute(['id' => $id]);
    $lignes = $itemsStmt->fetchAll();

    $title = 'Bon de commande ' . $commande['numero'];
} else {
    $stmt = $pdo->prepare('
        SELECT tp.id_transaction, tp.montant, tp.date_heure, mp.libelle AS mode,
               c.id_commande, c.numero, tr.nom AS table_name, u.nom AS waiter_name
        FROM transaction_paiement tp
        JOIN commande c ON c.id_commande = tp.id_commande
        JOIN table_restaurant tr ON tr.id_table = c.id_table
        JOIN utilisateur u ON u.id_utilisateur = c.id_utilisateur
        JOIN mode_paiement mp ON mp.id_mode_paiement = tp.id_mode_paiement
        WHERE tp.id_transaction = :id
        LIMIT 1
    ');
    $stmt->execute(['id' => $id]);
    $facture = $stmt->fetch();

    if (!$facture) {
        http_response_code(404);
        die('Facture introuvable.');
    }

    $itemsStmt = $pdo->prepare('
        SELECT p.nom, lc.quantite, lc.prix_unitaire
        FROM ligne_commande lc
        JOIN produit p ON p.id_produit = lc.id_produit
        WHERE lc.id_commande = :id
    ');
    $itemsStmt->execute(['id' => $facture['id_commande']]);
    $lignes = $itemsStmt->fetchAll();

    $title = 'Facture ' . $facture['numero'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($title) ?></title>
<style>
  @page { size: 80mm auto; margin: 4mm; }
  * { box-sizing: border-box; }
  body {
    font-family: 'DM Mono', 'Courier New', monospace;
    width: 80mm;
    margin: 0 auto;
    padding: 8px;
    color: #111;
    font-size: 12px;
  }
  h1 { font-size: 15px; text-align: center; margin: 0 0 2px; }
  .sub { text-align: center; font-size: 10px; color: #555; margin-bottom: 10px; }
  .line { border-top: 1px dashed #999; margin: 8px 0; }
  .row { display: flex; justify-content: space-between; margin: 2px 0; }
  table { width: 100%; border-collapse: collapse; margin: 6px 0; }
  th, td { text-align: left; padding: 2px 0; font-size: 11px; }
  th:last-child, td:last-child { text-align: right; }
  .total-row { font-weight: bold; font-size: 13px; }
  .footer { text-align: center; font-size: 10px; color: #555; margin-top: 10px; }
  .no-print { text-align: center; margin-top: 16px; }
  .no-print button {
    padding: 8px 16px; font-size: 13px; border: none; border-radius: 6px;
    background: #f97316; color: #fff; cursor: pointer;
  }
  @media print {
    .no-print { display: none; }
    body { width: auto; }
  }
</style>
</head>
<body>
  <h1>RestoPOS</h1>
  <p class="sub">Le Wouri Palace — Abidjan</p>
  <div class="line"></div>

  <?php if ($type === 'commande'): ?>
    <div class="row"><span>N° commande</span><span><?= htmlspecialchars($commande['numero']) ?></span></div>
    <div class="row"><span>Table</span><span><?= htmlspecialchars($commande['table_name']) ?></span></div>
    <div class="row"><span>Serveur</span><span><?= htmlspecialchars($commande['waiter_name']) ?></span></div>
    <div class="row"><span>Heure</span><span><?= date('d/m/Y H:i', strtotime($commande['heure_creation'])) ?></span></div>
    <div class="line"></div>
    <table>
      <thead><tr><th>Article</th><th>Qté</th></tr></thead>
      <tbody>
        <?php foreach ($lignes as $l): ?>
        <tr><td><?= htmlspecialchars($l['nom']) ?></td><td><?= (int)$l['quantite'] ?></td></tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="line"></div>
    <p class="footer">Bon à transmettre en cuisine</p>

  <?php else: ?>
    <div class="row"><span>N° facture</span><span><?= htmlspecialchars($facture['numero']) ?></span></div>
    <div class="row"><span>Table</span><span><?= htmlspecialchars($facture['table_name']) ?></span></div>
    <div class="row"><span>Serveur</span><span><?= htmlspecialchars($facture['waiter_name']) ?></span></div>
    <div class="row"><span>Date</span><span><?= date('d/m/Y H:i', strtotime($facture['date_heure'])) ?></span></div>
    <div class="line"></div>
    <table>
      <thead><tr><th>Article</th><th>Qté</th><th>Prix</th></tr></thead>
      <tbody>
        <?php foreach ($lignes as $l): ?>
        <tr>
          <td><?= htmlspecialchars($l['nom']) ?></td>
          <td><?= (int)$l['quantite'] ?></td>
          <td><?= fmtP($l['prix_unitaire'] * $l['quantite']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="line"></div>
    <div class="row total-row"><span>Total</span><span><?= fmtP($facture['montant']) ?></span></div>
    <div class="row"><span>Mode de paiement</span><span><?= htmlspecialchars($facture['mode']) ?></span></div>
    <div class="line"></div>
    <p class="footer">Merci de votre visite !</p>
  <?php endif; ?>

  <div class="no-print">
    <button onclick="window.print()">Imprimer</button>
  </div>

  <?php $auto = isset($_GET['auto']) ? (int)$_GET['auto'] : 1; ?>
  <?php if ($auto === 1): ?>
  <script>
    window.onload = function () {
      window.print();
    };
  </script>
  <?php endif; ?>
</body>
</html>