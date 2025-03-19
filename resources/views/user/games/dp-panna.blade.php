<div class="container" style="margin-bottom: 30px; height:95vh;">
    <form method="POST" id="game_form" action="{{ route('game_insert_game') }}">
        @csrf
        <div class="row">
            <span id="output"></span>
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label" for="gdate">Choose Date</label>
                    <input type="date" class="form-control" name="gdate" id="gdate" readonly
                        placeholder="Choose Date" value="" />
                </div>
            </div>

            <div class="col-12" id="groupbox">
                <p class="session_title">Choose Session</p>
                <div class="form-group">
                    @if ($current_time <= $open_time)
                        <label class="form-label" for="timetype" style="width: 100px; float: left;">
                            <input type="radio" name="timetype" id="timetype1" value="open" checked />
                            Open
                        </label>

                        <label class="form-label" for="timetype">
                            <input type="radio" name="timetype" id="timetype2" value="close" />
                            Close
                        </label>
                    @else
                        <label class="form-label" for="timetype" style="width: 100px; float: left;">
                            <input type="radio" name="timetype" id="timetype1" value="open" disabled />
                            Open
                        </label>

                        <label class="form-label" for="timetype">
                            <input type="radio" name="timetype" id="timetype2" value="close" checked />
                            Close
                        </label>
                    @endif
                </div>
            </div>

            <!-- Input for Panna and Button -->
            <div class="container">
                <div class="row d-flex justify-content-between">
                    <div class="form-group text-center" style="width: 45%;">
                        <label class="form-label" for="panna_input">Digit</label>
                        <input type="number" class="form-control" name="panna_input" id="panna_input"
                            placeholder="Enter Panna" required="required">
                    </div>
                    <div class="form-group text-center" style="width: 45%;">
                        <label class="form-label" for="amount">Enter Amount</label>
                        <input type="number" class="form-control" name="amount" id="amount"
                            placeholder="Enter Amount" required="required">
                    </div>
                </div>

                <div class="row">
                    <div class="w-100 mt-3 mx-4">
                        <button type="button" id="generate_double_panna_button"
                            class="btn btn-primary w-100 mb-2">Abb</button>
                    </div>
                </div>

                <!-- Display Generated Combinations in Table -->
                <div class="table-container">
                    <table class="table table-striped table-bordered" id="combinations-table"
                        style="display: none;">
                        <thead>
                            <tr>
                                <th class="text-white">Panna</th>
                                <th class="text-white">Point</th>
                                <th class="text-white">Action</th>
                            </tr>
                        </thead>
                        <tbody id="combinations-table-body"></tbody>
                    </table>
                </div>
                <div id="double-panna-summary" style="display: none;">
                    <p class="text-white" style="font-size: 1.5rem;">
                        Double Panna Count: <span id="double-panna-count" class="text-white">0</span>
                    </p>
                    <p class="text-white" style="font-size: 1.5rem;">
                        Total Amount: <span id="double-panna-total-amount" class="text-white">0</span>
                    </p>
                </div>

                <!-- Submit and Cancel Buttons -->
                <div class="row justify-content-center mb-5" id="bid-submit-container"
                    style="display: none;">
                    <div class="col-12 col-md-6 mb-2">
                        <input type="submit" id="submit-all-bids" class="btn btn-success w-100"
                            value="Submit All Bids">
                    </div>
                    <div class="col-12 col-md-6">
                        <button type="button" id="cancel-bids" class="btn btn-danger w-100">Cancel
                            Bids</button>
                    </div>
                </div>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="all_bids" id="all_bids">
            <input type="hidden" name="gtype_id" id="gtype" value="{{ $gtype_id }}">
            <input type="hidden" name="market_id" id="game" value="{{ $market_id }}">
        </div>
    </form>
</div>

