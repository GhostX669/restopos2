<?php
require_once __DIR__ . '/config.php';

function fmt($n) {
    return number_format($n, 0, ',', ' ') . ' FC';
}
function fmtShort($n) {
    return number_format($n, 0, ',', ' ');
}

$demoAccounts = [];
$roleConfig = [
    'Administrateur' => ['bg' => '#ede9fe', 'text' => '#7c3aed', 'color' => '#8b5cf6'],
    'Gérant'         => ['bg' => '#dbeafe', 'text' => '#1d4ed8', 'color' => '#3b82f6'],
    'Caissier'       => ['bg' => '#fef3c7', 'text' => '#b45309', 'color' => '#f59e0b'],
    'Serveur'        => ['bg' => '#d1fae5', 'text' => '#047857', 'color' => '#10b981'],
];

$navByRole = [
    'Administrateur' => [
        ['id' => 'dashboard', 'label' => 'Tableau de bord', 'icon' => 'layout-dashboard'],
        ['id' => 'tables', 'label' => 'Tables', 'icon' => 'utensils'],
        ['id' => 'orders', 'label' => 'Commandes', 'icon' => 'shopping-cart'],
        ['id' => 'menu', 'label' => 'Menu & Produits', 'icon' => 'coffee'],
        ['id' => 'payments', 'label' => 'Paiements', 'icon' => 'credit-card'],
        ['id' => 'reports', 'label' => 'Rapports', 'icon' => 'bar-chart-3'],
        ['id' => 'users', 'label' => 'Utilisateurs', 'icon' => 'users'],
        ['id' => 'settings', 'label' => 'Paramètres', 'icon' => 'settings'],
    ],
    'Gérant' => [
        ['id' => 'dashboard', 'label' => 'Tableau de bord', 'icon' => 'layout-dashboard'],
        ['id' => 'tables', 'label' => 'Tables', 'icon' => 'utensils'],
        ['id' => 'orders', 'label' => 'Commandes', 'icon' => 'shopping-cart'],
        ['id' => 'menu', 'label' => 'Menu & Produits', 'icon' => 'coffee'],
        ['id' => 'reports', 'label' => 'Rapports', 'icon' => 'bar-chart-3'],
        ['id' => 'settings', 'label' => 'Paramètres', 'icon' => 'settings'],
    ],
    'Caissier' => [
        ['id' => 'dashboard', 'label' => 'Caisse', 'icon' => 'wallet'],
        ['id' => 'encaissement', 'label' => 'Encaisser', 'icon' => 'credit-card'],
        ['id' => 'orders', 'label' => 'Commandes', 'icon' => 'shopping-cart'],
        ['id' => 'settings', 'label' => 'Paramètres', 'icon' => 'settings'],
    ],
    'Serveur' => [
        ['id' => 'dashboard', 'label' => 'Mon tableau de bord', 'icon' => 'layout-dashboard'],
        ['id' => 'prise-commande', 'label' => 'Prise de commande', 'icon' => 'shopping-cart'],
        ['id' => 'mes-tables', 'label' => 'Mes tables', 'icon' => 'table-2'],
        ['id' => 'settings', 'label' => 'Paramètres', 'icon' => 'settings'],
    ],
];

$viewLabels = [
    'dashboard' => 'Tableau de bord', 'tables' => 'Gestion des tables', 'orders' => 'Commandes',
    'menu' => 'Menu & Produits', 'payments' => 'Paiements & Caisse', 'reports' => 'Rapports statistiques',
    'users' => 'Utilisateurs', 'settings' => 'Paramètres', 'encaissement' => 'Encaisser une commande',
    'prise-commande' => 'Prise de commande', 'mes-tables' => 'Mes tables',
];

$tableStatusConfig = [
    'available' => ['label' => 'Libre', 'bg' => '#ecfdf5', 'dot' => '#10b981', 'text' => '#047857', 'border' => '#a7f3d0'],
    'occupied'  => ['label' => 'Occupée', 'bg' => '#fff7ed', 'dot' => '#f97316', 'text' => '#c2410c', 'border' => '#fed7aa'],
    'reserved'  => ['label' => 'Réservée', 'bg' => '#eff6ff', 'dot' => '#3b82f6', 'text' => '#1d4ed8', 'border' => '#bfdbfe'],
    'dirty'     => ['label' => 'À nettoyer', 'bg' => '#f9fafb', 'dot' => '#9ca3af', 'text' => '#6b7280', 'border' => '#e5e7eb'],
];

