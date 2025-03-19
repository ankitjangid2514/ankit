<div class="container-fluid">
    <div class="row" style="background: var(--primary); padding: 10px;">
        <div class="col-12">
            <span style="color: var(--white);">Half Sangam</span>
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

            <div class="col-12" id="groupbox">
                <p class="session_title">Choose Session</p>
                <div class="form-group d-flex">
                    <label style="color: #FFF;">
                        <input type="radio" name="timetype" id="openSession" value="open" checked>
                        Open
                    </label>
                    <label style="margin-left: 10px; color:#FFF;">
                        <input type="radio" name="timetype" id="closeSession" value="close">
                        Close
                    </label>
                </div>
            </div>

            <div id="half_sangam_1">
                <div class="col-12">
                    <div class="form-group">
                        <label for="open_digit_sangam_half_a" class="form-label">Open Digit:</label>
                        <input type="text" class="form-control" name="open_digit_sangam_half_a"
                            id="open_digit_sangam_half_a" maxlength="3" placeholder="Enter Open Digit"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1)" />
                    </div>
                    <div class="form-group">
                        <label for="close_panna_sangam_half_a" class="form-label">Close Panna:</label>
                        <input type="text" class="form-control" name="close_panna_sangam_half_a"
                            id="close_panna_sangam_half_a" maxlength="3" placeholder="Enter Close Panna"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3)" />
                    </div>
                </div>
            </div>

            <div id="half_sangam_2" style="display: none;">
                <div class="col-12">
                    <div class="form-group">
                        <label for="close_digit_half_sangam_b" class="form-label">Close Digit:</label>
                        <input type="text" class="form-control" name="close_digit_half_sangam_b"
                            id="close_digit_half_sangam_b" maxlength="3" placeholder="Enter Close Digit"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1)" />
                    </div>
                    <div class="form-group">
                        <label for="open_panna_half_sangam_b" class="form-label">Open Panna:</label>
                        <input type="text" class="form-control" name="open_panna_half_sangam_b"
                            id="open_panna_half_sangam_b" maxlength="3" placeholder="Enter Open Panna"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3)" />
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label class="form-label" for="amount">Enter Amount</label>
                    <input type="number" class="form-control" name="amount" id="amount"
                        placeholder="Enter Amount" required />
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <input type="hidden" name="gtype_id" id="gtype" value="{{ $gtype_id }}">
                    <input type="hidden" name="market_id" id="game" value="{{ $market_id }}">
                    <button type="button" id="gameSubmit"
                        style="padding: 5px 10px; background-color: #ffc827; border-radius: 10px;">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .toast-middle-center {
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        position: fixed;
        z-index: 1055;
    }
</style>

<script>
    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        positionClass: "toast-middle-center", // Custom position
        preventDuplicates: false,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "1000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    };
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('haffSangamToggleButton');
        const timeTypeInput = document.querySelector('input[name="timetype"]');
        const halfSangam1 = document.getElementById('half_sangam_1');
        const halfSangam2 = document.getElementById('half_sangam_2');
        const gameSubmit = document.getElementById('gameSubmit');
        const allPanna = [
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

        function validatePannaInput(inputId) {
            const input = document.getElementById(inputId);
            const value = input.value.trim();

            if (value.length !== 3 || !/^\d{3}$/.test(value)) {
                toastr.error('Please enter a valid three-digit number.', 'Invalid Input');
                return false;
            }
            if (!allPanna.includes(value)) {
                toastr.error(`The value "${value}" is not a valid panna.`, 'Invalid Input');
                return false;
            }
            return true;
        }


        gameSubmit.addEventListener('click', function () {
            const isValid = timeTypeInput.value === 'open'
                ? validatePannaInput('close_panna_sangam_half_a')
                : validatePannaInput('open_panna_half_sangam_b');

            if (!isValid) return;

            const formData = new FormData(document.getElementById('game_form'));

            $.ajax({
                url: "{{ route('halfSangamGame_insert') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    toastr.success(response.message, 'Success');
                },
                error: function (error) {
                    toastr.error(error.responseJSON?.message || 'An error occurred.', 'Error');
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
    const openSession = document.getElementById('openSession');
    const closeSession = document.getElementById('closeSession');
    const timeTypeInput = document.querySelector('input[name="timetype"]');
    const halfSangam1 = document.getElementById('half_sangam_1');
    const halfSangam2 = document.getElementById('half_sangam_2');

    // Add event listeners for radio buttons
    openSession.addEventListener('change', function () {
        if (openSession.checked) {
            timeTypeInput.value = 'open';
            halfSangam1.style.display = 'block';
            halfSangam2.style.display = 'none';
        }
    });

    closeSession.addEventListener('change', function () {
        if (closeSession.checked) {
            timeTypeInput.value = 'close';
            halfSangam1.style.display = 'none';
            halfSangam2.style.display = 'block';
        }
    });
});

</script>
