<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion des Congés</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --success: #4cc9f0;
            --danger: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
        }

        body {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .login-header p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .login-logo {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .login-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
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

        .form-check {
            margin-bottom: 20px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            accent-color: var(--primary);
        }

        .form-check-label {
            font-size: 14px;
            color: #666;
        }

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(67, 97, 238, 0.3);
            color: white;
        }

        .login-footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #666;
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
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
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h1>Connexion</h1>
                <p>Gestion des Congés</p>
            </div>

            <div class="login-body">
                @if ($errors->any())
                    <div class="alert-error">
                        <strong>Erreur !</strong> Identifiant ou mot de passe incorrect.
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert-error" style="background: #d4edda; border-color: #c3e6cb; color: #155724;">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required autofocus autocomplete="username">
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               required autocomplete="current-password">
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
                        <label for="remember_me" class="form-check-label">Se souvenir de moi</label>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </button>
                </form>

                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" style="color: var(--primary); font-size: 14px; text-decoration: none;">
                            Mot de passe oublié ?
                        </a>
                    </div>
                @endif
            </div>

            <div class="login-footer">
                Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
