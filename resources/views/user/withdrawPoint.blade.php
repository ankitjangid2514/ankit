@extends('user.layouts.main')
{{-- Add title --}}
@section('title', 'Withdraw Point')

@section('content')

<div class="container mb-4">
    <div class="row" style="background: var(--primary); padding: 10px; border-radius: 10px; margin: 0px;">
        <div class="col-12">
            <span class="text-dark">WITHDRAW POINT</span>
        </div>
    </div>
</div>

<div class="container" style="margin-top:10px; margin-bottom: 10px;">
    <!-- Display Success or Error Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="post" action="{{ route('withdrawal_amount') }}">
        @csrf
        <div class="card" style="width: 100%; height: auto; background: #fcfcfc; padding: 20px 0px; height: 170px; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s;">
            <div class="black">
                <span class="person-name">{{ $userData->name }}</span>
                <span class="person-call">{{ $userData->number }}</span>
            </div>

            <div class="balance-box">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-xs-4" style="padding-top:5px;width:16%;">
                            <img src="images/purse.png" id="wallet-pic"/>
                        </div>
                        <div class="col-lg-8 col-sm-8 col-xs-8" style="width:auto;">
                            <div class="amount"><b>₹ {{ $userData->balance }}</b></div>
                            <div class="balance-alert">Current Balance</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="form-group">
                    <!-- Display validation error for 'amount' -->
                    @if($errors->has('amount'))
                        <div class="alert alert-danger">
                            {{ $errors->first('amount') }}
                        </div>
                    @endif
                    <input type="number" 
                           class="form-control" 
                           style="font-size: 15px; background: #fff; padding: 20px; border-radius: 30px; border: none; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; margin-top: 30px;" 
                           name="amount" 
                           id="amount" 
                           placeholder="Enter Amount" 
                           value="{{ old('amount') }}" 
                           required />
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label style="margin-left: 10px;font-family:sans-serif;color:#ffc827;">Select Payment Mode</label>
                    <select class="form-select" 
                            style="font-size: 15px; background: #fff; padding: 20px; border-radius: 30px; border: none; width: 100%; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; margin-top: 10px;" 
                            name="mode" 
                            id="mode" 
                            required>
                        <option value="">-- Select Payment Mode --</option>
                        <option value="Paytm" {{ old('mode') == 'Paytm' ? 'selected' : '' }}>Paytm</option>
                        <option value="PhonePe" {{ old('mode') == 'PhonePe' ? 'selected' : '' }}>PhonePe</option>
                        <option value="GooglePay" {{ old('mode') == 'GooglePay' ? 'selected' : '' }}>GooglePay</option>
                    </select>
                    @if($errors->has('mode'))
                        <div class="alert alert-danger mt-2">
                            {{ $errors->first('mode') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    @if($errors->has('number'))
                        <div class="alert alert-danger">
                            {{ $errors->first('number') }}
                        </div>
                    @endif
                    <input type="number" 
                           class="form-control" 
                           style="font-size: 15px; background: #fff; padding: 20px; border-radius: 30px; border: none; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s; margin-top: 10px;" 
                           name="number" 
                           id="number" 
                           placeholder="Enter Phone Number" 
                           value="{{ old('number') }}" 
                           required />
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <input type="submit" 
                           class="btnmy" 
                           style="margin-top: 15px; padding: 13px; font-size: 14px; background-color:#500143; color:#ffc827;" 
                           id="submit" 
                           value="Withdraw Point">
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
