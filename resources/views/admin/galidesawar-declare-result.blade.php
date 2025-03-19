@extends('admin.layouts.main')
@section('title')
Declare Result
@endsection
@section('container')

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

                                                <div class="date-picker">

                                                    <div class="input-group">

                                                        <input class="form-control digits" type="date"
                                                            name="result_dec_date" id="desawar_result_dec_date"
                                                            value="">

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="form-group col-md-3">

                                                <label>Game Name </label>

                                                <select class="form-control" name="game_id" id="game_id">
                                                    <option value="0">Select Name</option>
                                                    @foreach ($data as $game)
                                                    <option value="{{ $game->id }}">{{ $game->desawar_name }}
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
                                    <form action="{{ route('desawar_result') }}" method="POST">
                                        @csrf <!-- CSRF token for form security -->
                                        <div class="row close_panna_area">
                                            <div class="col-12 col-md-12">
                                                <div class="row">

                                                    <!-- Hidden input for game_id -->
                                                    <input type="hidden" name="game_id" id="game_id_hidden"
                                                        value="">

                                                    <!-- Hidden input for result_dec_date -->
                                                    <input type="hidden" name="result_dec_date"
                                                        id="result_dec_date_hidden" value="">

                                                    <div class="form-group col-md-4">

                                                        <label>Digit:</label>
                                                        <script>
                                                            // Define the allowed jodi values
                                                            const jodi = [
                                                                "00", "01", "02", "03", "04", "05", "06", "07", "08", "09",
                                                                "10", "11", "12", "13", "14", "15", "16", "17", "18", "19",
                                                                "20", "21", "22", "23", "24", "25", "26", "27", "28", "29",
                                                                "30", "31", "32", "33", "34", "35", "36", "37", "38", "39",
                                                                "40", "41", "42", "43", "44", "45", "46", "47", "48", "49",
                                                                "50", "51", "52", "53", "54", "55", "56", "57", "58", "59",
                                                                "60", "61", "62", "63", "64", "65", "66", "67", "68", "69",
                                                                "70", "71", "72", "73", "74", "75", "76", "77", "78", "79",
                                                                "80", "81", "82", "83", "84", "85", "86", "87", "88", "89",
                                                                "90", "91", "92", "93", "94", "95", "96", "97", "98", "99"
                                                            ];

                                                            // Validate the input
                                                            function validateInput(input) {
                                                                const value = input.value.padStart(2, '0'); // Pad with 0 for single digits
                                                                if (!jodi.includes(value)) {
                                                                    input.value = ""; // Clear the input if not valid
                                                                    alert("Please enter a valid jodi value.");
                                                                }
                                                            }
                                                        </script>

                                                        <!-- Input Field -->
                                                        <input type="text" class="form-control" name="open_number"
                                                            id="open_number" data-placeholder="Select Digit"
                                                            maxlength="2" onblur="validateInput(this);" />

                                                    </div>




                                                    <div class="form-group col-md-4" id="open_div_msg">

                                                        <label>&nbsp;</label>
                                                        <!-- Save Button -->
                                                        <button type="button" class="btn btn-primary waves-light mr-1"
                                                            id="openSaveBtn" name="openSaveBtn"
                                                            onclick="OpenSaveData();">
                                                            Save
                                                        </button>

                                                        <!-- Show Winner Button (Initially Hidden) -->
                                                        <!-- Show Winner Button (Initially Hidden) -->
                                                        <button type="button" class="btn btn-primary waves-light mr-1"
                                                            id="showWinnerBtn" name="showWinnerBtn"
                                                            style="display: none;" onclick="winnerShow();">
                                                            Show Winner
                                                        </button>



                                                        <!-- Ensure this script is placed before the closing </body> tag -->


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
        </div>
        <!-- Winner Table (Initially Hidden) -->
        <!-- Winner Table (Initially Hidden) -->
        <!-- <div class="row"> -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="showWinnerTable" style="display: none;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User Name</th>
                                        <th>Bid Point</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Rows will be populated dynamically by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <!-- </div> -->


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
                                        <th>Declare Date</th>
                                        <th>Number</th>
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
<style>
    .select2-container {
        width: 398.984px !important;
    }
</style>

<!-- Edit Winner Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Winner Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editWinnerForm">
                    <div class="mb-3">
                        <label for="modalBidId" class="form-label">Bid ID</label>
                        <input type="text" class="form-control" id="modalBidId" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="modalBidPoint" class="form-label">Bid Point (Digit)</label>
                        <input type="number" class="form-control" id="modalBidPoint" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveEditBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery (required for Bootstrap's JavaScript) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 JS (ensure it's included after jQuery) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


