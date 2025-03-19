@extends('admin.layouts.main')
@section('title')
Notice Management
@endsection
@section('container')

<div class="main-content">	<div class="page-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
				  <div class="card-body">
				  <h4 class="card-title d-flex align-items-center justify-content-between">Notice Managment				 <a class="btn btn-primary btn-sm btn-float m-b-10" href="#addNoticeModal" role="button" data-toggle="modal">Add Notice</a></h4>
					<div class="">
					  <table id="noticeList" class="table table-striped">
						<thead>
						  <tr>
							<th>#</th>
							<th>Notice Title</th>
							<th>Content</th>
							<th>Notice Date</th>
							<th>Status</th>
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


<div class="modal fade" id="addNoticeModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal_right_side" role="document">
	<div class="modal-content col-12 col-md-5">
	  <div class="modal-header">
		<h5 class="modal-title">Add Notice</h5>
		<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  </div>
	  <div class="modal-body">
		<form class="theme-form" method="post" action="{{url
        ('notice_management_insert')}}">
			@csrf
			<div class="row">
					<div class="form-group col-6">
					<label for="exampleInputEmail1">Notice Title</label>
					<input type="text" name="notice_title" id="notice_title" class="form-control" placeholder="Enter Title"/>
				</div>
				<div class="form-group col-6">
					<label>Notice Date</label>
										<div class="date-picker">
						<div class="input-group">
						  <input class="form-control digits" type="date" value="2024-09-06" name="notice_date" id="notice_date" placeholder="Enter Notice Date">
						</div>
					</div>
				</div>
				<div class="form-group col-12">
					<label>Notice Content</label>
					<textarea class="form-control" name="description" rows="5" id="description"></textarea>
				</div>

				<div class="form-group col-12">
					<button type="submit" class="btn btn-primary waves-light m-t-10" id="submitBtn" name="submitBtn">Submit</button>
					<button type="reset" class="btn btn-danger waves-light m-t-10">Reset</button>
				</div>
			</div>
			<div class="form-group">
				<div id="error"></div>
			</div>
		</form>
	</div>
	</div>
  </div>
</div>
<div class="modal fade" id="editNoticeModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal_right_side" role="document">
	<div class="modal-content col-12 col-md-5">
	  <div class="modal-header">
		<h5 class="modal-title">Edit Notice</h5>
		<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  </div>
	  <div class="modal-body batch_body">
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
                const dateInput = document.getElementById('notice_date');

                // Set the max attribute to today's date
                dateInput.setAttribute('max', formattedDate);

                // Set the value to today's date
                dateInput.value = formattedDate;
            });
        </script>

        {{-- For Showing Notice --}}
    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize the DataTable
            var table = $('#noticeList').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('notice_management_data') }}',
                    type: 'Post',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                        d.result_pik_date = $('#result_pik_date').val(); // Add selected date as filter
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'notice_title' },
                    { data: 'notice_date' },
                    { data: 'description' },
                    { data: 'status' },
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

            $('#noticeList').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                console.log(id);
                // Implement your delete functionality here
                if (confirm('Are you sure you want to delete this record?')) {
                    $.ajax({
                        url: '{{ route('delete_notice') }}', // Your route to handle delete
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


            // Trigger DataTable reload on date change
            $('#result_pik_date').on('change', function() {
                table.ajax.reload(); // Reload DataTable with new date filter
            });
        });
    </script>
    @endsection

@endsection
