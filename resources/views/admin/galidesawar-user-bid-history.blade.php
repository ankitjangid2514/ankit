@extends('admin.layouts.main')
@section('title')
GaliDesawar Bid History
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
                                    <h5>Bid History Report</h5>
                                    <form class="theme-form mega-form" id="getStarlineBidHistoryFrm" name="getStarlineBidHistoryFrm" method="post">
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                                <label>Date</label>
                                                                                        <div class="date-picker">
                                                    <div class="input-group">
                                                    <input class="form-control digits" type="date" value="" name="bid_date" id="bid_date">
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Game Name</label>
                                            <select id="game_name" name="game_name" class="form-control">
                                                <option value=''>-Select Game Name-</option>
                                                <option value='10'>All Games</option>
                                                <option value="1">10:00 PM</option>
                                                    <option value="2">11:00 AM</option>
                                                    <option value="3">12:00 PM</option>
                                                    <option value="4">1:00 PM</option>
                                                    <option value="5">2:00 PM</option>
                                                    <option value="6">3:00 PM</option>
                                                    <option value="7">4:00 PM</option>
                                                    <option value="8">5:00 PM</option>
                                                    <option value="9">6:00 PM</option>
                                                    <option value="10">7:00 PM</option>
                                                    <option value="11">8:00 PM</option>
                                                    <option value="12">9:00 PM</option>
                                                    <option value="13">10:00 AM</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Game Type</label>
                                            <select id="game_type" name="game_type" class="form-control">
                                                <option value=''>-Select Game Type-</option>
                                                <option value='0'>All Type</option>
                                                <option value='1'>Left Digit</option>
                                                <option value='2'>Right Digit</option>
                                                <option value='3'>Jodi Digit</option>

                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                        <label>&nbsp;;</label>
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



        <input type="hidden" id="bidHistory_date">
        <input type="hidden" id="bid_game_name">
        <input type="hidden" id="bid_game_type">

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Bid History List
                            <!--<button id='export_btn' class="btn btn-primary btn-sm btn-float m-r-5" onclick="getStarlineBidHistoryExcelData()">Export To Excel</button>-->
                            </h4>
                            <div class="dt-ext table-responsive">
                                <table id="bidHistory" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>User Name</th>
                                            <th>Bid TX ID</th>
                                            <th>Game Name</th>
                                            <th>Game Type</th>
                                            <th>Digit</th>
                                            <th>Points</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bid_data">

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

        <script type="text/javascript">

            $(document).ready(function() {
                // Initialize the DataTable
                var table = $('#bidHistory').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('desawar_user_bid_history_list') }}',
                        type: 'GET',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                        }
                    },
                    columns: [
                        {
                            data: 'name'
                        },
                        {
                            data: 'id'
                        },
                        {
                            data: 'desawar_name'
                        },
                        {
                            data: 'gtype'
                        },
                        {
                            data: 'digit'
                        },
                        {
                            data: 'amount'
                        },
                        {
                data: 'desawar_bid_date',
                render: function(data, type, row) {
                    // Check if data is valid
                    if (data) {
                        // Create a Date object from the string
                        var date = new Date(data);
                        // Format the date as dd-mm-yyyy
                        var formattedDate = ('0' + date.getDate()).slice(-2) + '-' +
                                            ('0' + (date.getMonth() + 1)).slice(-2) + '-' +
                                            date.getFullYear();
                        return formattedDate;
                    }
                    return ''; // Return an empty string if date is not valid
                }
            }


                    ]
                });
            });

        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date().toISOString().split('T')[0];
                const dateInput = document.getElementById('bid_date');

                // Set the min attribute to today's date
                dateInput.setAttribute('max', today);

                // Set the value to today's date
                dateInput.value = today;
            });
        </script>

    @endsection

@endsection


