@extends('admin.layouts.main')
@section('title')
User Management
@endsection
@section('container')

<div class="main-content">	<div class="page-content">
    <div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="page-title-box d-flex align-items-center justify-content-between">
					<h4 class="mb-0 font-size-18">User List</h4>

					<div class="page-title-right">
						<ol class="breadcrumb m-0">
							<li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
							<li class="breadcrumb-item active">User List</li>
						</ol>
					</div>

				</div>
			</div>
		</div>

		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title d-flex align-items-center justify-content-between">&nbsp; <a href="{{route('un_approved_users_list')}}" class="btn btn-primary waves-effect waves-light btn-sm">Un-approved Users List</a></h4>
					<div class="table-responsive">
                        <table id="userList" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Mobile</th>
                                <th>Date</th>
                                <th>Balance</th>
                                {{-- <th>Betting</th>
                                <th>Transfer</th> --}}
                                <th>Status</th>
                                <th>View</th>
                            </tr>
                            </thead>

                        </table>
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
            const viewUserUrl = "{{ route('view_user', ['user_id' => '__user_id__']) }}";
            // Initialize the DataTable
            var table = $('#userList').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('getUserList') }}",
                    type: 'post',
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
                        data: 'created_at'
                    },
                    {
                        data: 'balance'
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            // Check the status and render the corresponding badge
                            if (data === 'approved') {
                                return '<a class="reject-btn" data-id="' + row.id + '"><span class="badge badge-success">approved</span></a>';
                            } else {
                                return '<a class="approve-btn" data-id="' + row.id + '"><span class="badge badge-danger">Unapproved</span></a>';
                            }
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            // Replace the placeholder with the actual user ID
                            const url = viewUserUrl.replace('__user_id__', row.id);
                            return `<a href="${url}" class="btn btn-info btn-sm view-btn" data-id="${row.id}">View</a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </a>`;
                        }
                    },


                ]
            }); 

            $(document).on('click', '.approve-btn', function() {
            var id = $(this).data('id');
            // Handle approve action
            if (confirm('Are you sure you want to approve this Users record?')) {
                $.ajax({
                    url: "{{ route('unapprove_users_approve') }}", // Your route to handle delete
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        table.ajax.reload(); // Reload the DataTable
                        alert('Users approved successfully.');
                    },
                    error: function() {
                        alert('Error occurred while approve the User.');
                    }
                });
            }
        });

            $(document).on('click', '.reject-btn', function() {
                var id = $(this).data('id');
                // Handle approve action
                if (confirm('Are you sure you want to Unapprove this User?')) {
                    $.ajax({
                        url: "{{ route('unapprove_users_unapprove') }}", // Your route to handle delete
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            table.ajax.reload(); // Reload the DataTable
                            alert('User Unapproved successfully.');
                        },
                        error: function() {
                            alert('Error occurred while Unapprove the User.');
                        }
                    });
                }
            });
            $('#userList').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                console.log(id);
                // Implement your delete functionality here
                if (confirm('Are you sure you want to delete this record?')) {
                    $.ajax({
                        url: "{{ route('delete_user') }}", // Your route to handle delete
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
