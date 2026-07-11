<?php
require __DIR__ . '/includes/data.php';
require_login();

$role = $_SESSION['role'];
$view = $_GET['view'] ?? 'dashboard';

$allowed = array_column($navByRole[$role], 'id');
if (!in_array($view, $allowed, true)) {
    $view = 'dashboard';
}

require __DIR__ . '/includes/layout_top.php';

if ($view === 'dashboard') {
    switch ($role) {
        case 'Administrateur':
            require __DIR__ . '/views/admin_dashboard.php';
            break;
        case 'Gérant':
            require __DIR__ . '/views/gerant_dashboard.php';
            break;
        case 'Caissier':
            require __DIR__ . '/views/caissier_dashboard.php';
            break;
        case 'Serveur':
            require __DIR__ . '/views/serveur_dashboard.php';
            break;
    }
} else {
    $map = [
        'tables'          => 'tables.php',
        'mes-tables'      => 'tables.php',
        'orders'          => 'orders.php',
        'menu'            => 'menu.php',
        'payments'        => 'payments.php',
        'reports'         => 'reports.php',
        'users'           => 'users.php',
        'settings'        => 'settings.php',
        'encaissement'    => 'encaissement.php',
        'prise-commande'  => 'prise_commande.php',
    ];
    if (isset($map[$view])) {
        require __DIR__ . '/views/' . $map[$view];
    }
}

require __DIR__ . '/includes/layout_bottom.php';
