<nav id="mySidenav" class="sidenav">
    <div class="sidenav-header" style="background: #ffc827; padding: 15px; color: #000;">
        <div>
            <strong>{{ $userData->name }}</strong><br>{{ $userData->number }}
        </div>
        <div onclick="closeNav()" style="font-size: 30px; cursor: pointer;">×</div>
    </div>

    <a href="{{ route('dashboard') }}"><img src="{{ url('images/1.png') }}" alt="Home Icon" />Home</a>
    <a href="{{ route('profile') }}"><img src="{{ url('images/2.png') }}" alt="User Icon" />User Profile</a>
    <a href="{{ route('wallet') }}"><img src="{{ url('images/3.png') }}" alt="Wallet Icon" />Wallet</a>
    <a href="{{ route('addPoint') }}"><img src="{{ url('images/point.png') }}" alt="Point Icon" />Add Point</a>
    <a href="{{ route('withdrawPoint') }}"><img src="{{ url('images/point.png') }}" alt="Withdraw Icon" />Withdraw Point</a>
    <a href="{{ route('winning_history') }}"><img src="{{ url('images/point.png') }}" alt="History Icon" />Winning History</a>
    <a href="{{ route('history_bid') }}"><img src="{{ url('images/point.png') }}" alt="Bid History Icon" />Bid History</a>
    <a href="{{ route('gameRates') }}"><img src="{{ url('images/4.png') }}" alt="Game Rate Icon" />Game Rate</a>
    <a href="{{ route('contact') }}"><img src="{{ url('images/5.png') }}" alt="Contact Icon" />Contact Us</a>
    <a href="{{ route('help') }}"><img src="{{ url('images/6.png') }}" alt="Help Icon" />How To Play</a>
    <a href="whatsapp://send?text=Download Our Matka App  https://baazigarmatka.in" target="_blank">
        <img src="{{ url('images/7.png') }}" alt="Share Icon" />Share
    </a>
    <a href="{{ route('password') }}"><img src="{{ url('images/9.png') }}" alt="Password Icon" />Update Password</a>
    <a href="{{ route('logout') }}"><img src="{{ url('images/8.png') }}" alt="Logout Icon" />Logout</a>
</nav>
