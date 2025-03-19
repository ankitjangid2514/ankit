@extends('layouts.app')

@section('content')
<style>
    /* Sidebar Styles */
    .sidebar {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        background-color: #350b2d;
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
        border-right: 1px solid var(--primary);
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar a {
        padding: 15px 20px;
        text-decoration: none;
        font-size: 15px;
        color: var(--white);
        display: block;
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(255, 200, 39, 0.1);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar a i {
        font-size: 18px;
        width: 24px;
        text-align: center;
    }

    .sidebar a:hover {
        background: rgba(255, 200, 39, 0.1);
        padding-left: 25px;
        color: var(--primary);
    }

    .sidebar .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
        color: var(--primary);
        border: none;
    }

    .sidebar .closebtn:hover {
        background: none;
        padding-left: 20px;
        color: var(--white);
    }

    .openbtn {
        font-size: 24px;
        cursor: pointer;
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 999;
        background: none;
        border: none;
        color: var(--primary);
        padding: 10px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .openbtn:hover {
        color: var(--white);
        background: rgba(255, 200, 39, 0.1);
        transform: scale(1.1);
    }

    #main {
        transition: margin-left .5s;
        padding: 20px;
    }

    .card {
        background: #350b2d !important;
        position: relative;
        border: 2px solid var(--primary) !important;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.6s ease-out;
        transform: translateY(0);
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(255, 200, 39, 0.25);
    }

    .payment-option {
        background: #350b2d !important;
        border: 2px solid var(--primary);
        border-radius: 20px;
        padding: 35px;
        margin-bottom: 30px;
        box-shadow: 0 5px 25px rgba(255, 200, 39, 0.15);
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .payment-option:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255, 200, 39, 0.05), transparent);
        transform: translateX(-100%);
        transition: 0.6s;
    }

    .payment-option:hover:before {
        transform: translateX(100%);
    }

    .payment-option:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 30px rgba(255, 200, 39, 0.25);
    }

    .quick-amount {
        background: rgba(255, 200, 39, 0.1);
        border: 2px solid var(--primary);
        color: var(--primary);
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.4s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .quick-amount:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--primary);
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.4s ease;
        z-index: -1;
    }

    .quick-amount:hover:before {
        transform: scaleX(1);
        transform-origin: left;
    }

    .quick-amount:hover, .quick-amount.active-amount {
        color: #350b2d;
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(255, 200, 39, 0.3);
    }
    
    .quick-amount.active-amount {
        background: var(--primary);
    }
    
    .quick-amount:active {
        transform: scale(0.95);
    }

    .input-group {
        border: 2px solid var(--primary);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.4s ease;
        background: rgba(255, 200, 39, 0.05);
    }

    .input-group:focus-within {
        box-shadow: 0 0 20px rgba(255, 200, 39, 0.25);
        transform: translateY(-2px);
    }

    .input-group-text {
        background-color: var(--primary);
        color: #350b2d;
        border: none;
        font-weight: 700;
        font-size: 18px;
        padding: 0 25px;
        display: flex;
        align-items: center;
    }

    .form-control {
        background-color: transparent !important;
        border: none;
        color: var(--white) !important;
        font-size: 16px;
        padding: 15px 20px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .form-control::placeholder {
        color: rgba(255, 200, 39, 0.4);
        font-weight: 400;
    }

    .btnmy {
        background: var(--primary);
        width: 280px;
        padding: 16px 32px;
        font-size: 16px;
        border-radius: 50px;
        text-transform: uppercase;
        color: #350b2d;
        border: none;
        transition: all 0.4s ease;
        margin: 30px auto;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        font-weight: 700;
        letter-spacing: 1.2px;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .btnmy:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: all 0.6s ease;
    }

    .btnmy:hover {
        background: var(--primary);
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(255, 200, 39, 0.4);
        color: #350b2d;
    }

    .btnmy:hover:before {
        left: 100%;
    }

    .balance-display {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 200, 39, 0.1);
        padding: 12px 30px;
        border-radius: 50px;
        border: 2px solid var(--primary);
        color: var(--primary) !important;
        font-weight: 700;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 10;
        transition: all 0.4s ease;
        backdrop-filter: blur(5px);
    }

    .balance-display:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 20px rgba(255, 200, 39, 0.25);
        background: rgba(255, 200, 39, 0.15);
    }

    .card-header {
        background-color: var(--primary);
        padding: 25px 30px;
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 22px;
        font-weight: 700;
        color: #350b2d;
        border-bottom: 2px solid var(--primary);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }

        .card {
            margin: 10px;
            border-radius: 15px;
        }

        .balance-display {
            position: relative;
            top: 0;
            right: 0;
            margin: 15px auto;
            width: fit-content;
            font-size: 14px;
            padding: 10px 20px;
        }

        .btnmy {
            width: 100%;
            padding: 14px 20px;
            font-size: 15px;
        }
        
        .card-header {
            padding: 18px 20px;
            font-size: 18px;
        }
        
        .payment-option {
            padding: 20px;
            border-radius: 15px;
        }
        
        .quick-amount-buttons {
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
        }
        
        .quick-amount {
            padding: 10px 15px;
            font-size: 14px;
            margin: 5px 2px;
            min-width: 80px;
        }
        
        .input-group {
            border-radius: 10px;
        }
        
        .form-control {
            padding: 12px 15px;
            font-size: 15px;
        }
        
        .input-group-text {
            padding: 0 15px;
            font-size: 16px;
        }
        
        #main {
            padding: 10px;
        }
    }
    
    @media (max-width: 480px) {
        .openbtn {
            top: 10px;
            left: 10px;
            font-size: 20px;
            padding: 8px;
        }
        
        .card-header {
            padding: 15px;
            font-size: 16px;
        }
        
        .quick-amount {
            padding: 8px 12px;
            font-size: 13px;
            min-width: 70px;
        }
        
        .btnmy {
            padding: 12px 15px;
            font-size: 14px;
            margin: 20px auto;
        }
        
        .form-group label {
            font-size: 14px;
        }
        
        .payment-option h5 {
            font-size: 16px;
        }
    }
