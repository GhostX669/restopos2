  <?php require __DIR__ . '/includes/data.php';

  $error = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $found = null;

    if ($pdo instanceof PDO) {
      $stmt = $pdo->prepare('
        SELECT u.id_utilisateur, u.nom, u.mot_passe, u.initiales, u.actif, r.libelle AS role
        FROM utilisateur u
        JOIN role r ON r.id_role = u.id_role
        WHERE u.login = :login
        LIMIT 1
      ');
      $stmt->execute(['login' => $login]);
      $user = $stmt->fetch();

      if ($user && $user['actif'] && hash_equals($user['mot_passe'], hash('sha256', $password))) {
        $found = [
          'id' => (int)$user['id_utilisateur'],
          'role' => $user['role'],
          'name' => $user['nom'],
          'initials' => $user['initiales'],
        ];
      } elseif ($user && !$user['actif']) {
        $error = 'Ce compte est désactivé.';
      }
    }

    if (!$found) {
      foreach ($demoAccounts as $acc) {
        if ($acc['login'] === $login && $acc['password'] === $password) {
          $found = $acc;
          break;
        }
      }
    }

    if ($found) {
      $_SESSION['id_utilisateur'] = $found['id'] ?? null;
      $_SESSION['role'] = $found['role'];
      $_SESSION['name'] = $found['name'];
      $_SESSION['initials'] = $found['initials'];
      header('Location: index.php');
      exit;
    } elseif ($error === '') {
      $error = 'Identifiant ou mot de passe incorrect.';
    }
  }

  if (!empty($_SESSION['role'])) {
    header('Location: index.php');
    exit;
  }
  ?>
  <!DOCTYPE html>
  <html lang="fr">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — RestoPOS</title>
    <link rel="stylesheet" href="assets/css/01-variables.css">
    <link rel="stylesheet" href="assets/css/02-base.css">
    <link rel="stylesheet" href="assets/css/04-components.css">
    <link rel="stylesheet" href="assets/css/06-login.css">
    <link rel="stylesheet" href="assets/css/07-utilities.css">
  </head>

  <body>
    <div class="login-shell">
      <div class="login-visual" style="background-image:url('assets/images/restaurant-bg.avif');">
        <div class="flex gap-3" style="align-items:center;">
          <div class="logo-badge" style="width:40px;height:40px;border-radius:12px;background:var(--accent);display:flex;align-items:center;justify-content:center;">
            <?= icon('utensils-crossed', 20, 'color:#fff;') ?>
          </div>
          <span style="font-size:20px;font-weight:700;">RestoPOS</span>
        </div>
        <div>
          <h2 style="font-size:28px;font-weight:700;line-height:1.4;">Gérez votre restaurant<br>avec efficacité</h2>
          <p style="color:#9ca3af;max-width:380px;font-size:14px;line-height:1.6;">Commandes, tables, paiements et rapports — tout en un seul endroit, accessible à chaque membre de votre équipe.</p>
          <div class="grid grid-2 mt-4" style="max-width:380px;">
            <?php
            $stats = [['label' => 'Tables gérées', 'value' => '15'], ['label' => 'Rôles disponibles', 'value' => '4'], ['label' => 'Modes de paiement', 'value' => '4'], ['label' => 'Rapports temps réel', 'value' => '∞']];
            foreach ($stats as $s): ?>
              <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:14px;">
                <p class="mono" style="font-size:22px;font-weight:700;margin:0;"><?= $s['value'] ?></p>
                <p style="font-size:11px;color:#9ca3af;margin:2px 0 0;"><?= $s['label'] ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div style="font-size:11px;color:#6b7280;">© 2025 RestoPOS — Projet académique de fin d'études</div>
      </div>

      <div class="login-form-side">
        <div class="login-box">
          <div class="mb-4">
            <h1 style="font-size:24px;font-weight:700;margin:0;">Connexion</h1>
            <p class="text-muted text-sm mt-2" style="margin:6px 0 0;">Entrez vos identifiants pour accéder au système</p>
          </div>

          <form method="post" action="login.php">
            <div class="mb-3">
              <label class="field-label">Identifiant</label>
              <div style="position:relative;">
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted-foreground);"><?= icon('user', 14) ?></span>
                <input type="text" name="login" placeholder="Votre identifiant" required class="field-input" style="padding-left:34px;" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
              </div>
            </div>
            <div class="mb-3">
              <label class="field-label">Mot de passe</label>
              <div style="position:relative;">
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted-foreground);"><?= icon('lock', 14) ?></span>
                <input type="password" name="password" placeholder="••••••••" required class="field-input" style="padding-left:34px;">
              </div>
            </div>

            <?php if ($error): ?>
              <div class="error-box"><?= icon('alert-circle', 14) ?><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <button type="submit" class="btn btn-accent" style="width:100%;justify-content:center;padding:12px;">
              Se connecter <?= icon('arrow-right', 14) ?>
            </button>
          </form>

          <div class="mt-4">
            <p class="text-xs text-muted" style="text-align:center;margin-bottom:10px;">— Comptes de démonstration —</p>
            <div class="demo-accounts">
              <?php foreach ($demoAccounts as $acc): $rc = $roleConfig[$acc['role']]; ?>
                <button type="button" class="demo-btn" onclick="document.querySelector('[name=login]').value='<?= $acc['login'] ?>'; document.querySelector('[name=password]').value='<?= $acc['password'] ?>';">
                  <span class="avatar" style="background:<?= $rc['color'] ?>;"><?= $acc['initials'] ?></span>
                  <span>
                    <p style="font-size:12px;font-weight:600;margin:0;"><?= explode(' ', $acc['name'])[0] ?></p>
                    <p style="font-size:11px;font-weight:600;margin:0;color:<?= $rc['color'] ?>;"><?= $acc['role'] ?></p>
                  </span>
                </button>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>  
    <script src="assets/js/lucide.min.js"></script>
    <script>
      lucide.createIcons();
    </script>
  </body>

  </html>