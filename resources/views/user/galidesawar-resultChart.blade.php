
<!DOCTYPE html>
<html>
<head>
	<title>GaliDesawar Result Chart</title>
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
            <img src="{{url('images/wallet.png')}}" style="width:18px;height:18px;margin-right: 10px;"
        />
            <div style="padding-top: 2px !important;color: #000;width: 45px;float: right;">{{$userData->balance}}</div>
        </div>
    </div>
</div>

<div class="container-fluid">
	<section class="dashCard1" style="margin-top:5px;">

		<div class="row">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xlg-12">
				<h3 class="resultHeading"><span>GaliDesawar
                    @foreach ($star as $market)
                        {{$market->desawar_name}}
                    @endforeach
            </span> Result Chart </h3>
			</div>

			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xlg-12">
				
			  	<div class="row">

					@foreach($data as $result)

						@php
							$timestamp = strtotime($result->result_date);
							$formattedDate = date('d M Y', $timestamp);
						@endphp
						
						<div class="col-3 card-body chartResult">
							<div class="card text-white bg-danger mb-3">
								<div class="row " style="display: flex;justify-content: center;">

									<div class="col-12 pull-center">
										<h4 class="card-title game result_chart">{{$formattedDate}}</h4>
									</div>
								
									<div class="col-10">
										<div class="row ">
                                            <div class="col-12 pull-center">
												<h4 class="card-title game">{{$result->digit}}</h4>
											</div>
										</div>
									</div>

									

									

								</div>

								

							</div>

						</div>		
					
					@endforeach

				</div>

				

			

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
        }y
	 </script>

</body>
</html>