<div class="container-fluid">
    <div class="row" style="background: var(--primary);padding: 10px;">
        <div class="col-12">
            <span style="color:var(--white);">Full Sangam</span>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 30px; margin-bottom: 30px;">
    <form method="post" id="game_form">
        @csrf
        <div class="row">
            <span id="output"></span>
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label" for="gdate">Choose Date</label>
                    <input type="date" class="form-control" name="gdate" id="gdate" readonly
                        placeholder="Choose Date" value="{{ now()->toDateString() }}" />
                </div>
            </div>

            <div class="col-12" id="digitbox">
                <div class="form-group">
                    <label class="form-label" for="open_panna" id="xyz1">Enter Open Panna</label>
                    <input type="number" class="form-control" name="open_panna_full_sangam" id="open_panna_full_sangam"
                        placeholder="Enter Panna" required="required" />
                    <small id="open_panna_error" style="color: red; display: none;">Invalid value. Please
                        enter a valid Panna.</small>
                </div>
            </div>

            <div class="col-12" id="pannabox">
                <div class="form-group">
                    <label class="form-label" for="close_panna" id="xyz2">Enter Close Panna</label>
                    <input type="number" class="form-control" name="close_panna_full_sangam"
                        id="close_panna_full_sangam" placeholder="Enter Panna" required="required" />
                    <small id="close_panna_error" style="color: red; display: none;">Invalid value. Please
                        enter a valid Panna.</small>
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
                    <button type="button" id="gameSubmit"
                        style="padding: 10px; background-color: #ffc827; border-radius: 5px;">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const all_panna = [
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

        function validateInput(inputId, errorId) {
            const inputField = document.getElementById(inputId);
            const errorField = document.getElementById(errorId);
            const submitButton = document.getElementById('gameSubmit');

            inputField.addEventListener('input', () => {
                let value = inputField.value;

                // Restrict input to numeric only and exactly 3 digits
                value = value.replace(/\D/g, ''); // Remove non-numeric characters
                if (value.length > 3) {
                    value = value.slice(0, 3); // Restrict to 3 digits
                }
                inputField.value = value;

                // Show error if length is not exactly 3 or value is invalid
                if (value.length === 3) {
                    if (all_panna.includes(value)) {
                        errorField.style.display = 'none';
                        submitButton.disabled = false;
                    } else {
                        errorField.textContent = 'Invalid value. The given value is not a valid Panna.';
                        errorField.style.display = 'block';
                        submitButton.disabled = true;
                    }
                } else {
                    errorField.textContent = 'Input must be exactly 3 digits.';
                    errorField.style.display = 'block';
                    submitButton.disabled = true;
                }
            });
        }

        // Attach validation for both panna inputs
        validateInput('open_panna_full_sangam', 'open_panna_error');
        validateInput('close_panna_full_sangam', 'close_panna_error');

        // Handle form submission
        const gameSubmit = document.getElementById('gameSubmit');
        gameSubmit.addEventListener('click', function () {
            const openPanna = document.getElementById('open_panna_full_sangam').value;
            const closePanna = document.getElementById('close_panna_full_sangam').value;

            // Final validation on submit
            if (!all_panna.includes(openPanna) || !all_panna.includes(closePanna)) {
                toastr.error('Please ensure both Open Panna and Close Panna are valid 3-digit values.', 'Error');
                return;
            }

            const formData = new FormData(document.getElementById('game_form'));

            $.ajax({
                url: "{{ route('fullSangamGame_insert') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-middle-center',
                        timeOut: '3000',
                    };
                    toastr.success(response.message, 'Success');
                },
                error: function (error) {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-middle-center',
                        timeOut: '3000',
                    };
                    toastr.error(error.responseJSON?.message || 'An error occurred.', 'Error');
                }
            });
        });
    });
</script>


<style>
    .toast-middle-center {
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        position: fixed;
        z-index: 1055;
    }
</style>
