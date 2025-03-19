<div class="container-fluid">
    <div class="row" style="background: var(--primary);padding: 10px;">
        <div class="col-12">
            <span style="color:black;">Single Digit</span>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 30px; margin-bottom: 30px;">
    <form id="digit-form" method="post">
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
                    @if ($current_time >= $open_time)
                        <div class="form-group">
                            <label class="form-label" for="timetype" style="width: 100px; float: left;">
                                <input type="radio" name="timetype" id="timetype1" value="open"
                                    disabled />
                                Open </label>

                            <label class="form-label" for="timetype">
                                <input type="radio" name="timetype" id="timetype2" value="close"
                                    checked />
                                Close </label>
                        </div>
                    @else
                        <div class="form-group">
                            <label class="form-label" for="timetype" style="width: 100px; float: left;">
                                <input type="radio" name="timetype" id="timetype1" value="open"
                                    checked />
                                Open </label>

                            <label class="form-label" for="timetype">
                                <input type="radio" name="timetype" id="timetype2" value="close" />
                                Close </label>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12" id="digitbox">
                <div class="form-group">
                    <label class="form-label" for="digit" id="xyz1">Enter Digit</label>
                    <input list="browsers" type="number" class="form-control" name="digit"
                        id="digit" maxlength="1" placeholder="Enter Digit" required="required" />
                    <small id="error-message" style="color: red; display: none;">Please enter a single digit
                        value.</small>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label class="form-label" for="amount">Enter Amount</label>
                    <input type="number" class="form-control" name="amount" id="amount"
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
    </form>
</div>

<!-- Popup for notifications -->
<div id="popup" style="
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
    z-index: 1000;
">
    <span id="popup-message" style="font-size: 16px; color: black;"></span>
    <button id="close-popup" style="
        display: block;
        margin: 10px auto 0;
        background: var(--primary);
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
    ">Close</button>
</div>
<div id="overlay" style="
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
"></div>

<script>
    const digitInput = document.getElementById('digit');
    const errorMessage = document.getElementById('error-message');
    const form = document.getElementById('digit-form');
    const popup = document.getElementById('popup');
    const overlay = document.getElementById('overlay');
    const popupMessage = document.getElementById('popup-message');
    const closePopupButton = document.getElementById('close-popup');

    // Show popup function
    function showPopup(message, isSuccess = true) {
        popupMessage.textContent = message;
        popup.style.display = 'block';
        overlay.style.display = 'block';
        popup.style.borderColor = isSuccess ? 'green' : 'red';

        // Automatically hide after 3 seconds
        setTimeout(hidePopup, 3000);
    }

    // Hide popup function
    function hidePopup() {
        popup.style.display = 'none';
        overlay.style.display = 'none';
    }

    // Close popup button event
    closePopupButton.addEventListener('click', hidePopup);

    // Form submission with validation and AJAX
    form.addEventListener('submit', function(event) {
        const value = digitInput.value;

        // Check if the value is a single digit
        if (value.length !== 1 || !/^[0-9]$/.test(value)) {
            errorMessage.style.display = 'inline'; // Show error message
            event.preventDefault(); // Prevent form submission
        } else {
            errorMessage.style.display = 'none'; // Hide error message

            // AJAX request
            event.preventDefault(); // Prevent default form submission

            var formData = new FormData(form); // Gather all form data

            $.ajax({
                url: '{{ route("singleDigitGame_insert") }}', // Game insert route
                method: 'POST',
                data: formData,
                processData: false, // Don't process the data
                contentType: false, // Don't set content type for FormData
                success: function(response) {
                    if (response.success) {
                        showPopup(response.message, true); // Show success popup
                        setTimeout(() => {
                            window.location.reload(); // Reload the page after success
                        }, 3000); // Wait for the popup to hide
                    } else {
                        showPopup(response.message, false); // Show error popup
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    showPopup('There was an error processing the request.', false);
                }
            });
        }
    });

    // Optional: prevent input from exceeding one character (digits only)
    digitInput.addEventListener('input', function() {
        const value = digitInput.value;
        if (value.length > 1 || !/^[0-9]$/.test(value)) {
            digitInput.value = value.slice(0, 1); // Allow only the first digit entered
        }
    });
</script>

