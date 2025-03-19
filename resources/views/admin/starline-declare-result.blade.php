@extends('admin.layouts.main')
@section('title')
    Declare Result
@endsection
@section('container')
    <style>
        .display-none {
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
                                        <h5>Select Game</h5>

                                        <form class="theme-form mega-form" name="starlineGameSrchFrm"
                                            id="starlineGameSrchFrm" method="post">

                                            <input type="hidden" name="id" id="id">

                                            <div class="row">
                                                <div class="form-group col-md-3">

                                                    <label>Result Date</label>

                                                    <label>Result Date</label>

                                                    <div class="date-picker">

                                                        <div class="input-group">

                                                            <input class="form-control digits" type="date"
                                                                name="result_dec_date" id="starline_result_dec_date"
                                                                value="">

                                                        </div>

                                                    </div>
                                                    <!--<div class="date-picker">

                                                                                    <div class="input-group">

                                                                                    <input class="datepicker-here form-control digits" type="text" value="2024-09-06"  data-date-format="yyyy-mm-dd" readonly data-language="en" value="" name="result_dec_date" id="result_dec_date" placeholder="Enter Start Date" data-position="bottom left">

                                                                                    </div>

                                                                                </div>-->

                                                </div>

                                                <div class="form-group col-md-3">

                                                    <label>Game Name </label>

                                                    <select class="form-control" name="game_id" id="game_id">
                                                        <option value="0">Select Name</option>
                                                        @foreach ($data as $market)
                                                            <option value="{{ $market->id }}">{{ $market->starline_name }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                </div>

                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-primary btn-block"
                                                        onclick="toggleDiv()">Go</button>

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



                <div class="row" id="result_div" style="display: none">

                    <div class="col-12 col-sm-12 col-lg-12">

                        <div class="row">

                            <div class="col-sm-12 col-12 ">

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Declare Result</h4>
                                        <form action="{{ route('starline_result') }}" method="POST">
                                            @csrf <!-- CSRF token for form security -->
                                            <div class="row close_panna_area">
                                                <div class="col-12 col-md-12">
                                                    <div class="row">

                                                        <!-- Hidden input for game_id -->
                                                        <input type="hidden" name="game_id" id="game_id_hidden"
                                                            value="{{ $market->id }}">

                                                        <!-- Hidden input for result_dec_date -->
                                                        <input type="hidden" name="result_dec_date"
                                                            id="result_dec_date_hidden" value="">

                                                        <div class="form-group col-md-4">
                                                            <label>Panna:</label>
                                                            <input class="form-control" name="open_number" id="open_number"
                                                                type="number" placeholder="Enter panna" maxlength="3">
                                                            <small id="pannaError" class="text-danger"></small>
                                                        </div>




                                                        <div class="form-group col-md-4">
                                                            <label>Digit</label>
                                                            <input class="form-control" readonly type="number"
                                                                name="open_result" id="open_result" value="">
                                                        </div>

                                                        <div class="form-group col-md-4" id="open_div_msg">

                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-primary waves-light mr-1"
                                                                id="openSaveBtn" name="openSaveBtn"
                                                                onclick="OpenSaveData();">Save</button>
                                                            <button type="button"
                                                                class="btn btn-primary waves-light mr-1 display-none"
                                                                id="winnerShowbtn" name="winnerShowbtn"
                                                                onclick="winnerShow();">show winners</button>
                                                            <button type="submit"
                                                                class="btn btn-primary waves-light mr-1 display_none"
                                                                id="openDecBtn" name="openDecBtn">Declare</button>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </form>


                                        <div class="form-group">

                                            <div id="error2"></div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="guess_winners_div" style="display: ">

                    <div class="col-12 col-sm-12 col-lg-12">

                        <div class="row">

                            <div class="col-sm-12 col-12 ">

                                <div class="table-responsive" id="table_wrapper" style="display: none;">
                                    <table class="table table-striped table-bordered" id="guess_winners_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Game Name</th>
                                                <th>User Name</th>
                                                <th>Amount</th>
                                                <th>Win Amount</th>
                                                <th>Bid Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Winner rows will be dynamically inserted here -->
                                        </tbody>
                                    </table>
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
                                            <input class="form-control digits" type="date" name="result_star_pik_date"
                                                id="result_star_pik_date" value="2024-09-06" max="2024-09-06">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="getStarlineResultHistory">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Game Name</th>
                                            <th>Result Date</th>
                                            <!-- <th>Declare Date</th> -->
                                            <th>Digit</th>
                                            <th>Panna</th>
                                            <th>Action</th>


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

    <!-- Edit Bid Modal -->
    <div class="modal fade" id="editBidModal" tabindex="-1" aria-labelledby="editBidModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBidModalLabel">Edit Bid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBidForm">
                        <input type="hidden" id="bid_id" name="bid_id">
                        <input type="hidden" id="starline_gtype_id" name="starline_gtype_id">

                        <div class="mb-3">
                            <label for="bid_point" class="form-label">Bid Point</label>
                            <input type="number" class="form-control" id="bid_point" name="bid_point" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .select2-container {
            width: 398.984px !important;
        }
    </style>

    <!-- jQuery (required for Bootstrap's JavaScript) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 JS (ensure it's included after jQuery) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.getElementById('starline_result_dec_date');
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.getElementById('result_star_pik_date');

            // Set the min attribute to today's date
            dateInput.setAttribute('max', today);

            // Set the value to today's date
            dateInput.value = today;
        });
    </script>

    {{-- Result declare script --}}
    <script>
        document.getElementById('open_number').addEventListener('change', function() {
            const selectedNumber = this.value;

            if (!selectedNumber) {
                // Clear the result if no option is selected
                document.getElementById('open_result').value = '';
                return;
            }

            // Function to sum digits and get the last digit if sum > 9
            function sumDigits(num) {
                let sum = num.split('').reduce((acc, digit) => acc + parseInt(digit), 0);

                // If the sum is greater than 9, get the last digit
                if (sum > 9) {
                    sum = sum % 10; // Use modulus operator to get the last digit
                }

                return sum;
            }

            // Get the sum of the digits
            const result = sumDigits(selectedNumber);

            // Set the result in the input field
            document.getElementById('open_result').value = result;
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

            let winnerBtn = document.getElementById("winnerShowbtn");

            // Toggle the 'display-none' class
            winnerBtn.classList.toggle("display-none");
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
        });

        // Update hidden result_dec_date when the date input changes
        function updateDateHidden() {
            const selectedDate = document.getElementById('starline_result_dec_date').value;
            document.getElementById('result_dec_date_hidden').value = selectedDate; // Set value in hidden input
        }
    </script>

    <script>
        function toggleDiv() {
            var gameSelect = document.getElementById("game_id");
            var selectedValue = gameSelect.value;
            var resultDiv = document.getElementById("result_div");
            var errorMessage = document.getElementById("error");

            errorMessage.innerHTML = '';

            if (selectedValue !== '0') {
                // Display the selected game name
                resultDiv.style.display = 'block';
            } else {
                // Hide the result div and show error message
                resultDiv.style.display = 'none';
                errorMessage.innerHTML = 'Please select a game time.';
            }
        }
    </script>

    {{-- For Showing Result History --}}
    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize the DataTable
            var table = $('#getStarlineResultHistory').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('starline_result_history_list') }}",
                    type: 'get',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                        d.result_star_pik_date = $('#result_star_pik_date')
                            .val(); // Add selected date as filter
                    }
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'starline_name'
                    },
                    {
                        data: 'result_date'
                    },
                    {
                        data: 'digit'
                    },

                    {
                        data: 'panna'
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
            $('#result_star_pik_date').on('change', function() {
                table.ajax.reload(); // Reload DataTable with new date filter
            });

            // Handle delete button click
            $('#getStarlineResultHistory').on('click', '.delete-btn', function() {
                var resultId = $(this).data('id');

                if (confirm("Are you sure you want to delete this result?")) {
                    $.ajax({
                        url: "{{ route('starline_delete_result') }}", // Route to handle deletion
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
        ];

        document.getElementById("open_number").addEventListener("input", function() {
            let inputValue = this.value.trim();
            let errorElement = document.getElementById("pannaError");

            // Check if input length is exactly 3 digits
            if (inputValue.length !== 3) {
                errorElement.textContent = "Panna must be exactly 3 digits.";
                this.style.borderColor = "red";
                return;
            }

            // Check if input exists in pannaArray
            if (!pannaArray.includes(inputValue)) {
                errorElement.textContent = "Invalid Panna. Please enter a valid 3-digit Panna.";
                this.style.borderColor = "red";
                return;
            }

            // If valid, clear error message and reset border color
            errorElement.textContent = "";
            this.style.borderColor = "";
        });
    </script>



    <script>
        $(document).ready(function() {
            // Fetch and display winners when the button is clicked
            $("#winnerShowbtn").on("click", function() {
                $.ajax({
                    url: "/starline_result_guess", // Adjust the route if needed
                    type: "POST",
                    data: {
                        game_id: $("#game_id_hidden").val(),
                        open_result: $("#open_result").val(),
                        open_number: $("#open_number").val(),
                        result_dec_date: $("#result_dec_date_hidden").val(),
                        _token: $('meta[name="csrf-token"]').attr(
                            "content") // CSRF token for Laravel
                    },
                    success: function(response) {
                        updateWinnersTable(response.guess_winners);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                    }
                });
            });

            // Function to update the winners table dynamically
            function updateWinnersTable(winners) {
                let tableWrapper = $("#guess_winners_table").parent(); // Get the table container
                let tableBody = $("#guess_winners_table tbody");

                tableBody.empty(); // Clear existing table data

                if (winners.length > 0) {
                    $.each(winners, function(index, winner) {
                        let row = `<tr>
                    <td>${index + 1}</td>
                    <td>${winner.market_name}</td>
                    <td>${winner.user_name}</td>
                    <td>${winner.bid_point}</td>
                    <td>${winner.winning_amount}</td>
                    <td>${winner.bid_type}</td>
                    <td>
                        <button class="btn btn-primary edit-btn" data-id="${winner.bid_id}" 
                                data-gtype="${winner.gtype}" 
                                data-point="${winner.bid_point}">
                            Edit
                        </button>
                    </td>
                </tr>`;
                        tableBody.append(row);
                    });

                    tableWrapper.show(); // Show the table if there are winners

                } else {

                    tableBody.append(`<tr><td colspan="7" class="text-center text-danger">No winners found.</td></tr>`);
                    tableWrapper.hide(); // Hide the table if no winners
                }
            }

            // Show the modal when clicking the "Edit" button
            $(document).on("click", ".edit-btn", function() {
                let bidId = $(this).data("id");
                let starlineGtypeId = $(this).data("gtype");
                let bidPoint = $(this).data("point");

                $("#bid_id").val(bidId);
                $("#starline_gtype_id").val(starlineGtypeId);
                $("#bid_point").val(bidPoint);

                $("#editBidModal").modal("show"); // Show the modal
            });

            // Handle form submission for updating bid_point
            $("#editBidForm").on("submit", function(e) {
                e.preventDefault();

                let bidId = $("#bid_id").val();
                let starlineGtypeId = $("#starline_gtype_id").val();
                let bidPoint = $("#bid_point").val();

                $.ajax({
                    url: "/starlineBidEdit", // Your Laravel update route
                    type: "POST",
                    data: {
                        bid_id: bidId,
                        starline_gtype_id: starlineGtypeId,
                        bid_point: bidPoint,
                        _token: $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(response) {
                        if (response.success) {
                            alert("Bid updated successfully!");
                            $("#editBidModal").modal("hide");
                            $("#winnerShowbtn").click(); // Refresh the table
                        } else {
                            alert("Error updating bid!");
                        }
                    },
                    error: function() {
                        alert("Failed to update bid!");
                    }
                });
            });
        });
    </script>
@endsection


@endsection
