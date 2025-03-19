
<!DOCTYPE html>
<html>
<head>
	<title>Result Chart</title>
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

        .market-section {
        display: none;
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-in-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
</head>
<body>


<div class="container-fluid">
<section class="dashCard1" style="margin-top:5px;">
    <div class="row">
        <div class="col-12">
            <h3 class="resultHeading">
                <span>Result Chart</span>
            </h3>
        </div>
        <div class="col-12">
            @foreach($data->groupBy('market_name') as $marketName => $results)
                <div class="market-section">
                    <!-- Display Market Name -->
                    <h3 class="marketHeading">{{ $marketName }}</h3>

                    <div class="row market-results">
                        @foreach($results as $result)
                            @php
                                $timestamp = strtotime($result->result_date);
                                $formattedDate = date('d M Y', $timestamp);
                            @endphp

                            <!-- Display Result Card -->
                            <div class="col-3 card-body chartResult result-card" style="display:none;">
                                <div class="card text-white bg-danger mb-3">
                                    <div class="row" style="display: flex; justify-content: center; margin-bottom: 10px;">
                                        <div class="col-12 pull-center">
                                            <h4 class="card-title game result_chart">{{$formattedDate}}</h4>
                                        </div>

                                        <div class="col-10">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h4 class="card-title game">{{ substr($result->open_panna, 0, 1) }}</h4>
                                                </div>
                                                <div class="col-6 pull-right">
                                                    <h4 class="card-title game">{{ substr($result->open_panna, 1, 1) }}</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-10">
                                            <div class="row">
                                                <div class="col-4">
                                                    <h4 class="card-title game">{{ substr($result->open_panna, 2, 1) }}</h4>
                                                </div>
                                                <div class="col-4 pull-center" style="padding:0px;">
                                                    <h4 class="card-title game">{{$result->open_digit.$result->close_digit}}</h4>
                                                </div>
                                                <div class="col-4 pull-right paddingLeft0">
                                                    <h4 class="card-title game">{{ substr($result->close_panna, 0, 1) }}</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-10">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h4 class="card-title game">{{ substr($result->close_panna, 1, 1) }}</h4>
                                                </div>
                                                <div class="col-6 pull-right">
                                                    <h4 class="card-title game">{{ substr($result->close_panna, 2, 1) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

</div>

	
<!--===============================================================================================-->
	<script src="{{url('vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	
<!--===============================================================================================-->
	<!-- <script src="vendor/bootstrap/js/popper.js"></script> -->
	<!-- <script src="vendor/bootstrap/js/bootstrap.min.js"></script> -->
<!--===============================================================================================-->
	<!-- <script src="js/main.js"></script> -->
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

<script>
    // JavaScript to show market sections one by one
    document.addEventListener("DOMContentLoaded", function() {
        const marketSections = document.querySelectorAll('.market-section');
        let marketDelay = 0;

        marketSections.forEach(section => {
            marketDelay += 1000; // Delay between each market
            setTimeout(() => {
                section.style.display = "block";
                const cards = section.querySelectorAll('.result-card');
                let cardDelay = 0;
                
                cards.forEach(card => {
                    cardDelay += 500; // Delay between each card within the market
                    setTimeout(() => {
                        card.style.display = "block";
                        card.classList.add("fade-in");
                    }, cardDelay);
                });
            }, marketDelay);
        });
    });
</script>

</body>
</html>