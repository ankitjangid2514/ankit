@extends('admin.layouts.main')
@section('title')
Clear Data
@endsection
@section('container')

<div class="main-content">	<div class="page-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12 col-xl-12 col-md-12">
				<div class="row">
					<div class="col-sm-12">
						<div class="card">
							<div class="card-header p-t-15 p-b-15">
								<h5>Clear Data</h5>
							</div>
							<div class="card-body">
								<form class="theme-form mega-form" id="clearDataFrm" name="clearDataFrm" method="post">
								<div class="row">
									<div class="form-group col-md-2">
										<label>Date To</label>
																				<div class="date-picker">
											<div class="input-group">
											  <input class="form-control digits" type="date" value="2024-09-06" name="result_date" id="result_date" max="2024-09-06" >
											</div>
										</div>
									</div>
									<!--<div class="form-group col-md-2">
										<label>&nbsp;</label>
										<button type="submit" class="btn btn-primary btn-block" id="submitBtn" name="submitBtn">Submit</button>
									</div>-->
									<div class="form-group col-md-3">
										<label>&nbsp;</label>
										<button type="submit" class="btn btn-primary btn-block" id="submitBtn" name="submitBtn">Download Bid History</button>
									</div>
									<div class="form-group col-md-3">
										<label>&nbsp;</label>
										<button type="button" onclick='walletTxnBackupFunction()' class="btn btn-primary btn-block">Download Wallet History</button>
									</div>
									<div class="form-group col-md-3">
										<label>&nbsp;</label>
										<button type="button" onclick='dataCleanFunction()' class="btn btn-primary btn-block" id="submitBtn" name="submitBtn">Clean Data</button>
									</div>

								</div>
									<div class="form-group">
										<div id="error_msg"></div>
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

<div class="modal fade" id="cleanDataModel" tabindex="-1" role="dialog" aria-hidden="true" >
  <div class="modal-dialog modal-frame modal-top modal-md">
	<div class="modal-content">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
  </div>
	  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-left">
						<h5>Are you sure you want to clean data to this selected date...?</h5>
					</div>
					<div class="col-md-4 text-left">
						<button onclick="data_clean(this.value)" id="data_clean_date" class="btn btn-danger waves-effect waves-light">Yes</button>
						<button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
					</div>
				</div>
			</div>
	  </div>
	</div>
  </div>
</div>

@endsection
