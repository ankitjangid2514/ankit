@extends('user.layouts.main')
@section('title','games')
@section('content')
    <div class="container" style="margin-top: 30px; margin-bottom: 30px;">
        <div class="row">


            @foreach ($data as $gtype)
                @foreach ($time as $times)
                    @php
                        $current_time = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
                        $current_time = $current_time->format('H:i:s');

                        $open_time = new DateTime($times->open_time, new DateTimeZone('Asia/Kolkata'));
                        $open_time = $open_time->format('H:i:s');
                    @endphp
                @endforeach

                <div class="{{ $gtype->id == 9 ? 'col-12' : 'col-6' }}"
                    style="text-align: center; background:transparent;">
                    <div class="games_box mt-2">
                        @if ($current_time >= $open_time && in_array($gtype->id, [5, 6, 7]))
                            <a>
                                <img src="{{ url($gtype->img) }}" alt="img" onclick="abcc();">
                            </a>
                        @else
                           <a href="{{ route('addGame', ['gtype_id' => $gtype->id, 'market_id' => $marketid]) }}">
                                <img src="{{ url($gtype->img) }}" alt="img" style="height:125px;">
                                <p style="font-size:15px; color:#ffc827;margin-top:10px;">{{ $gtype->gtype }}</p>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <script>
        var txt;

        function alertt(txt) {

            $("#alert").text(txt);
            $("#alert").show('slow');
            setTimeout(function() {
                $("#alert").hide('slow');
            }, 2000);



        }

        function abcc() {
            alertt("Times Up");

            if ("vibrate" in navigator) {
                navigator.vibrate(200);
            }
        }
    </script>
    <script src="{{ url('vendor/jquery/jquery-3.2.1.min.js') }}"></script>

    <!--===============================================================================================-->

    <!--===============================================================================================-->

    <!--===============================================================================================-->
    <!-- <script src="vendor/bootstrap/js/popper.js"></script> -->
    <!-- <script src="vendor/bootstrap/js/bootstrap.min.js"></script> -->
    <!--===============================================================================================-->
    <!-- <script src="js/main.js"></script> -->
    <script>
        function openNav() {
            localStorage.setItem("menubar", "1");
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            localStorage.setItem("menubar", "0");
            document.getElementById("mySidenav").style.width = "0";
        }
    </script>

</body>

</html>
@endsection