
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Admin -
        @yield('title')

    </title>
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="{{asset('adminassets/images/favicon.ico')}}">

	<!-- Bootstrap Css -->
	<link href="{{asset('adminassets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<!-- Icons Css -->
	<link href="{{asset('adminassets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />

	<link href="{{asset('adminassets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('adminassets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('adminassets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('adminassets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
	<!-- App Css-->
	<link href="{{asset('adminassets/css/app.min.css?v=2')}}" id="app-style" rel="stylesheet" type="text/css" />

	<link href="{{asset('adminassets/css/custom.css?v=11')}}" rel="stylesheet" type="text/css" />

    <!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Boxicons CDN -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<link href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">





  </head>
  <body data-sidebar="dark">

	<div id="layout-wrapper">
	<header id="page-topbar">
	<div class="navbar-header">
		<div class="d-flex">
			<!-- LOGO -->
			<div class="navbar-brand-box">
				<a href="" class="logo logo-dark">
					<span class="logo-sm">
						<img src="{{asset('adminassets/images/logo1.jpg')}}" alt="" height="22">
					</span>
					<span class="logo-lg">
						<img src="{{asset('adminassets/images/logo1.jpg')}}" alt="" height="17">
					</span>
				</a>

				<a href="" class="logo logo-light">
					<span class="logo-sm">
						<img src="{{asset('adminassets/images/logo1.jpg')}}" alt="" height="22">
					</span>
					<span class="logo-lg">
						<img src="{{asset('adminassets/images/logo1.jpg')}}" alt="" height="50">
					</span>
				</a>
			</div>

			<button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
				<i class="fa fa-fw fa-bars"></i>
			</button>

			<button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
				<b><a href="{{route('adminDashboard')}}">Home</a></b>
			</button>


		</div>

		<div class="d-flex">


			<div class="dropdown d-none d-lg-inline-block ml-1">
				<button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
					<i class="bx bx-fullscreen"></i>
				</button>
			</div>


			<div class="dropdown d-inline-block">
				<button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
					data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<img class="rounded-circle header-profile-user" src="{{asset('adminassets/images/logo1.jpg')}}"
						alt="Header Avatar">
					<span class="d-none d-xl-inline-block ml-1" key="t-henry">Admin</span>
					<i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-right">
					<!-- item-->
					<a class="dropdown-item" href="{{route('change_password')}}"><i class="bx bx-user font-size-16 align-middle mr-1"></i> <span key="t-lock-screen">Change Password</span></a>
					<a class="dropdown-item d-block" href="{{route('main_settings')}}"><i class="bx bx-wrench font-size-16 align-middle mr-1"></i> <span key="t-settings">Settings</span></a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item text-danger" href="{{route('adminLogout')}}"><i class="bx bx-power-off font-size-16 align-middle mr-1 text-danger"></i> <span key="t-logout">Logout</span></a>
				</div>
			</div>



		</div>
	</div>
</header>

