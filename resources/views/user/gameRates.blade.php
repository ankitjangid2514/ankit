@extends('user.layouts.main')
@section('title','gameRates')

@section('content')

<div class="container-fluid">
	<section class="dashCard1" style="">
			<div class="row">
			<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xlg-12">
				<div class="card mb-3" style="background:#500143;color:#ffc827;">
				  <div class="card-header text-center game" id="a1" style="border-color:#ffc827">Game Rates</div>
				  <div class="card-body" id="a2">
				  	<div class="row card_row">

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
					  		<h4 class="card-title game">Single Digit</h4>
					  	</div>

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">
					  		<h4 class="card-title game">{{$data->single_digit_bid}}-{{$data->single_digit_win}}</h4>
					  	</div>

				  	</div>

				  	<div class="row card_row">

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
					  		<h4 class="card-title game">Jodi Digit</h4>
					  	</div>

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">
					  		<h4 class="card-title game">{{$data->jodi_digit_bid}}-{{$data->jodi_digit_win}}</h4>
					  	</div>

				  	</div>
					

				  	<div class="row card_row">

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
					  		<h4 class="card-title game">Single Panna</h4>
					  	</div>

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">
					  		<h4 class="card-title game">{{$data->single_match_bid}}-{{$data->single_match_win}}</h4>
					  	</div>

				  	</div>

				  		<div class="row card_row">

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
					  		<h4 class="card-title game">Double Panna</h4>
					  	</div>

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">
					  		<h4 class="card-title game">{{$data->double_match_bid}}-{{$data->double_match_win}}</h4>
					  	</div>

				  	</div>


				  		<div class="row card_row">

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
					  		<h4 class="card-title game">Triple Panna</h4>
					  	</div>

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">
					  		<h4 class="card-title game">{{$data->triple_match_bid}}-{{$data->triple_match_win}}</h4>
					  	</div>

				  	</div>


				  		<div class="row card_row">

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
					  		<h4 class="card-title game">Half Sangam</h4>
					  	</div>

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">
					  		<h4 class="card-title game">{{$data->half_sangam_bid}}-{{$data->half_sangam_win}}</h4>
					  	</div>

				  	</div>


				  		<div class="row card_row">

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
					  		<h4 class="card-title game">Full Sangam</h4>
					  	</div>

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">
					  		<h4 class="card-title game">{{$data->full_sangam_bid}}-{{$data->full_sangam_win}}</h4>
					  	</div>

				  	</div>

				  </div>


				  <div class="card-header text-center game" style="border-color:#ffc827">Starline Game Rates</div>
				 <div class="card-body" style="background:#0c93111a;">
				  	<div class="row card_row">

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
					  		<h4 class="card-title game">Single Digit</h4>
					  	</div>

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">
					  		<h4 class="card-title game">{{$starline->single_digit_bid}}-{{$starline->single_digit_win}}</h4>
					  	</div>

				  	</div>
				  	

				  	<div class="row card_row">

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
					  		<h4 class="card-title game">Single Panna</h4>
					  	</div>

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">

					  		<h4 class="card-title game">{{$starline->single_match_bid}}-{{$starline->single_match_win}}</h4>
					  	</div>

				  	</div>

				  	<div class="row card_row">

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
					  		<h4 class="card-title game">Double Panna</h4>
					  	</div>

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">

					  		<h4 class="card-title game">{{$starline->double_match_bid}}-{{$starline->double_match_win}}</h4>
					  	</div>

				  	</div>

				  	 <div class="row">

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
					  		<h4 class="card-title game">Triple Panna</h4>
					  	</div>

					  	<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">

					  		<h4 class="card-title game">{{$starline->triple_match_bid}}-{{$starline->triple_match_win}}</h4>
					  	</div>

				  	</div>


				  </div>

				</div>
			</div>


		</div>



	</section>

</div>
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->

</body>
</html>

@endsection