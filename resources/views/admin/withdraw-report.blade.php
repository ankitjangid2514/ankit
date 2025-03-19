@extends('admin.layouts.main')
@section('title')
    Withdraw Report
@endsection
@section('container')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-xl-12 col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header p-t-15 p-b-15">
                                        <h5>Withdraw History Report</h5>
                                    </div>
                                    <div class="card-body">
                                        <form class="theme-form mega-form" id="withdrawReportFrm" name="withdrawReportFrm"
                                            method="post">
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <label>Date</label>
                                                    <div class="date-picker">
                                                        <div class="input-group">
                                                            <input class="form-control digits" type="date"
                                                                value="2024-09-05" name="withdraw_date" id="withdraw_date"
                                                                max="2024-09-05">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary btn-block" id="submitBtn"
                                                        name="submitBtn">Submit</button>
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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Withdraw List</h4>
                                <div class="mt-3" id="withdraw_data_details">
                                    <div class="bs_box bs_box_light_withdraw">
                                        <i class="mdi mdi-wallet mr-1"></i>
                                        <span>Total Withdraw Amount :-</span>
                                        <b><span id="t_withdraw_amt">0</span></b>
                                    </div>

                                    <div class="bs_box bs_box_light_accept">
                                        <i class="mdi mdi-wallet mr-1"></i>
                                        <span>Total Accepted :-</span>
                                        <b><span id="t_accept_reqts">0</span></b>
                                    </div>
                                    <div class="bs_box bs_box_light_reject">
                                        <i class="mdi mdi-wallet mr-1"></i>
                                        <span>Total Rejected :-</span>
                                        <b><span id="t_reject_reqts">0</span></b>
                                    </div>
                                </div>
                                <div class="mt-3 dt-ext table-responsive">
                                    <table id="withdrawList" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>User Name</th>
                                                <th>Mobile</th>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Request No.</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                {{-- <th>View</th> --}}
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="withdraw_data">



                                        </tbody>
                                    </table>
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
                const dateInput = document.getElementById('withdraw_date');

                // Set the max attribute to today's date
                dateInput.setAttribute('max', formattedDate);

                // Set the value to today's date
                dateInput.value = formattedDate;
            });
        </script>

        {{-- For Showing The Data Of Withdrawal List --}}
        <script type="text/javascript">
            $(document).ready(function() {
                // Initialize the DataTable
                var table = $('#withdrawList').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('withdrawal_request_list') }}',
                        type: 'GET',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                            d.withdraw_date = $('#withdraw_date').val(); // Add the selected date
                        }
                    },
                    columns: [
                        { data: 'name' },
                        { data: 'number' },
                        { data: 'amount' },
                        { data: 'payout' },
                        { data: 'id' },
                        { data: 'withdrawal_date' },
                        { data: 'status' },
                        {
                            data: 'status',
                            className: 'text-center',
                            render: function(data, type, row) {
                                let buttons = '';
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

                // Event listener for the submit button
                $('#submit_date').on('click', function() {
                    table.ajax.reload(); // Reload the DataTable with the selected date filter
                });

                // Existing click handlers for approve, reject, and delete buttons...
                $(document).on('click', '.approve-btn', function() {
                    var id = $(this).data('id');
                    if (confirm('Are you sure you want to approve this withdrawal record?')) {
                        $.ajax({
                            url: '{{ route('withdrawal_request_approve') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id
                            },
                            success: function(response) {
                                table.ajax.reload();
                                alert('Withdrawal Request approved successfully.');
                            },
                            error: function() {
                                alert('Error occurred while approving the withdrawal Request.');
                            }
                        });
                    }
                });

                $(document).on('click', '.reject-btn', function() {
                    var id = $(this).data('id');
                    if (confirm('Are you sure you want to reject this withdrawal record?')) {
                        $.ajax({
                            url: '{{ route('withdrawal_request_reject') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id
                            },
                            success: function(response) {
                                table.ajax.reload();
                                alert('Withdrawal Request rejected successfully.');
                            },
                            error: function() {
                                alert('Error occurred while rejecting the withdrawal.');
                            }
                        });
                    }
                });

                $('#withdrawList').on('click', '.delete-btn', function() {
                    var id = $(this).data('id');
                    if (confirm('Are you sure you want to delete this record?')) {
                        $.ajax({
                            url: '{{ route('delete_withdrawal_request') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id
                            },
                            success: function(response) {
                                table.ajax.reload();
                                alert('Record deleted successfully.');
                            },
                            error: function() {
                                alert('Error occurred while deleting the record.');
                            }
                        });
                    }
                });

                $('#submitBtn').on('click', function(event) {
                    event.preventDefault(); // Prevent the default form submission
                    table.ajax.reload(); // Reload the DataTable with the selected date filter
                });

            });
        </script>

    @endsection

@endsection