<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

	<div data-simplebar class="h-100">

		<!--- Sidemenu -->
		<div id="sidebar-menu">
			<!-- Left Menu Start -->
			<ul class="metismenu list-unstyled" id="side-menu">
				<li>
					<a href="{{route('adminDashboard')}}" class="waves-effect mm-active">
						<i class="bx bx-home-circle"></i>
						<span>Dashboard</span>
					</a>
				</li>

				<li>
					<a href="{{route('user_management')}}" class="waves-effect">
						<i class="bx bxs-user-detail"></i>
						<span>User Management</span>
					</a>
				</li>

				<li>
					<a href="{{route('declare_result')}}" class="waves-effect">
						<i class="bx bx-bullseye"></i>
						<span>Declare Result</span>
					</a>
				</li>

				<li>
					<a href="{{route('winning_prediction')}}" class="waves-effect">
						<i class="bx bx-bullseye"></i>
						<span>Winning Prediction</span>
					</a>
				</li>

							<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="bx bx-file"></i>
						<span>Report Management</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="{{route('user_bid_history')}}">Users Bid History</a></li>
						<li><a href="{{route('winning_report')}}">Winning Report</a></li>
						{{-- <li><a href="{{route('transfer_point_report')}}">Transfer Point Report</a></li> --}}
						<li><a href="{{route('bid_winning_report')}}">Bid Win Report</a></li>
						<li><a href="{{route('withdraw_report')}}">Withdraw Report</a></li>
						<li><a href="{{route('auto_deposite_history')}}">Auto Deposit History</a> </li>
						 {{-- <li><a href="{{route('get_referral_amount_data')}}">Referral History</a> </li> --}}
					</ul>
				</li>

				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="bx bx-wallet"></i>
						<span>Wallet Management</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="{{route('fund_request_management')}}">Fund Request</a> </li>
						<li><a href="{{route('withdrawal_request')}}">Withdraw Request</a> </li>
						<li><a href="{{route('add_fund_user_wallet_management')}}">Add Fund (User Wallet)</a> </li>
						<li><a href="{{route('bid_revert')}}">Bid Revert</a> </li>
					</ul>
				</li>
							<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="bx bx-bullseye"></i>
						<span>Games Management</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="{{route('gameNameView')}}">Game Name</a> </li>
						<li><a href="{{route('game_rates_show')}}">Game Rates</a></li>
					</ul>
				</li>



				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="bx bx-bullseye"></i>
						<span>Game & Numbers</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="{{route('single_digit')}}">Single Digit</a></li>

						<li><a href="{{route('jodi_digit')}}">Jodi Digit</a> </li>

						<li><a href="{{route('single_pana')}}">Single Pana</a> </li>

						<li><a href="{{route('double_pana')}}">Double Pana</a> </li>

						<li><a href="{{route('tripple_pana')}}">Tripple Pana</a> </li>

						<li><a href="{{route('half_sangam')}}">Half Sangam</a> </li>

						<li><a href="{{route('full_sangam')}}">Full Sangam</a> </li>
					</ul>
				</li>

				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="bx bx-cog"></i>
						<span>Settings</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="{{route('main_settings')}}">Main Settings</a></li>
						<li><a href="{{route('contact_settings')}}">Contact Settings</a></li>
						{{-- <li><a href="{{route('clear_data')}}">Clear Data</a></li> --}}
						<li><a href="{{route('slider_images_management')}}">Slider Images</a></li>
						<li><a href="{{route('how_to_play')}}">How To Play</a> </li>
					</ul>
				</li>


				<li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="bx bx-cog"></i>
						<span>Notice Management</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="{{route('notice_management')}}">Notice Management</a> </li>
						<li><a href="{{route('send_notification')}}">Send Notification</a> </li>
					</ul>
				</li>



				 <li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="bx bx-file"></i>
						<span>Starline Management</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="{{route('starline_game_name')}}">Game Name</a></li>
						<li><a href="{{route('starline_game_rates')}}">Game Rates</a></li>
						<li><a href="{{route('starline_user_bid_history')}}">Bid History</a></li>
						<li><a href="{{route('starline_declare_result')}}">Decleare Result</a></li>
						<li><a href="{{route('starline_result_history')}}">Result History</a></li>
						<li><a href="{{route('starline_winning_report')}}">Starline Winning report</a></li>
						<li><a href="{{route('starline_winning_prediction')}}">Starline Winning Prediction</a></li>
					</ul>
				</li>


				 <li>
					<a href="javascript: void(0);" class="has-arrow waves-effect">
						<i class="bx bx-file"></i>
						<span>Desawar Management</span>
					</a>
					<ul class="sub-menu mm-collapse" aria-expanded="false">
						<li><a href="{{route('desawar_game_name')}}">Game Name</a></li>
						<li><a href="{{route('desawar_game_rates')}}">Game Rates</a></li>
						<li><a href="{{route('desawar_user_bid_history')}}">Bid History</a></li>
						<li><a href="{{route('desawar_declare_result')}}">Decleare Result</a></li>
						<li><a href="{{route('desawar_result_history')}}">Result History</a></li>
						<li><a href="{{route('desawar_winning_report')}}">Desawar Winning report</a></li>
						<li><a href="{{route('desawar_winning_prediction')}}">Desawar Winning Prediction</a></li>
					</ul>
				</li>


				<!-- <li>
					<a href="{{route('sub_admin_management')}}" class="waves-effect">
						<i class="bx bxs-user-detail"></i>
						<span>Sub Admin Management</span>
					</a>
				</li> -->

			</ul>
		</div>
		<!-- Sidebar -->
	</div>
</div>
<!-- Left Sidebar End -->
