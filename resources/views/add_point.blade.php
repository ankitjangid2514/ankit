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
                    <div class="card-header">{{ __('Add Points') }}</div>

                    <div class="card-body">
                        <form id="paymentForm" class="needs-validation" novalidate>
                            @csrf
                            <div class="form-group mb-3">
                                <label for="amount">{{ __('Amount (INR)') }}</label>
                                <input type="number"
                                    class="form-control"
                                    id="amount"
                                    name="amount"
                                    placeholder="Enter amount"
                                    min="1"
                                    max="10000"
                                    required>
                                <div class="invalid-feedback">
                                    Please enter an amount between 1 and 10,000 INR
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ __('Proceed to Pay') }}
                            </button>
                        </form>
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
        const form = document.getElementById('paymentForm');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const amount = document.getElementById('amount').value;

            if (amount < 1 || amount > 10000) {
                alert('Please enter an amount between 1 and 10,000 INR');
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

        function checkPaymentStatus() {
            // Implement payment status checking logic here
            // This should be customized based on your UPI provider's requirements
        }
    });
</script>
        });
    </script>
    @endpush