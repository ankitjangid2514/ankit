@extends('user.layouts.main')

@section('title', 'Add Point')

@section('content')
<!-- Page Content -->
<div id="pageContent">
    <div class="container-fluid">
        <div class="row" style="background: var(--primary); padding: 10px;">
            <div class="col-12">
                <span style="color:var(--white);font-family: monospace;">ADD POINT</span>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 30px;">
        <!-- Display Success or Error Message -->
        <div id="paymentMessage" class="alert d-none"></div>

        <div class="card" style="background: #ffc827; padding: 20px 10px; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); margin:auto;">
            <div class="black">
                <span class="person-name">{{ $userData->name }}</span>
                <span class="person-call">{{ $userData->number }}</span>
            </div>
            <div class="balance-box">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <img src="{{ url('images/purse.png') }}" id="wallet-pic" />
                        </div>
                        <div class="col-8">
                            <div class="amount"><b>₹ {{ $userData->balance }}</b></div>
                            <div class="balance-alert">Current Balance</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- UPI QR Code -->
        <div class="row mt-3" id="qrCodeDiv">
            <div class="col-12 text-center">
                <img src="{{ $admin->qr_code }}" alt="QR Code" class="img-fluid" style="width:100%; max-width:500px;">
            </div>
        </div>

        <!-- Payment Form -->
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="form-group">
                    <input type="number" class="form-control" id="amount" placeholder="Enter Amount" required />
                </div>
            </div>

            <div class="col-12 text-center mt-3">
                <h5 style="text-align:left;margin-bottom:15px;color:#fff;">Upload Screenshot</h5>
                <input type="file" name="upload_qr" id="upload_qr">
            </div>

            <div class="col-12 mt-3">
                <button class="btn btn-primary" onclick="makePayment()" style="background:#ffc827;border-color:black;width:100%;">
                    Proceed to Pay
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function makePayment() {
        let amount = document.getElementById('amount').value;
        if (!amount || amount <= 0) {
            alert("Please enter a valid amount");
            return;
        }

        let upiId = "kalam.easebuzz@hdfcbank"; // Merchant UPI ID
        let transactionRef = "TXN" + Math.floor(Math.random() * 1000000); // Unique Transaction ID

        // Corrected UPI URL (Removed `url` parameter causing error)
        let upiUrl = `upi://pay?pa=${upiId}&pn=Easebuzz&tr=${transactionRef}&tn=Payment for Points&am=${amount}&cu=INR`;

        // Redirect to UPI payment app
        window.location.href = upiUrl;
    }

    function checkPaymentStatus() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status'); // Capture UPI response

        if (status === "success") {
            showPaymentMessage("✅ Payment Successful!", "alert-success");
            // alert("Payment Successful",urlParams.toString());
        } else if (status === "failed") {
            showPaymentMessage("❌ Payment Failed. Please try again!", "alert-danger");
        }
    }

    function showPaymentMessage(message, alertClass) {
        let messageBox = document.getElementById("paymentMessage");
        messageBox.className = `alert ${alertClass}`;
        messageBox.innerHTML = message;
        messageBox.classList.remove("d-none");
    }

    // Check payment status on page load
    window.onload = checkPaymentStatus;
</script>

@endsection
