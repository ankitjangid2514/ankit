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

<div id="main">
    <button class="openbtn" onclick="openNav()">☰ Open Sidebar</button>
    <div class="container mt-4">
        <h1>Welcome to the Home Page</h1>
        <!-- Other content goes here -->
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