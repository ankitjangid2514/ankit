@extends('admin.layouts.main')
@section('title')
    View User
@endsection
@section('container')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0 font-size-18">User Details</h4>
                            <input type="text" id="user_id" name="user_id" hidden value="{{ $user_data->id }}">
                            {{-- <input type="text" id="user_id" name="user_id" hidden value="{{ auth()->id() }}"> --}}


                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">User Management</a></li>
                                    <li class="breadcrumb-item active">User Details</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row row_col">
                    <div class="col-xl-4">
                        <div class="card overflow-hidden h100p">
                            <div class="bg-soft-primary">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-3">
                                            <h5 class="text-primary">{{ $user_data->name }}</h5>
                                            <p>{{ $user_data->number }} <a href="tel:918762313954"><i
                                                        class="mdi mdi-cellphone-iphone"></i></a>
                                                <a href="https://wa.me/{{ $user_data->number }}" target="blank"><i
                                                        class="mdi mdi-whatsapp"></i></a>
                                            </p>
                                            <h5 class="text-primary">Passsword : {{ $user_data->duplicate_password }}</h5>

                                        </div>
                                    </div>
                                    <div class="col-5 align-center">
                                        <div class="p-3 text-right">
                                            <div class="mb-2">
                                                Active:
                                                <a role="button" class="activeDeactiveStatus"
                                                    id="success-572-tb_user-user_id-status"><span
                                                        class="badge badge-pill badge-success font-size-12">Yes</span></a>
                                            </div>
                                            <div class="mb-2">
                                                Betting:
                                                <a role="button" class="activeDeactiveStatus"
                                                    id="success-572-tb_user-user_id-betting_status"><span
                                                        class="badge badge-pill badge-success font-size-12">Yes</span></a>
                                            </div>

                                            <div class="mb-2">
                                                TP:
                                                <a role="button" class="activeDeactiveStatus"
                                                    id="success-572-tb_user-user_id-transfer_point_status"><span
                                                        class="badge badge-pill badge-success font-size-12">Yes</span></a>
                                            </div>

                                            <div class="mb-2">
                                                Logout Status:
                                                <a role="button" onClick="changeLogoutStatus(572);"><span
                                                        class="badge badge-pill badge-success font-size-12">Logout
                                                        Now</span></a>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="card-body pt-0">
                          <div class="row">
                           <div class="col-sm-4">
                            <div class="avatar-md profile-user-wid mb-4">
                             <img src="{{ url('adminassets/images/user.png') }}" alt="" class="img-thumbnail rounded-circle">
                            </div>

                           </div>

                           <div class="col-sm-8">
                            <div class="pt-4">

                             <div class="row">
                              <div class="col-6">
                               <p class="text-muted mb-0">Security Pin</p>
                               <h5 class="font-size-15 mb-0">7866</h5>
                              </div>
                              <div class="col-6">
                               <button class="btn btn-primary btn-sm" id="changePin">Change</button>
                              </div>
                             </div>

                            </div>
                           </div>
                          </div>
                         </div> -->
                            <div class="card-body border-top">

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div>
                                            <p class="text-muted mb-2">Available Balance</p>
                                            <h5>{{ $user_data->balance }}</h5>
                                        </div>

                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mt-3">
                                            <button class="btn btn-success btn-sm w-md btn-block" id="adFund">Add
                                                Fund</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mt-3">
                                            <button class="btn btn-danger btn-sm w-md btn-block" id="withdrawFund">Withdraw
                                                Fund</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="col-xl-8">
                        <div class="card h100p">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Personal Information</h4>
                                <div class="table-responsive">
                                    <table class="table table-nowrap mb-0">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Full Name :</th>
                                                <td>{{ $user_data->name }}</td>

                                                <th scope="row">Mobile :</th>
                                                <td>{{ $user_data->number }} <a href="tel:{{ $user_data->number }}"><i
                                                            class="mdi mdi-cellphone-iphone"></i></a>
                                                    <a href="https://wa.me/91{{ $user_data->number }}" target="blank"><i
                                                            class="mdi mdi-whatsapp"></i></a>

                                                </td>

                                            </tr>
                                            <tr>
                                                <th scope="row">Total Winning :</th>
                                                <td>{{ $total_winning }}</td>

                                                <th scope="row">Total Withdrawal :</th>
                                                <td>{{ $total_withdrawal }}</td>

                                            </tr>
                                            <tr>
                                                <th scope="row">District Name :</th>
                                                <td>N/A</td>
                                                <th scope="row">Flat/Plot No. :</th>
                                                <td>N/A</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Address Lane 1 :</th>
                                                <td>N/A</td>
                                                <th scope="row">Address Lane 2 :</th>
                                                <td>N/A</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Area :</th>
                                                <td>N/A</td>
                                                <th scope="row">Pin Code :</th>
                                                <td>N/A</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">State Name :</th>
                                                <td>N/A</td>
                                                <th scope="row"></th>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Creation Date :</th>
                                                <td>{{ $user_data->created_at }}</td>
                                                <th scope="row">Last Seen :</th>
                                                <td>31 Aug 2024 12:01:05 AM</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Payment Information</h4>
                                <div class="table-responsive">
                                    <table class="table table-nowrap mb-0">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Bank Name :</th>
                                                <td>N/A</td>
                                                <th scope="row">Branch Address :</th>
                                                <td>N/A</td>
                                                <th scope="row"></th>
                                                <td></td>

                                            </tr>
                                            <tr>
                                                <th scope="row">A/c Holder Name :</th>
                                                <td>N/A</td>
                                                <th scope="row">A/c Number :</th>
                                                <td>N/A</td>
                                                <th scope="row">IFSC Code :</th>
                                                <td>N/A</td>

                                            </tr>
                                            <tr>
                                                <th scope="row">PhonePe No. :</th>
                                                <td>N/A</td>
                                                <th scope="row">Google Pay No. :</th>
                                                <td>N/A</td>
                                                <th scope="row">Paytm No. :</th>
                                                <td>N/A</td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add Fund Request List</h4>
                                <div class="dt-ext table-responsive demo-gallery">
                                    <table class="table table-striped table-bordered " id="fundRequestList">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Request Amount</th>
                                                <th>Request No.</th>
                                                <th>Receipt Image</th>
                                                <th>Date</th>
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

            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Withdraw Request List</h4>
                                <div class="table-responsive">
                                    {{-- --}}
                                    <table class="table table-striped table-bordered " id="withdrawRequestList">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Request Amount </th>
                                                <th>Request No.</th>
                                                <th>Request Date</th>
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
            <input type="hidden" id="user_id" value="572">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Bid History</h4>
                                <!-- Bid History Table -->
                                <div class="dt-ext table-responsive">
                                    <table class="table table-striped table-bordered" id="bid_data">
                                        <thead>
                                            <tr>
                                                <th>Game Name</th>
                                                <th>Game Type</th>
                                                <th>Session</th>
                                                <th>Points</th>
                                                <th>Amount</th>
                                                <th>Bid Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data populated by DataTables -->
                                        </tbody>
                                    </table>
                                </div>

                                <div id="msg"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-xl-12 xl-100">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Wallet Transaction History</h4>
                                <ul class="nav nav-tabs nav-tabs-custom nav-justified" id="top-tab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" id="top-allr-tab" data-toggle="tab"
                                            href="#top-allr" role="tab" aria-controls="top-allr"
                                            aria-selected="true">All</a></li>
                                    <li class="nav-item"><a class="nav-link" id="inr-top-tab" data-toggle="tab"
                                            href="#top-inr" role="tab" aria-controls="top-inr"
                                            aria-selected="false">Credit</a></li>
                                    <li class="nav-item"><a class="nav-link" id="outr-top-tab" data-toggle="tab"
                                            href="#top-outr" role="tab" aria-controls="top-outr"
                                            aria-selected="false">Debit</a></li>
                                </ul>
                                <div class="tab-content p-3" id="top-tabContent">
                                    <div class="tab-pane fade show active" id="top-allr" role="tabpanel"
                                        aria-labelledby="top-allr-tab">
                                        <div class="">
                                            <table id="allTransactionTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Amount</th>
                                                        <th>Date</th>
                                                        <th>Tx Req. No.</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="top-inr" role="tabpanel"
                                        aria-labelledby="inr-top-tab">
                                        <div class="">
                                            <table id="inTransactionTable" class="table table-striped table-bordered"
                                                style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Amount</th>
                                                        <th>Date</th>
                                                        <th>Tx Req. No.</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="top-outr" role="tabpanel"
                                        aria-labelledby="outr-top-tab">
                                        <div class="">
                                            <table id="outTransactionTable" class="table table-striped table-bordered"
                                                style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Amount</th>
                                                        <th>Date</th>
                                                        <th>Tx Req. No.</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-xl-12 col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Winning History Report</h4>
                                        <form class="theme-form mega-form" id="userWinningHistoryFrm"
                                            name="userWinningHistoryFrm" method="post">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label>Date</label>
                                                    <div class="date-picker">
                                                        <div class="input-group">
                                                            <input class="form-control digits" type="date"
                                                                value="2024-09-05" name="result_date" id="result_date">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary waves-light btn-block"
                                                        name="submitBtn_2">Submit</button>
                                                </div>
                                            </div>
                                            <!-- <div class="form-group">
                               <div id="error_msg"></div>
                              </div> -->
                                        </form>

                                        <table id="resultHistory" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Amount(&#x20b9)</th>
                                                    <th>Game Name</th>
                                                    <th>Tx Id</th>
                                                    <th>Tx Date</th>
                                                </tr>
                                            </thead>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>




        <div class="modal fade" id="bettingAllowedModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-frame modal-top modal-md">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>Are you sure you want to allowed betting for this user.</p>
                                </div>
                                <div class="col-md-12">
                                    <form class="theme-form" id="bettingAllowedFrm" method="post"
                                        enctype="multipart/formdata">
                                        <input type="hidden" name="user_id" id="user_id" value="572">
                                        <div class="form-group">
                                            <button class="btn btn-danger waves-effect waves-light"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-info waves-effect waves-light"
                                                id="submitBtn">Yes</button>
                                        </div>
                                    </form>
                                    <div class="form-group m-b-0">
                                        <div id="alert"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="addFundModel" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Fund</h5><button type="button" class="close"
                            data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form class="theme-form" method="post" enctype="multipart/formdata"
                            action="{{ route('add_fund') }}">
                            @csrf
                            <div class="form-group">
                                <label class="col-form-label">Amount</label>
                                <input class="form-control" type="Number" min="0" name="user_amount"
                                    id="user_amount" placeholder="Enter Amount" data-original-title="" title="">
                            </div>
                            <input type="hidden" name="user_id" id="user_id" value="{{ $id }}">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-sm m-t-10" id="submitAddBtn"
                                    name="submitBtn">Submit</button>
                            </div>
                            <div class="form-group m-b-0">
                                <div id="alert_msg"></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

        <div id="changePinModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Pin</h5><button type="button" class="close"
                            data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form class="theme-form" id="changePinFrm" method="post" enctype="multipart/formdata">

                            <div class="form-group">
                                <label class="col-form-label">Enter New Pin</label>
                                <input class="form-control digit_number" type="number" name="security_pin"
                                    id="security_pin" placeholder="Enter Security Pin" min="0" max="9999"
                                    maxlength="4">
                            </div>
                            <input type="hidden" name="user_id" id="user_id" value="572">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-sm m-t-10" id="submitchangepinBtn"
                                    name="submitchangepinBtn">Submit</button>
                            </div>
                            <div class="form-group m-b-0">
                                <div id="alert_msg"></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>




        <div id="withdrawFundModel" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Withdraw Fund</h5><button type="button" class="close"
                            data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form class="theme-form" method="post" enctype="multipart/formdata"
                            action="{{ route('withdraw_fund') }}">
                            @csrf
                            <div class="form-group">
                                <label class="col-form-label">Amount</label>
                                <input class="form-control" type="Number" min="0" name="amount" id="amount"
                                    placeholder="Enter Amount" data-original-title="" title="">
                            </div>
                            <input type="hidden" name="user_id" id="user_id" value="{{ $id }}">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-sm m-t-10" id="submitWithdrawBtn"
                                    name="submitBtn">Submit</button>
                            </div>
                            <div class="form-group m-b-0">
                                <div id="with_error_msg"></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>



        <div id="viewWithdrawRequest" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">Withdraw Request Detail</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body viewWithdrawRequestBody">

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
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


        {{-- For Getting The Add Fund Request List --}}
        <script type="text/javascript">
            $(document).ready(function() {
                // Initialize the DataTable
                var table = $('#fundRequestList').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('fund_request_management_list') }}",
                        type: 'GET',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                            d.user_id =
                                '{{ $id }}'; // Pass the user_id for the specific user (e.g., passed from the backend)
                        }
                    },
                    columns: [{
                            data: 'user_id'
                        },
                        {
                            data: 'deposit_amount'
                        },
                        {
                            data: 'id'
                        },
                        {
                            data: 'upload_qr',
                            render: function(data, type, row) {
                                return '<img src="' + data +
                                    '" alt="Payment Screenshot" style="width:50px;height:50px;">';
                            }
                        },
                        {
                            data: 'deposite_date'
                        },
                        {
                            data: 'deposite_status'
                        },
                        {
                            data: 'deposite_status', // Action column
                            className: 'text-center',
                            render: function(data, type, row) {
                                let buttons = '';

                                // Create action buttons based on the status of the deposit
                                if (row.deposite_status === 'pending') {
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
                                } else if (row.deposite_status === 'rejected') {
                                    buttons = `
                            <a href="javascript:void(0);" class="btn btn-success btn-sm approve-btn" data-id="${row.id}">
                                <i class="fa-solid fa-check"></i> Approve
                            </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                <i class="fa-solid fa-trash"></i> Delete
                            </a>
                        `;
                                } else if (row.deposite_status === 'approved') {
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

                // Approve, Reject, and Delete handlers
                $(document).on('click', '.approve-btn', function() {
                    var id = $(this).data('id');
                    if (confirm('Are you sure you want to approve this deposit request?')) {
                        $.ajax({
                            url: "{{ route('deposite_request_approve') }}",
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id
                            },
                            success: function(response) {
                                table.ajax.reload();
                                alert('Deposit Request approved successfully.');
                            },
                            error: function() {
                                alert('Error occurred while approving the deposit request.');
                            }
                        });
                    }
                });

                $(document).on('click', '.reject-btn', function() {
                    var id = $(this).data('id');
                    if (confirm('Are you sure you want to reject this deposit request?')) {
                        $.ajax({
                            url: "{{ route('deposite_request_reject') }}",
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id
                            },
                            success: function(response) {
                                table.ajax.reload();
                                alert('Deposit Request rejected successfully.');
                            },
                            error: function() {
                                alert('Error occurred while rejecting the deposit.');
                            }
                        });
                    }
                });

                $('#fundRequestList').on('click', '.delete-btn', function() {
                    var id = $(this).data('id');
                    if (confirm('Are you sure you want to delete this record?')) {
                        $.ajax({
                            url: "{{ route('delete_deposite_request') }}",
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
            });
        </script>

        <script>
            $(document).ready(function() {
                var table = $('#bid_data').DataTable({
                    processing: true,
                    serverSide: true, // Important for server-side processing
                    ajax: {
                        url: '{{ route('history_bid_user') }}', // Ensure this route points to the correct controller action
                        type: 'GET',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token
                            d.bid_date = $('#bid_date').val(); // Get selected bid date
                            d.user_id = $('#user_id').val(); // Get user ID
                            d.game_name = $('#game_name').val(); // Get selected game name
                            d.game_type = $('#game_type').val(); // Get selected game type
                        },
                        dataSrc: function(json) {
                            // Ensure the server is returning the expected data format
                            if (json.error) {
                                console.log('Error:', json.error);
                                return [];
                            }
                            return json.data; // Make sure data is returned as 'data'
                        }
                    },
                    columns: [{
                            data: 'market_name'
                        },
                        {
                            data: 'gtype'
                        },
                        {
                            data: 'session',
                            render: function(data, type, row) {
                                // Check if session is null, "N/A", or "na" and style accordingly
                                if (data === null || data.toLowerCase() === 'na' || data === 'N/A') {
                                    return '<span style="color: red;">N/A</span>';
                                }
                                return data; // Return the original session value if it's valid
                            }
                        },

                        {
                            data: 'bid_point'
                        },
                        {
                            data: 'amount'
                        },
                        {
                            data: 'bid_date'
                        }
                    ],
                    error: function(xhr, error, code) {
                        console.error("Error loading data:", xhr.responseText);
                        alert("Error loading data. Please check the console for more details.");
                    },
                    pageLength: 5, // Default number of records per page
                    lengthMenu: [5, 10, 25, 50] // Option to select the number of records per page
                });
            });
        </script>

        {{-- For Getting the Withdraw Request List --}}
        <script type="text/javascript">
            $(document).ready(function() {
                // Initialize the DataTable
                var table = $('#withdrawRequestList').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('withdrawal_request_list') }}", // The route to the controller method
                        type: 'GET',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                            d.user_id = $('#user_id')
                                .val(); // Get the selected user ID from the hidden field
                        }
                    },
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'amount'
                        },
                        {
                            data: 'id'
                        },
                        {
                            data: 'withdrawal_date'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: 'status',
                            className: 'text-center',
                            render: function(data, type, row) {
                                let buttons = '';
                                // Check the status of the row and create buttons accordingly
                                if (row.status === 'pending') {
                                    buttons = `
                            <a href="javascript:void(0);" class="btn btn-success btn-sm withdraw-approve-btn" data-id="${row.id}">
                                <i class="fa-solid fa-check"></i> Approve
                            </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm withdraw-reject-btn" data-id="${row.id}">
                                <i class="fa-solid fa-ban"></i> Reject
                            </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm withdraw-delete-btn" data-id="${row.id}">
                                <i class="fa-solid fa-trash"></i> Delete
                            </a>
                        `;
                                } else if (row.status === 'rejected') {
                                    buttons = `
                            <a href="javascript:void(0);" class="btn btn-success btn-sm withdraw-approve-btn" data-id="${row.id}">
                                <i class="fa-solid fa-check"></i> Approve
                            </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm withdraw-delete-btn" data-id="${row.id}">
                                <i class="fa-solid fa-trash"></i> Delete
                            </a>
                        `;
                                } else if (row.status === 'approved') {
                                    buttons = `
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm withdraw-reject-btn" data-id="${row.id}">
                                <i class="fa-solid fa-ban"></i> Reject
                            </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm withdraw-delete-btn" data-id="${row.id}">
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

                // Handle actions such as approve, reject, and delete
                $(document).on('click', '.withdraw-approve-btn', function() {
                    var id = $(this).data('id');
                    // Handle approve action
                    if (confirm('Are you sure you want to approve this withdrawal record?')) {
                        $.ajax({
                            url: "{{ route('withdrawal_request_approve') }}", // Your route to handle approve
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
                                alert('Error occurred while approving the withdrawal request.');
                            }
                        });
                    }
                });

                // Handle reject action
                $(document).on('click', '.withdraw-reject-btn', function() {
                    var id = $(this).data('id');
                    if (confirm('Are you sure you want to reject this withdrawal record?')) {
                        $.ajax({
                            url: "{{ route('withdrawal_request_reject') }}", // Your route to handle reject
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
                                alert('Error occurred while rejecting the withdrawal.');
                            }
                        });
                    }
                });

                // Handle delete action
                $('#withdrawRequestList').on('click', '.withdraw-delete-btn', function() {
                    var id = $(this).data('id');
                    if (confirm('Are you sure you want to delete this record?')) {
                        $.ajax({
                            url: "{{ route('delete_withdrawal_request') }}", // Your route to handle delete
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

        {{-- For Showing Bid History --}}
        <!-- <script type="text/javascript">
            $(document).ready(function() {
                // Initialize the DataTable
                var table = $('#bid_data').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('bid_history') }}",
                        type: 'get',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                            d.bid_date = $('#bid_date').val(); // Get the selected date
                            d.game_name = $('#game_name').val(); // Get selected game name
                            d.game_type = $('#game_type').val(); // Get selected game type
                        }
                    },
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'market_name'
                        },
                        {
                            data: 'gtype'
                        },
                        {
                            data: 'session',
                            render: function(data) {
                                return data ? data : '<span style="color: red;">N/A</span>';
                            }
                        },
                        {
                            data: 'open_panna',
                            render: function(data) {
                                return data ? data : '<span style="color: red;">N/A</span>';
                            }
                        },
                        {
                            data: 'open_digit',
                            render: function(data) {
                                return data ? data : '<span style="color: red;">N/A</span>';
                            }
                        },
                        {
                            data: 'close_panna',
                            render: function(data) {
                                return data ? data : '<span style="color: red;">N/A</span>';
                            }
                        },
                        {
                            data: 'close_digit',
                            render: function(data) {
                                return data ? data : '<span style="color: red;">N/A</span>';
                            }
                        },
                        {
                            data: 'amount'
                        },
                        {
                            data: 'bid_date'
                        },

                    ]
                });

                // Trigger DataTable reload on filter change
                $('#submitBtn').on('click', function(e) {
                    e.preventDefault(); // Prevent the form from submitting the traditional way

                    // Clear previous error messages
                    $('#error_msg').text('').removeClass('text-danger').hide(); // Clear and hide initially

                    // Get selected values
                    var gameName = $('#game_name').val();
                    var gameType = $('#game_type').val();

                    // Validation
                    if (!gameName && !gameType) {
                        // Show error message if both are not selected
                        $('#error_msg').text('Error: Please select both game name and game type.').addClass(
                            'text-danger').fadeIn(300);
                        setTimeout(function() {
                            $('#error_msg').fadeOut(300); // Hide after 3 seconds
                        }, 3000);
                        return; // Exit if validation fails
                    } else if (!gameName) {
                        // Show error message if Game Name is not selected
                        $('#error_msg').text('Error: Please select game name.').addClass('text-danger').fadeIn(
                            300);
                        setTimeout(function() {
                            $('#error_msg').fadeOut(300); // Hide after 3 seconds
                        }, 3000);
                        return; // Exit if validation fails
                    } else if (!gameType) {
                        // Show error message if Game Type is not selected
                        $('#error_msg').text('Error: Please select game type.').addClass('text-danger').fadeIn(
                            300);
                        setTimeout(function() {
                            $('#error_msg').fadeOut(300); // Hide after 3 seconds
                        }, 3000);
                        return; // Exit if validation fails
                    }

                    // Reload DataTable if validation passes
                    table.ajax.reload();
                });

            });
        </script> -->

        <!-- For Showing All approved Transaction History -->
        <script type="text/javascript">
            $(document).ready(function() {
                // Initialize the DataTable
                var table = $('#allTransactionTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('approved_transiction') }}",
                            type: 'get',
                            data: function(d) {
                                d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                                d.user_id = $('#user_id')
                                    .val(); // Get the selected user ID from the hidden field
                            }
                        },
                        columns: [
                            {
                            data: null, // No data from the server for this column
                            render: function(data, type, row, meta) {
                                return meta.row + 1; // Generate serial number (1, 2, 3, ...)
                            }
                        },
                        {
                            data: 'amount',

                            render: function(data, type, row) {
                                return row.type === 'deposite' ? data : data;
                            }
                        },
                        {
                            data: 'date',

                            render: function(data, type, row) {
                                // Conditionally display 'Deposit Date' or 'Withdrawal Date' based on type
                                return row.type === 'deposit' ? data : data;
                            }
                        },
                        {
                            data: 'type',

                            render: function(data) {
                                // Display 'Deposit' or 'Withdrawal' based on the type value
                                return data === 'deposite' ? 'Deposite' : 'Withdrawal';
                            }
                        }
                    ]
                });

            // Reload DataTable on specific action/event
            // For example, on button click to refresh data
            $('#refreshButton').click(function() {
                table.ajax.reload();
            });
            });
        </script>

        <!-- For Showing All approved Withdrawal History -->
        <script type="text/javascript">
            $(document).ready(function() {
                var table = $('#outTransactionTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('approved_debit') }}",
                        type: 'get',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                            d.user_id = $('#user_id')
                                .val(); // Get the selected user ID from the hidden field
                        }
                    },
                    columns: [{
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            },
                        },
                        {
                            data: 'amount'
                        },
                        {
                            data: 'withdrawal_date'
                        },
                        {
                            data: 'id'
                        }
                    ]
                });

                $('#refreshButton').click(function() {
                    table.ajax.reload();
                });
            });
        </script>

        <!-- For Showing All approved Deposite History -->
        <script type="text/javascript">
            $(document).ready(function() {
                var table = $('#inTransactionTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('approved_credit') }}",
                        type: 'get',

                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                            d.user_id = $('#user_id')
                                .val(); // Get the selected user ID from the hidden field
                        }
                    },
                    columns: [{
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            },
                        },
                        {
                            data: 'deposit_amount'
                        },
                        {
                            data: 'deposite_date'
                        },
                        {
                            data: 'id'
                        }
                    ]
                });

                $('#refreshButton').click(function() {
                    table.ajax.reload();
                });
            });
        </script>




        {{-- For Showing Winning History --}}
        <script type="text/javascript">
            $(document).ready(function() {
                // Initialize the DataTable
                var table = $('#resultHistory').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('winning_report_list') }}", // Ensure this route is correct
                        type: 'get',
                        data: function(d) {
                            // Include CSRF token for security
                            d._token = '{{ csrf_token() }}';
                            d.user_id = $('#user_id')
                                .val(); // Get user_id from hidden input or any other source
                            d.result_date = $('#result_date').val(); // Get the result_date filter
                            // You can also add other filters if necessary
                        }
                    },
                    columns: [{
                            data: 'winning_amount'
                        },
                        {
                            data: 'market_name'
                        },
                        {
                            data: 'bid_id'
                        },
                        {
                            data: 'created_at'
                        }
                    ],
                    order: [
                        [3, 'desc']
                    ], // Sort by created_at (descending) by default
                });

                // Reload the DataTable when the form is submitted
                $('#userWinningHistoryFrm').on('submit', function(e) {
                    e.preventDefault(); // Prevent the form from submitting normally
                    table.draw(); // Reload the DataTable with new filter data
                });
            });
        </script>
    @endsection


@endsection
