<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>BAAZIGAR - Online App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ url('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('fonts/iconic/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ url('css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />


    <style>
        body {
            font-family: "Lato", sans-serif;
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

        .custom-fixed {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
        }

        .custom-slider {
            position: fixed;
            top: 15%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* z-index: 1; */
            width: 100%;
            height: 200px;
        }

        .custom-card {
            position: fixed;
            top: 44%;
            /* Center vertically */
            left: 50%;
            /* Center horizontally */
            transform: translate(-50%, -50%);
            /* Adjust for the width and height of the card */
            width: 100%;
            /* Make the card responsive */
            /* Set a max-width for larger screens */
            padding: 5px 20px;
            /* Add some padding */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Optional: add a shadow */
            border-radius: 8px;
            /* Optional: round corners */
            background-color: #500143;
            /* Ensure a solid background */
            /* Handle overflow for small screens */
        }

        /* Optional: Media query for very small screens */
        @media (max-width: 480px) {
            .custom-card {
                width: 95%;
                /* Increase width for very small screens */
            }
        }


        .market {
            position: fixed;
            /* or static */
            top: 53%;
            height: calc(100vh - 60%);
            /* Adjust height so it fills the rest of the screen */
            overflow-y: auto;
            /* Allow vertical scrolling */
            padding-left: 20px;
        }

        /* For modern browsers like Chrome, Edge, and Safari */
        .market::-webkit-scrollbar {
            width: 6px;
            /* Reduce the scrollbar width */
        }

        .market::-webkit-scrollbar-thumb {
            background-color: #ffc827;
            /* Color of the scrollbar thumb */
            border-radius: 10px;
            /* Roundness of the scrollbar */
        }

        .market::-webkit-scrollbar-track {
            background-color: #500143;
            /* Color of the scrollbar track */
        }

        /* For Firefox */
        .market {
            scrollbar-width: thin;
            /* Reduce the scrollbar width */
            scrollbar-color: #ffc827 #500143;
            /* Thumb color and track color */
        }




        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }

        .alert {
            padding: 0px 10px 0px 10px !important;
        }
    </style>
</head>

<div class="container-fluid custom-fixed">
    <div class="row" style="background:#ffc827;padding: 10px;">

        <div id="mySidenav" class="sidenav" style="padding-top: 0px;">

            <div
                style="padding: 0; height: 80px; padding-right: 15px; background: #ffc827; padding-left: 25px; color: #000;">
                <div style="float: left; margin-top: 20px;">
                    {{ $userData->name }}</br>{{ $userData->number }}
                </div>
                <div onclick="closeNav()"
                    style="font-size: 30px;/* height:19px; *//* margin-right:14px; */float: right;/* margin-bottom: 10px; */margin-top: 19px;top: 0;">×
                </div>
            </div>

            <a href="{{ route('dashboard') }}"><img src="{{ url('images/1.png') }}"
                    style="width:19px;height:19px; margin-right:14px;" />Home</a>
            <a href="{{ route('profile') }}"><img src="images/2.png"
                    style="width:19px;height:19px; margin-right:14px;" />User
                Profile</a>
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
                    style="width:19px;height:19px; margin-right:14px;" />How To
                Play</a>
            <a href="whatsapp://send?text=Download Our Matka App  https://baazigarmatka.in"
                data-action="share/whatsapp/share" target="_blank">
                <img src="images/7.png" style="width:19px;height:19px; margin-right:14px;" />Share</a>
            <a href="{{ route('password') }}"><img src="images/9.png"
                    style="width:19px;height:19px; margin-right:14px;" />Update Password</a>
            <a href="{{ route('logout') }}"><img src="images/8.png"
                    style="width:19px;height:19px; margin-right:14px;" />Logout</a>
        </div>


        <div class="col-2"><span style="cursor:pointer" onclick="openNav()"><i class="fa fa-bars"
                    style="color:#000;font-size:19px;width:30px;margin-top: 7px;"> </i></span>
        </div>
        <div class="col-10 pull-right">
            <div
                style="padding-top: 2px !important;color: #000;float: left;font-weight: bold;font-size: 15px;font-family: unset;">
                KALYAN MAHADEV </div>
            <img src="images/wallet.png" style="width:18px;height:18px;margin-right: 10px;" />
            <div style="padding-top: 2px !important;color: #000;width: 45px;float: right;">{{$userData->balance}}</div>
        </div>
    </div>
</div>

  <!-- Displaying Errors Here -->
  @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Display Success or Error Messages -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


<div class="container custom-slider" style="margin-top: 40px; ">
    <div class="row">
        <div class="col-12 mt-3 p-0">


            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->

                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    @if($images->isEmpty())
                    <!-- Default image when no images are available from the database -->
                    <div class="item active">
                        <img src="{{url('images/kalyan-mahadev.jpg')}}" style="width:100%;height:180px;">
                    </div>
                    @else
                    @foreach($images as $image)
                    <div class="item {{ $loop->first ? 'active' : '' }}">
                        <img src="{{ $image->image }}" style="width:100%;height:180px;">
                    </div>
                    @endforeach
                    @endif
                </div>


            </div>

        </div>
    </div>
</div>


<div class="container card custom-card" style="margin: 0px; ">
    <div class="row">

        <div class="col-4">
            <div class="row">


                <div style="width:90%;margin:5px;">
                    <a href="{{ route('addPoint') }}">
                        <div class=""
                            style="border:1px solid #000;background-color: #ffc827;color:#000;border-radius: 4px;height: 36px;">
                            <span style="font-size: 19px;float: left;padding-left: 12px;padding-top: 1px;">
                                <i class="fa-solid fa-wallet"></i>
                            </span>

                            <div
                                style="FONT-SIZE: 12px;padding-left: 9px;font-family: sans-serif;float: left;padding-top: 7px;">
                                Add Point
                            </div>

                        </div>
                    </a>

                </div>


                <div style="width:90%;margin:5px;">
                    <a href="{{ route('withdrawPoint') }}">
                        <div class=""
                            style="border:1px solid #000;background-color: #ffc827;color:#000;border-radius: 4px;height: 36px;">
                            <span style="font-size: 19px;float: left;padding-left: 12px;padding-top: 1px;">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                            </span>

                            <div
                                style="FONT-SIZE: 12px;padding-left: 9px;font-family: sans-serif;float: left;padding-top: 7px;">
                                Withdraw
                            </div>

                        </div>
                    </a>

                </div>


            </div>
        </div>
        <div class="col-4" style="padding-left: 5px; ">
            <div class="row" style="margin-left: 0px;">

                <div class="col-lg-12" style="padding: 5px;margin:0px;">

                    <a href="https://wa.me/{{$admin->whatsapp_number}}/">

                        <div
                            style="width: 100%;border-radius:50px;text-align: center;margin-top: 5px;FONT-SIZE: 11px;border:1px solid #000;background-color: #ffc827;color:#000;">
                            <div style="width: 100%;text-align: center;margin-top:7px;">
                                <i class="fab fa-whatsapp" style="color:#000;font-size: 30px;"></i>
                            </div>
                            Whatsapp
                        </div>


                    </a>



                </div>





            </div>
        </div>

        <div class="col-4">
            <div class="row">


                <div style="width:90%;margin:5px;">
                    <a href="{{ route('starline') }}">
                        <div class=""
                            style="border:1px solid #000;background-color: #ffc827;color:#000;border-radius: 4px;height: 36px;">
                            <span style="font-size: 19px;float: left;padding-left: 12px;padding-top: 1px;">
                                <i class="fa fa-play-circle"></i>
                            </span>

                            <div
                                style="FONT-SIZE: 12px;padding-left: 9px;font-family: sans-serif;float: left;padding-top: 7px;">
                                STARLINE
                            </div>

                        </div>
                    </a>

                </div>


                <div style="width:90%;margin:5px;">
                    <a href="{{ route('galidesawar') }}">
                        <div class=""
                            style="border:1px solid #000;background-color: #ffc827;color:#000;text-align: center;border-radius: 4px;height: 36px;">


                            <span
                                style="FONT-SIZE: 12px;/* padding-left: 4px; */font-family: sans-serif;float: left;padding-top: 7px;width: 100%;">
                                Gali-Disawar
                            </span>

                        </div>
                    </a>

                </div>


            </div>
        </div>
    </div>
</div>

<div class="col-12 market" style="padding-right: 0;padding-left: 0;">
    <div class="container">

        @foreach ($markets as $game)

        @php
        // Create a DateTime object from the AM/PM time format
        $time = new DateTime($game->open_time);
        $times = new DateTime($game->close_time);

        // Format the time to 24-hour format (e.g., '14:30')
        $formattedTime = $time->format('g:i A');
        $formattedTimes = $times->format('g:i A');

        @endphp

        @php

        $specific_time = new DateTime('01:00:00', new DateTimeZone('Asia/Kolkata'));
        $specific_time = $specific_time->format('H:i:s');

        $current_time = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        $current_time = $current_time->format('H:i:s');

        $open_time = new DateTime($game->open_time, new DateTimeZone('Asia/Kolkata'));
        $open_time = $open_time->format('H:i:s');

        $close_time = new DateTime($game->close_time, new DateTimeZone('Asia/Kolkata'));
        $close_time = $close_time->format('H:i:s');

        @endphp


        <section class="thirdRow">
            <div class="row">
                <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xlg-1"
                    style="color:#ffff;padding: 0px 6px;">
                    <div class="alert alert-dismissible alert-default"
                        style="background-color:#500143;border: 3px solid #ffc827;padding-bottom: 0px;margin-bottom: 0px; padding-right:5px;">
                        <div class="row">
                            <div class="col-3 col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xlg-3"
                                style="color:#21b427;padding:0px;text-align:center;">
                                <a href="{{ route('resultChart', ['result_id' => $game->id]) }}"><img src="images/chart.jpg"
                                        style="width: 35px;margin-top: 11px;height: 35px;"></a>
                                <!-- <a><img src="images/chart.jpg"
                                                style="width: 35px;margin-top: 11px;height: 35px;" onclick="show();"></a> -->
                                <div style="width:100%;">
                                    <span style="color:#ffc827;border-radius:6px;text-align:center;font-size: 13px;">Result
                                        Chart</span>
                                </div>
                            </div>
                            <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6" style="padding:0px;">
                                <p style="color:#ffc827;text-align:center;font-size:20px;" class="game_name">{{$game->market_name}} </p>
                                <p style="color:#FFF;font-size:1.12rem;text-align:center;" class="result_time">
                                    Open : {{ $formattedTime }} | Close : {{$formattedTimes}}</p>

                                @if($current_time >= $close_time)
                                <!-- <p style="color:red;text-align:center;font-size:13px;" class="result_time">Market Close</p> -->
                                @else
                                <!-- <p style="color:ffc827;text-align:center;font-size:13px;" class="result_time">Market Open</p> -->
                                @endif



                                @if (isset($results[$game->id])) <!-- Check if result exists for this market -->
                                @foreach ($results[$game->id] as $result)
                                <p style="color: grren;font-size:14px;text-align:center;" class="result_time">
                                    {{ $result->open_panna ?? '***' }}
                                    {{ $result->open_digit ?? '*' }}{{ $result->close_digit ?? '*' }}
                                    {{ $result->close_panna ?? '***' }}

                                </p>
                                @endforeach
                                @else
                                <p style="color: #ffc827; text-align: center;font-size:20px;">*** ** ***</p>
                                @endif


                            </div>

                            @if($current_time >= $close_time)
                            <div class="col-3 col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xlg-3"
                                style="padding:0px;text-align:center;">
                                <i class="fa fa-play-circle" onclick="abcc();"
                                    style="width: 33px;margin-top: 11px;height: 33px;font-size: 38px;color:red;background: #fff;border-radius: 47px;">
                                </i>
                                <div style="width:100%;">
                                    <span
                                        style="color:red;border-radius:6px;text-align:center;font-size: 1.5rem; padding-right:5px;">
                                        Close</span>
                                </div>


                            </div>
                            @else
                            <div class="col-3 col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xlg-3"
                                style="padding:0px;text-align:center;">

                                <a href="{{ route('games', ['market_id' => $game->id]) }}"><i class="fa fa-play-circle"
                                        style="width: 33px;margin-top: 11px;height: 33px;font-size: 38px;color:#ffc827;background: transparent;border-radius: 47px;box-shadow: -3.828px -3.828px 6px 0px rgba(255, 200, 39, 0.4), 3px 5px 8px 0px rgba(255, 82, 1, 0.2);">
                                    </i></a>
                                <div style="width:100%;">
                                    <span
                                        style="color:#ffc827;border-radius:6px;text-align:center;font-size: 1.5rem;">Play
                                        Now</span>
                                </div>

                            </div>
                            @endif

                        </div>

                    </div>

                </div>
            </div>
        </section>

        @endforeach

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

    function abcc() {
        alertt("Market Closed !");

        if ("vibrate" in navigator) {
            navigator.vibrate(200);
        }
    }

    function show() {
        alertt("Something went wrong");

        if ("vibrate" in navigator) {
            navigator.vibrate(200);
        }
    }

    var txt;

    function alertt(txt) {

        $("#alert").text(txt);
        $("#alert").show('slow');
        setTimeout(function() {
            $("#alert").hide('slow');
        }, 2000);



    }


    if (localStorage.getItem("menubar") == "") {
        localStorage.setItem("menubar", "0");
    } else {
        if (localStorage.getItem("menubar") == "1") {
            openNav();
        }
    }


    // Event listener for back button press
    window.addEventListener('popstate', function(event) {
        // Call the hideMenuBar function when back button is pressed
        closeNav();
    });

    // Push a new state to the history when the page loads
    window.history.pushState({
        page: 1
    }, "", "");

    // Function to handle navigating away from the page
    window.onbeforeunload = function() {
        // Push a new state to the history when navigating away
        window.history.pushState({
            page: 2
        }, "");
    };

    function openWhatsApp() {

        var phoneNumber = "916377453718"; // Replace with your WhatsApp phone number
        var url = "https://wa.me/" + phoneNumber;
        window.location.href = url;
    }
</script>
<!--===============================================================================================-->
<script src="{{ url('vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->

<!--===============================================================================================-->
<!-- <script src="vendor/bootstrap/js/popper.js"></script> -->
<!-- <script src="vendor/bootstrap/js/bootstrap.min.js"></script> -->
<!--===============================================================================================-->
<!-- <script src="js/main.js"></script> -->

</body>

</html>