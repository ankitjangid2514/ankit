<div class="container-fluid">
    <div class="row" style="background: var(--primary); padding: 10px;">
        <div class="col-12">
            <span style="color: var(--white);">Double Panna</span>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 30px; margin-bottom: 30px;">
    <form method="POST" id="game_form">
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

            <div class="col-12" id="pannabox">
                <div class="form-group">
                    <label class="form-label" for="panna" id="xyz2">Enter Panna</label>
                    <input type="number" class="form-control" name="panna" id="panna" placeholder="Enter Panna"
                        required="required" oninput="if(this.value.length > 3) this.value = this.value.slice(0, 3);" />
                    <div id="error-message" style="color: red; display: none;">The entered value is not a valid double
                        panna.</div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="amount">Enter Amount</label>
                        <input type="number" class="form-control " name="amount" id="amount"
                            placeholder="Enter Amount" required="required" />
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
        </div>
    </form>
</div>

<div id="toaster" class="toaster"></div>

<script>
    $(document).ready(function() {
        const double_panna = [
            "100", "110", "112", "113", "114", "115", "116", "117", "118", "119",
            "122", "133", "144", "155", "166", "177", "188", "199", "200", "220",
            "223", "224", "225", "226", "227", "228", "229", "233", "244", "255",
            "266", "277", "288", "300", "330", "334", "335", "336", "337", "338",
            "339", "344", "355", "366", "377", "388", "399", "400", "440", "445",
            "446", "447", "448", "449", "455", "466", "477", "488", "499", "500",
            "550", "557", "558", "559", "566", "577", "588", "599", "600", "660",
            "667", "668", "669", "677", "688", "700", "770", "778", "779", "788",
            "799", "800", "880", "889", "890", "899", "900", "990","699"
        ];
        const min_bid_amount = 10; // Replace this with the actual minimum bid amount

        const pannaInput = document.getElementById('panna');
        const amountInput = document.getElementById('amount');
        const errorMessage = document.getElementById('error-message');

        pannaInput.addEventListener('input', function() {
            const inputValue = pannaInput.value;

            if (inputValue.length === 3 && double_panna.includes(inputValue)) {
                errorMessage.style.display = 'none';
            } else if (inputValue.length === 3) {
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
            }
        });

        $('#game_form').on('submit', function(e) {
            e.preventDefault();

            const amountValue = parseInt(amountInput.value, 10);

            // Validate minimum bid amount
            if (amountValue < min_bid_amount) {
                showToaster(`Bid amount must be at least ${min_bid_amount}.`, 'error');
                return;
            }

            const formData = $(this).serialize();

            $.ajax({
                url: "{{ route('doublePannaGame_insert') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (!response.success) {
                        showToaster(response.message, 'error');
                    } else {
                        showToaster('Data submitted successfully!', 'success');
                        $('#panna').val('');
                        $('#amount').val('');
                        $('#error-message').hide();
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON || {};
                    let message = 'An error occurred.';
                    if (response.errors) {
                        const errors = Object.values(response.errors)
                            .map(err => err[0])
                            .join(' ');
                        message = errors || response.message || message;
                    }
                    showToaster(message, 'error');
                }
            });
        });

        function showToaster(message, type) {
            const toaster = $('#toaster');
            toaster.removeClass('toaster-success toaster-error').addClass(type === 'success' ?
                'toaster-success' : 'toaster-error');
            toaster.text(message).fadeIn();

            setTimeout(() => {
                toaster.fadeOut();
            }, 3000);
        }
    });
</script>

<style>
    /* Toaster styling */
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
