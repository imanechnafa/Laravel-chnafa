<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RH Congés - @yield('title', 'Gestion des Congés')</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fb;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            color: white;
            width: 250px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 25px;
            text-align: center;
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: none;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 10px 25px;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }
        
        .page-title {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        
        .table thead {
            background-color: #f8f9fa;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .badge-pending { background-color: #fff3cd; color: #856404; }
        .badge-approved { background-color: #d4edda; color: #155724; }
        .badge-rejected { background-color: #f8d7da; color: #721c24; }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            .sidebar .nav-text {
                display: none;
            }
            .sidebar .nav-link {
                text-align: center;
                padding: 15px 5px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-none d-md-block">
            <div class="p-4">
                <h4 class="text-center mb-4">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <span class="nav-text">RH Congés</span>
                </h4>
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="nav-text ms-2">Dashboard</span>
                        </a>
                    </li>
                    
                    @if(auth()->user()->employe && auth()->user()->employe->role === 'employe')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('conges.*') ? 'active' : '' }}" href="{{ route('conges.index') }}">
                            <i class="fas fa-umbrella-beach"></i>
                            <span class="nav-text ms-2">Mes Congés</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('conges.create') }}">
                            <i class="fas fa-plus-circle"></i>
                            <span class="nav-text ms-2">Nouvelle Demande</span>
                        </a>
                    </li>
                    @endif
                    
                    @if(auth()->user()->employe && auth()->user()->employe->role === 'manager')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('conges.validation.index') }}">
                            <i class="fas fa-check-circle"></i>
                            <span class="nav-text ms-2">Validations</span>
                            <span class="badge bg-warning float-end">
                                {{ auth()->user()->employe->congesEnAttente()->count() }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('employes.mes-employes') }}">
                            <i class="fas fa-users"></i>
                            <span class="nav-text ms-2">Mon Équipe</span>
                        </a>
                    </li>
                    @endif
                    
                    @if(auth()->user()->employe && auth()->user()->employe->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('departements.*') ? 'active' : '' }}" href="{{ route('departements.index') }}">
                            <i class="fas fa-building"></i>
                            <span class="nav-text ms-2">Départements</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('employes.*') ? 'active' : '' }}" href="{{ route('employes.index') }}">
                            <i class="fas fa-user-tie"></i>
                            <span class="nav-text ms-2">Employés</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.statistiques') }}">
                            <i class="fas fa-chart-bar"></i>
                            <span class="nav-text ms-2">Statistiques</span>
                        </a>
                    </li>
                    @endif
                    
                    <hr class="bg-light my-3">
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('notifications.index') }}">
                            <i class="fas fa-bell"></i>
                            <span class="nav-text ms-2">Notifications</span>
                            @php
                                $unreadCount = Auth::user()->notifications()->where('read', false)->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-circle"></i>
                            <span class="nav-text ms-2">Mon Profil</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-start w-100" style="color: inherit;">
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="nav-text ms-2">Déconnexion</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Navbar Mobile -->
            <nav class="navbar navbar-expand-lg d-md-none">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavbar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a class="navbar-brand ms-2" href="{{ route('dashboard') }}">RH Congés</a>
                    
                    <div class="collapse navbar-collapse" id="mobileNavbar">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <!-- Mêmes liens que sidebar mais adaptés -->
                        </ul>
                    </div>
                </div>
            </nav>
            
            <!-- Top Bar -->
            <div class="bg-white shadow-sm py-3 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">@yield('page-title', 'Tableau de bord')</h5>
                    <small class="text-muted">@yield('page-subtitle', 'Gestion des congés')</small>
                </div>
                
                <div class="d-flex align-items-center">
                    <div class="me-3 text-end">
                        <div class="fw-bold">{{ auth()->user()->name }}</div>
                        <small class="text-muted">
                            @if(auth()->user()->employe)
                                {{ auth()->user()->employe->role }}
                                @if(auth()->user()->employe->departement)
                                    - {{ auth()->user()->employe->departement->nom }}
                                @endif
                            @endif
                        </small>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>Mon Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <main class="main-content">
                <!-- Notifications -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Scripts communs
        $(document).ready(function() {
            // Auto-dismiss alerts après 5 secondes
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
            
            // Confirmation pour les actions critiques
            $('.btn-delete').click(function(e) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                    e.preventDefault();
                }
            });
            
            // Tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
    
    @stack('scripts')
</body>
</html>