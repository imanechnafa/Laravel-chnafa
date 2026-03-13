<!DOCTYPE html>
<html>
<head>
    <title>Tableau de bord - Employé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #danger-zone, .danger-zone { display: none !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">RH Congés</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h1>Tableau de bord Employé</h1>
        
        <div class="alert alert-info">
            <h4>Bienvenue, {{ Auth::user()->name }}!</h4>
            <p>Email: {{ Auth::user()->email }}</p>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mon profil</h5>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Voir mon profil</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mes congés</h5>
                        <a href="{{ route('conges.index') }}" class="btn btn-success">Gérer mes congés</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