</style>

<div id="mySidebar" class="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i>Home</a>
    <a href="{{ route('profile') }}"><i class="fas fa-user"></i>User Profile</a>
    <a href="{{ route('wallet') }}"><i class="fas fa-wallet"></i>Wallet</a>
    <a href="{{ route('addPoint') }}"><i class="fas fa-plus-circle"></i>Add Point</a>
    <a href="{{ route('withdrawPoint') }}"><i class="fas fa-money-bill-wave"></i>Withdraw Point</a>
    <a href="{{ route('winning_history') }}"><i class="fas fa-trophy"></i>Winning History</a>
    <a href="{{ route('history_bid') }}"><i class="fas fa-history"></i>Bid History</a>
    <a href="{{ route('gameRates') }}"><i class="fas fa-percentage"></i>Game Rate</a>
    <a href="{{ route('contact') }}"><i class="fas fa-envelope"></i>Contact Us</a>
    <a href="{{ route('help') }}"><i class="fas fa-question-circle"></i>How To Play</a>
    <a href="javascript:void(0)" onclick="shareApp()"><i class="fas fa-share-alt"></i>Share</a>
    <a href="{{ route('password') }}"><i class="fas fa-key"></i>Update Password</a>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i>Logout
    </a>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<button class="openbtn" onclick="openNav()">
    <i class="fas fa-bars"></i>
</button>

<div id="main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="balance-display">
                        <i class="fas fa-coins"></i>
                        <span>₹{{ number_format(Auth::user()->coins, 2) }}</span>
                    </div>

                    <div class="card-header">
                        <i class="fas fa-coins"></i>{{ __('Add Points') }}
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>{{ session('success') }}
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>{{ session('error') }}
                        </div>
                        @endif

                        <!-- UPI Payment Option -->
                        <div class="payment-option">
                            <h5 class="text-center mb-4"><i class="fas fa-mobile-alt me-2"></i>UPI Payment</h5>
                            <div class="quick-amount-buttons mb-4 d-flex flex-wrap justify-content-center gap-2">
                                <button type="button" class="quick-amount" data-amount="100">₹100</button>
                                <button type="button" class="quick-amount" data-amount="500">₹500</button>
                                <button type="button" class="quick-amount" data-amount="1000">₹1000</button>
                                <button type="button" class="quick-amount" data-amount="5000">₹5000</button>
                                <button type="button" class="quick-amount" data-amount="10000">₹10000</button>
                            </div>
                            <form id="upiPaymentForm" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-group mb-4">
                                    <label for="upi_amount" class="form-label">{{ __('Amount (INR)') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number"
                                            class="form-control"
                                            id="upi_amount"
                                            name="amount"
                                            placeholder="Enter amount (100 - 10,000)"
                                            min="100"
                                            max="10000"
                                            required>
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter an amount between ₹100 and ₹10,000
                                    </div>
                                </div>

                                <button type="submit" class="btnmy" onclick="handleUPIPayment(event)">
                                    <i class="fas fa-money-bill-wave"></i>{{ __('Pay with UPI') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openNav() {
        document.getElementById("mySidebar").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
    }

    function shareApp() {
        if (navigator.share) {
            navigator.share({
                    title: 'KALYAN MAHADEV',
                    text: 'Play and win with KALYAN MAHADEV!',
                    url: window.location.origin
                })
                .then(() => console.log('Share successful'))
                .catch((error) => console.log('Error sharing:', error));
        } else {
            // Fallback for desktop or browsers that don't support Web Share API
            const dummy = document.createElement('input');
            document.body.appendChild(dummy);
            dummy.value = window.location.origin;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);
            alert('Link copied to clipboard!');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const upiForm = document.getElementById('upiPaymentForm');
        const amountInput = document.getElementById('upi_amount');
        const quickAmountButtons = document.querySelectorAll('.quick-amount');

        // Add click event listeners to quick amount buttons with visual feedback
        quickAmountButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                quickAmountButtons.forEach(btn => btn.classList.remove('active-amount'));
                // Add active class to clicked button
                this.classList.add('active-amount');
                
                const amount = this.getAttribute('data-amount');
                amountInput.value = amount;
                
                // Add vibration feedback for mobile devices if supported
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            });
        });
        
        // Add touch feedback for mobile
        if ('ontouchstart' in window) {
            quickAmountButtons.forEach(button => {
                button.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.95)';
                });
                button.addEventListener('touchend', function() {
                    this.style.transform = '';
                });
            });
        }

        upiForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const amount = amountInput.value;

            if (amount < 100 || amount > 10000) {
                alert('Please enter an amount between ₹100 and ₹10,000');
                return;
            }

            try {
                const response = await fetch('/process-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        amount: amount
                    })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    // Redirect to UPI app
                    window.location.href = data.upi_link;

                    // Poll for payment status
                    checkPaymentStatus();
                } else {
                    alert(data.message || 'Payment initiation failed');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while processing your request');
            }
        });

        async function checkPaymentStatus(amount) {
            try {
                // Poll payment status every 5 seconds
                const pollInterval = setInterval(async () => {
                    const response = await fetch('/check-payment-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            amount: amount
                        })
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        clearInterval(pollInterval);
                        window.location.reload(); // Reload to update wallet balance
                    } else if (data.status === 'failed') {
                        clearInterval(pollInterval);
                        alert('Payment failed. Please try again.');
                    }
                }, 5000);

                // Stop polling after 5 minutes
                setTimeout(() => {
                    clearInterval(pollInterval);
                }, 300000);
            } catch (error) {
                console.error('Error checking payment status:', error);
            }
        }
    });
