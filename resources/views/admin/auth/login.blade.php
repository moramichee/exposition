<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
    <main class="section">
        <div class="container admin-login-wrap">
            <article class="card admin-card">
                <h1 class="page-title">Connexion administrateur</h1>
                <p class="muted">Connectez-vous pour gerer les elements dynamiques de la page d'accueil.</p>

                <form method="POST" action="{{ route('admin.login.submit') }}" class="admin-form">
                    @csrf

                    <label for="email">
                        Email
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    </label>
                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror

                    <label for="password">
                        Mot de passe
                        <input id="password" type="password" name="password" required>
                    </label>
                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror

                    <label class="admin-check" for="remember">
                        <input id="remember" type="checkbox" name="remember" value="1" @checked(old('remember'))>
                        Garder la session active
                    </label>

                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>
            </article>
        </div>
    </main>
</body>
</html>
