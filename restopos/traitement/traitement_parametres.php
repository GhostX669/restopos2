<?php
require __DIR__ . '/../includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_utilisateur']) || !in_array($_SESSION['role'], ['Administrateur', 'Gérant'], true)) {
    header('Location: ../login.php');
    exit;
}

$allowedKeys = [
    'restaurant_nom', 'restaurant_telephone', 'restaurant_email', 'restaurant_ville',
    'restaurant_adresse', 'restaurant_devise', 'fond_de_caisse',
    'notif_commande', 'notif_paiement', 'notif_stock',
    'imprimante_ip', 'imprimante_port', 'imprimante_largeur', 'imprimante_copies',
    'apparence_couleur', 'apparence_langue',
];
$notifKeys = ['notif_commande', 'notif_paiement', 'notif_stock'];

$stmt = $pdo->prepare('INSERT INTO parametre (cle, valeur) VALUES (:cle, :valeur) ON DUPLICATE KEY UPDATE valeur = :valeur2');

foreach ($allowedKeys as $key) {
    if (array_key_exists($key, $_POST)) {
        $value = trim($_POST[$key]);
        $stmt->execute(['cle' => $key, 'valeur' => $value, 'valeur2' => $value]);
    } elseif (in_array($key, $notifKeys, true) && isset($_POST['notif_section'])) {
        // Case à cocher non cochée = absente du POST = considérée à '0'
        $stmt->execute(['cle' => $key, 'valeur' => '0', 'valeur2' => '0']);
    }
}

header('Location: ../index.php?view=settings&succes=' . urlencode('Paramètres enregistrés.'));
exit;