</script>
<script>
    function handleUPIPayment(event) {
        event.preventDefault();

        const amount = document.getElementById('upi_amount').value;
        if (amount < 100 || amount > 10000) {
            alert('Please enter an amount between ₹100 and ₹10,000');
            return;
        }

        // UPI Payment Apps
        const upiApps = [{
                name: 'Google Pay',
                scheme: 'gpay://upi/pay'
            },
            {
                name: 'PhonePe',
                scheme: 'phonepe://pay'
            },
            {
                name: 'Paytm',
                scheme: 'paytm://pay'
            },
            {
                name: 'BHIM',
                scheme: 'bhim://pay'
            },
            {
                name: 'Amazon Pay',
                scheme: 'amazonpay://pay'
            }
        ];

        // Create UPI payment URI
        const paymentData = {
            pa: 'YOUR_UPI_ID@bank', // Replace with your UPI ID
            pn: 'KALYAN MAHADEV',
            tn: 'Add Points',
            am: amount,
            cu: 'INR'
        };

        // Check if the device supports UPI deep linking
        const isUpiSupported = /android|iphone|ipad|ipod/i.test(navigator.userAgent);

        if (isUpiSupported) {
            // Create a modal dialog to show UPI app options
            const modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
            modal.style.display = 'flex';
            modal.style.justifyContent = 'center';
            modal.style.alignItems = 'center';
            modal.style.zIndex = '9999';

            const modalContent = document.createElement('div');
            modalContent.style.backgroundColor = '#350b2d';
            modalContent.style.padding = '20px';
            modalContent.style.borderRadius = '10px';
            modalContent.style.maxWidth = '90%';
            modalContent.style.width = '400px';
            modalContent.style.border = '2px solid var(--primary)';

            const title = document.createElement('h5');
            title.textContent = 'Choose Payment App';
            title.style.color = 'var(--primary)';
            title.style.marginBottom = '20px';
            title.style.textAlign = 'center';

            modalContent.appendChild(title);

            upiApps.forEach(app => {
                const button = document.createElement('button');
                button.className = 'btnmy w-100 mb-3';
                button.textContent = app.name;
                button.onclick = () => {
                    const upiUrl = `${app.scheme}?${new URLSearchParams(paymentData).toString()}`;
                    window.location.href = upiUrl;
                    modal.remove();
                };
                modalContent.appendChild(button);
            });

            const closeButton = document.createElement('button');
            closeButton.className = 'btnmy w-100';
            closeButton.textContent = 'Cancel';
            closeButton.onclick = () => modal.remove();
            modalContent.appendChild(closeButton);

            modal.appendChild(modalContent);
            document.body.appendChild(modal);
        } else {
            alert('UPI payment is not supported on this device');
        }
    }
</script>
@endpush