@extends('admin.layouts.main')
@section('title')
    Un Approved Users List
@endsection
@section('container')

    <div class="main-content">	<div class="page-content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-sm-12">
                    <div class="card">
                    <div class="card-body">
                    <h4 class="card-title">Un-Approved Users List</h4>
                        <div class="table-responsive">
                        <table class="table table-bordered" id="unApprovedUsersList">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Mobile</th>
                                <th>Date</th>
                                <th>Balance</th>
                                {{-- <th>Betting</th>
                                <th>Transfer</th> --}}
                                <th>Active</th>
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
            var table = $('#unApprovedUsersList').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('un_approved_users_data') }}",
                    type: 'POST',
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
                        data: 'status'
                    },
                {
                    data: 'status', // Specifies the data source for this column
                    className: 'text-center', // Optional: center-align the action column
                    render: function(data, type, row) {
                        let buttons = '';

                    // Check the status of the row and create buttons accordingly
                    if (row.status === 'unapproved') {
                        buttons = `
                            <a href="javascript:void(0);" class="btn btn-success btn-sm approve-btn" data-id="${row.id}">
                                <i class="fa-solid fa-check"></i> Approve
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

        

        $('#withdrawRequestList').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            console.log(id);
            // Implement your delete functionality here
            if (confirm('Are you sure you want to delete this record?')) {
                $.ajax({
                    url: "{{ route('delete_unapprove_users') }}", // Your route to handle delete
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
