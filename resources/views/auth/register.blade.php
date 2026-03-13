<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Gestion des Congés</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --danger: #f72585;
            --light: #f8f9fa;
        }

        body {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            padding: 20px;
        }

        .register-container {
            width: 100%;
            max-width: 450px;
        }

        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .register-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .register-header p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .register-logo {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .register-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            font-size: 14px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .form-error {
            color: var(--danger);
            font-size: 13px;
            margin-top: 5px;
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(67, 97, 238, 0.3);
            color: white;
        }

        .register-footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #666;
        }

        .register-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }

        .alert-error {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="register-logo">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1>Inscription</h1>
                <p>Créez un nouveau compte</p>
            </div>

            <div class="register-body">
                @if ($errors->any())
                    <div class="alert-error">
                        <strong>Erreur !</strong> Veuillez corriger les champs ci-dessous.
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Nom Complet *</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required autofocus autocomplete="name">
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Adresse Email *</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required autocomplete="username">
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe *</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               required autocomplete="new-password">
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" 
                               required autocomplete="new-password">
                        @error('password_confirmation')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-plus"></i> S'inscrire
                    </button>
                </form>
            </div>

            <div class="register-footer">
                Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
