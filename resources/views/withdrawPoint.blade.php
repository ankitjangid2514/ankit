@extends('layouts.app')

@section('content')
<div id="mySidebar" class="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="/home"><i class="fas fa-home"></i>Home</a>
    <a href="/user-profile"><i class="fas fa-user"></i>User Profile</a>
    <a href="/wallet"><i class="fas fa-wallet"></i>Wallet</a>
    <a href="/add-point"><i class="fas fa-plus-circle"></i>Add Point</a>
    <a href="/withdraw-point"><i class="fas fa-money-bill-wave"></i>Withdraw Point</a>
    <a href="/winning-history"><i class="fas fa-trophy"></i>Winning History</a>
    <a href="/bid-history"><i class="fas fa-history"></i>Bid History</a>
    <a href="/game-rate"><i class="fas fa-percentage"></i>Game Rate</a>
    <a href="/contact-us"><i class="fas fa-envelope"></i>Contact Us</a>
    <a href="/how-to-play"><i class="fas fa-question-circle"></i>How To Play</a>
    <a href="javascript:void(0)" onclick="shareApp()"><i class="fas fa-share-alt"></i>Share</a>
    <a href="/update-password"><i class="fas fa-key"></i>Update Password</a>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i>Logout
    </a>
</div>

<div id="main" style="background-image: url('/path/to/your/background-image.jpg'); background-size: cover; padding: 20px;">
    <button class="openbtn" onclick="openNav()">☰ Open Sidebar</button>
    <div class="container mt-4">
        <div class="card shadow" style="border-radius: 15px; background: linear-gradient(to right, #ffffff, #f8f9fa);">
            <div class="card-header text-center">
                <h2 style="font-family: 'Arial', sans-serif;"><i class="fas fa-money-bill-wave"></i> Withdraw Points</h2>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('withdrawal_amount') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="amount">Amount to Withdraw (INR)</label>
                        <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter amount" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-3" style="border-radius: 10px; background: linear-gradient(45deg, #007bff, #00c6ff); color: white; transition: all 0.3s; box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);">
                        <i class="fas fa-money-bill-wave" style="font-size: 1.2em;"></i> Withdraw Points
                    </button>
                </form>
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
</script>
@endpush 