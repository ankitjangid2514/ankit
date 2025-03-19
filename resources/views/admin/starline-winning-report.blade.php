@extends('admin.layouts.main')
@section('title')
Starline Winning Report
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

                                    <h5>Starline Winning Report</h5>

                                </div>

                                <div class="card-body">

                                    <form class="theme-form mega-form" id="starlineWinningReportFrm" name="starlineWinningReportFrm" method="post">

                                    <div class="row">

                                        <div class="form-group col-md-3">

                                        <label>Date</label>
                                                                                        <div class="date-picker">
                                                    <div class="input-group">
                                                    <input class="form-control digits" type="date" value="2024-09-06" name="result_date" id="result_date" max="2024-09-06" >
                                                    </div>
                                                </div>
                                            

                                        </div>
                                            <div class="form-group col-md-3">
                                            <label>Game Name</label>
                                            <select id="win_game_name" name="win_game_name" class="form-control">
                                                <option value=''>-Select Game Name-</option>
                                                @foreach($data as $market)
                                                <option value="{{$market->id}}">{{$market->starline_name}}</option>
                                                @endforeach
                                                    
                                            </select>
                                        </div>



                                        <div class="form-group col-md-2">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block" id="submitBtn" name="submitBtn">Submit</button>

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



        <input type="hidden" id="result_date">

        <input type="hidden" id="result_game_name">




        <div class="container-fluid">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-body">

                            <h4 class="card-title">Winning History List


                            </h4>

                            <div class="dt-ext table-responsive">

                                <table id="resultHistory" class="table table-striped table-bordered">

                                    <thead>

                                        <tr>

                                            <th>User Name</th>



                                            <th>Game Name</th>
                                            <th>Game Type</th>
                                            <th>Bid Point</th>
                                            <th>Amount</th>

                                            <th>Tx Id</th>
                                            <th>Tx Date</th>

                                        </tr>

                                    </thead>

                                    <tbody id="result_data">



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


        <!-- For Showing Winning List -->
        <script type="text/javascript">

            $(document).ready(function() {
                // Initialize the DataTable
                var table = $('#resultHistory').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('starline_winning_report_list') }}',
                        type: 'get',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                        }
                    },
                    columns: [
                        {
                            data: 'name'
                        },
                        {
                            data: 'starline_name'
                        },

                        {
                            data: 'bid_type'
                        },
                        {
                            data: 'bid_point'
                        },
                        {
                            data: 'winning_amount'
                        },

                        {
                            data: 'bid_id'
                        },
                        {
                            data: 'created_at'
                        },

                    ]
                });
            });
        </script>

    @endsection

@endsection
