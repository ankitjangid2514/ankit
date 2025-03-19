    {{-- include the header blade file  --}}
    @include('user.layouts.header')

    @php
        $current_time = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        $current_time = $current_time->format('H:i:s');

        $open_time = new DateTime($time->open_time, new DateTimeZone('Asia/Kolkata'));
        $open_time = $open_time->format('H:i:s');
    @endphp

    @if ($gtype_id == '1')
     {{-- incluse single-digit-game blade file  --}}
     @include('user.games.single-digit-game')
    @elseif ($gtype_id == '2')
    @include('user.games.single-panna')
     
    @elseif ($gtype_id == '3')
    @include('user.games.double-panna')
  
    @elseif ($gtype_id == '4')
    @include('user.games.triple-panna')
     
    @elseif ($gtype_id == '5')
    
    @include('user.games.half-sangam')

    @elseif ($gtype_id == '6')
    @include('user.games.full-sangam')
    
    @elseif($gtype_id == '7')
    @include('user.games.jodi-digit')

    @elseif ($gtype_id == '8')
    @include('user.games.sp-panna')

    @elseif($gtype_id == '9')

     @include('user.games.dp-panna')


    @endif

    <script src="{{ url('js/main.js') }}"></script>

    {{-- For Getting The Today Date --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const year = today.getFullYear();
            const month = ('0' + (today.getMonth() + 1)).slice(-2); // Adding 1 because months are 0-indexed
            const day = ('0' + today.getDate()).slice(-2); // Ensure two digits for day

            const formattedDate = `${year}-${month}-${day}`;
            const dateInput = document.getElementById('gdate');

            // Set the max attribute to today's date
            dateInput.setAttribute('max', formattedDate);

            // Set the value to today's date
            dateInput.value = formattedDate;
        });

        function openNav() {
            localStorage.setItem("menubar", "1");
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            localStorage.setItem("menubar", "0");
            document.getElementById("mySidenav").style.width = "0";
        }
    </script>








    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>




</body>


</html>
