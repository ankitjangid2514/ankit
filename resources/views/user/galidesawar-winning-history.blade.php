@extends('user.layouts.main')  

@section('title', 'Winning History')  

@section('content') 

<div class="container">
    <div class="row" style="background: var(--primary); padding: 10px; border-radius:10px;">
        <div class="col-12">
            <span style="color: var(--white); font-family: auto; font-size: 18px;">Winning History</span>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 10px; margin-bottom: 10px;">

    @if($data->isEmpty()) <!-- Check if data is empty -->
        <div class="row">
            <div class="col-12 text-center">
                <p style="font-size: 18px; color: #d90707;">No Winning History Available</p> <!-- Message when no data -->
            </div>
        </div>
    @else
        @foreach ($data as $show)
            @php
                $dateFromDB = $show->created_at;  // Example date from your database
                $formattedDate = date('d M Y', strtotime($dateFromDB));
            @endphp

            <div class="row" style="border-bottom: 1px var(--primary) solid;">
                <div class="col-12">
                    <div class="row">
                        <div class="col-11">
                            <p class="game_name" style="margin-top: 8px; font-size: 14px; font-family: system-ui;">
                                {{$show->desawar_name}} ({{$show->bid_type}}) - {{$show->bid_point}} ---
                            </p>
                            <p style="color: #89543c; font-size: 12px; margin-bottom: 9px;">{{$formattedDate}}</p>
                        </div>
                        <div class="col-1">
                            <p style="float: right; margin-top: 16px;" class="game_name">{{$show->winning_amount}}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

</div>

<script src="{{url('vendor/jquery/jquery-3.2.1.min.js')}}"></script>
</body>
</html>

@endsection
