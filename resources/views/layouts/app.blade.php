<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KALYAN MAHADEV</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #ffc827;
            --white: #ffffff;
            --green: #33c454;
            --orange: #f05209;
            --red: #db0101;
            --blue: #0b7abe;
            --darkblue: #0a1b77;
            --primary_hover: #0f7a14;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #350b2d;
            color: var(--white);
            line-height: 1.6;
        }

        .navbar {
            background-color: var(--primary) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--white) !important;
            font-size: 1.8rem;
            letter-spacing: 1px;
        }

        .nav-link {
            color: var(--white) !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--darkblue) !important;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            background: var(--white);
        }

        .card-header {
            background-color: var(--primary);
            border-bottom: 1px solid #e5e7eb;
            padding: 1.25rem;
            font-weight: 600;
            color: var(--white);
            font-size: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
            background: var(--white);
            color: #666666;
        }

        .payment-option {
            background-color: var(--white);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .payment-option h5 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 1.25rem;
            font-size: 1.1rem;
        }

        .form-control {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 200, 39, 0.1);
        }

        .btn-primary {
            background-color: var(--primary);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary_hover);
            transform: translateY(-1px);
        }

        .alert {
            border-radius: 8px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border: none;
        }

        .alert-success {
            background-color: var(--green);
            color: var(--white);
        }

        .alert-danger {
            background-color: var(--red);
            color: var(--white);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }

        .invalid-feedback {
            color: var(--red);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        hr {
            margin: 2rem 0;
            border-color: var(--primary);
            opacity: 0.1;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background: var(--white);
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: var(--primary);
        }

        .dropdown-item:hover {
            background-color: var(--primary);
            color: var(--white);
        }

        .input-group-text {
            background-color: var(--primary);
            color: var(--white);
            border: none;
        }

        @media (max-width: 768px) {
            .card {
                margin: 1rem;
            }

            .payment-option {
                padding: 1rem;
            }
        }

        .balance-display {
            background: rgba(255, 200, 39, 0.15);
            padding: 5px 15px;
            border-radius: 20px;
            border: 1px solid rgba(255, 200, 39, 0.3);
            color: var(--white) !important;
            margin-left: 10px;
            font-weight: 500;
        }

        .balance-display i {
            color: var(--white);
        }

        .balance-display span {
            color: var(--white) !important;
            font-weight: 600;
        }
    </style>

    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-om me-2"></i>KALYAN MAHADEV
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    @auth
                    <li class="nav-item">
                        <div class="nav-link balance-display">
                            <i class="fas fa-coins me-1"></i><span style="color: var(--white)">Balance:</span> <span>₹{{ number_format(Auth::user()->coins, 2) }}</span>
                        </div>
                    </li>
                    @endauth
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>{{ __('Login') }}
                        </a>
                    </li>
                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i>{{ __('Register') }}
                        </a>
                    </li>
                    @endif
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('scripts')
</body>

</html>