@extends('admin.layouts.main');
@section('title')
Game Rates Managment
@endsection

@section('container')
<div class="main-content">
	<div class="page-content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-lg-8 mr-auto ml-auto">
					<div class="row">
						<div class="col-sm-12 col-12 ">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title">Add Games Rate</h4>
									<form class="theme-form mega-form" action="{{route('game_rates_insert')}}" method="post">
                                        @csrf
										<input type="hidden" name="game_rate_id" value="1">
										<div class="row">
											<div class="form-group col-md-6">
												<label class="col-form-label">Single Digit Value 1</label>
												<input class="form-control" type="number" name="single_digit_bid" value="{{$data->single_digit_bid}}" placeholder="Enter Single Digit Value">
											</div>
											<div class="form-group col-md-6">
												<label class="col-form-label">Single Digit Value 2</label>
												<input class="form-control" type="number" name="single_digit_win" value="{{$data->single_digit_win}}" placeholder="Enter Single Digit Value">
											</div>
											<div class="form-group col-md-6">
												<label class="col-form-label">Jodi Digit Value 1</label>
												<input class="form-control" type="number" name="jodi_digit_bid" value="{{$data->jodi_digit_bid}}" placeholder="Enter Jodi Digit Value">
											</div>
											<div class="form-group col-md-6">
												<label class="col-form-label">Jodi Digit Value 2</label>
												<input class="form-control" type="number" name="jodi_digit_win" value="{{$data->jodi_digit_win}}" placeholder="Enter Jodi Digit Value">
											</div>
											<div class="form-group col-md-6">
												<label class="col-form-label">Single Pana Value 1</label>
												<input class="form-control" type="number" name="single_match_bid" value="{{$data->single_match_bid}}" placeholder="Enter Single Pana Value">
											</div>
											<div class="form-group col-md-6">
												<label class="col-form-label">Single Pana Value 2</label>
												<input class="form-control" type="number" name="single_match_win"  value="{{$data->single_match_win}}" placeholder="Enter Single Pana Value">
											</div>
											<div class="form-group col-md-6">
												<label class="col-form-label">Double Pana Value 1</label>
												<input class="form-control" type="number" name="double_match_bid" value="{{$data->double_match_bid}}" placeholder="Enter Double Pana Value">
											</div>
											<div class="form-group col-md-6">
												<label class="col-form-label">Double Pana Value 2</label>
												<input class="form-control" type="number" name="double_match_win" value="{{$data->double_match_win}}" placeholder="Enter Double Pana Value">
											</div>
											<div class="form-group col-md-6">
												<label class="col-form-label">Tripple Pana Value 1</label>
												<input class="form-control" type="number" name="triple_match_bid" value="{{$data->triple_match_bid}}" placeholder="Enter Tripple Pana Value">
											</div>
											<div class="form-group col-md-6">
												<label class="col-form-label">Tripple Pana Value 2</label>
												<input class="form-control" type="number" name="triple_match_win" value="{{$data->triple_match_win}}" placeholder="Enter Tripple Pana Value">
											</div>
											<div class="form-group col-md-6">
												<label class="col-form-label">Half Sangam Value 1</label>
												<input class="form-control" type="number" name="half_sangam_bid" value="{{$data->half_sangam_bid}}" placeholder="Enter Half Sangam Value">
											</div>
											<div class="form-group col-md-6">
												<label class="col-form-label">Half Sangam Value 2</label>
												<input class="form-control" type="number" name="half_sangam_win" value="{{$data->half_sangam_win}}" placeholder="Enter Half Sangam Value">
											</div>
											<div class="form-group col-md-6">
												<label class="col-form-label">Full Sangam Value 1</label>
												<input class="form-control" type="number" name="full_sangam_bid" value="{{$data->full_sangam_bid}}" placeholder="Enter Full Sangam Value">
											</div>
											<div class="form-group col-md-6">
												<label class="col-form-label">Full Sangam Value 2</label>
												<input class="form-control" type="number" name="full_sangam_win" value="{{$data->full_sangam_win}}" placeholder="Enter Full Sangam Value">
											</div>
										</div>
										<div class="form-group">
											<button type="submit" class="btn btn-primary waves-light m-t-10" name="buysubmitBtn">Submit</button>
										</div>
										<div class="form-group">
											<div id="error"></div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endsection
	<!-- @include('admin.layouts.footer'); -->
