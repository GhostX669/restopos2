<?php
require_login();

$role = $_SESSION['role'];
$name = $_SESSION['name'];
$initials = $_SESSION['initials'];
$rc = $roleConfig[$role];
$nav = $navByRole[$role];
$view = $_GET['view'] ?? 'dashboard';
$title = $viewLabels[$view] ?? $view;
$checklistItems = $checklistItems ?? [];
$notifications = $notifications ?? [];
$checkedChecklistCount = 0;
foreach ($checklistItems as $item) {
  if (!empty($item['checked'])) {
    $checkedChecklistCount++;
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title) ?> — RestoPOS</title>
<link rel="stylesheet" href="assets/css/01-variables.css">
<link rel="stylesheet" href="assets/css/02-base.css">
<link rel="stylesheet" href="assets/css/03-layout.css">
<link rel="stylesheet" href="assets/css/04-components.css">
<link rel="stylesheet" href="assets/css/05-tables.css">
<link rel="stylesheet" href="assets/css/07-utilities.css">
</head>
<body>
<div id="sidebar-overlay" class="sidebar-overlay" onclick="closeSidebar()"></div>
<div class="app-shell">
  <aside class="sidebar" id="sidebar" style="--accent-role: <?= $rc['color'] ?>;">
    <div class="sidebar-logo">
      <div class="logo-badge" style="background:<?= $rc['color'] ?>;"><?= icon('utensils-crossed', 16, 'color:#fff;') ?></div>
      <div class="label-text">
        <p class="brand-name">RestoPOS</p>
        <p class="brand-version">v2.1.0</p>
      </div>
    </div>
    <nav class="sidebar-nav">
      <?php foreach ($nav as $item): $active = $view === $item['id']; ?>
      <a href="index.php?view=<?= $item['id'] ?>" class="<?= $active ? 'active' : '' ?>" style="<?= $active ? 'background:'.$rc['color'].';' : '' ?>">
        <?= icon($item['icon'], 16) ?>
        <span class="label-text"><?= htmlspecialchars($item['label']) ?></span>
      </a>
      <?php endforeach; ?>
    </nav>
    <div class="sidebar-user">
      <div class="avatar" style="background:<?= $rc['color'] ?>;"><?= htmlspecialchars($initials) ?></div>
      <div class="user-meta">
        <p class="user-name"><?= htmlspecialchars($name) ?></p>
        <p class="user-role"><?= htmlspecialchars($role) ?></p>
      </div>
      <a href="logout.php" class="logout-btn" title="Déconnexion"><?= icon('log-out', 14) ?></a>
    </div>
  </aside>

  <div class="main-col">
    <header class="topbar">
      <div class="topbar-left">
        <button class="icon-btn" onclick="toggleSidebar()"><?= icon('menu', 18) ?></button>
        <h1><?= htmlspecialchars($title) ?></h1>
      </div>
      <div class="topbar-right">
        <span class="role-pill hidden-mobile" style="background:<?= $rc['bg'] ?>;color:<?= $rc['text'] ?>;"><?= htmlspecialchars($role) ?></span>
        <span class="mono font-bold hidden-mobile"><?= date('H:i') ?></span>
        <div style="position:relative;">
          <button class="icon-btn" onclick="toggleChecklist()" aria-label="Checklist"><?= icon('list-checks', 16) ?></button>
          <div id="checklist-panel" class="floating-panel" style="display:none;">
            <div class="panel-header">
              <span class="font-semibold">Checklist du service</span>
              <span id="checklist-count" class="panel-badge"><?= $checkedChecklistCount ?>/<?= count($checklistItems) ?></span>
            </div>
            <div class="panel-body">
              <?php foreach ($checklistItems as $item): ?>
              <label class="check-item"><span><?= htmlspecialchars($item['label']) ?></span><input type="checkbox" data-task="<?= htmlspecialchars($item['id']) ?>" <?= !empty($item['checked']) ? 'checked' : '' ?>></label>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div style="position:relative;">
          <button class="icon-btn" onclick="toggleNotifications()" aria-label="Notifications"><?= icon('bell', 16) ?></button>
          <div id="notifications-panel" class="floating-panel" style="display:none;">
            <div class="panel-header">
              <span class="font-semibold">Notifications</span>
              <span class="panel-badge"><?= count($notifications) ?></span>
            </div>
            <div class="panel-body" id="notifications-list" data-items='<?= htmlspecialchars(json_encode($notifications), ENT_QUOTES, 'UTF-8') ?>'></div>
          </div>
        </div>
      </div>
    </header>
    <main class="content">
      <?php $flashError = $_GET['erreur'] ?? ''; $flashSuccess = $_GET['succes'] ?? ''; ?>
      <?php if ($flashError !== ''): ?>
      <div class="error-box" style="margin-bottom:16px;"><?= htmlspecialchars($flashError) ?></div>
      <?php endif; ?>
      <?php if ($flashSuccess !== ''): ?>
      <div class="card" style="margin-bottom:16px;padding:12px 14px;background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;"><?= htmlspecialchars($flashSuccess) ?></div>
      <?php endif; ?>