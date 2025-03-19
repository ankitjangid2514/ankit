@extends('admin.layouts.main')
@section('title', 'Auto Deposite History')


@section('container')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-xl-12 col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Auto Deposit</h4>
                                        <form class="theme-form mega-form" id="autoDepositeFrm" name="autoDepositeFrm"
                                            method="post" autocomplete="off">
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <label>Date</label>
                                                    <div class="date-picker">
                                                        <div class="input-group">
                                                            <input class="form-control digits" type="date"
                                                                name="bid_revert_date" id="bid_revert_date" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary btn-block" id="submitBtn"
                                                        name="submitBtn">
                                                        Submit
                                                    </button>
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
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Auto Deposit History</h4>
                                <div class="mt-3">
                                    <table class="table table-striped table-bordered" id="autoDepositeTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User Name</th>
                                                <th>Amount</th>
                                                <th>Method</th>
                                                <th>UPI</th>
                                                <th>Txn Request No.</th>
                                                <th>Txn ID</th>
                                                <th>Txn Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="auto_deposite_data_1">
                                            <!-- Dynamic rows will be added here -->
                                        </tbody>
                                    </table>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> ©Matka.
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-right d-none d-sm-block">

                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    </div>


    <input type="hidden" id="base_url" value="https://realratanmatka.org/">
    <input type="hidden" id="admin" value="realratan-admin">

    <div id="snackbar"></div>
    <div id="snackbar-info"></div>
    <div id="snackbar-error"></div>
    <div id="snackbar-success"></div>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-top modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8 text-right">
                                <p>Are you sure you want to logout? If you logout then your session is terminated.</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <button class="btn btn-info waves-effect waves-light" data-dismiss="modal">Cancel</button>
                                <a href="logout.php" class="btn btn-info waves-effect waves-light">Logout</a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmOpenResutlt" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-top modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Are you sure you want to delete this result?</p>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
                                <input type="hidden" name="delete_game_id" id="delete_game_id" value="">
                                <button onclick="OpenDeleteResultData();" id="openDecBtn1"
                                    class="btn btn-success waves-effect waves-light">Yes</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="deleteConfirmOpenStarlineResutlt" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-top modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Are you sure you want to delete this result?</p>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
                                <input type="hidden" name="delete_starline_game_id" id="delete_starline_game_id">
                                <button onclick="OpenDeleteStarlineResultData();" id="openDecBtn1"
                                    class="btn btn-success waves-effect waves-light">Yes</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="deleteConfirmOpenGalidisswarResutlt" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-top modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Are you sure you want to delete this result?</p>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
                                <input type="hidden" name="delete_gali_game_id" id="delete_gali_game_id">
                                <button onclick="OpenDeleteGalidisswarResultData();" id="openDecBtn1"
                                    class="btn btn-success waves-effect waves-light">Yes</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="deleteConfirmCloseResutlt" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-top modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Are you sure you want to delete this result?</p>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="delete_close_game_id" id="delete_close_game_id"
                                    value="">
                                <button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
                                <button onclick="closeDeleteResultData();" id="closeDecBtn1"
                                    class="btn btn-success waves-effect waves-light">Yes</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="fundRequestAcceptModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-top modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Are you sure you want to accept this fund request?</p>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
                                <button onclick="accept_request(this.value)" id="accept_request_id"
                                    class="btn btn-success waves-effect waves-light">Yes</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="winnerListModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-top modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Winner List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Total Bid Amount : <b><span id="total_bid">n</span></b></h5>
                                <h5>Total Winning Amount : <b><span id="total_winneing_amt">j</span></b></h5>

                                <div class="dt-ext table-responsive" style="max-height: 400px;overflow-y: scroll;">

                                    <table class="table table-striped table-bordered">

                                        <thead>

                                            <tr>

                                                <th>#</th>
                                                <th>User Name</th>
                                                <th>Bid Points</th>
                                                <th>Winning Amount</th>
                                                <th>Type</th>
                                                <th>Bid TX ID</th>

                                            </tr>

                                        </thead>

                                        <tbody id="winner_result_data">

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

    <div class="modal fade" id="fundRequestRejectModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-top modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Are you sure you want to reject this fund request?</p>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
                                <button onclick="reject_request(this.value)" id="reject_request_id"
                                    class="btn btn-danger waves-effect waves-light">Yes</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="fundRequestAutoRejectModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-top modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Are you sure you want to reject this fund request?</p>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Remark</label>
                                <input type="text" name="reject_auto_remark" id="reject_auto_remark"
                                    class="form-control" placeholder="Enter Remark" />
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
                                <button onclick="reject_auto_request(this.value)" id="reject_auto_request_id"
                                    class="btn btn-danger waves-effect waves-light">Yes</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="autoDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-top modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Are you sure you want to delete this fund request?</p>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
                                <button onclick="delete_auto_request(this.value)" id="delete_auto_id"
                                    class="btn btn-danger waves-effect waves-light">Yes</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fundRequestAutoAcceptModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-top modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Are you sure you want to accept this fund request?</p>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
                                <button onclick="accept_auto_request(this.value)" id="accept_auto_request_id"
                                    class="btn btn-success waves-effect waves-light">Yes</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="autoDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-frame modal-top modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Are you sure you want to delete this fund request?</p>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
                                <button onclick="delete_auto_request(this.value)" id="delete_auto_id"
                                    class="btn btn-danger waves-effect waves-light">Yes</button>

                            </div>
                        </div>
                    </div>
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


    <div id="requestApproveModel" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Withdraw Request</h5><button type="button" class="close"
                        data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="theme-form" id="withdrawapproveFrm" method="post" enctype="multipart/formdata">
                        <div class="form-group user_info">

                        </div>
                        <div class="form-group">
                            <label for="">Payment Receipt Image<span class="Img_ext">(Allow
                                    Only.jpeg,.jpg,.png)</span></label>
                            <input class="form-control" name="file" id="file" type="file"
                                onchange="return validateImageExtensionOther(this.value,1)" />
                        </div>
                        <div class="form-group">
                            <label>Remark</label>
                            <input type="text" name="remark" id="remark" class="form-control"
                                placeholder="Enter Remark" />
                        </div>
                        <input type="hidden" name="withdraw_req_id" id="withdraw_req_id" value="">
                        <div class="form-group">
                            <button type="submit" class="btn btn-info btn-sm m-t-10" id="submitBtn"
                                name="submitBtn">Submit</button>
                        </div>
                        <div class="form-group m-b-0">
                            <div id="alert_msg_manager"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>


    <div id="requestRejectModel" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Withdraw Request</h5><button type="button" class="close"
                        data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="theme-form" id="withdrawRejectFrm" method="post" enctype="multipart/formdata">
                        <div class="form-group">
                            <label>Remark</label>
                            <input type="text" name="remark" id="remark" class="form-control"
                                placeholder="Enter Remark" />
                        </div>
                        <input type="hidden" name="withdraw_req_id" id="r_withdraw_req_id" value="">
                        <div class="form-group">
                            <button type="submit" class="btn btn-info btn-sm m-t-10" id="submitBtnReject"
                                name="submitBtnReject">Submit</button>
                        </div>
                        <div class="form-group m-b-0">
                            <div id="alert_msg"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade " id="open-img-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" style="text-align:right" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <img class="my_image" />
                </div>
            </div>
        </div>
    </div>

    <script src="adminassets/libs/jquery/jquery.min.js"></script>
    <script src="adminassets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="adminassets/libs/metismenu/metisMenu.min.js"></script>
    <script src="adminassets/libs/simplebar/simplebar.min.js"></script>
    <script src="adminassets/libs/node-waves/waves.min.js"></script>
    <script src="adminassets/libs/select2/js/select2.min.js"></script>
    <script src="adminassets/js/pages/form-advanced.init.js"></script>
    <!-- Required datatable js -->
    <script src="adminassets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="adminassets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="adminassets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="adminassets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="adminassets/libs/jszip/jszip.min.js"></script>
    <script src="adminassets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="adminassets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="adminassets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="adminassets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="adminassets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

    <!-- Responsive examples -->
    <script src="adminassets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="adminassets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <!-- Datatable init js -->
    <script src="adminassets/js/pages/datatables.init.js"></script>
    <!-- App js -->
    <script src="adminassets/js/app.js"></script>
    <script src="adminassets/js/customjs.js?v=8959"></script>


    <script>
        $(document).ready(function() {
            $(".open-img-modal").click(function() {
                var imgsrc = $(this).data('id');
                $('.my_image').attr('src', imgsrc);
                $("#open-img-modal").modal('show');
            });

            $(".categor_select_2").select2({
                placeholder: "Select Category",
                allowClear: true
            });

            $(".select_digit").select2({
                placeholder: "Select Digit",
                allowClear: true
            });

        });

        Date.prototype.toShortFormat = function() {

            var month_names = ["Jan", "Feb", "Mar",
                "Apr", "May", "Jun",
                "Jul", "Aug", "Sep",
                "Oct", "Nov", "Dec"
            ];

            var day = this.getDate();
            var month_index = this.getMonth();
            var year = this.getFullYear();
            var hours = this.getHours();
            var minutes = this.getMinutes();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;
            var strTime = hours + ':' + minutes + ' ' + ampm;

            return "" + day + "-" + month_names[month_index] + "-" + year + " " + strTime;
        }
        var today = new Date();
        var exceldate = today.toShortFormat()
    </script>
    <script>
        //$('.timepicker').val('')

        var options = {
            twentyFour: false,
            upArrow: 'wickedpicker__controls__control-up',
            downArrow: 'wickedpicker__controls__control-down',
            close: 'wickedpicker__close',
            showSeconds: false,
            secondsInterval: 1,
            minutesInterval: 1,
            beforeShow: null,
            show: null,
            clearable: false,

        };
        var seridevi_open_time = $('#seridevi_open_time').val();;
        var seridevi_close_time = $('#seridevi_close_time').val();;
        var madhur_m_open_time = $('#madhur_m_open_time').val();;
        var madhur_m_close_time = $('#madhur_m_close_time').val();;
        var gold_d_open_time = $('#gold_d_open_time').val();;
        var gold_d_close_time = $('#gold_d_close_time').val();;
        var madhur_d_open_time = $('#madhur_d_open_time').val();;
        var madhur_d_close_time = $('#madhur_d_close_time').val();;
        var super_milan_open = $('#super_milan_open').val();;
        var super_milan_close = $('#super_milan_close').val();;
        var rajdhani_d_open = $('#rajdhani_d_open').val();;
        var rajdhani_d_close = $('#rajdhani_d_close').val();;
        var supreme_d_open = $('#supreme_d_open').val();;
        var supreme_d_close = $('#supreme_d_close').val();;
        var sridevi_night_open = $('#sridevi_night_open').val();;
        var sridevi_night_close = $('#sridevi_night_close').val();;
        var gold_night_open = $('#gold_night_open').val();;
        var gold_night_close = $('#gold_night_close').val();;
        var madhure_night_open = $('#madhure_night_open').val();;
        var madhure_night_close = $('#madhure_night_close').val();;
        var supreme_night_open = $('#supreme_night_open').val();;
        var supreme_night_close = $('#supreme_night_close').val();;
        var rajhdhani_night_open = $('#rajhdhani_night_open').val();;
        var rajhdhani_night_close = $('#rajhdhani_night_close').val();;

        $('.timepicker').wickedpicker(options);



        $('#seridevi_open_time').val(seridevi_open_time);;
        $('#seridevi_close_time').val(seridevi_close_time);;
        $('#madhur_m_open_time').val(madhur_m_open_time);;
        $('#madhur_m_close_time').val(madhur_m_close_time);;
        $('#gold_d_open_time').val(gold_d_open_time);;
        $('#gold_d_close_time').val(gold_d_close_time);;
        $('#madhur_d_open_time').val(madhur_d_open_time);;
        $('#madhur_d_close_time').val(madhur_d_close_time);;
        $('#super_milan_open').val(super_milan_open);;
        $('#super_milan_close').val(super_milan_close);;
        $('#rajdhani_d_open').val(rajdhani_d_open);;
        $('#rajdhani_d_close').val(rajdhani_d_close);;
        $('#supreme_d_open').val(supreme_d_open);;
        $('#supreme_d_close').val(supreme_d_close);;
        $('#sridevi_night_open').val(sridevi_night_open);;
        $('#sridevi_night_close').val(sridevi_night_close);;
        $('#gold_night_open').val(gold_night_open);;
        $('#gold_night_close').val(gold_night_close);;
        $('#madhure_night_open').val(madhure_night_open);;
        $('#madhure_night_close').val(madhure_night_close);;
        $('#supreme_night_open').val(supreme_night_open);;
        $('#supreme_night_close').val(supreme_night_close);;
        $('#rajhdhani_night_open').val(rajhdhani_night_open);;
        $('#rajhdhani_night_close').val(rajhdhani_night_close);;
    </script>
    <script>
        var dataTable = '';
    </script>

    <script>
      $(document).ready(function () {
    $('#autoDepositeFrm').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        const selectedDate = $('#bid_revert_date').val();
        const errorDiv = $('#error');
        const tableBody = $('#auto_deposite_data_1'); // Target the table body

        // Clear previous error messages and table rows
        errorDiv.html('');
        tableBody.html('');

        $.ajax({
            url: "{{ route('autoDepositReport') }}", // Route name
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}", // CSRF Token for security
                bid_revert_date: selectedDate // Send the selected date
            },
            beforeSend: function () {
                $('#submitBtn').prop('disabled', true).text('Processing...');
            },
            success: function (response) {
                if (response.success) {
                    const data = response.data;

                    if (data.length === 0) {
                        // Show a "no data" message inside the table
                        tableBody.append(`
                            <tr>
                                <td colspan="8" class="text-center">No data available for the selected date.</td>
                            </tr>
                        `);
                    } else {
                        // Populate the table with dynamic data
                        data.forEach((item, index) => {
                            tableBody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.user_id}</td>
                                    <td>${item.deposit_amount}</td>
                                    <td>${item.deposite_status}</td>
                                    <td>${item.marchantId || 'N/A'}</td>
                                    <td>${item.id}</td>
                                    <td>${item.trnxnId || 'N/A'}</td>
                                    <td>${item.deposite_date}</td>
                                </tr>
                            `);
                        });
                    }
                } else {
                    errorDiv.html(`<div class="alert alert-danger">${response.message}</div>`);
                }
            },
            error: function (xhr) {
                const errorMessage = xhr.responseJSON?.message || "Something went wrong.";
                errorDiv.html(`<div class="alert alert-danger">${errorMessage}</div>`);
            },
            complete: function () {
                $('#submitBtn').prop('disabled', false).text('Submit');
            }
        });
    });
});

    </script>

    <script>
        function initializeDateInput() {
            const today = new Date().toISOString().split('T')[0];
            $('#bid_revert_date').attr('value', today); // Set today's date as default
            $('#bid_revert_date').attr('max', today); // Set max date as today
        }

        // Call the function on page load
        $(document).ready(function() {
            initializeDateInput();
        });
    </script>

    </body>

    </html>