<script>
    // Toastr configuration for showing alerts
    toastr.options = {
        "positionClass": "toast-top-center",  // Standard position for toast message
        "timeOut": "1000",                    // Display toast for 3 seconds
        "closeButton": true                   // Show close button in the toast
    };

    // Function to generate Double Panna combinations
    function generateDoublePanna(inputNumber) {
        const digits = inputNumber.toString().split('').filter((d) => d !== '0');
        const uniqueDigits = [...new Set(digits)];
        const pannaResults = [];

        function generateAAB() {
            uniqueDigits.forEach((a) => {
                uniqueDigits.forEach((b) => {
                    if (a !== b && parseInt(a) < parseInt(b)) {
                        pannaResults.push(`${a}${a}${b}`);
                    }
                });
            });
        }

        function generateABB() {
            uniqueDigits.forEach((a) => {
                uniqueDigits.forEach((b) => {
                    if (a !== b && parseInt(a) < parseInt(b)) {
                        pannaResults.push(`${a}${b}${b}`);
                    }
                });
            });
        }

        function generateSpecialCases() {
            uniqueDigits.forEach((a) => {
                pannaResults.push(`${a}00`);
                pannaResults.push(`${a}${a}0`);
            });
        }

        if (inputNumber.toString().includes('0')) {
            generateSpecialCases();
            generateAAB();
            generateABB();
        } else {
            generateAAB();
            generateABB();
        }

        return [...new Set(pannaResults)].sort();
    }

    // Event Listener for Button Click
    document.getElementById('generate_double_panna_button').addEventListener('click', function() {
        const inputNumber = document.getElementById('panna_input').value;
        const amountInput = document.getElementById('amount').value;

        if (!inputNumber || isNaN(inputNumber) || inputNumber.length < 3) {
            toastr.error('Please enter a valid number with at least 3 digits.');
            return;
        }

        if (!amountInput || isNaN(amountInput) || amountInput <= 0) {
            toastr.error('Please enter a valid amount.');
            return;
        }

        const doublePanna = generateDoublePanna(inputNumber);
        const tableBody = document.getElementById('combinations-table-body');
        tableBody.innerHTML = ''; // Clear previous results

        const allBids = [];
        let totalAmount = 0;

        doublePanna.forEach((panna) => {
            allBids.push({
                panna_s: panna,
                amount: amountInput
            });
            totalAmount += parseInt(amountInput);

            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${panna}</td>
                <td>${amountInput}</td>
                <td><button type="button" class="btn btn-danger delete-row">Delete</button></td>
            `;

            row.querySelector('.delete-row').addEventListener('click', function() {
                row.remove();
                updateAllBids();
            });

            tableBody.appendChild(row);
        });

        document.getElementById('combinations-table').style.display = 'table';
        document.getElementById('bid-submit-container').style.display = 'flex';
        updateAllBids();

        const summaryContainer = document.getElementById('double-panna-summary');
        document.getElementById('double-panna-count').textContent = allBids.length;
        document.getElementById('double-panna-total-amount').textContent = totalAmount;

        summaryContainer.style.display = allBids.length > 0 ? 'block' : 'none';

        function updateAllBids() {
            const rows = document.querySelectorAll('#combinations-table-body tr');
            const updatedBids = [];

            rows.forEach(row => {
                const panna = row.children[0].textContent.trim();
                updatedBids.push(panna);
            });

            document.getElementById('all_bids').value = JSON.stringify(allBids);
        }

        toastr.success('Double Panna combinations generated successfully!');
    });

    @if (session('success'))
        toastr.success('{{ session('success') }}');
    @elseif (session('error'))
        toastr.error('{{ session('error') }}');
    @endif
</script>

<style>
 /* Override default Toastr position */
.toast-container {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
}

/* Style for toast close button */
.toast-close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    font-weight: bold;
    cursor: pointer;
    margin-left: 10px;
}

/* Style for error toast */
.toast-container.error {
    background-color: #dc3545;
}

/* Custom styles for better visibility */
.toast-top-center {
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
}

</style>
<style>
    .table-container {
        max-height: 300px;
        /* Set the desired height */
        overflow-y: auto;
        /* Enable vertical scrolling */
        overflow-x: hidden;
        /* Disable horizontal scrolling */
    }

    .table {
        width: 100%;
        /* Ensure the table takes up full width */
        border-collapse: collapse;
        /* Optional: Clean table border spacing */
    }
    td{
        color: #fff;
    }
    .table-container::-webkit-scrollbar {
        width: 8px;
        /* Scrollbar width */
    }

    .table-container::-webkit-scrollbar-thumb {
        background-color: #888;
        /* Scrollbar color */
        border-radius: 4px;
        /* Round edges of the scrollbar */
    }

    .table-container::-webkit-scrollbar-thumb:hover {
        background-color: #555;
        /* Scrollbar color on hover */
    }
</style>