
@extends('user.layouts.main')
@section('title','contact')

@section('content')

<div class="container-fluid">
    <section class="dashCard1" style="margin-top:5px;">

        <div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xlg-12">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header text-center game">Contact us</div>
                    <div class="card-body">
                        <a href="tel:+918952022181">
                            <div class="row card_row" style="color:#fff;">

                                <div class="col-4">
                                    <h4 class="card-title game">Mobile No.</h4>
                                </div>

                                <div class="col-5 pull-center">
                                    <h4 class="card-title game">{{ $admin->mobile_number }}</h4>
                                </div>

                                <div class="col-3 pull-right">
                                    <h4 class="card-title game"><i class="fa fa-phone fa-3x"></i></h4>
                                </div>

                            </div>
                        </a>

                        {{-- <a href="tel:+91">
                            <div class="row card_row" style="color:#fff;">

                                <div class="col-4">
                                    <h4 class="card-title game">Mobile No. 2</h4>
                                </div>

                                <div class="col-5 pull-center">
                                    <h4 class="card-title game">{{ $admin->mobile_number }}</h4>
                                </div>

                                <div class="col-3 pull-right">
                                    <h4 class="card-title game"><i class="fa fa-phone fa-3x"></i></h4>
                                </div>

                            </div>
                        </a> --}}

                        {{-- <a href="mailto:{{$admin->mobile_number}}">

                            <div class="row card_row" style="color:#fff;">

                                <div class="col-3">
                                    <h4 class="card-title game">Email</h4>
                                </div>

                                <div class="col-7 pull-center">
                                    <h4 class="card-title game">Matkaking@gmail.com</h4>
                                </div>

                                <div class="col-2 pull-right">
                                    <h4 class="card-title game"><i class="fa fa-envelope fa-3x"></i></h4>
                                </div>

                            </div>
                        </a> --}}
                        <a href="//api.whatsapp.com/send?phone={{$admin->whatsapp_number}}&text=Hii I have query">
                            <div class="row card_row1" style="color:#fff;">

                                <div class="col-4">
                                    <h4 class="card-title game">Whatsapp</h4>
                                </div>

                                <div class="col-5 pull-center">
                                    <h4 class="card-title game">{{$admin->whatsapp_number}}</h4>
                                </div>

                                <div class="col-3 pull-right">
                                    <h4 class="card-title game"><i class="fa-brands fa-whatsapp fa-3x"></i></h4>
                                </div>

                            </div>
                        </a>
                    </div>

                </div>
            </div>


        </div>



    </section>

</div>


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