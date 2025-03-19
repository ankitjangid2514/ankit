
@extends('admin.layouts.main')
@section('title')
Contact Settings Management
@endsection
@section('container')

<div class="main-content">	<div class="page-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12 col-xl-12">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-xl-12">
						<div class="card">
						  <div class="card-header">
							<h5>Contact Settings</h5>
						  </div>
							<div class="card-body">
								<form class="theme-form mega-form" action="{{route('insert_contact')}}" method="post" enctype="multipart/form-data">
									@csrf
								<!-- {{$data->id}} -->
									<input type="hidden" name="id" value="{{$data->id}}">
									<div class="row">
										<div class="form-group col-md-6">
											<label class="col-form-label"><strong>Mobile Number <span class="Img_ext">eg.9876543210</span></strong></label>
											<input type="text" class="form-control mobile-valid" name="mobile_1" id="mobile_1" placeholder="Please Enter Mobile Number" value="{{$data->mobile_number}}">
										</div>
										<div class="form-group col-md-6">
											<label class="col-form-label"><strong>Telegram Mobile (Optional)</strong></label>
											<input type="text" class="form-control" name="mobile_2" id="mobile_2" placeholder="Please Enter Second Mobile Number" value="{{$data->telegram_link}}">
										</div>
										<div class="form-group col-md-6">
											<label class="col-form-label"><strong>WhatsApp Number</strong></label>
											<input type="text" class="form-control" name="whats_mobile" id="whats_mobile" placeholder="Please Enter WhatsApp Mobile Number" value="{{$data->whatsapp_number}}">
										</div>
										<div class="form-group col-md-6">
											<label class="col-form-label"><strong>Upload Qr Code</strong></label>
											<input type="file" class="form-control" name="qr_code" id="qr_code">
										</div>
										
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary waves-light m-t-10" id="submitBtn" name="submitBtn">Submit</button>
									</div>
									<div class="form-group">
										<div id="errormsg"></div>
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
