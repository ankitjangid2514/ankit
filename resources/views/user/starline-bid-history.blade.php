@extends('user.layouts.main')  

@section('title', 'Starline bid history')  

@section('content') 

<div class="container">
    <div class="row" style="background: var(--primary); padding: 10px; border-radius:10px;">
        <div class="col-12">
            <span style="color: var(--white); font-size: 18px;">Bid History</span>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 10px; margin-bottom: 10px;">
    @if($data->isEmpty()) <!-- Check if $data is empty -->
        <div class="row">
            <div class="col-12 text-center">
                <p style="font-size: 18px; color: #d90707;">No Bid History</p> <!-- Display message if no bids -->
            </div>
        </div>
    @else
        <div class="row" style="border-bottom: 1px var(--primary) solid;">
            @foreach ($data as $bid)
                @php
                    $dateFromDB = $bid->starline_bid_date;  // Example date from your database
                    $formattedDate = date('d M Y', strtotime($dateFromDB));
                @endphp

                <div class="col-12">
                    <div class="row">
                        <div class="col-11">
                            <p class="game_name" style="margin-top: 8px; font-size: 14px; font-family: system-ui;">
                                {{$bid->starline_name}} ({{$bid->gtype}}) - 
                                @if($bid->digit !== 'N/A')
                                    {{$bid->digit}}
                                @elseif($bid->panna !== 'N/A')
                                    {{$bid->panna}}
                                @endif
                            </p>
                            <p style="color: #89543c; font-size: 12px; margin-bottom: 9px;">{{$formattedDate}}</p>
                        </div>
                        <div class="col-1">
                            <p class="game_name" style="float: right; margin-top: 16px;">{{$bid->amount}}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="{{ url('vendor/jquery/jquery-3.2.1.min.js') }}"></script>
</body>
</html>
@endsection
