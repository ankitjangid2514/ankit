@extends('admin.layouts.main')
@section('title')
Winning Prediction
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
                                <div class="card-body">
                                <h4 class="card-title">Winning prediction</h4>

                                <form class="theme-form mega-form" name="geWinningpredictFrm" method="get" autocomplete="off">
                                    @csrf
                                    <div class="row">
                                    <div class="form-group col-md-2">
                                        <label>Date</label>
                                                                        <div class="date-picker">
                                            <div class="input-group">
                                                <input class="form-control digits" type="date" value="2024-09-05" name="result_date" id="result_date"  max="2024-09-05" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Game Name</label>
                                        <select id="win_game_name" name="win_game_name" class="form-control" onchange="checkGameDeclare(this.value);">

                                            <option value=''>-Select Game Name-</option>
                                            @foreach($data as $market)
                                                <option value="{{$market->id}}">{{$market->market_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Session Time</label>
                                        <select id="win_market_status" name="win_market_status" onchange="showclose(this.value);" class="form-control">
                                            <option value=''>-Select Market Time-</option>
                                            <option value="1">Open Market</option>
                                            <option value="2">Close Market</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Open Pana</label>
                                        <select class="form-control select2" name="winning_ank" id="winning_ank" data-placeholder="Select open number">
                                            <option></option>
                                            <option value="129">129</option>
                                             <option value="999">999</option>
                                        </select>

                                    </div>
                                    <div class="form-group col-md-2" id="showclosediv" style="display:none;" >
                                        <label>Close Pana</label>
                                        <!--<input class="form-control" type="text"  value="" name="close_number" id="close_number" placeholder="Enter close number" >-->
                                            <select class="form-control select2" name="close_number" id="close_number" data-placeholder="Select close number">
                                                    <option></option>
                                                    <option value="129">129</option>
                                                    <option value="999">999</option>
                                            </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block" id="submitBtn" name="submitBtn">Submit</button>
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
                            <h4 class="card-title">Winning Prediction List
                            </h4>
                                <div class="mt-3">
                                    <div class="bs_box bs_box_light">
                                        <i class="mdi mdi-gavel mr-1"></i>
                                        <span>Total Bid Amount : </span>
                                        <b><span id="t_bid">{{$totalbid}}</span></b>
                                    </div>

                                    <div class="bs_box bs_box_light">
                                        <i class="mdi mdi-wallet mr-1"></i>
                                        <span>Total Winning Amount : </span>
                                        <b><span id="t_winneing_amt">{{$totalwin}}</span></b>
                                    </div>
                                </div>

                                <div class="mt-3 table-responsive">
                                <table class="table table-striped table-bordered" id="winner_result_data">
                                    <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>User Name</th>
                                        <th>Bid Points</th>
                                        <th>Winning Amount</th>
                                        <th>Type</th>
                                        <th>Bid TX ID</th>
                                        <th>Date</th>
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

        <div class="modal fade" id="updatebidhistoryModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal_right_side" role="document">
                <div class="modal-content col-12 col-md-3">
                <div class="modal-header">
                    <h5 class="modal-title">Update Bid</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body modal_update_edibidhistory">
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

        {{-- For Getting The Winning List --}}
        <script type="text/javascript">
            $(document).ready(function() {
                // Initialize the DataTable
                var table = $('#winner_result_data').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('winning_prediction_list') }}', // Your Laravel route
                        type: 'get',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token
                            // Fetch form filter values
                            d.result_date = $('#result_date').val();
                            d.win_game_name = $('#win_game_name').val();
                            d.win_market_status = $('#win_market_status').val();
                            d.winning_ank = $('#winning_ank').val();
                            d.close_number = $('#close_number').val();
                        }
                    },
                    columns: [
                        { data: 'id' },
                        { data: 'name' },        // User name from the joined table
                        { data: 'bid_point' },
                        { data: 'winning_amount' },
                        { data: 'bid_type' },
                        { data: 'bid_id' },
                        { data: 'created_at' },  // Date when winner record was created
                    ]
                });

                // Reload the table when the form is submitted
                $('#geWinningpredictFrm').on('submit', function(e) {
                    e.preventDefault(); // Prevent form submission
                    table.draw();       // Reload DataTable with new filter data
                });
            });
        </script>



    @endsection
@endsection
