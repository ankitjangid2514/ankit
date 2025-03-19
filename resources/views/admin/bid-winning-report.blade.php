@extends('admin.layouts.main')
@section('title')
    Bid Win Report
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
								<h5>Winning History Report</h5>
							</div>
							<div class="card-body">
							<form action="{{ route('filterWinners') }}" method="POST"> <!-- Assuming you're using a POST method -->
								@csrf <!-- For security -->
								<div class="row">
									<div class="form-group col-md-2">
										<label>Date</label>
										<div class="date-picker">
											<div class="input-group">
												<input class="form-control digits" type="date" name="result_date" id="result_date" max="{{ date('Y-m-d') }}" required>
											</div>
										</div>
									</div>
									<div class="form-group col-md-4">
										<label>Game Name</label>
										<select id="win_game_name" name="win_game_name" class="form-control" required>
											<option value="">-Select Game Name-</option>
											@foreach ($data as $market)
												<option value="{{ $market->id }}">{{ $market->market_name }}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group col-md-2">
										<label>&nbsp;</label>
										<button type="submit" class="btn btn-primary btn-block" id="submitBtn" name="submitBtn">Submit</button>
									</div>
								</div>
							</form>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" id="total_data">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-body">
					    <div class="row">
					        <div class="col-md-6">
    							<div class="bid_history_box bhb_bid_amt">
    								<div class="row">
    								    <div class="col-md-6">
    								     <h5 class="text-muted font-weight-medium">Total Bid Amount</h5>
    								    </div>
    								    <div class="col-md-3">
    								        <h5 id="total_bid_amt">0</h5>
    								    </div>
    								    <div class="col-md-3 text-sm-right">
    								     <button type="button" class="btn btn-primary waves-light btn-xs" onclick="OpenBidHistory();" id="winner_btn">View</button>
    								     </div>
    								</div>
    							</div>
    							<div class="bid_history_box bhb_win_amt">
    							    <div class="row">
    								    <div class="col-md-6">
    								        <h5 class="text-muted font-weight-medium">Total Win Amount</h5>
        								</div>
        								<div class="col-md-3">
        								    <h5 class="mb-0" id="total_win_amt">0</h5>
        								</div>
        								<div class="col-md-3 text-sm-right">
        								    <button type="button" class="btn btn-primary waves-light btn-xs" onclick="OpenWinHistoryDetails();" id="winner_btn">View</button>
    								    </div>
    								</div>
    							</div>
    							<div class="bid_history_area"></div>

							</div>
							<div class="col-md-6">
							    <div class="smile_box"></div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<div class="modal fade" id="bidHistoryModal" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-lg">
		<div class="modal-content">
		<div class="modal-header">
        <h5 class="modal-title">Bid History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="dt-ext table-responsive" style="max-height: 400px;overflow-y: scroll;">
							<table id="bidHistoryDetails" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>User Name</th>
										<th>Bid TX ID</th>
										<th>Game Type</th>
										<th>Session</th>
										<th>Open Paana</th>
										<th>Open Digit</th>
										<th>Close Paana</th>
										<th>Close Digit</th>
										<th>Points</th>
									</tr>
								</thead>
								<tbody id="bid_result_data">

								</tbody>
							</table>
						</div>
					</div>

				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>

<div class="modal fade" id="totalWinDetailsModal" tabindex="-1" role="dialog" aria-hidden="true" >
  <div class="modal-dialog modal-frame modal-top modal-lg">
	<div class="modal-content">
	<div class="modal-header">
	<h5 class="modal-title">Winning History</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
  </div>
	  <div class="modal-body">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="dt-ext table-responsive" style="max-height: 400px;overflow-y: scroll;">
						<table id="winningHistoryDetails" class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>User Name</th>
									<th>Game Name</th>
									<th>Game Type</th>
									<th>Open Pana</th>
									<th>Open digit</th>
									<th>Close Pana</th>
									<th>Close Digit</th>
									<th>Points</th>
									<th>Amount</th>
									<th>Tx Id</th>
									<th>Tx Date</th>
								</tr>
							</thead>
							<tbody id="bid_winning_data">

							</tbody>
						</table>
					</div>
				</div>

			</div>
		</div>
	  </div>
	</div>
  </div>
</div>

	@section('script')
        {{-- For Getting The Today Date --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date();
                const year = today.getFullYear();
                const month = ('0' + (today.getMonth() + 1)).slice(-2); // Adding 1 because months are 0-indexed
                const day = ('0' + today.getDate()).slice(-2); // Ensure two digits for day

                const formattedDate = `${year}-${month}-${day}`;
                const dateInput = document.getElementById('result_date');

                // Set the max attribute to today's date
                dateInput.setAttribute('max', formattedDate);

                // Set the value to today's date
                dateInput.value = formattedDate;
            });
        </script>

    @endsection

@endsection