$orderStatusConfig = [
    'pending'   => ['label' => 'En attente', 'bg' => '#fef3c7', 'text' => '#b45309'],
    'preparing' => ['label' => 'En préparation', 'bg' => '#dbeafe', 'text' => '#1d4ed8'],
    'ready'     => ['label' => 'Prêt à servir', 'bg' => '#d1fae5', 'text' => '#047857'],
    'served'    => ['label' => 'Servi', 'bg' => '#f3f4f6', 'text' => '#6b7280'],
    'cancelled' => ['label' => 'Annulé', 'bg' => '#fee2e2', 'text' => '#dc2626'],
];

$hourlyRevenue = [];
$weeklyRevenue = [];
$paymentMethods = [];
$staffPerf = [];
$tables = [];
$orders = [];
$menuItems = [];
$recentTransactions = [];
$staffList = [];
$rolesList = [];
$categoriesList = [];
$checklistItems = [];
$notifications = [];

if (isset($pdo)) {
    try {
        $stmtRoles = $pdo->query('SELECT id_role, libelle, couleur_fond, couleur_texte, couleur FROM role ORDER BY id_role');
        foreach ($stmtRoles->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $roleConfig[$row['libelle']] = [
                'bg' => $row['couleur_fond'] ?: '#f3f4f6',
                'text' => $row['couleur_texte'] ?: '#111827',
                'color' => $row['couleur'] ?: '#111827',
            ];
        }
    } catch (PDOException $e) {
        // Fallback vers la configuration par défaut.
    }

    try {
        $stmtStatusTables = $pdo->query('SELECT code, libelle, couleur_fond, couleur_point, couleur_texte, couleur_bordure FROM statut_table');
        foreach ($stmtStatusTables->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $tableStatusConfig[$row['code']] = [
                'label' => $row['libelle'],
                'bg' => $row['couleur_fond'],
                'dot' => $row['couleur_point'],
                'text' => $row['couleur_texte'],
                'border' => $row['couleur_bordure'],
            ];
        }
    } catch (PDOException $e) {
        // Fallback vers la configuration par défaut.
    }

    try {
        $stmtStatusOrders = $pdo->query('SELECT code, libelle, couleur_fond, couleur_texte FROM statut_commande');
        foreach ($stmtStatusOrders->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $orderStatusConfig[$row['code']] = [
                'label' => $row['libelle'],
                'bg' => $row['couleur_fond'],
                'text' => $row['couleur_texte'],
            ];
        }
    } catch (PDOException $e) {
        // Fallback vers la configuration par défaut.
    }

    try {
        $stmtDemo = $pdo->query("SELECT u.id_utilisateur, u.nom, u.login, r.libelle AS role, u.initiales FROM utilisateur u JOIN role r ON r.id_role = u.id_role ORDER BY u.id_utilisateur LIMIT 4");
        $demoAccounts = [];
        $defaultPasswords = ['admin' => 'admin123', 'gerant' => 'gerant123', 'caissier' => 'caissier123', 'serveur' => 'serveur123'];
        foreach ($stmtDemo->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $demoAccounts[] = [
                'id' => (int)$row['id_utilisateur'],
                'role' => $row['role'],
                'login' => $row['login'],
                'password' => $defaultPasswords[$row['login']] ?? '123456',
                'name' => $row['nom'],
                'initials' => $row['initiales'],
            ];
        }
    } catch (PDOException $e) {
        $demoAccounts = [];
    }

    try {
        $stmt = $pdo->query("SELECT id_categorie, libelle FROM categorie ORDER BY id_categorie");
        while ($row = $stmt->fetch()) {
            $categoriesList[] = [
                'id_categorie' => (int)$row['id_categorie'],
                'libelle' => $row['libelle'],
            ];
        }
    } catch (PDOException $e) {
        $categoriesList = [];
    }

    try {
        $stmt = $pdo->query("SELECT id_role, libelle FROM role ORDER BY id_role");
        while ($row = $stmt->fetch()) {
            $rolesList[] = [
                'id_role' => (int)$row['id_role'],
                'libelle' => $row['libelle'],
            ];
        }
    } catch (PDOException $e) {
        $rolesList = [];
    }

    try {
        $stmt = $pdo->query("SELECT p.id_produit AS id, p.nom AS name, p.prix AS price, p.disponible AS available, p.image_url AS image, p.id_categorie AS category_id, c.libelle AS category FROM produit p JOIN categorie c ON c.id_categorie = p.id_categorie ORDER BY p.id_produit DESC");
        while ($row = $stmt->fetch()) {
            $menuItems[] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'category' => $row['category'],
                'category_id' => (int)$row['category_id'],
                'price' => (float)$row['price'],
                'available' => (bool)$row['available'],
                'image' => $row['image'] ?: 'assets/images/default-plat.jpg',
            ];
        }
    } catch (PDOException $e) {
        $menuItems = [];
    }

    try {
        $stmt = $pdo->query("SELECT tr.id_table AS id, tr.nom AS name, tr.capacite AS capacity, st.code AS status, u.nom AS waiter, tr.note, tr.depuis AS since FROM table_restaurant tr LEFT JOIN utilisateur u ON u.id_utilisateur = tr.id_utilisateur JOIN statut_table st ON st.id_statut_table = tr.id_statut_table ORDER BY tr.id_table");
        while ($row = $stmt->fetch()) {
            $tables[] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'capacity' => (int)$row['capacity'],
                'status' => $row['status'],
                'waiter' => $row['waiter'],
                'order' => null,
                'since' => $row['since'],
                'total' => null,
                'note' => $row['note'],
            ];
        }
    } catch (PDOException $e) {
        $tables = [];
    }

    try {
        $stmt = $pdo->query("SELECT c.id_commande AS id, c.numero AS numero, tr.nom AS table_name, u.nom AS waiter_name, sc.code AS status, c.montant_total AS total, DATE_FORMAT(c.heure_creation, '%H:%i') AS time FROM commande c JOIN table_restaurant tr ON tr.id_table = c.id_table JOIN utilisateur u ON u.id_utilisateur = c.id_utilisateur JOIN statut_commande sc ON sc.id_statut_commande = c.id_statut_commande ORDER BY c.id_commande DESC");
        while ($row = $stmt->fetch()) {
            $itemsStmt = $pdo->prepare("SELECT p.nom, lc.quantite FROM ligne_commande lc JOIN produit p ON p.id_produit = lc.id_produit WHERE lc.id_commande = :id");
            $itemsStmt->execute(['id' => $row['id']]);
            $itemsList = [];
            while ($item = $itemsStmt->fetch()) {
                $itemsList[] = $item['nom'] . ' x' . $item['quantite'];
            }
            $orders[] = [
                'id' => $row['numero'],
                'id_commande' => (int)$row['id'],
                'table' => $row['table_name'],
                'waiter' => $row['waiter_name'],
                'status' => $row['status'],
                'items' => count($itemsList),
                'total' => (float)$row['total'],
                'time' => $row['time'],
                'items_list' => $itemsList,
            ];
        }
    } catch (PDOException $e) {
        $orders = [];
    }

    try {
        $stmt = $pdo->query("SELECT u.id_utilisateur, u.nom AS name, r.libelle AS role, u.email, u.actif AS active, u.derniere_connexion AS last_login FROM utilisateur u JOIN role r ON r.id_role = u.id_role ORDER BY u.id_utilisateur");
        while ($row = $stmt->fetch()) {
            $staffList[] = [
                'id' => (int)$row['id_utilisateur'],
                'name' => $row['name'],
                'role' => $row['role'],
                'email' => $row['email'],
                'active' => (bool)$row['active'],
                'lastLogin' => $row['last_login'] ? date('d/m/Y H:i', strtotime($row['last_login'])) : 'Jamais',
            ];
        }
    } catch (PDOException $e) {
        $staffList = [];
    }

    try {
        $stmt = $pdo->query("SELECT tp.id_transaction, c.numero AS id, tr.nom AS table_name, tp.montant AS amount, mp.libelle AS method, DATE_FORMAT(tp.date_heure, '%H:%i') AS time FROM transaction_paiement tp JOIN commande c ON c.id_commande = tp.id_commande JOIN table_restaurant tr ON tr.id_table = c.id_table JOIN mode_paiement mp ON mp.id_mode_paiement = tp.id_mode_paiement ORDER BY tp.id_transaction DESC LIMIT 10");
        while ($row = $stmt->fetch()) {
            $recentTransactions[] = [
                'id' => $row['id'],
                'id_transaction' => (int)$row['id_transaction'],
                'table' => $row['table_name'],
                'amount' => (float)$row['amount'],
                'method' => $row['method'],
                'time' => $row['time'],
            ];
        }
    } catch (PDOException $e) {
        $recentTransactions = [];
    }

    try {
        $stmt = $pdo->query("SELECT DATE_FORMAT(heure_creation, '%Hh') AS hour, SUM(montant_total) AS revenue, COUNT(*) AS orders FROM commande WHERE heure_creation >= DATE_SUB(NOW(), INTERVAL 12 HOUR) GROUP BY DATE_FORMAT(heure_creation, '%H') ORDER BY MIN(heure_creation)");
        while ($row = $stmt->fetch()) {
            $hourlyRevenue[] = ['hour' => $row['hour'], 'revenue' => (float)$row['revenue'], 'orders' => (int)$row['orders']];
        }
    } catch (PDOException $e) {
        $hourlyRevenue = [];
    }

    try {
        $stmt = $pdo->query("SELECT DATE_FORMAT(heure_creation, '%Y-%m-%d') AS day, SUM(montant_total) AS revenue FROM commande WHERE heure_creation >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY DATE_FORMAT(heure_creation, '%Y-%m-%d') ORDER BY day");
        while ($row = $stmt->fetch()) {
            $weeklyRevenue[] = ['day' => date('D', strtotime($row['day'])), 'revenue' => (float)$row['revenue'], 'target' => (float)$row['revenue'] + 1000];
        }
    } catch (PDOException $e) {
        $weeklyRevenue = [];
    }

    try {
        $stmt = $pdo->query("SELECT mp.libelle AS name, COUNT(tp.id_transaction) AS value, mp.couleur AS color FROM transaction_paiement tp JOIN mode_paiement mp ON mp.id_mode_paiement = tp.id_mode_paiement GROUP BY mp.id_mode_paiement, mp.libelle, mp.couleur ORDER BY value DESC");
        while ($row = $stmt->fetch()) {
            $paymentMethods[] = ['name' => $row['name'], 'value' => (int)$row['value'], 'color' => $row['color']];
        }
    } catch (PDOException $e) {
        $paymentMethods = [];
    }

    try {
        $stmt = $pdo->query("SELECT u.nom AS name, COUNT(c.id_commande) AS orders, COALESCE(SUM(c.montant_total), 0) AS revenue FROM utilisateur u LEFT JOIN commande c ON c.id_utilisateur = u.id_utilisateur JOIN role r ON r.id_role = u.id_role AND r.libelle = 'Serveur' GROUP BY u.id_utilisateur, u.nom ORDER BY orders DESC, revenue DESC LIMIT 5");
        while ($row = $stmt->fetch()) {
            $staffPerf[] = ['name' => $row['name'], 'orders' => (int)$row['orders'], 'revenue' => (float)$row['revenue'], 'rating' => 4.5];
        }
    } catch (PDOException $e) {
        $staffPerf = [];
    }
}

