<div class="container-fluid">
    <div class="row" style="background: var(--primary); padding: 10px;">
        <div class="col-12">
            <span style="color: var(--white);">Triple Panna</span>
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
                    <input type="date" class="form-control" name="gdate" id="gdate" readonly placeholder="Choose Date" value="" />
                </div>
            </div>

            <div class="col-12" id="groupbox">
                <p class="session_title">Choose Session</p>
                <div class="form-group">
                    @if ($current_time <= $open_time)
                        <label class="form-label" for="timetype" style="width: 100px; float: left;">
                            <input type="radio" name="timetype" id="timetype1" value="open" checked /> Open
                        </label>
                        <label class="form-label" for="timetype">
                            <input type="radio" name="timetype" id="timetype2" value="close" /> Close
                        </label>
                    @else
                        <label class="form-label" for="timetype" style="width: 100px; float: left;">
                            <input type="radio" name="timetype" id="timetype1" value="open" disabled /> Open
                        </label>
                        <label class="form-label" for="timetype">
                            <input type="radio" name="timetype" id="timetype2" value="close" checked /> Close
                        </label>
                    @endif
                </div>
            </div>

            <div class="col-12" id="pannabox">
                <div class="form-group">
                    <label class="form-label" for="panna" id="xyz2">Enter Panna</label>
                    <input type="text" class="form-control" name="panna" id="panna" maxlength="3" placeholder="Enter Panna" required="required" />
                    <div id="error-message" style="color: red; display: none;">The entered value is not a valid triple panna.</div>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label class="form-label" for="amount">Enter Amount</label>
                    <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter Amount" required="required" />
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
    $(document).ready(function () {
        const triple_panna = ['111', '222', '333', '444', '555', '666', '777', '888', '999', '000'];
        const pannaInput = document.getElementById('panna');
        const amountInput = document.getElementById('amount');
        const errorMessage = document.getElementById('error-message');

        pannaInput.addEventListener('input', function () {
            let inputValue = pannaInput.value;

            // Allow only numeric input and trim length to 3
            pannaInput.value = inputValue.replace(/[^0-9]/g, '').slice(0, 3);
            inputValue = pannaInput.value;

            // Check if the input is valid
            if (inputValue.length === 3 && triple_panna.includes(inputValue)) {
                errorMessage.style.display = 'none'; // Hide error message if valid
            } else if (inputValue.length === 3) {
                errorMessage.style.display = 'block'; // Show error message if invalid
            } else {
                errorMessage.style.display = 'none'; // Hide error message for incomplete input
            }
        });

        $('#game_form').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission
            const formData = $(this).serialize(); // Serialize form data

            $.ajax({
                url: "{{ route('triplePannaGame_insert') }}", // Use your route here
                type: "POST",
                data: formData,
                success: function (response) {
                    showToaster('Data submitted successfully!', 'success');
                    // Reset specific fields, but not `gdate` or session fields
                    $('#panna').val('');
                    $('#amount').val('');
                    $('#error-message').hide();
                },
                error: function (xhr) {
                    const response = xhr.responseJSON || {};
                    let message = response.message || 'An error occurred.';
                    showToaster(message, 'error'); // Show error message in toaster
                },
            });
        });

        function showToaster(message, type) {
            const toaster = $('#toaster');
            toaster.removeClass('toaster-success toaster-error').addClass(type === 'success' ? 'toaster-success' : 'toaster-error');
            toaster.text(message).fadeIn();

            setTimeout(() => {
                toaster.fadeOut();
            }, 3000);
        }
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
