<div class="container-fluid">
    <div class="row" style="background: var(--primary); padding: 10px;">
        <div class="col-12">
            <span style="color: var(--white);">Single Panna</span>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 30px; margin-bottom: 30px;">
    <form id="game_form">
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
                            Open </label>
                        <label class="form-label" for="timetype">
                            <input type="radio" name="timetype" id="timetype2" value="close" />
                            Close </label>
                    @else
                        <label class="form-label" for="timetype" style="width: 100px; float: left;">
                            <input type="radio" name="timetype" id="timetype1" value="open" disabled />
                            Open </label>
                        <label class="form-label" for="timetype">
                            <input type="radio" name="timetype" id="timetype2" value="close" checked />
                            Close </label>
                    @endif
                </div>
            </div>

            <div class="col-12" id="single_panna_pannabox">
                <div class="form-group">
                    <label for="single_panna_combinations" class="form-label">Enter Panna:</label>
                    <input type="text" class="form-control" name="single_panna_panna" id="single_panna_combinations"
                        maxlength="3" placeholder="Enter Panna" required="required"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3)" />
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label class="form-label" for="amount">Enter Amount</label>
                    <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter Amount"
                        required="required" />
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <input type="hidden" name="gtype_id" id="gtype" value="{{ $gtype_id }}">
                    <input type="hidden" name="market_id" id="game" value="{{ $market_id }}">
                    <button type="submit" id="gameSubmit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Toast Container -->
<div id="toast-container"
    style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; display: none;">
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // List of valid panna combinations
    const single_panna_combinations_list = [
        '120', '123', '124', '125', '126', '127', '128', '129', '130', '134', '135', '136',
        '137', '138', '139', '140', '145', '146', '147', '148', '149', '150', '156', '157', '158', '159',
        '160', '167', '168', '169', '170', '178', '179', '180', '189', '190', '230', '234', '235', '236',
        '237', '238', '239', '240', '245', '246', '247', '248', '249', '250', '256', '257', '258', '259',
        '260', '267', '268', '269', '270', '278', '279', '280', '289', '290', '340', '345', '346', '347',
        '348', '349', '350', '356', '357', '358', '359', '360', '367', '368', '369', '370', '378', '379',
        '380', '389', '390', '450', '456', '457', '458', '459', '460', '467', '468', '469', '470', '478',
        '479', '480', '489', '490', '560', '567', '568', '569', '570', '578', '579', '580', '589', '590',
        '670', '678', '679', '680', '689', '690', '780', '789', '790', '890'
    ];

    // Function to show toast
    function showToast(message, type = 'success') {
        const toastId = `toast_${Date.now()}`;
        const toastHtml = `
            <div id="${toastId}" class="toast ${type}" style="
                background-color: ${type === 'success' ? '#4caf50' : '#f44336'};
                color: white;
                padding: 15px;
                border-radius: 5px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                min-width: 300px;
                max-width: 90%;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                margin-bottom: 10px;
                text-align: center;
            ">
                <span>${message}</span>
                <button style="
                    background: none;
                    border: none;
                    color: white;
                    font-size: 16px;
                    cursor: pointer;
                    margin-left: 10px;
                " onclick="removeToast('${toastId}')">&times;</button>
            </div>
        `;

        $('#toast-container').append(toastHtml).fadeIn();

        // Auto-remove toast after 3 seconds
        setTimeout(() => {
            removeToast(toastId);
        }, 3000);
    }

    // Function to remove toast
    function removeToast(toastId) {
        $(`#${toastId}`).fadeOut(() => $(`#${toastId}`).remove());
    }

    $(document).ready(function() {
        const pannaInput = $('#single_panna_combinations');
        const gdateInput = $('#gdate'); // Reference to gdate input

        // Handle form submission
        $('#game_form').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            const pannaValue = pannaInput.val();
            if (pannaValue.length === 3 && !single_panna_combinations_list.includes(pannaValue)) {
                showToast('Value is not a valid single panna.', 'error');
                return; // Stop form submission
            }

            const formData = {
                _token: $('input[name="_token"]').val(),
                gdate: gdateInput.val(), // Send current value of gdate
                timetype: $('input[name="timetype"]:checked').val(),
                single_panna_panna: pannaValue,
                amount: $('#amount').val(),
                gtype_id: $('#gtype').val(),
                market_id: $('#game').val(),
            };

            $.ajax({
                url: '/singlePannaGame_insert', // Laravel route for insertion
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showToast('Game data successfully submitted!', 'success');

                        // Manually reset the fields while preserving gdate
                        $('#single_panna_combinations').val(''); // Clear panna input
                        $('#amount').val(''); // Clear amount input
                         // Uncheck session inputs
                        // $('input[name="timetype"]:checked').prop('checked',
                        // false);
                    } else {
                        if (response.message) {
                            showToast(response.message,
                            'error'); // Display error message from backend
                        } else {
                            showToast('Something went wrong.', 'error');
                        }
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.message) {
                        showToast(response.message,
                        'error'); // Display error message from backend
                    } else {
                        showToast('Something went wrong. Please try again.', 'error');
                    }
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
