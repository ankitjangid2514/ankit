@extends('user.layouts.main')

@section('title', 'galidesawar')

@section('content')

    <div class="container">

        <div class="card-header text-center game text-success" style="font-size:large;">Gali Desawar Game Rates</div>
        <div class="card-body" style="background:#ffc827;">
            <div class="row card_row">

                <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
                    <h4 class="card-title game">Left Digit</h4>
                </div>

                <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">
                    <h4 class="card-title game">{{ $data->left_digit_bid }}-{{ $data->left_digit_win }}</h4>
                </div>

            </div>

            <div class="row card_row">

                <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
                    <h4 class="card-title game">Right Digit</h4>
                </div>

                <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">
                    <h4 class="card-title game">{{ $data->right_digit_bid }}-{{ $data->right_digit_win }}</h4>
                </div>

            </div>

            <div class="row card_row">

                <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6">
                    <h4 class="card-title game">Jodi</h4>
                </div>

                <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6 pull-right">
                    <h4 class="card-title game">{{ $data->jodi_digit_bid }}-{{ $data->jodi_digit_win }}</h4>
                </div>

            </div>



        </div>

        <div class="row" style="display: flex; justify-content: space-between; margin: 15px 10px; ">
            <!-- BID HISTORY -->
            <div class="col-5" style="padding: 5px; margin: 0px;">
                <a href="{{ route('desawar_bid_history') }}">
                    <div style="width: 100%; text-align: center; margin-top: 3px;">
                        <img src="images/bid.png" style="width: 45px;" />
                    </div>
                    <div style="width: 100%; border-radius: 5px; text-align: center; margin-top: 12px; font-size: 13px; padding: 4px 10px; border: 1px solid #00b0ff; background-color: #00b0ff; color: #FEFFFF;">
                        BID HISTORY
                    </div>
                </a>
            </div>
            
            <!-- Empty Spacer (if needed for spacing) -->
            <div class="col-2" style="padding: 5px; margin: 0px;"></div>
            
            <!-- WIN HISTORY -->
            <div class="col-5" style="padding: 5px; margin: 0px;">
                <a href="{{ route('desawar_winning_history') }}">
                    <div style="width: 100%; text-align: center; margin-top: 3px;">
                        <img src="images/winning.png" style="width: 45px;" />
                    </div>
                    <div style="width: 100%; border-radius: 5px; text-align: center; margin-top: 12px; font-size: 13px; padding: 4px 10px; border: 1px solid #b42121; background-color: #b42121; color: #FEFFFF;">
                        WIN HISTORY
                    </div>
                </a>
            </div>
        </div>
        
    </div>





    <div class="container">

        @foreach ($desawar_market as $game)
            @php
                // Create a DateTime object from the AM/PM time format
                $time = new DateTime($game->open_time);
                $times = new DateTime($game->close_time);

                // Format the time to 24-hour format (e.g., '14:30')
                $formattedTime = $time->format('g:i A');
                $formattedTimes = $times->format('g:i A');

            @endphp

            @php

                $specific_time = new DateTime('01:00:00', new DateTimeZone('Asia/Kolkata'));
                $specific_time = $specific_time->format('H:i:s');

                $current_time = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
                $current_time = $current_time->format('H:i:s');

                $open_time = new DateTime($game->open_time, new DateTimeZone('Asia/Kolkata'));
                $open_time = $open_time->format('H:i:s');

                $close_time = new DateTime($game->close_time, new DateTimeZone('Asia/Kolkata'));
                $close_time = $close_time->format('H:i:s');

            @endphp

            <section class="thirdRow">
                <div class="row">
                    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xlg-1" style="color:#ffff;padding:6px;">
                        <div class="alert alert-dismissible alert-default"
                            style="background-color:#500143;border: 3px solid #ffc827;padding-bottom: 6px;
                margin-bottom: 0px;">
                            <div class="row">
                                <div class="col-3 col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xlg-3"
                                    style="color:#ffc827;padding:0px;text-align:center;">
                                    <a href="{{ route('galidesawar_resultChart', ['result_id' => $game->id]) }}"><img
                                            src="images/chart.jpg" style="width: 35px;margin-top: 11px;height: 35px;"></a>
                                    <div style="width:100%;">
                                        <span
                                            style="color:#ffc827;border-radius:6px;text-align:center;font-size: 13px;">Result
                                            Chart</span>
                                    </div>
                                </div>
                                <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xlg-6" style="padding:0px;">
                                    <p style="color:#ffc827;text-align:center;" class="game_name">{{ $game->desawar_name }}
                                    </p>
                                    <p style="color:#fff;font-size:0.7rem;text-align:center;" class="result_time">
                                        Open : {{ $formattedTime }} | Close : {{ $formattedTimes }} </p>

                                    @if ($current_time >= $close_time)
                                        {{-- <p style="color:#d90707;text-align:center;font-size:13px;" class="result_time"> --}}
                                            {{-- Market Close</p> --}}
                                    @else
                                        {{-- <p style="color:#09a02c;text-align:center;" class="result_time">Market Open</p> --}}
                                    @endif

                                    @if (isset($results[$game->id]))
                                        <!-- Check if result exists for this market -->
                                        @foreach ($results[$game->id] as $result)
                                            <p style="color: grren;font-size:14px;text-align:center;" class="result_time">
                                                {{ $result->digit ?? '**' }}


                                            </p>
                                        @endforeach
                                    @else
                                        <p style="color: #ffc827;font-size:18px;text-align:center;" class="result_time">**
                                        </p>
                                    @endif
                                </div>

                                @if ($current_time >= $close_time)
                                    <div class="col-3 col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xlg-3"
                                        style="padding:0px;text-align:center;">
                                        <i class="fa fa-play-circle" onclick="abcc();"
                                            style="width: 33px;margin-top: 11px;height: 33px;font-size: 38px;color:#d90707;border-radius: 47px;">
                                        </i>
                                        <div style="width:100%;">
                                            <span
                                                style="color:#ffc827;border-radius:6px;text-align:center;font-size: 13px;">Market
                                                Close</span>
                                        </div>


                                    </div>
                                @else
                                    <div class="col-3 col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xlg-3"
                                        style="padding:0px;text-align:center;">
                                        <a href="{{ route('galidesawar_games', ['desawar_id' => $game->id]) }}"><i
                                                class="fa fa-play-circle"
                                                style="width: 33px;margin-top: 11px;height: 33px;font-size: 38px;color:#ffc827;background: #fff;border-radius: 47px;">
                                            </i></a>
                                        <div style="width:100%;">
                                            <span
                                                style="background:#fff;color:#ffc827;border-radius:6px;text-align:center;padding:4px;font-size: 9px;">Play
                                                Now</span>
                                        </div>
                                    </div>
                                @endif

                            </div>

                        </div>

                    </div>
                </div>
            </section>
        @endforeach




    </div>


    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

        function abcc() {
            alertt("Market Closed !");
            if ("vibrate" in navigator) {
                navigator.vibrate(200);
            }
        }

        var txt;

        function alertt(txt) {

            $("#alert").text(txt);
            $("#alert").show('slow');
            setTimeout(function() {
                $("#alert").hide('slow');
            }, 2000);



        }

        function openNav() {
            localStorage.setItem("menubar", "1");
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            localStorage.setItem("menubar", "0");
            document.getElementById("mySidenav").style.width = "0";
        }
    </script>
    <!--===============================================================================================-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->

    <!--===============================================================================================-->
    <!-- <script src="vendor/bootstrap/js/popper.js"></script> -->
    <!-- <script src="vendor/bootstrap/js/bootstrap.min.js"></script> -->
    <!--===============================================================================================-->
    <!-- <script src="js/main.js"></script> -->

    </body>

    </html>

@endsection
