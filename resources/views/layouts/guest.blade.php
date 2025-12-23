<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FU System')</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        body {
            min-height: 100vh;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
        }
        .sidebar a {
            color: #ccc;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
            color: white;
        }
        .logo {
            height: 40px;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('assets/logowithname.svg') }}" class="logo" alt="Logo">
        </a>
        <div class="ms-auto d-flex align-items-center">
            <div class="dropdown">
                <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-circle-user me-1"></i> Profile
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ url('profile') }}">My Profile</a></li>
                    <li><a class="dropdown-item" href="{{ url('UsersManagement') }}">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ url('logout') }}">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <nav class="py-4">
                    <a href="{{ url('/') }}"><i class="bi bi-house me-2"></i> Home</a>
                    <a href="{{ url('studentForm') }}"><i class="bi bi-person me-2"></i> Student Form</a>
                    <a href="{{ url('parentForm') }}"><i class="bi bi-people me-2"></i> Parent Form</a>
                    <a href="{{ url('staffForm') }}"><i class="bi bi-person-badge me-2"></i> Staff Form</a>
                    <a href="#"><i class="bi bi-bar-chart me-2"></i> Reports</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 py-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