@section('script')
{{-- For Showing Today Date --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const dateInput = document.getElementById('desawar_result_dec_date');
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



        // Perform your save operation here (e.g., sending data to the server)

        // Show the "Show Winner" button after saving
        document.getElementById('showWinnerBtn').style.display = 'inline-block';

        // Show the Declare button after saving (if applicable)
        document.getElementById('openDecBtn')?.classList.remove('display_none');
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
        const selectedDate = document.getElementById('desawar_result_dec_date').value;
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


{{-- For Getting The Today Date --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const year = today.getFullYear();
        const month = ('0' + (today.getMonth() + 1)).slice(-2); // Adding 1 because months are 0-indexed
        const day = ('0' + today.getDate()).slice(-2); // Ensure two digits for day

        const formattedDate = $ {
            year
        } - $ {
            month
        } - $ {
            day
        };
        const dateInput = document.getElementById('result_star_pik_date');

        // Set the max attribute to today's date
        dateInput.setAttribute('max', formattedDate);

        // Set the value to today's date
        dateInput.value = formattedDate;
    });
</script>

{{-- For Showing result history according to selected date --}}
<script>
    $(document).ready(function() {
        // Initialize the DataTable
        var table = $('#getStarlineResultHistory').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('desawar_result_history_list') }}",
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
                    data: 'desawar_name'
                },
                {
                    data: 'result_date'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'digit'
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

        $('#getStarlineResultHistory').on('click', '.delete-btn', function() {
            var resultId = $(this).data('id');

            if (confirm("Are you sure you want to delete this result?")) {
                $.ajax({
                    url: "{{ route('desawar_delete_result') }}", // Route to handle deletion
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
    function winnerShow() {
        console.log("winnerShow function triggered");

        const openNumber = document.getElementById('open_number').value;
        const resultDecDate = document.getElementById('desawar_result_dec_date').value;
        const gameId = document.getElementById('game_id').value;

        if (!openNumber || !resultDecDate || !gameId) {
            alert('Please ensure all fields (Open Number, Result Date, and Game ID) are filled.');
            return;
        }

        $.ajax({
            url: '{{ url('showDesawarwinner')}}', // Ensure this route is correct
            type: 'POST',
            data: {
                open_number: openNumber,
                result_dec_date: resultDecDate,
                game_id: gameId,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            dataType: 'json',
            success: function(response) {
                console.log("Response received:", response);

                // Check if the response has status 'success' and contains data
                if (response.status === 'success' && Array.isArray(response.data) && response.data.length > 0) {
                    // Show the table
                    document.getElementById('showWinnerTable').style.display = 'table';

                    // Clear existing rows
                    const tableBody = document.querySelector('#showWinnerTable tbody');
                    tableBody.innerHTML = '';

                    // Loop through the data and populate the table
                    response.data.forEach(function(winner, index) {
                        const row = document.createElement('tr');

                        // Add data cells
                        row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${winner.user_name}</td>
                    <td>${winner.bid_point}</td>
                    <td>${winner.winning_amount}</td>
                    <td>
                        <button class="btn btn-info" onclick="editDetails(${winner.bid_id})">Edit</button>
                    </td>
                `;

                        // Append the row to the table body
                        tableBody.appendChild(row);
                    });
                } else {
                    alert('No winners found!');
                    // Hide the table if no winners
                    document.getElementById('showWinnerTable').style.display = 'none';
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred while fetching winners.');
            }
        });

    }

    // Optional: Example action button function (e.g., to view details)
</script>


<script>
    // Function to edit bid details
    // Function to edit bid details
    function editDetails(bidId) {
        if (!bidId || isNaN(bidId)) {
    alert('Invalid Bid ID. Please try again.');
    var jay = bidId;
    console.error('Invalid Bid ID:', bidId);
    return;
}


        console.log("Edit details for bidId:", bidId);

        // Make AJAX request to get the bid details
        $.ajax({
                url: '/showDesawarwinnerEdit', // Ensure this route is correct
                type: 'GET',
                data: {
                    bid_id: bidId, // Send the correct bid_id to the backend
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        console.log("Bid ID received:", response.data.bid_id); // Log bid_id to check if it's correct
                        $('#modalBidId').val(response.data.id); // Set bid_id in the modal input
                        $('#modalUserName').val(response.data.user_name);
                        $('#modalBidAmount').val(response.data.winning_amount);
                        $('#modalBidPoint').val(response.data.digit); // Populate the bid point (digit)
                        if (!response.data.id) {
    alert('Error: Received empty Bid ID from the server.');
    console.error('Empty Bid ID received:', response);
    return;
}


                        // Show the modal
                        $('#editModal').modal('show'); // This opens the modal
                    } else {
                        alert('Error fetching bid details.');
                    }
             

            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred while fetching bid details.');
            }
        });
    }

    // Function to save updated bid point (digit)
    $('#saveEditBtn').click(function() {
        const bidId = $('#modalBidId').val(); // Ensure bid_id is populated
        const newBidPoint = $('#modalBidPoint').val(); // Get the new bid point (digit)

        // Validate input
        if (!newBidPoint || newBidPoint <= 0) {
            alert('Please enter a valid bid point.');
            return;
        }

        // Make AJAX request to update the digit (bid point)
        $.ajax({
            url: '/updateDesawarwinner', // Define the update route in routes/web.php
            type: 'POST',
            data: {
                bid_id: bidId, // Ensure bid_id is passed
                digit: newBidPoint, // Send the new bid point (digit)
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert('Bid point updated successfully!');
                    $('#editModal').modal('hide'); // Close the modal
                    // Optionally, update the table row dynamically here
                    updateTableRow(bidId, newBidPoint);
                } else {
                    alert('Error updating bid point.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred while updating the bid point.');
            }
        });
    });


    // Function to update the table row with the new bid point
    function updateTableRow(bidId, newBidPoint) {
        const table = document.getElementById('showWinnerTable');
        const rows = table.getElementsByTagName('tr');

        // Find the row with the matching bidId and update the bid point
        for (let row of rows) {
            const bidIdCell = row.cells[0];
            if (bidIdCell && bidIdCell.innerText == bidId) {
                row.cells[2].innerText = newBidPoint; // Update the bid point (3rd column)
                break;
            }
        }
    }
</script>

@endsection