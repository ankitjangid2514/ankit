<!DOCTYPE html>
<html>

<head>
    <title>@yield('title', 'Kalyan Matka')</title> {{-- Default title if no section is set --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{ url('vendor/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ url('fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('fonts/iconic/css/material-design-iconic-font.min.css') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ url('css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

       <!-- Toastr CSS -->
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

       <!-- jQuery -->
       <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
       <!-- Toastr JS -->
       <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
   
       <!-- Toastr Configuration -->
       
       <script>
           toastr.options = {
               "closeButton": true,
               "debug": false,
               "newestOnTop": true,
               "progressBar": true,
               "positionClass": "toast-top-right",
               "preventDuplicates": true,
               "onclick": null,
               "showDuration": "300",
               "hideDuration": "1000",
               "timeOut": "5000",
               "extendedTimeOut": "1000",
               "showEasing": "swing",
               "hideEasing": "linear",
               "showMethod": "fadeIn",
               "hideMethod": "fadeOut"
           };
       </script>
       
    <style>
        /* Custom styles */
        #game_form .card {
            width: 100%;
            background: darkgrey;
            padding: 20px 0px;
        }

        .black {
            width: 100%;
            background: black;
            padding: 10px 0px;
            color: #fff;
            text-align: center;
        }

        .black span {
            width: 100%;
            display: block;
        }

        .balance-box {
            width: 100%;
            padding: 20px 10px;
        }

        .balance-box #wallet-pic {
            width: 30px;
            height: 30px;
            color: orange;
        }

        .balance-box .amount {
            width: 100%;
        }

        .balance-box .balance-alert {
            width: 100%;
        }

        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background: #000;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidenav a {
            padding: 15px 8px 15px 32px;
            text-decoration: none;
            font-size: 14px;
            color: #ffc827;
            border-bottom: 1px #ffc827 solid;
            display: block;
            transition: 0.3s;
            background: #500143;
        }

        .sidenav a:hover {
            color: #fff;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        /* Media Queries for responsiveness */
        @media (max-width: 768px) {
            .balance-box .amount {
                font-size: 14px;
            }

            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }

        @media (max-width: 576px) {
            .black span {
                font-size: 12px;
            }

            .balance-box .amount {
                font-size: 16px;
            }
        }

        /* Custom scrollbar styling */
        .sidenav::-webkit-scrollbar {
            width: 3px;
            /* Thin scrollbar width */
        }

        .sidenav::-webkit-scrollbar-track {
            background: #333;
            /* Track color */
        }

        .sidenav::-webkit-scrollbar-thumb {
            background: #fff;
            /* Scrollbar handle color */
            border-radius: 10px;
            /* Rounded edges for scrollbar */
        }

        .sidenav::-webkit-scrollbar-thumb:hover {
            background: yellow;
            /* Handle color on hover */
        }
    </style>
</head>

<body>

    <div class="container-fluid custom-fixed mb-3">
        <div class="row" style="background:#ffc827;padding: 10px;">

            <div id="mySidenav" class="sidenav" style="padding-top: 0px;">
                <div style="padding-left: 40px; height: 80px; background: #ffc827; color: #000;">
                    <div style="float: left; margin-top: 20px;">
                        {{ $userData->name }}<br>{{ $userData->number }}
                    </div>
                    <div onclick="closeNav()" style="font-size: 50px; float: right; margin-top: 5px; margin-right:15px">
                        ×
                    </div>
                </div>
                <a href="{{ route('dashboard') }}"><img src="{{ url('images/1.png') }}"
                        style="width:19px;height:19px; margin-right:14px;" />Home</a>
                <a href="{{ route('profile') }}"><img src="images/2.png"
                        style="width:19px;height:19px; margin-right:14px;" />User Profile</a>
                <a href="{{ route('wallet') }}"><img src="images/3.png"
                        style="width:19px;height:19px; margin-right:14px;" />Wallet</a>
                <a href="{{ route('addPoint') }}"><img src="images/point.png"
                        style="width:19px;height:19px; margin-right:14px;" />Add Point</a>
                <a href="{{ route('withdrawPoint') }}"><img src="images/point.png"
                        style="width:19px;height:19px; margin-right:14px;" />Withdraw Point</a>
                <a href="{{ route('winning_history') }}"><img src="images/point.png"
                        style="width:19px;height:19px; margin-right:14px;" />Winning History</a>
                <a href="{{ route('history_bid') }}"><img src="images/point.png"
                        style="width:19px;height:19px; margin-right:14px;" />Bid History</a>
                <a href="{{ route('gameRates') }}"><img src="images/4.png"
                        style="width:19px;height:19px; margin-right:14px;" />Game Rate</a>
                <a href="{{ route('contact') }}"><img src="images/5.png"
                        style="width:19px;height:19px; margin-right:14px;" />Contact Us</a>
                <a href="{{ route('help') }}"><img src="images/6.png"
                        style="width:19px;height:19px; margin-right:14px;" />How To Play</a>
                <a href="whatsapp://send?text=Download Our Matka App  https://baazigarmatka.in" target="_blank">
                    <img src="images/7.png" style="width:19px;height:19px; margin-right:14px;" />Share</a>
                <a href="{{ route('password') }}"><img src="images/9.png"
                        style="width:19px;height:19px; margin-right:14px;" />Update Password</a>
                <a href="{{ route('logout') }}"><img src="images/8.png"
                        style="width:19px;height:19px; margin-right:14px;" />Logout</a>
            </div>

            <div class="col-2">
                <span style="cursor:pointer" onclick="openNav()">
                    <i class="fa fa-bars" style="color:#000;font-size:19px;width:30px;margin-top: 7px;"></i>
                </span>
            </div>

            <div class="col-10">
                <div style="padding-top: 2px;color: #000;float: left;font-weight: bold;font-size: 15px;">KALYAN MAHADEV
                </div>

                <div style="float: right;">
                    <img src="{{ url('images/wallet.png') }}" style="width:18px;height:18px;margin-right: 10px;" />
                    <span id="userBalance">{{$userData->balance}}</span> <!-- Placeholder for balance -->
                </div>
            </div>
        </div>
    </div>


    <script>
        function openNav() {
            localStorage.setItem("menubar", "1");
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            localStorage.setItem("menubar", "0");
            document.getElementById("mySidenav").style.width = "0";
        }
    </script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
    // Function to fetch user balance using AJAX
    function fetchUserBalance() {
        $.ajax({
            url: "{{ route('getBalance') }}", // Backend route to fetch balance
            method: 'GET',
            success: function (response) {
                // Update the balance in the span element
                if (response.balance !== undefined) {
                    document.getElementById('userBalance').textContent = response.balance;
                } else {
                    document.getElementById('userBalance').textContent = 'Error fetching ';
                }
            },
            error: function () {
                document.getElementById('userBalance').textContent = 'Error fetching balance';
            }
        });
    }

    // Initial fetch
    fetchUserBalance();

    // Optionally refresh balance every 30 seconds
    setInterval(fetchUserBalance, 30000);
});

    </script> --}}
    