if (empty($checklistItems)) {
    $pendingOrdersCount = 0;
    $readyOrdersCount = 0;
    $dirtyTablesCount = 0;
    foreach ($orders as $order) {
        if (($order['status'] ?? '') === 'pending') {
            $pendingOrdersCount++;
        }
        if (($order['status'] ?? '') === 'ready') {
            $readyOrdersCount++;
        }
    }
    foreach ($tables as $table) {
        if (($table['status'] ?? '') === 'dirty') {
            $dirtyTablesCount++;
        }
    }

    $checklistItems = [
        ['id' => 'tables', 'label' => 'Vérifier les tables libres', 'checked' => $dirtyTablesCount === 0],
        ['id' => 'payments', 'label' => 'Valider les paiements prêts', 'checked' => $readyOrdersCount === 0],
        ['id' => 'orders', 'label' => 'Envoyer les commandes en cuisine', 'checked' => $pendingOrdersCount === 0],
    ];
}

if (empty($notifications)) {
    $notifications = [];
    if (!empty($orders)) {
        $pendingOrdersCount = 0;
        $readyOrdersCount = 0;
        foreach ($orders as $order) {
            if (($order['status'] ?? '') === 'pending') {
                $pendingOrdersCount++;
            }
            if (($order['status'] ?? '') === 'ready') {
                $readyOrdersCount++;
            }
        }
        if ($pendingOrdersCount > 0) {
            $notifications[] = ['title' => 'Commande(s) en attente', 'desc' => $pendingOrdersCount . ' commande(s) à confirmer.'];
        }
        if ($readyOrdersCount > 0) {
            $notifications[] = ['title' => 'Commande(s) prêtes', 'desc' => $readyOrdersCount . ' commande(s) prête(s) à servir.'];
        }
    }
    $dirtyTablesCount = 0;
    foreach ($tables as $table) {
        if (($table['status'] ?? '') === 'dirty') {
            $dirtyTablesCount++;
        }
    }
    if ($dirtyTablesCount > 0) {
        $notifications[] = ['title' => 'Table(s) à nettoyer', 'desc' => $dirtyTablesCount . ' table(s) nécessite(nt) un nettoyage rapide.'];
    }
    if (empty($notifications)) {
        $notifications[] = ['title' => 'Service fluide', 'desc' => 'Aucune action urgente pour le moment.'];
    }
}

function require_login() {
    if (empty($_SESSION['role'])) {
        header('Location: login.php');
        exit;
    }
}

function initials_from_name($name) {
    $parts = explode(' ', $name);
    $ini = '';
    foreach (array_slice($parts, 0, 2) as $p) { $ini .= mb_substr($p, 0, 1); }
    return mb_strtoupper($ini);
}

function icon($name, $size = 14, $extra = '') {
    return '<i data-lucide="' . htmlspecialchars($name) . '" style="width:' . $size . 'px;height:' . $size . 'px;' . $extra . '"></i>';
}