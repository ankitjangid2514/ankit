@extends('admin.layouts.main')
@section('title')
    Bid History Managment
@endsection
@section('container')

<style>
    #error_msg {
    display: none; /* Start hidden */
    background-color: #c9bfa1; /* Brown background */
    color: brown; /* White text color */
    padding: 10px; /* Padding around text */
    border-radius: 5px; /* Rounded corners */
    font-weight: bold; /* Bold text */
    margin-bottom: 15px; /* Space below the error message */
}

</style>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Bid History Report</h4>
                                        <form id="getBidHistoryFrm" name="getBidHistoryFrm" method="post">
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <label>Date</label>
                                                    <div class="date-picker">
                                                        <div class="input-group">
                                                            <input class="form-control digits" type="date" value=""
                                                                name="bid_date" id="bid_date">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Game Name</label>
                                                    <select id="game_name" name="game_name" class="form-control">
                                                        <option value=''>-Select Game Name-</option>

                                                        @foreach ($data as $market)
                                                            <option value='{{ $market->id }}'>{{ $market->market_name }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Game Type</label>
                                                    <select id="game_type" name="game_type" class="form-control">
                                                        <option value=''>-Select Game Type-</option>
                                                        <option value='all'>All Type</option>

                                                        @foreach ($gtype as $list)
                                                            <option value='{{ $list->id }}'>{{ $list->gtype }}</option>
                                                        @endforeach

                                                    </select>
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

            <input type="hidden" id="bidHistory_date">
            <input type="hidden" id="bid_game_name">
            <input type="hidden" id="bid_game_type">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Bid History List
                                </h4>
                                <div class="dt-ext table-responsive">
                                    <table class="table table-striped table-bordered" id="bid_data">
                                        <thead>
                                            <tr>
                                                <th>User Name</th>
                                                <th>Bid TX ID</th>
                                                <th style="">market Name</th>
                                                <th>Game Type</th>

                                                <th>Session</th> 
                                                <th>Points</th>
                                                <th>amount</th>
                                                <th>Bid_date</th>
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
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body modal_update_edibidhistory">
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
                const dateInput = document.getElementById('bid_date');

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
                var table = $('#bid_data').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('bid_history') }}',
                        type: 'get',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                            d.bid_date = $('#bid_date').val(); // Get the selected date
                            d.game_name = $('#game_name').val(); // Get selected game name
                            d.game_type = $('#game_type').val(); // Get selected game type
                        }
                    },
                    columns: [{
                            data: 'name'
                        },
                        {
                            data: 'bid_id'
                        },
                        {
                            data: 'market_name'
                        },
                        {
                            data: 'session',
                            render: function(data) {
                                return data ? data : '<span style="color: red;">N/A</span>';
                            }
                        }, 
                        {
                            data: 'game_type',
                            render: function(data) {
                                return data ? data : '<span style="color: red;">N/A</span>';
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
        </script>
    @endsection

@endsection
