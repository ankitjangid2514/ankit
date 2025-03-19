<!DOCTYPE html>
<html>

<head>
    @if($desawar_gtype_id == '1')
    <title>Add Left Digit</title>

    @elseif($desawar_gtype_id == '2')
    <title>Add Right Digit</title>


    @elseif($desawar_gtype_id == '3')
    <title>Add Jodi Digit</title>


    @endif


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{url('vendor/bootstrap/css/bootstrap.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{url('fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{url('fonts/iconic/css/material-design-iconic-font.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{url('css/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('css/main.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
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

        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <div id="alert" class="alertss"></div>

    <div class="container-fluid custom-fixed mb-3">
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
                <img src="{{url('images/wallet.png')}}" style="width:18px;height:18px;margin-right: 10px;" />
                <div style="padding-top: 2px !important;color: #000;width: 45px;float: right;">{{$userData->balance}}</div>
            </div>
        </div>
    </div>

    @if($desawar_gtype_id == '1')
    <div class="container-fluid">
        <div class="row" style="background: var(--primary);padding: 10px;">
            <div class="col-12">

                <span style="color:var(--white);">Left Digit</span>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 30px;">
        <form method="post" action="{{route('desawar_game_insert')}}">
            @csrf
            <div class="row">
                <span id="output"></span>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="gdate">Choose Date</label>
                        <input type="text" class="form-control" name="gdate" id="gdate" readonly placeholder="Choose Date" value="Sep 25,2024" />
                    </div>
                </div>

                <div class="col-12" id="digitbox">
                    <div class="form-group">
                        <label class="form-label" for="digit" id="xyz1">Enter Digit</label>
                        <input type="text" class="form-control" name="digit" id="digit" maxlength="1" placeholder="Enter Digit" required="required" />
                        <small id="digit_error" style="color: red; display: none;">Invalid input. Please enter a single digit (0-9).</small>
                    </div>
                </div>

                <script>
                    const digitInput = document.getElementById('digit');
                    const digitError = document.getElementById('digit_error');

                    digitInput.addEventListener('input', () => {
                        let value = digitInput.value;

                        // Remove non-numeric characters
                        value = value.replace(/\D/g, '');

                        // Restrict to a single digit
                        if (value.length > 1) {
                            value = value.slice(0, 1);
                        }

                        digitInput.value = value;

                        // Validate single-digit input
                        if (value.length === 1 && /^[0-9]$/.test(value)) {
                            digitError.style.display = 'none';
                        } else if (value.length === 0) {
                            digitError.style.display = 'none'; // Hide error if the field is empty
                        } else {
                            digitError.style.display = 'block';
                        }
                    });
                </script>



                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="amount">Enter Amount</label>
                        <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter Amount" required="required" />
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <input type="hidden" name="desawar_gtype_id" id="gtype" value="{{$desawar_gtype_id}}">
                        <input type="hidden" name="desawar_id" id="game" value="{{$desawar_id}}">
                        <input type="submit" id="gameSubmit" value="Submit">
                    </div>
                </div>

            </div>
        </form>
    </div>

    @elseif ($desawar_gtype_id == '2')

    <div class="container-fluid">
        <div class="row" style="background: var(--primary);padding: 10px;">
            <div class="col-12">

                <span style="color:var(--white);">Right Digit</span>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 30px;">
        <form method="post" action="{{route('desawar_game_insert')}}">
            @csrf
            <div class="row">
                <span id="output"></span>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="gdate">Choose Date</label>
                        <input type="text" class="form-control" name="gdate" id="gdate" readonly placeholder="Choose Date" value="Sep 26,2024" />
                    </div>
                </div>



                <div class="col-12" id="digitbox">
                    <div class="form-group">
                        <label class="form-label" for="digit" id="xyz1">Enter Digit</label>
                        <input type="text" class="form-control" name="digit" id="digit" maxlength="1" placeholder="Enter Digit" required="required" />
                        <small id="digit_error" style="color: red; display: none;">Please enter a valid single digit (0-9).</small>
                    </div>
                </div>

                <script>
                    const digitInput = document.getElementById('digit');
                    const digitError = document.getElementById('digit_error');

                    digitInput.addEventListener('input', () => {
                        let value = digitInput.value;

                        // Remove non-numeric characters
                        value = value.replace(/\D/g, '');

                        // Restrict to a single digit
                        if (value.length > 1) {
                            value = value.slice(0, 1);
                        }

                        digitInput.value = value;

                        // Validate input
                        if (value.length === 1 && /^[0-9]$/.test(value)) {
                            digitError.style.display = 'none';
                        } else if (value.length === 0) {
                            digitError.style.display = 'none'; // Hide error if field is empty
                        } else {
                            digitError.style.display = 'block';
                        }
                    });

                    // Additional validation on form submission or blur event
                    digitInput.addEventListener('blur', () => {
                        if (digitInput.value.length !== 1 || !/^[0-9]$/.test(digitInput.value)) {
                            digitError.style.display = 'block';
                        } else {
                            digitError.style.display = 'none';
                        }
                    });
                </script>




                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="amount">Enter Amount</label>
                        <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter Amount" required="required" />
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <input type="hidden" name="desawar_gtype_id" id="gtype" value="{{$desawar_gtype_id}}">
                        <input type="hidden" name="desawar_id" id="game" value="{{$desawar_id}}">
                        <input type="submit" id="gameSubmit" value="Submit">
                    </div>
                </div>

            </div>
        </form>
    </div>

    @elseif ($desawar_gtype_id == '3')


    <div class="container-fluid">
        <div class="row" style="background: var(--primary);padding: 10px;">
            <div class="col-12">

                <span style="color:var(--white);">Jodi Digit</span>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 30px;">
        <form method="post" action="{{route('desawar_game_insert')}}">
            @csrf
            <div class="row">
                <span id="output"></span>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="gdate">Choose Date</label>
                        <input type="text" class="form-control" name="gdate" id="gdate" readonly placeholder="Choose Date" value="Sep 26,2024" />
                    </div>
                </div>



                <div class="col-12" id="digitbox">
                    <div class="form-group">
                        <label class="form-label" for="digit" id="xyz1">Enter Jodi</label>
                        <input type="text" class="form-control" name="digit" id="digit" maxlength="2" placeholder="Enter Jodi" required="required" />
                        <small id="digit_error" style="color: red; display: none;">Invalid input. Please enter a valid Jodi (two-digit value).</small>
                    </div>
                </div>

                <script>
                    const digitInput = document.getElementById('digit');
                    const digitError = document.getElementById('digit_error');
                    const jodi = [
                        12, 17, 21, 26, 62, 67, 71, 76,
                        13, 18, 31, 36, 63, 68, 81, 86,
                        14, 19, 41, 46, 64, 69, 91, 96,
                        1, 6, 10, 15, 51, 56, 60, 65,
                        23, 28, 32, 37, 73, 78, 82, 87,
                        24, 29, 42, 47, 74, 79, 92, 97,
                        2, 7, 20, 25, 52, 57, 70, 75,
                        34, 39, 43, 48, 84, 89, 93, 98,
                        3, 8, 30, 35, 53, 58, 80, 85,
                        4, 9, 40, 45, 54, 59, 90, 95,
                        5, 16, 27, 38, 49, 50, 61, 72, 83, 94,
                        0, 11, 22, 33, 44, 55, 66, 77, 88, 99
                    ];

                    digitInput.addEventListener('input', () => {
                        let value = digitInput.value;

                        // Remove non-numeric characters
                        value = value.replace(/\D/g, '');

                        // Restrict to two digits
                        if (value.length > 2) {
                            value = value.slice(0, 2);
                        }

                        digitInput.value = value;

                        // Validate Jodi
                        if (value.length === 2 && jodi.includes(Number(value))) {
                            digitError.style.display = 'none';
                        } else if (value.length === 2) {
                            digitError.style.display = 'block';
                        } else {
                            digitError.style.display = 'none';
                        }
                    });

                    // Additional validation on blur
                    digitInput.addEventListener('blur', () => {
                        const value = digitInput.value;

                        if (value.length === 2 && !jodi.includes(Number(value))) {
                            digitError.style.display = 'block';
                        } else {
                            digitError.style.display = 'none';
                        }
                    });
                </script>




                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="amount">Enter Amount</label>
                        <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter Amount" required="required" />
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <input type="hidden" name="desawar_gtype_id" id="gtype" value="{{$desawar_gtype_id}}">
                        <input type="hidden" name="desawar_id" id="game" value="{{$desawar_id}}">
                        <input type="submit" id="gameSubmit" value="Submit">
                    </div>
                </div>

            </div>
        </form>
    </div>

    @endif

    <script src="{{url('js/main.js')}}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const dateInput = document.getElementById('gdate');

            // Format the date as 'Sep 26, 2024'
            const formattedDate = today.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });

            // Set the value to today's formatted date
            dateInput.value = formattedDate;
        });

        function openNav() {
            localStorage.setItem("menubar", "1");
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            localStorage.setItem("menubar", "0");
            document.getElementById("mySidenav").style.width = "0";
        }
    </script>
    <script src="{{url('vendor/jquery/jquery-3.2.1.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Check for session success message
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            timer: 3000, // Auto-hide after 3 seconds
            timerProgressBar: true, // Show a progress bar
            showConfirmButton: true, // Show the close button
            confirmButtonText: 'Close', // Label for the close button
            allowOutsideClick: true // Allow dismissing the popup by clicking outside
        });
        @endif

        // Check for session error message
        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            timer: 3000, // Auto-hide after 3 seconds
            timerProgressBar: true,
            showConfirmButton: true,
            confirmButtonText: 'Close',
            allowOutsideClick: true
        });
        @endif

        // Display validation errors
        @if($errors->any())
        Swal.fire({
            icon: 'warning',
            title: 'Validation Error',
            html: '{!! implode("<br>", $errors->all()) !!}', // Display all errors as a list
            timer: 3000, // Auto-hide after 3 seconds
            timerProgressBar: true,
            showConfirmButton: true,
            confirmButtonText: 'Close',
            allowOutsideClick: true
        });
        @endif
    });
</script>




</body>


</html>