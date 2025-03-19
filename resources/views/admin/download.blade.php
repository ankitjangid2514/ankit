<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kalyan Online Matka | Best Satta Matka App</title>
    <meta name="description"
        content="Play online Matka with Kalyan Online Matka App. Get live results, tips, and expert advice. Download now!">
    <meta name="keywords" content="Kalyan Matka, Online Matka, Satta Matka, Matka Play Online">

    <!-- Favicons -->
    <link rel="icon" href="images/app_icon.png" type="image/x-icon">

    <!-- Bootstrap & CSS Libraries -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">

    <style>
        body {
            background-color: #141415;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.8);
        }

        .btn-custom {
            background: linear-gradient(to bottom, #57cc03, #004f1c);
            border: 1px solid #b1fd2b;
            color: #fff;
            border-radius: 7px;
            transition: all 0.3s ease-in-out;
        }

        .btn-custom:hover {
            background: linear-gradient(to bottom, #b1fd2b, #31eb6f);
            transform: scale(1.05);
        }

        header {
            text-align: center;
            padding-top: 120px;
        }

        .about-list li {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top animate__animated animate__fadeInDown">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="images/app_icon.png" alt="logo" width="50"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="#result">Results</a></li> --}}
                    <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="#time-table">Time Table</a></li> --}}
                    {{-- <li class="nav-item"><a class="nav-link" href="#rate-chart">Rate Chart</a></li> --}}
                    {{-- <li class="nav-item"><a class="nav-link" href="#how-to-play">How to Play</a></li> --}}
                    {{-- <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li> --}}
                </ul>
            </div>
        </div>
    </nav>

    <header data-aos="fade-up">
        <h1 class="mt-5">Download Best Online Matka App!</h1>
        <p class="mt-3">Play and win daily with Kalyan Online Matka.</p>
        <a href="{{ url('aj/mainRatan.apk') }}"
            class="btn btn-custom mt-3 px-4 py-2 animate__animated animate__pulse animate__infinite"
            download >
            <i class="fa fa-download me-2"></i>Download APK
        </a>


    </header>

    <section id="about" class="container my-5" data-aos="fade-right">
        <div class="col-md-8 mx-auto">
            <p class="about-desc">
                Welcome to <strong>Main Ratan Online Matka</strong>, your trusted destination for safe and exciting
                Matka gameplay!
                Whether you're a seasoned player or just starting, we provide a seamless experience with expert guidance
                and real-time updates.
            </p>

            <p><strong>Why Choose Us?</strong></p>
            <ul class="about-list">
                <li><strong>Fastest Live Results:</strong> Get instant updates for all major Matka markets.</li>
                <li><strong>Secure & Trusted:</strong> Enjoy a fully protected and fair gaming experience.</li>
                <li><strong>Expert Matka Tips:</strong> Enhance your winning chances with strategies from professionals.
                </li>
                <li><strong>User-Friendly Interface:</strong> Smooth navigation for an enjoyable experience.</li>
                <li><strong>24/7 Support:</strong> Our team is always available to assist you.</li>
            </ul>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000, // Animation duration
            easing: 'ease-in-out',
            once: true // Animation only once per page load
        });
    </script>
</body>

</html>
