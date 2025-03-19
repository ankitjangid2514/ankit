@extends('admin.layouts.main')
@section('title')
    Winning Report
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

                                    <h5>Winning History Report</h5>

                                </div>

                                <div class="card-body">

                                    <form class="theme-form mega-form" name="geWinningHistoryFrm" method="post">

                                        <div class="row">

                                            <div class="form-group col-md-2">

                                                <label>Date</label>


                                                <div class="date-picker">

                                                    <div class="input-group">

                                                    <input class="form-control digits" type="date" value="2024-09-05" name="result_date" id="result_date" max="2024-09-05" >

                                                    </div>

                                                </div>

                                            </div>
                                                <div class="form-group col-md-4">
                                                <label>Game Name</label>
                                                <select id="win_game_name" name="win_game_name" class="form-control">
                                                    <option value='0'>-Select Game Name-</option>

                                                    @foreach ($data as $market)
                                                        
                                                        <option value="{{$market->id}}">{{$market->market_name}}</option>

                                                    @endforeach
                                                        
                                                </select>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label>Market Time</label>
                                                <select id="win_market_status" name="win_market_status" class="form-control">
                                                    <option value='0'>-Select Market Time-</option>
                                                        <option value="1">Open Market</option>
                                                        <option value="2">Close Market</option>

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

                            <h4 class="card-title">Winning History List</h4>

                            <div class="dt-ext table-responsive">

                                <table id="resultHistory" class="table table-striped table-bordered">

                                    <thead>

                                        <tr>

                                            <th>User Name</th>



                                            <th>Game Name</th>
                                            <th>Game Type</th>
                                            <th>Points</th>
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

        {{-- For Showing Bid History --}}
        <script type="text/javascript">
            $(document).ready(function() {
                // Initialize the DataTable
                var table = $('#resultHistory').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('winning_report_list') }}",
                        type: 'get',
                        data: function(d) {
                            // Include CSRF token for security
                            d._token = '{{ csrf_token() }}';
                            
                            // Get the form values and pass them to the server
                            d.result_date = $('#result_date').val();
                            d.win_game_name = $('#win_game_name').val();
                            d.win_market_status = $('#win_market_status').val();
                        }
                    },
                    columns: [
                        { data: 'name' },
                        { data: 'market_name' },
                        { data: 'bid_type' },
                        { data: 'bid_point' },
                        { data: 'winning_amount' },
                        { data: 'bid_id' },
                        { data: 'created_at' }
                    ]
                });

                // Reload the DataTable when the form is submitted
                $('#geWinningHistoryFrm').on('submit', function(e) {
                    e.preventDefault(); // Prevent the form from submitting normally
                    table.draw();       // Reload the DataTable with new filter data
                });
            });
        </script>


    @endsection
@endsection
