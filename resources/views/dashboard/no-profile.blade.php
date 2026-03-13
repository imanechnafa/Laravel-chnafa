<!DOCTYPE html>
<html>
<head>
    <title>Profil incomplet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-warning">
            <h4>Profil incomplet</h4>
            <p>Vous êtes connecté en tant que <strong>{{ $user->name }}</strong> mais vous n'avez pas de profil employé.</p>
            <p>Contactez l'administrateur pour configurer votre compte.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Se déconnecter</button>
            </form>
        </div>
    </div>
</body>
</html>