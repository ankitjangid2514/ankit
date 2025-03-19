@extends('admin.layouts.main')
@section('title')
    Bid Revert
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
                                        <h4 class="card-title">Bid Revert</h4>

                                        <form class="theme-form mega-form" id="bidRevertFrm" name="bidRevertFrm"
                                            method="post" autocomplete="off">
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <label>Date</label>
                                                    <div class="date-picker">
                                                        <div class="input-group">
                                                            <input class="form-control digits" type="date"
                                                                value="2024-09-05" name="bid_revert_date"
                                                                id="bid_revert_date" max="2024-09-05">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Game Name</label>
                                                    <select id="win_game_name" name="win_game_name" class="form-control">
                                                        <option value=''>-Select Game Name-</option>

                                                        @foreach ($data as $market)
                                                            <option value="{{ $market->id }}">{{ $market->market_name }}
                                                                    
                                                            </option>
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
                                <h4 class="card-title">Bid Revert List
                                    <a class="btn btn-primary btn-sm btn-float clear_btn" href="#revertModel" role="button"
                                        data-toggle="modal">Clear & Refund All</a>
                                </h4>
                                <div class="mt-3">
                                    <table class="table table-striped table-bordered" id="bidRevertTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User Name</th>
                                                <th>Bid Points</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bid_result_data">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="modal fade" id="revertModel" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-frame modal-top modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 text-left">
                                    <h5>Are you sure you want to clean and refund bid amount...?</h5>
                                </div>
                                <div class="col-md-4 text-left">
                                    <button onclick="data_refund()" id="data_clean_date"
                                        class="btn btn-danger waves-effect waves-light">Yes</button>
                                    <button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
                                </div>
                                <div class="error_msg">
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
                    const dateInput = document.getElementById('bid_revert_date');

                    // Set the max attribute to today's date
                    dateInput.setAttribute('max', formattedDate);

                    // Set the value to today's date
                    dateInput.value = formattedDate;
                });
            </script>

            {{-- For Showing User Bid History --}}
            <script type="text/javascript">
                $(document).ready(function() {
                    // Initialize the DataTable
                    var table = $('#bidRevertTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{ route('bid_data') }}',
                            type: 'post',
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
                                data: 'amount'
                            },
                            {
                                data: 'gtype'
                            },
                        ]
                    });

                });
            </script>

            <script>
                $(document).ready(function() {
    $('#submitBtn').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        
        // Optional: Fetch additional data to send with the request
        let bidRevertDate = $('#bid_revert_date').val(); // Example input field
        let winGameName = $('#win_game_name').val(); // Example input field

        // Perform the AJAX request
        $.ajax({
            url: '{{ route('bid_revert_data') }}', // Your route for bid_revert_data
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // CSRF token for security
                bid_revert_date: bidRevertDate,
                win_game_name: winGameName
            },
            success: function(response) {
                if (response.success) {
                    alert('Data reverted successfully!');
                    console.log(response.data); // Display returned data in the console
                } else {
                    alert('Failed to revert data: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred while reverting data.');
            }
        });
    });
});

            </script>
        @endsection
    @endsection
