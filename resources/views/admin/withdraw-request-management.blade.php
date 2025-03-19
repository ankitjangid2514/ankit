@extends('admin.layouts.main')
    @section('title')
	Withdraw Request Management
	@endsection
    @section('container')

<div class="main-content">	<div class="page-content">
	<div class="container-fluid">
		<div class="row">
		<!-- Zero Configuration  Starts-->
			<div class="col-sm-12">
				<div class="card">
				  <div class="card-body">
				  <h4 class="card-title">Withdraw Request List</h4>
					<div class="table-responsive">
                        {{-- --}}
					  <table class="table table-striped table-bordered " id="withdrawRequestList" >
						<thead>
						  <tr>
							<th>#</th>
							<th>User Name</th>
							<th>Mobile</th>
							<th>Amount</th>
							{{-- <th>Request No.</th> --}}
							<th>Date</th>
							<th>Status</th>
							{{-- <th>View</th> --}}
							<th>Action</th>
						  </tr>
						</thead>

						</table>
					</div>
						<div id="msg"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

    @section('script')

        <script type="text/javascript">

                    $(document).ready(function() {
                        // Initialize the DataTable
                        var table = $('#withdrawRequestList').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '{{ route('withdrawal_request_list') }}',
                                type: 'GET',
                                data: function(d) {
                                    d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                                }
                            },
                            columns: [{
                                    data: 'id'
                                },
                                {
                                    data: 'name'
                                },
                                {
                                    data: 'number'
                                },
                                {
                                    data: 'amount'
                                },
                                // {
                                //     data: N/A
                                // },
                                {
                                    data: 'withdrawal_date'
                                },
                                {
                                    data: 'status'
                                },
                            {
                                data: 'status', // Specifies the data source for this column
                                className: 'text-center', // Optional: center-align the action column
                                render: function(data, type, row) {
                                    let buttons = '';

                                // Check the status of the row and create buttons accordingly
                                if (row.status === 'pending') {
                                    buttons = `
                                        <a href="javascript:void(0);" class="btn btn-success btn-sm approve-btn" data-id="${row.id}">
                                            <i class="fa-solid fa-check"></i> Approve
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm reject-btn" data-id="${row.id}">
                                            <i class="fa-solid fa-ban"></i> Reject
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </a>
                                `;
                                } else if (row.status === 'rejected') {
                                    buttons = `
                                        <a href="javascript:void(0);" class="btn btn-success btn-sm approve-btn" data-id="${row.id}">
                                            <i class="fa-solid fa-check"></i> Approve
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </a>
                                    `;
                                } else if (row.status === 'approved') {
                                    buttons = `
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm reject-btn" data-id="${row.id}">
                                            <i class="fa-solid fa-ban"></i> Reject
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </a>
                                    `;
                                } else {
                                    buttons = `<span>No Actions Available</span>`;
                                }

                                return buttons;
                                }
                            }


                            ]
                        });

                    $(document).on('click', '.approve-btn', function() {
                        var id = $(this).data('id');
                        // Handle approve action
                        if (confirm('Are you sure you want to approve this withdrawal record?')) {
                            $.ajax({
                                url: '{{ route('withdrawal_request_approve') }}', // Your route to handle delete
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    id: id
                                },
                                success: function(response) {
                                    table.ajax.reload(); // Reload the DataTable
                                    alert('Withdrawal Request approved successfully.');
                                },
                                error: function() {
                                    alert('Error occurred while approve the withdrawal Request.');
                                }
                            });
                        }
                    });

                    $(document).on('click', '.reject-btn', function() {
                        var id = $(this).data('id');
                        // Handle approve action
                        if (confirm('Are you sure you want to reject this withdrawal record?')) {
                            $.ajax({
                                url: '{{ route('withdrawal_request_reject') }}', // Your route to handle delete
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    id: id
                                },
                                success: function(response) {
                                    table.ajax.reload(); // Reload the DataTable
                                    alert('Withdrawal Request rejected successfully.');
                                },
                                error: function() {
                                    alert('Error occurred while reject the withdrawal.');
                                }
                            });
                        }
                    });

                    $('#withdrawRequestList').on('click', '.delete-btn', function() {
                        var id = $(this).data('id');
                        console.log(id);
                        // Implement your delete functionality here
                        if (confirm('Are you sure you want to delete this record?')) {
                            $.ajax({
                                url: '{{ route('delete_withdrawal_request') }}', // Your route to handle delete
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    id: id
                                },
                                success: function(response) {
                                    table.ajax.reload(); // Reload the DataTable
                                    alert('Record deleted successfully.');
                                },
                                error: function() {
                                    alert('Error occurred while deleting the record.');
                                }
                            });
                        }
                    });

                });
        </script>

    @endsection

@endsection
