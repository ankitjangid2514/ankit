@extends('admin.layouts.main')
@section('title')
    Declare Result
@endsection
@section('container')

    <style>
        .display_none {
            display: none;
        }
    </style>

    <div class="main-content">
        <div class="page-content">

            <div class="container-fluid">

                <div class="row">

                    <div class="col-12 col-sm-12 col-lg-12">

                        <div class="row">

                            <div class="col-sm-12 col-12 ">

                                <div class="card">

                                    <div class="card-body">
                                        <h4 class="card-title">Select Game</h4>
                                        <form name="gameSrchFrm" id="gameSrchFrm" method="post">

                                            <input type="hidden" name="id" id="id">

                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label>Result Date</label>

                                                    <div class="date-picker">

                                                        <div class="input-group">
                                                            <input class="form-control digits" type="date"
                                                                name="result_dec_date" id="result_dec_date" value="">
                                                        </div>


                                                    </div>

                                                </div>




                                                <div class="form-group col-md-4">

                                                    <label>Game Name </label>

                                                    <select class="form-control" name="game_id" id="game_id">
                                                        <option value="">Select Name</option>
                                                        @foreach ($data as $show)
                                                            @php
                                                                // Create a DateTime object from the AM/PM time format
                                                                $time = new DateTime($show->open_time);
                                                                $times = new DateTime($show->close_time);

                                                                // Format the time to 24-hour format (e.g., '14:30')
                                                                $formattedTime = $time->format('g:i A');
                                                                $formattedTimes = $times->format('g:i A');
                                                            @endphp

                                                            <option value="{{ $show->id }}">{{ $show->market_name }}
                                                                ({{ $formattedTime }} - {{ $formattedTimes }})
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label>Session</label>
                                                    <select id="market_status" name="market_status" class="form-control">
                                                        <option value="">-Select Session-</option>
                                                        <option value="1">Open</option>
                                                        <option value="2">Close</option>
                                                    </select>
                                                </div>

                                                {{-- <div class="form-group col-md-2" id="srchBtn" class="srchBtn">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-primary btn-block">Go</button>
                                                </div> --}}

                                                <div class="form-group col-md-2" id="srchBtn">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-primary btn-block"
                                                        onclick="toggleDiv()">Go</button>
                                                </div>

                                            </div>




                                            <div class="form-group">

                                                <div id="error" style="color: red;"></div>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>




            <div class="row" id="open_result_div" style="display: none;">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="row">
                        <div class="col-sm-12 col-12 ">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('declare_result_data') }}" method="POST">
                                        @csrf <!-- CSRF token for form security -->
                                        <h4 class="card-title">Declare Result</h4>
                                        <input type="hidden" name="game_id" id="game_id_hidden" value="">

                                        <input type="hidden" name="market_status" id="market_status_hidden">

                                        <!-- Hidden input for result_dec_date -->
                                        <input type="hidden" name="result_dec_date" id="result_dec_date_hidden"
                                            value="">

                                        <div class="row open_panna_area">
                                            <div class="col-12 col-md-12">
                                                <div class="row">
                                                    <div class="form-group col-md-4">

                                                        <label>Open Panna:</label>
                                                        <input class="form-control" name="open_number" id="open_number"
                                                            placeholder="Select panna">
                                                        <small class="error" id="open-error" style="display: none;">The
                                                            value is not a valid open panna.</small>

                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Digit</label>
                                                        <input class="form-control" type="number" name="open_result"
                                                            id="open_result" readonly>
                                                    </div>
                                                    <div class="form-group col-md-4" id="open_div_msg">
                                                        <label>&nbsp;</label>
                                                        <button type="button"
                                                            class="btn btn-primary waves-light mr-1 display_none"
                                                            id="winnerBtn" name="winnerBtn" onclick="showWinners();">Show
                                                            Winners</button>
                                                        <button type="button" class="btn btn-primary waves-light mr-1"
                                                            id="openSaveBtn" name="openSaveBtn"
                                                            onclick="OpenSaveData();">Save</button>
                                                        <button type="submit"
                                                            class="btn btn-primary waves-light mr-1 display_none"
                                                            id="openDecBtn" name="openDecBtn">Declare</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="table-responsive display_none" id="winnersTable">
                                        <table class="table table-striped table-bordered" id="showwinnersdata">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>user</th>
                                                    <th>Bid</th>
                                                    <th>Points</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Winner data rows will go here -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="form-group">
                                        <div id="error2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="close_result_div" style="display: none;">

                <div class="col-12 col-sm-12 col-lg-12">

                    <div class="row">

                        <div class="col-sm-12 col-12 ">

                            <div class="card">

                                <div class="card-body">

                                    <form action="{{ route('declare_result_data') }}" method="POST">
                                        @csrf <!-- CSRF token for form security -->
                                        <h4 class="card-title">Declare Result</h4>
                                        <input type="hidden" name="game_id" id="game_id_hidden_cls" value="">

                                        <input type="hidden" name="market_status" id="market_status_hidden_cls">

                                        <!-- Hidden input for result_dec_date -->
                                        <input type="hidden" name="result_dec_date" id="result_dec_date_hidden_cls"
                                            value="">

                                        <div class="mt-3" id="withdraw_data_details">
                                            <div class="bs_box bs_box_light_withdraw">
                                                <span>Open Result :-</span>
                                                <b><span id="open_result_data">0</span></b>
                                            </div>
                                        </div>
                                        <div class="row close_panna_area">
                                            <div class="col-12 col-md-12">
                                                <div class="row">

                                                    <div class="form-group col-md-4">

                                                        <label>Close Panna:</label>
                                                        <input class="form-control" name="close_number" id="close_number"
                                                            placeholder="Select panna">
                                                        <small class="error" id="close-error" style="display: none;">The
                                                            value is not a valid close panna.</small>


                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Digit</label>
                                                        <input class="form-control" type="number" name="close_result"
                                                            id="close_result">
                                                    </div>

                                                    <div class="form-group col-md-4" id="close_div_msg">
                                                        <label>&nbsp;</label>

                                                        <button type="button"
                                                            class="btn btn-primary waves-light mr-1 display_none"
                                                            id="winnersBtn" name="winnersBtn"
                                                            onclick="closeshowWinners();">Show Winners</button>

                                                        <button type="button" class="btn btn-primary waves-light mr-1"
                                                            id="closeSaveBtn" name="closeSaveBtn"
                                                            onclick="CloseSaveData();">Save</button>

                                                        <button type="submit"
                                                            class="btn btn-primary waves-light mr-1 display_none"
                                                            id="closeDecBtn" name="closeDecBtn">Declare</button>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </form>

                                    <div class="table-responsive display_none" id="closewinnersTable">
                                        <table class="table table-striped table-bordered" id="closeshowwinnersdata">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>User</th>
                                                    <th>Winning Type</th>
                                                    <th>Points</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Winners data will be dynamically populated here -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="form-group">
                                        <div id="error2"></div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Game Result History</h4>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label>Select Result Date</label>
                                    <div class="date-picker">
                                        <div class="input-group">
                                            <input class="form-control digits" type="date" name="result_pik_date"
                                                id="result_pik_date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="getGameResultHistory">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Game Name</th>
                                            <th>Result Date</th>
                                            <th>Open Digit</th>
                                            <th>Close Digit</th>
                                            <th>Open Panna</th>
                                            <th>Close Panna</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>

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
    <!-- For Showing the Open showwinners Button -->
    <script>
        document.getElementById('open_number').addEventListener('change', function() {
            var selectedValue = this.value;
            var winnerBtn = document.getElementById('winnerBtn');

            // If a value is selected, show the button. Otherwise, hide it.
            if (selectedValue) {
                winnerBtn.classList.remove('display_none');
            } else {
                winnerBtn.classList.add('display_none');
            }
        });
    </script>

    <!-- For Showing the Close showwinners Button -->
    <script>
        document.getElementById('close_number').addEventListener('change', function() {
            var selectedValue = this.value;
            var winnerBtn = document.getElementById('winnersBtn');

            // If a value is selected, show the button. Otherwise, hide it.
            if (selectedValue) {
                winnerBtn.classList.remove('display_none');
            } else {
                winnerBtn.classList.add('display_none');
            }
        });
    </script>

    <!-- For Showing the Open showwinnersData Table -->
    <script>
        function showWinners() {
            var winnersTable = document.getElementById('winnersTable');

            // Toggle the visibility of the table by removing/adding the display_none class
            if (winnersTable.classList.contains('display_none')) {
                winnersTable.classList.remove('display_none');

                var table = $('#showwinnersdata').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true, // Reinitialize if the table already exists
                    order: [
                        [1, 'asc']
                    ],
                    ajax: {
                        url: "{{ route('show_winner') }}",
                        type: 'get',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                            d.game_id = $('#game_id_hidden').val(); // Hidden input field: game_id
                            d.market_status = $('#market_status_hidden')
                                .val(); // Hidden input field: market_status
                            d.result_dec_date = $('#result_dec_date_hidden')
                                .val(); // Hidden input field: result_dec_date
                            d.open_number = $('#open_number').val(); // Selected input field: open_number
                            d.open_result = $('#open_result').val(); // Selected input field: open_number

                        }
                    },
                    columns: [{
                            data: null,
                            orderable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart +
                                    1; // Calculate sequence number
                            }
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'open_digit',
                            render: function(data, type, row) {
                                // If 'close_digit' is "N/A", use 'close_panna' instead
                                return data === "N/A" ? row.open_panna : data;
                            }
                        },
                        {
                            data: 'amount'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<button class="btn btn-primary  edit-btn" data-id="${row.id}">Edit</button>`;
                            }
                        }
                    ]

                });

                // Handle Edit button click
                $('#showwinnersdata').on('click', '.edit-btn', function() {
                    var id = $(this).data('id');
                    var newBidPoints = prompt("Enter new bid points:");

                    if (newBidPoints !== null) {
                        // AJAX request to the route that handles editing the user bid
                        $.ajax({
                            url: "{{ route('edit_bid') }}",
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                bid_id: id,
                                points: newBidPoints, // Send the new bid points to the server
                            },
                            success: function(response) {
                                alert(response.message);

                                // Reload the table data without refreshing the entire page
                                $.ajax({
                                    url: "{{ route('show_winner') }}", // Route to fetch updated table data
                                    type: 'GET',
                                    success: function(data) {
                                        var tbody = $('#showwinnersdata tbody');
                                        if (data.hasData) {
                                            tbody.html(data
                                                .html
                                            ); // Replace table body with updated data
                                        } else {
                                            tbody.html(
                                                "<tr><td colspan='5' style='text-align: center;'>No Bid Match found According to Selecting Result</td></tr>"
                                            ); // Adjust colspan based on table columns
                                        }
                                    },
                                    error: function(xhr) {
                                        alert('Failed to reload table');
                                    }
                                });
                            },
                            error: function(xhr) {
                                alert('Failed to update bid');
                            }
                        });
                    }
                });



            } else {
                winnersTable.classList.add('display_none');
            }
        }
    </script>

    <!-- For Showing the Close showwinnersData Table -->
    <script>
        function closeshowWinners() {
            var winnersTable = document.getElementById('closewinnersTable');

            // Toggle the visibility of the table
            if (winnersTable.classList.contains('display_none')) {
                winnersTable.classList.remove('display_none');

                // Initialize DataTable
                var table = $('#closeshowwinnersdata').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true, // Reinitialize if it already exists
                    ajax: {
                        url: "{{ route('show_winner_close') }}", // Backend route
                        type: 'GET',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}';
                            d.game_id = $('#game_id_hidden').val(); // Hidden input for game_id
                            d.result_dec_date = $('#result_dec_date_hidden')
                                .val(); // Hidden input for result_dec_date
                            d.close_number = $('#close_number').val(); // User-selected close number
                            d.close_result = $('#close_result').val(); // User-selected close result
                        }
                    },
                    columns: [{
                            data: null,
                            orderable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1; // Sequence number
                            }
                        },
                        {
                            data: 'name',
                            title: 'User'
                        }, // User's name
                        {
                            data: 'type', // Define the "type" column in the backend
                            render: function(data, type, row) {
                                return data ? data : "N/A"; // Handle missing data
                            }
                        },
                        {
                            data: 'amount',
                            title: 'Points'
                        }, // Points won
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<button class="btn btn-primary edit-btn-close" data-id="${row.bid_id}">Edit</button>`;
                            }
                        }
                    ],
                    order: [
                        [1, 'asc']
                    ]
                });
            } else {
                winnersTable.classList.add('display_none');
            }
        }

        // Handle Edit button click
        $('#closeshowwinnersdata').on('click', '.edit-btn-close', function() {
            var id = $(this).data('id');
            var newPoints = prompt("Enter new bid points:");
            if (newPoints !== null) {
                // AJAX request to the route that handles editing the user bid
                $.ajax({
                    url: "{{ route('close_edit_bid') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        bid_id: id,
                        points: newPoints, // Send the new bid points to the server
                    },
                    success: function(response) {
                        alert(response.message);
                        // Reload the table data without refreshing the entire page
                        $.ajax({
                            url: "{{ route('show_winner_close') }}",
                            type: 'GET',
                            success: function(data) {
                                var tbody = $('#closeshowwinnersdata tbody');
                                if (data.hasData) {
                                    tbody.html(data
                                        .html
                                    ); // Replace table body with updated data
                                } else {
                                    tbody.html(
                                        "<tr><td colspan='5' style='text-align: center;'>No Bid Match found According to Selecting Result</td></tr>"
                                    ); // Adjust colspan based on table columns
                                }
                            },
                            error: function(xhr) {
                                alert('Failed to reload table');
                            }
                        });
                    },
                    error: function(xhr) {
                        alert('Failed to update bid');
                    }
                });
            }
        });
    </script>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const year = today.getFullYear();
            const month = ('0' + (today.getMonth() + 1)).slice(-2); // Adding 1 because months are 0-indexed
            const day = ('0' + today.getDate()).slice(-2); // Ensure two digits for day

            const formattedDate = `${year}-${month}-${day}`;
            const dateInput = document.getElementById('result_dec_date');
            const dateHiddenInput = document.getElementById('result_dec_date_hidden');
            const dateHiddenInputcls = document.getElementById('result_dec_date_hidden_cls');

            // Set the max attribute to today's date
            dateInput.setAttribute('max', formattedDate);

            // Set the value to today's date
            dateInput.value = formattedDate;

            // Set the hidden input value to today's date
            dateHiddenInput.value = formattedDate;
            dateHiddenInputcls.value = formattedDate;

            // Update the hidden input value whenever the date input changes
            dateInput.addEventListener('change', function() {
                dateHiddenInput.value = dateInput.value;
                dateHiddenInputcls.value = dateInput.value;
            });
        });
    </script>

    {{-- Filter For Open Close Result Div --}}
    <script>
        function toggleDiv() {
            var marketStatus = document.getElementById("market_status").value;
            var openResultDiv = document.getElementById("open_result_div");
            var closeResultDiv = document.getElementById("close_result_div");

            if (marketStatus === "1") {
                openResultDiv.style.display = "block";
            } else {
                openResultDiv.style.display = "none";
            }

            if (marketStatus === "2") {
                closeResultDiv.style.display = "block";
            } else {
                closeResultDiv.style.display = "none";
            }

        }
    </script>

    {{-- For Getting The Today Date --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const year = today.getFullYear();
            const month = ('0' + (today.getMonth() + 1)).slice(-2); // Adding 1 because months are 0-indexed
            const day = ('0' + today.getDate()).slice(-2); // Ensure two digits for day

            const formattedDate = `${year}-${month}-${day}`;
            const dateInput = document.getElementById('result_pik_date');

            // Set the max attribute to today's date
            dateInput.setAttribute('max', formattedDate);

            // Set the value to today's date
            dateInput.value = formattedDate;
        });
    </script>

    {{-- For Doing Some Of Selected Panna --}}
    <script>
        document.getElementById('open_number').addEventListener('change', function() {
            const selectedNumber = this.value;

            // Clear the result if no option is selected
            if (!selectedNumber) {
                document.getElementById('open_result').value = '';
                return;
            }

            // Function to sum digits and get the last digit if sum > 9
            function sumDigits(num) {
                let sum = num.split('').reduce((acc, digit) => acc + parseInt(digit), 0);

                // If the sum is greater than 9, get the last digit
                return sum > 9 ? sum % 10 : sum;
            }

            // Get the sum of the digits
            const result = sumDigits(selectedNumber);

            // Set the result in the input field
            document.getElementById('open_result').value = result;
        });
    </script>

    {{-- For Doing Some Of Selected Panna --}}
    <script>
        document.getElementById('close_number').addEventListener('change', function() {
            const selectedNumber = this.value;

            // Clear the result if no option is selected
            if (!selectedNumber) {
                document.getElementById('close_result').value = '';
                return;
            }

            // Function to sum digits and get the last digit if sum > 9
            function sumDigits(num) {
                let sum = num.split('').reduce((acc, digit) => acc + parseInt(digit), 0);

                // If the sum is greater than 9, get the last digit
                return sum > 9 ? sum % 10 : sum;
            }

            // Get the sum of the digits
            const result = sumDigits(selectedNumber);

            // Set the result in the input field
            document.getElementById('close_result').value = result;
        });
    </script>

    <script>
        // Save button click event handler
        function OpenSaveData() {
            // Get the selected panna and digit result
            const selectedPanna = document.getElementById('open_number').value;
            const resultDigit = document.getElementById('open_result').value;

            if (!selectedPanna || !resultDigit) {
                alert("Please select a panna and ensure the digit result is calculated.");
                return;
            }

            // Log or save the values (for demonstration purposes)
            // console.log("Selected Panna: " + selectedPanna);
            // console.log("Result Digit: " + resultDigit);

            // Show the Declare button after saving
            document.getElementById('openDecBtn').classList.remove('display_none');
        }

        function CloseSaveData() {
            // Get the selected panna and digit result
            const selectedPanna = document.getElementById('close_number').value;
            const resultDigit = document.getElementById('close_result').value;

            if (!selectedPanna || !resultDigit) {
                alert("Please select a panna and ensure the digit result is calculated.");
                return;
            }

            // Log or save the values (for demonstration purposes)
            // console.log("Selected Panna: " + selectedPanna);
            // console.log("Result Digit: " + resultDigit);

            // Show the Declare button after saving
            document.getElementById('closeDecBtn').classList.remove('display_none');
        }

        // Declare button click event handler (for your custom logic)
        // function decleareOpenResult() {
        //     alert('Declare button clicked!');
        //     // Add your custom logic here
        // }

        // Function to handle game_id selection from the outer select box
        document.getElementById('game_id').addEventListener('change', function() {
            const selectedGameId = this.value;
            document.getElementById('game_id_hidden').value = selectedGameId; // Set value in hidden input
            document.getElementById('game_id_hidden_cls').value = selectedGameId; // Set value in hidden input
        });

        // Update hidden result_dec_date when the date input changes
        function updateDateHidden() {
            const selectedDate = document.getElementById('result_dec_date').value;
            document.getElementById('result_dec_date_hidden').value = selectedDate; // Set value in hidden input
            document.getElementById('result_dec_date_hidden_cls').value = selectedDate; // Set value in hidden input
        }

        document.getElementById('market_status').addEventListener('change', function() {
            const selectedSessionId = this.value;
            document.getElementById('market_status_hidden').value = selectedSessionId; // Set value in hidden input
            document.getElementById('market_status_hidden_cls').value =
                selectedSessionId; // Set value in hidden input
        });
    </script>


    {{-- For Showing Result History --}}
    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize the DataTable
            var table = $('#getGameResultHistory').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('result_history') }}",
                    type: 'get',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                        d.result_pik_date = $('#result_pik_date').val(); // Add selected date as filter
                    }
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'market_name'
                    },
                    {
                        data: 'result_date'
                    },
                    {
                        data: 'open_digit'
                    },
                    {
                        data: 'close_digit'
                    },
                    {
                        data: 'open_panna'
                    },
                    {
                        data: 'close_panna'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<button class="btn btn-danger delete-btn" data-id="${row.id}">Delete</button>`;
                        }
                    }
                ]
            });

            // Trigger DataTable reload on date change
            $('#result_pik_date').on('change', function() {
                table.ajax.reload(); // Reload DataTable with new date filter
            });

            // Handle delete button click
            $('#getGameResultHistory').on('click', '.delete-btn', function() {
                var resultId = $(this).data('id');

                if (confirm("Are you sure you want to delete this result?")) {
                    $.ajax({
                        url: "{{ route('delete_result') }}", // Route to handle deletion
                        type: 'delete',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: resultId
                        },
                        success: function(response) {
                            alert(response.message); // Display success message
                            table.ajax.reload(); // Reload DataTable after deletion
                        },
                        error: function(xhr) {
                            alert("An error occurred while deleting the result.");
                        }
                    });
                }
            });
        });
    </script>

    <script>
        // Predefined panna arrays
        const pannaArray = [
            '111', '222', '333', '444', '555', '666', '777', '888', '999', '000',
            "100", "110", "112", "113", "114", "115", "116", "117", "118", "119",
            "122", "133", "144", "155", "166", "177", "188", "199", "200", "220",
            "223", "224", "225", "226", "227", "228", "229", "233", "244", "255",
            "266", "277", "288", "300", "330", "334", "335", "336", "337", "338",
            "339", "344", "355", "366", "377", "388", "399", "400", "440", "445",
            "446", "447", "448", "449", "455", "466", "477", "488", "499", "500",
            "550", "557", "558", "559", "566", "577", "588", "599", "600", "660",
            "667", "668", "669", "677", "688", "700", "770", "778", "779", "788",
            "799", "800", "880", "889", "890", "899", "900", "990", '120', '123', '124', '125', '126',
            '127', '128', '129', '130', '134', '135', '136', '699',
            '137', '138', '139', '140', '145', '146', '147', '148', '149', '150', '156', '157', '158',
            '159', '160', '167', '168', '169', '170', '178', '179', '180', '189', '190', '230', '234',
            '235', '236', '237', '238', '239', '240', '245', '246', '247', '248', '249', '250', '256',
            '257', '258', '259', '260', '267', '268', '269', '270', '278', '279', '280', '289', '290',
            '340', '345', '346', '347', '348', '349', '350', '356', '357', '358', '359', '360', '367',
            '368', '369', '370', '378', '379', '380', '389', '390', '450', '456', '457', '458', '459',
            '460', '467', '468', '469', '470', '478', '479', '480', '489', '490', '560', '567', '568',
            '569', '570', '578', '579', '580', '589', '590', '670', '678', '679', '680', '689', '690',
            '780', '789', '790', '890'
        ]; // Example panna numbers

        // Get references to inputs and error messages
        const openNumberInput = document.getElementById('open_number');
        const openError = document.getElementById('open-error');

        const closeNumberInput = document.getElementById('close_number');
        const closeError = document.getElementById('close-error');

        // Validation function
        function validatePanna(inputElement, errorElement) {
            const inputValue = inputElement.value.trim();

            // Check if the input value is in the panna array
            if (pannaArray.includes(inputValue)) {
                errorElement.style.display = 'none'; // Hide the error message
            } else {
                errorElement.style.display = 'block'; // Show the error message
            }
        }

        // Add event listeners for both inputs
        openNumberInput.addEventListener('input', function() {
            validatePanna(openNumberInput, openError);
        });

        closeNumberInput.addEventListener('input', function() {
            validatePanna(closeNumberInput, closeError);
        });
    </script>
@endsection
@endsection
