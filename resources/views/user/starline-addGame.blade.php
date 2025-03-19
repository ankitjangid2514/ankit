<!DOCTYPE html>
<html>

<head>
    @if($starline_gtype_id == '1')
    <title>Add Single Digit</title>

    @elseif($starline_gtype_id == '2')
    <title>Add Single Panna</title>

    @elseif($starline_gtype_id == '3')
    <title>Add Double Panna</title>

    @elseif($starline_gtype_id == '4')
    <title>Add Tripple Panna</title>

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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--===============================================================================================-->
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
    @if($starline_gtype_id == '1')
    <div class="container-fluid">
        <div class="row" style="background: var(--primary);padding: 10px;">
            <div class="col-12">

                <span style="color:var(--white);">Single Digit</span>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 30px;">
        <form method="post" id="game_form" action="{{route('starline_game_insert')}}">
            @csrf
            <div class="row">
                <span id="output"></span>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="gdate">Choose Date</label>
                        <input type="text" class="form-control" name="gdate" id="gdate" readonly placeholder="Choose Date" value="Sep 13,2024" />
                    </div>
                </div>


                <div class="col-12" id="digitbox">
                    <div class="form-group">
                        <label class="form-label" for="digit" id="xyz1">Enter Digit</label>
                        <input
                            list="browsers"
                            type="text"
                            class="form-control"
                            name="digit"
                            id="digit"
                            maxlength="1"
                            placeholder="Enter Digit"
                            required />
                        <small id="digit_error" class="text-danger d-none">Please enter a single digit (0-9).</small>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="amount">Enter Amount</label>
                        <input
                            type="text"
                            class="form-control"
                            name="amount"
                            id="amount"
                            placeholder="Enter Amount"
                            required />
                        <small id="amount_error" class="text-danger d-none">Please enter a valid amount greater than 0.</small>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <input type="hidden" name="starline_gtype_id" id="gtype" value="{{$starline_gtype_id}}">
                        <input type="hidden" name="starline_id" id="game" value="{{$starline_id}}">
                        <input type="submit" id="gameSubmit" value="Submit" class="btn btn-primary">
                    </div>
                </div>

                <script>
                    document.getElementById('gameSubmit').addEventListener('click', function(e) {
                        // e.preventDefault();

                        const digitInput = document.getElementById('digit');
                        const amountInput = document.getElementById('amount');
                        const digitError = document.getElementById('digit_error');
                        const amountError = document.getElementById('amount_error');

                        let isValid = true;

                        // Validate digit input
                        if (!digitInput.value || digitInput.value.length !== 1 || isNaN(digitInput.value) || +digitInput.value < 0 || +digitInput.value > 9) {
                            digitError.classList.remove('d-none');
                            isValid = false;
                        } else {
                            digitError.classList.add('d-none');
                        }

                        // Validate amount input
                        if (!amountInput.value || isNaN(amountInput.value) || +amountInput.value <= 0) {
                            amountError.classList.remove('d-none');
                            isValid = false;
                        } else {
                            amountError.classList.add('d-none');
                        }

                        // If valid, submit the form
                        if (isValid) {
                            alert('Form submitted successfully!');
                            // Optionally, you can manually submit the form here if not using a form tag
                            // document.querySelector('form').submit();
                        }
                    });

                    // Restrict input to digits only for digit field
                    document.getElementById('digit').addEventListener('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1); // Allow only one digit
                    });

                    // Restrict input to digits only for amount field
                    document.getElementById('amount').addEventListener('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numeric values
                    });
                </script>


            </div>
        </form>
    </div>




    @elseif ($starline_gtype_id == '2')

    <div class="container-fluid">
        <div class="row" style="background: var(--primary);padding: 10px;">
            <div class="col-12">

                <span style="color:var(--white);">Single Panna</span>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 30px;">
        <form method="post" id="game_form" action="{{route('starline_game_insert')}}">
            @csrf
            <div class="row">
                <span id="output"></span>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="gdate">Choose Date</label>
                        <input type="text" class="form-control" name="gdate" id="gdate" readonly placeholder="Choose Date" value="Sep 14,2024" />
                    </div>
                </div>





                <div class="col-12" id="pannabox">
                    <div class="form-group">
                        <label class="form-label" for="panna" id="xyz2">Enter Panna</label>
                        <input
                            type="text"
                            class="form-control"
                            name="panna"
                            id="panna"
                            maxlength="3"
                            placeholder="Enter Panna"
                            required="required" />
                        <small id="panna_error" class="text-danger d-none">The entered value is not a valid Single Panna.</small>
                    </div>
                </div>

                <script>
                    // The list of valid single panna combinations
                    const singlePannaCombinationsList = [
                        '120', '123', '124', '125', '126', '127', '128', '129', '130', '134', '135', '136', '137', '138', '139',
                        '140', '145', '146', '147', '148', '149', '150', '156', '157', '158', '159', '160', '167', '168', '169', '170',
                        '178', '179', '180', '189', '190', '230', '234', '235', '236', '237', '238', '239', '240', '245', '246', '247',
                        '248', '249', '250', '256', '257', '258', '259', '260', '267', '268', '269', '270', '278', '279', '280', '289',
                        '290', '340', '345', '346', '347', '348', '349', '350', '356', '357', '358', '359', '360', '367', '368', '369',
                        '370', '378', '379', '380', '389', '390', '450', '456', '457', '458', '459', '460', '467', '468', '469', '470',
                        '478', '479', '480', '489', '490', '560', '567', '568', '569', '570', '578', '579', '580', '589', '590', '670',
                        '678', '679', '680', '689', '690', '780', '789', '790', '890'
                    ];

                    // Get references to the input field and error message
                    const pannaInput = document.getElementById('panna');
                    const pannaError = document.getElementById('panna_error');

                    // Function to validate the panna input
                    function validatePannaInput() {
                        const pannaValue = pannaInput.value.trim();

                        // Check if the value is a valid single panna
                        if (!singlePannaCombinationsList.includes(pannaValue)) {
                            pannaError.classList.remove('d-none'); // Show error message
                        } else {
                            pannaError.classList.add('d-none'); // Hide error message if valid
                        }
                    }

                    // Listen for input and validate only numeric values
                    pannaInput.addEventListener('input', function() {
                        // Restrict input to numeric values only
                        this.value = this.value.replace(/[^0-9]/g, ''); // Allow only digits
                        validatePannaInput();
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
                        <input type="hidden" name="starline_gtype_id" id="gtype" value="{{$starline_gtype_id}}">
                        <input type="hidden" name="starline_id" id="game" value="{{$starline_id}}">
                        <input type="submit" id="gameSubmit" value="Submit">
                    </div>
                </div>

            </div>
        </form>
    </div>


    @elseif ($starline_gtype_id == '3')

    <div class="container-fluid">
        <div class="row" style="background: var(--primary);padding: 10px;">
            <div class="col-12">

                <span style="color:var(--white);">Double Panna</span>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 30px;">
        <form method="post" id="game_form" action="{{route('starline_game_insert')}}">
            @csrf
            <div class="row">
                <span id="output"></span>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="gdate">Choose Date</label>
                        <input type="text" class="form-control" name="gdate" id="gdate" readonly placeholder="Choose Date" value="Sep 14,2024" />
                    </div>
                </div>





                <div class="col-12" id="pannabox">
                    <div class="form-group">
                        <label class="form-label" for="panna" id="xyz2">Enter Panna</label>
                        <input
                            type="text"
                            class="form-control"
                            name="panna"
                            id="panna"
                            maxlength="3"
                            placeholder="Enter Panna"
                            required="required" />
                        <small id="panna_error" class="text-danger d-none">The entered value is not a valid Double Panna.</small>
                    </div>
                </div>

                <script>
                    // List of valid double panna combinations
                    const doublePanna = [
                        "100", "110", "112", "113", "114", "115", "116", "117", "118", "119",
                        "122", "133", "144", "155", "166", "177", "188", "199", "200", "220",
                        "223", "224", "225", "226", "227", "228", "229", "233", "244", "255",
                        "266", "277", "288", "300", "330", "334", "335", "336", "337", "338",
                        "339", "344", "355", "366", "377", "388", "399", "400", "440", "445",
                        "446", "447", "448", "449", "455", "466", "477", "488", "499", "500",
                        "550", "557", "558", "559", "566", "577", "588", "599", "600", "660",
                        "667", "668", "669", "677", "688", "700", "770", "778", "779", "788",
                        "799", "800", "880", "889", "890", "899", "900", "990"
                    ];

                    // Get references to the input field and error message
                    const pannaInput = document.getElementById('panna');
                    const pannaError = document.getElementById('panna_error');

                    // Function to validate the panna input
                    function validatePannaInput() {
                        const pannaValue = pannaInput.value.trim();

                        // Check if the value is a valid double panna
                        if (!doublePanna.includes(pannaValue)) {
                            pannaError.classList.remove('d-none'); // Show error message
                        } else {
                            pannaError.classList.add('d-none'); // Hide error message if valid
                        }
                    }

                    // Listen for input and validate only numeric values
                    pannaInput.addEventListener('input', function() {
                        // Restrict input to numeric values only
                        this.value = this.value.replace(/[^0-9]/g, ''); // Allow only digits
                        validatePannaInput();
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
                        <input type="hidden" name="starline_gtype_id" id="gtype" value="{{$starline_gtype_id}}">
                        <input type="hidden" name="starline_id" id="game" value="{{$starline_id}}">
                        <input type="submit" id="gameSubmit" value="Submit">
                    </div>
                </div>

            </div>
        </form>
    </div>




    @elseif ($starline_gtype_id == '4')


    <div class="container-fluid">
        <div class="row" style="background: var(--primary);padding: 10px;">
            <div class="col-12">

                <span style="color:var(--white);">Triple Panna</span>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 30px;">
        <form method="post" id="game_form" action="{{route('starline_game_insert')}}">
            @csrf
            <div class="row">
                <span id="output"></span>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="gdate">Choose Date</label>
                        <input type="text" class="form-control" name="gdate" id="gdate" readonly placeholder="Choose Date" value="Sep 14,2024" />
                    </div>
                </div>


                <div class="col-12" id="pannabox">
                    <div class="form-group">
                        <label class="form-label" for="panna" id="xyz2">Enter Panna</label>
                        <input
                            type="text"
                            class="form-control"
                            name="panna"
                            id="panna"
                            maxlength="3"
                            placeholder="Enter Panna"
                            required="required" />
                        <small id="panna_error" class="text-danger d-none">The entered value is not a valid Triple Panna.</small>
                    </div>
                </div>

                <script>
                    // List of valid triple panna combinations
                    const triplePanna = ['111', '222', '333', '444', '555', '666', '777', '888', '999', '000'];

                    // Get references to the input field and error message
                    const pannaInput = document.getElementById('panna');
                    const pannaError = document.getElementById('panna_error');

                    // Function to validate the panna input
                    function validatePannaInput() {
                        const pannaValue = pannaInput.value.trim();

                        // Check if the value is a valid triple panna
                        if (!triplePanna.includes(pannaValue)) {
                            pannaError.classList.remove('d-none'); // Show error message
                        } else {
                            pannaError.classList.add('d-none'); // Hide error message if valid
                        }
                    }

                    // Listen for input and validate only numeric values
                    pannaInput.addEventListener('input', function() {
                        // Restrict input to numeric values only
                        this.value = this.value.replace(/[^0-9]/g, ''); // Allow only digits
                        validatePannaInput();
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
                        <input type="hidden" name="starline_gtype_id" id="gtype" value="{{$starline_gtype_id}}">
                        <input type="hidden" name="starline_id" id="game" value="{{$starline_id}}">
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
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.getElementById('gdate');

            // Set the min attribute to today's date
            dateInput.setAttribute('max', today);

            // Set the value to today's date
            dateInput.value = today;
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


</body>


</html>