<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin.php';

if (admin_is_authenticated()) {
    redirect_to('index.php');
}

$error = flash_pull('admin_error');
$email = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (admin_attempt_login($email, $password)) {
        redirect_to('index.php');
    }

    $error = 'Identifiants invalides.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/site.css">
</head>
<body>
    <main class="section">
        <div class="container admin-login-wrap">
            <article class="card admin-card">
                <h1 class="page-title">Connexion administrateur</h1>
                <p class="muted">Connectez-vous pour gerer les elements dynamiques du site natif.</p>

                <?php if ($error !== null): ?>
                    <p class="error"><?= h($error) ?></p>
                <?php endif; ?>

                <form method="post" action="login.php" class="admin-form">
                    <label for="email">
                        Email
                        <input id="email" type="email" name="email" value="<?= h($email) ?>" required autofocus>
                    </label>

                    <label for="password">
                        Mot de passe
                        <input id="password" type="password" name="password" required>
                    </label>

                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>

                <p class="muted" style="margin-top:1rem;">Compte par defaut: admin@communiquer.local / admin12345</p>
            </article>
        </div>
    </main>
</body>
</html>
