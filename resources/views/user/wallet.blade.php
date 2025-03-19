@extends('user.layouts.main')

@section('title', 'Wallet ')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 pull-center wallet_img">
            <img src="{{ asset('images/purse.png') }}" width="50px" height="50px" alt="Wallet Image">
            <p>Available Points {{ $userData->balance ?? 0 }} INR</p>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 10px; margin-bottom: 15px;">
    <div class="row">
        <div class="col-6" style="margin-bottom: 20px;">
            <a href="{{ url('withdrawPoint') }}">
                <div class="wallet_box">
                    <p><i class="fa fa-google-wallet"></i></p>
                    <p>Withdraw Points</p>
                </div>
            </a>
        </div>

        <div class="col-6" style="margin-bottom: 20px;">
            <a href="{{ url('addPoint') }}">
                <div class="wallet_box">
                    <p><i class="fa fa-trophy"></i></p>
                    <p>Add Points</p>
                </div>
            </a>
        </div>

        <div class="col-6" style="margin-bottom: 20px;">
            <a href="{{ route('wallet_history') }}">
                <div class="wallet_box">
                    <p><i class="fa fa-file"></i></p>
                    <p>Wallet History</p>
                </div>
            </a>
        </div>
    </div>
</div>


@endsection
