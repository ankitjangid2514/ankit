@extends('admin.layouts.main')
@section('title')
Starline Winning Prediction
@endsection
@section('container')

    <div class="main-content">	<div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-xl-12 col-md-12">
                    <div class="row">
                        <div class="col-sm-12">
                        <div class="card">
                                <div class="card-body">
                                <h4 class="card-title">Winning prediction</h4>

                                <form class="theme-form mega-form" id="stralineWinningpredictFrm" name="stralineWinningpredictFrm" method="post" autocomplete="off">
                                    <div class="row">
                                    <div class="form-group col-md-2">
                                        <label>Date</label>
                                                                        <div class="date-picker">
                                            <div class="input-group">
                                                <input class="form-control digits" type="date" value="2024-09-06" name="result_date" id="result_date"  max="2024-09-06" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Game Name</label>
                                        <select id="win_game_name" name="win_game_name" class="form-control">
                                            <option value=''>-Select Game Name-</option>
                                            @foreach($data as $market)
                                            <option value="{{$market->id}}">{{$market->desawar_name}}</option>
                                            @endforeach
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
                                    <span>Total Bid Amount</span>
                                    <b><span id="t_bid">{{$totalbid}}</span></b>
                                </div>

                                <div class="bs_box bs_box_light">
                                    <i class="mdi mdi-wallet mr-1"></i>
                                    <span>Total Winning Amount</span>
                                    <b><span id="t_winneing_amt">{{$totalwin}}</span></b>
                                </div>
                            </div>

                            <div class="mt-3">
                            <table class="table table-striped table-bordered" id="winners">
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


                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @section('script')

        {{-- For Showing Today Date --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date().toISOString().split('T')[0];
                const dateInput = document.getElementById('result_date');
                const dateHiddenInput = document.getElementById('result_dec_date_hidden');

                // Set the max attribute to today's date
                dateInput.setAttribute('max', today);

                // Set the value to today's date
                dateInput.value = today;

                // Set the hidden input value to today's date
                dateHiddenInput.value = today;

                // Update the hidden input value whenever the date input changes
                dateInput.addEventListener('change', function() {
                    dateHiddenInput.value = dateInput.value;
                });
            });
        </script>

        <!-- For Showing Winners List -->
        <script type="text/javascript">

            $(document).ready(function() {
                // Initialize the DataTable
                var table = $('#winners').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('desawar_winning_prediction_list') }}',
                        type: 'get',
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
                            data: 'bid_point'
                        },
                        {
                            data: 'winning_amount'
                        },
                        {
                            data: 'bid_type'
                        },
                        {
                            data: 'bid_id'
                        },

                    ]
                });
            });
        </script>

    @endsection

@endsection
