<div class="container-fluid">
    <div class="row" style="background: var(--primary); padding: 10px;">
        <div class="col-12">
            <span style="color: var(--white);">Jodi Digit</span>
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
                        placeholder="Choose Date" value="" />
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label class="form-label" for="digit" id="xyz1">Enter Digit</label>
                    <input type="number" class="form-control" name="digit" id="digit" maxlength="2"
                        placeholder="Enter Digit" required="required" />
                    <small id="digit_error" style="color: red; display: none;">Invalid value. Please enter a valid
                        Jodi.</small>
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
                    <input type="submit" id="gameSubmit" value="Submit">
                </div>
            </div>
        </div>
    </form>
</div>

<div id="toaster" class="toaster"></div>

<script>
    $(document).ready(function() {
        // Define valid Jodi digits
        const jodi = [
            12, 17, 21, 26, 62, 67, 71, 76,
            13, 18, 31, 36, 63, 68, 81, 86,
            14, 19, 41, 46, 64, 69, 91, 96,
            01, 06, 10, 15, 51, 56, 60, 65,
            23, 28, 32, 37, 73, 78, 82, 87,
            24, 29, 42, 47, 74, 79, 92, 97,
            02, 07, 20, 25, 52, 57, 70, 75,
            34, 39, 43, 48, 84, 89, 93, 98,
            03, 08, 30, 35, 53, 58, 80, 85,
            04, 09, 40, 45, 54, 59, 90, 95,
            05, 16, 27, 38, 49, 50, 61, 72, 83, 94,
            00, 11, 22, 33, 44, 55, 66, 77, 88, 99
        ];

        // Handle the digit input field
        const digitInput = $('#digit');
        const digitError = $('#digit_error');

        digitInput.on('input', function() {
            let value = digitInput.val();

            // Remove non-numeric characters and restrict to two digits
            value = value.replace(/\D/g, '').slice(0, 2);
            digitInput.val(value);

            // Validate Jodi digit
            if (value.length === 2) {
                if (jodi.includes(parseInt(value, 10))) {
                    digitError.hide();
                } else {
                    digitError.show();
                }
            } else {
                digitError.hide();
            }
        });

        // Handle form submission
        $(document).ready(function() {
            $('#game_form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                const formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: "{{ route('jodiGame_insert') }}", // Use your route here
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        showToaster(response.message ||
                            'Data submitted successfully!', 'success');
                        $('#digit').val('');
                        $('#amount').val('');
                        $('#digit_error').hide();
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON || {};
                        const message = response.message || 'An error occurred.';
                        showToaster(message, 'error');
                    },
                });
            });

            // Function to show toaster message
            function showToaster(message, type) {
                const toaster = $('#toaster');
                toaster.removeClass('toaster-success toaster-error')
                    .addClass(type === 'success' ? 'toaster-success' : 'toaster-error');
                toaster.text(message).fadeIn();

                setTimeout(() => {
                    toaster.fadeOut();
                }, 3000);
            }
        });



    });
</script>

<style>
    .toaster {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1050;
        display: none;
        min-width: 300px;
        background-color: #fff;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        font-weight: bold;
    }

    .toaster-success {
        border: 2px solid #28a745;
        color: #28a745;
    }

    .toaster-error {
        border: 2px solid #dc3545;
        color: #dc3545;
    }
</style>
