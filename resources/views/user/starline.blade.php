@extends('user.layouts.main')

@section('title', 'Starline')

@section('content')

<style>
    /* General Styles */
    .result-chart-text {
        white-space: nowrap;
        color: #ffc827;
        font-size: 1rem;
    }

    .result_time {
        white-space: nowrap;
        color: #fff;
        font-size: 1rem;
        margin: 0;
        padding: 0;
    }

    .game_name {
        color: #ffc827;
        font-size: 1rem;
    }

    /* Adjustments for smaller screens */
    @media (max-width: 768px) {
        .result_time {
            font-size: 0.9rem;
        }

        .result-chart-text {
            font-size: 0.9rem;
        }

        .fa-play-circle {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 576px) {
        .game_name {
            font-size: 0.85rem;
        }

        .result_time {
            font-size: 0.85rem;
        }

        .fa-play-circle {
            font-size: 1.6rem;
        }

        .result-chart-text {
            font-size: 0.8rem;
        }
    }
</style>

<div class="container" style="padding: 0 15px;">
    <div class="card-header text-center game text-success" style="font-size:large;">Starline Game Rates</div>
    <div class="card-body" style="background:#ffc827;">
        <div class="row card_row">
            <div class="col-6">
                <h4 class="card-title game">Single Digit</h4>
            </div>
            <div class="col-6 text-right">
                <h4 class="card-title game">{{ $data->single_digit_bid }}-{{ $data->single_digit_win }}</h4>
            </div>
        </div>
        <div class="row card_row">
            <div class="col-6">
                <h4 class="card-title game">Single Panna</h4>
            </div>
            <div class="col-6 text-right">
                <h4 class="card-title game">{{ $data->single_digit_bid }}-{{ $data->single_digit_win }}</h4>
            </div>
        </div>
        <div class="row card_row">
            <div class="col-6">
                <h4 class="card-title game">Double Panna</h4>
            </div>
            <div class="col-6 text-right">
                <h4 class="card-title game">{{ $data->double_match_bid }}-{{ $data->double_match_win }}</h4>
            </div>
        </div>
        <div class="row card_row">
            <div class="col-6">
                <h4 class="card-title game">Triple Panna</h4>
            </div>
            <div class="col-6 text-right">
                <h4 class="card-title game">{{ $data->triple_match_bid }}-{{ $data->triple_match_win }}</h4>
            </div>
        </div>
        <!-- Repeat for other game types -->
    </div>

    <div class="row mt-3 text-center">
    <div class="col-5">
        <a href="{{ route('starline_bid_history') }}" class="d-flex flex-column align-items-center">
            <img src="images/bid.png" alt="Bid History" class="img-fluid" style="max-width: 45px;">
            <div class="btn btn-info text-white mt-2" style="font-size: 0.85rem;">BID HISTORY</div>
        </a>
    </div>
    <div class="col-2"></div>
    <div class="col-5">
        <a href="{{ route('starline_winning_history') }}" class="d-flex flex-column align-items-center">
            <img src="images/winning.png" alt="Win History" class="img-fluid" style="max-width: 45px;">
            <div class="btn btn-danger text-white mt-2" style="font-size: 0.85rem;">WIN HISTORY</div>
        </a>
    </div>
</div>



</div>

<div class="container mt-3">
    @foreach ($starline_market as $game)
    @php
    // Time formatting
    $time = new DateTime($game->open_time);
    $times = new DateTime($game->close_time);
    $formattedTime = $time->format('g:i A');
    $formattedTimes = $times->format('g:i A');

    $current_time = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
    $current_time = $current_time->format('H:i:s');
    $open_time = new DateTime($game->open_time, new DateTimeZone('Asia/Kolkata'));
    $open_time = $open_time->format('H:i:s');
    $close_time = new DateTime($game->close_time, new DateTimeZone('Asia/Kolkata'));
    $close_time = $close_time->format('H:i:s');
    @endphp

    <section class="thirdRow ">
        <div class="row">
            <div class="col-12" style="padding-left: 10px; padding-right:10px">
                <div class="alert alert-dismissible alert-default d-flex align-items-center justify-content-between"
                    style="background-color:#500143; border: 3px solid #ffc827; padding: 12px 1px;">

                    <!-- Result Chart -->
                    <div class="col-3 text-center">
                        <a href="{{ route('starline_resultChart', ['result_id' => $game->id]) }}">
                            <img src="{{ url('images/chart.jpg') }}"
                                alt="Result Chart"
                                class="img-fluid"
                                style="max-width: 35px;">
                        </a>
                        <span class="result-chart-text">Result Chart</span>
                    </div>

                    <!-- Game Details -->
                    <div class="col-6 text-center" style="margin-right: 15px;">
                        <p class="game_name"> {{ $game->starline_name }}</p>
                        <p class="result_time">Open: {{ $formattedTime }} | Close: {{ $formattedTimes }}</p>
                        <p class="result_time" style="color: {{ $current_time >= $close_time ? '#d90707' : '#ffc827' }};">****</p>
                    </div>

                    <!-- Play Now / Close -->
                    <div class="col-3 text-center" style="padding-left: 15px; margin-left:8px;">
                        @if ($current_time >= $close_time)
                        <i class="fa fa-play-circle"
                            onclick="abcc();"
                            style="font-size: 2rem; color:#d90707;"></i>
                        <span style="color: red; font-size: 0.8rem;">Close</span>
                        @else
                        <a href="{{ route('starline_games', ['starline_id' => $game->id]) }}">
                            <i class="fa fa-play-circle" style="font-size: 2rem; color:#ffc827;"></i>
                        </a>
                        <span style="color: #ffc827; font-size: 0.8rem;">Play Now</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endforeach
</div>

<script>
    function abcc() {
        alertt("Market Closed!");
        if ("vibrate" in navigator) {
            navigator.vibrate(200);
        }
    }

    function alertt(txt) {
        $("#alert").text(txt).show('slow');
        setTimeout(() => $("#alert").hide('slow'), 2000);
    }
</script>
@endsection