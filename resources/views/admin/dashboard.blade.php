@extends('admin.layouts.main')
@section('title')
    Dashboard
@endsection
@section('container')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0 font-size-18">Dashboard</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4">
                        <div class="card overflow-hidden">
                            <div class="bg-soft-primary">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-3">
                                            <h5 class="text-primary">Welcome To KK Matka</h5>
                                            <p>Admin Dashboard</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="adminassets/images/profile-img.png" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0 dboard_pro_mht">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <img src="adminassets/images/logo1.jpg" alt=""
                                                class="img-thumbnail rounded-circle">
                                        </div>
                                        <h5 class="font-size-15 text-truncate">
                                            KK Kalyan </h5>
                                        <p class="text-muted mb-0 text-truncate">Admin</p>
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="pt-4">

                                            <div class="row">
                                                <div class="col-6">
                                                    <a href="{{route('un_approved_users_list')}}">
                                                        <h5 class="font-size-15">{{$unapproved}}</h5>
                                                        <p class="text-muted mb-0">Unapproved Users</p>
                                                    </a>
                                                </div>
                                                <div class="col-6">
                                                    <a href="{{route('user_management')}}">
                                                        <h5 class="font-size-15">{{$approved}}</h5>
                                                        <p class="text-muted mb-0">Approved Users</p>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Market Bid Details</h4>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p class="text-muted">Game Name</p>
                                        <form id="getMarketBidFrm" name="getMarketBidFrm" method="post">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <select id="game_name" name="game_name" class="form-control">
                                                        <option value='0'>-Select Game Name-</option>
                                                        @foreach ($market_data as $market)
                                                            @php
                                                                $time = new DateTime($market->open_time);
                                                                $times = new DateTime($market->close_time);
                                                                $formattedTime = $time->format('g:i A');
                                                                $formattedTimes = $times->format('g:i A');
                                                            @endphp
                                                            <option value="{{ $market->id }}">
                                                                {{ $market->market_name }} ({{ $formattedTime }} - {{ $formattedTimes }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </form>

                                        <h3 id="bid_amt">Please select a game</h3>
                                        <p class="text-muted">Market Amount</p>
                                    </div>


                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card mini-stats-wid">
                                    <a href="{{ route('user_management') }}">
                                        <div class="card-body">
                                            <div class="media">
                                                <div class="media-body">
                                                    <p class="text-muted font-weight-medium">Users</p>
                                                    <h4 class="mb-0">{{ $data }}</h4>
                                                </div>

                                                <div
                                                    class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                                    <span class="avatar-title">
                                                        <i class="bx bx-user font-size-24"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mini-stats-wid"> <a href="{{ route('game_name') }}">
                                        <div class="card-body">
                                            <div class="media">
                                                <div class="media-body">
                                                    <p class="text-muted font-weight-medium">Games</p>
                                                    <h4 class="mb-0">{{ $total }}</h4>
                                                </div>

                                                <div
                                                    class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        <i class="bx bx-archive-in font-size-24"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mini-stats-wid">
                                    <div class="card-body">
                                        <div class="media">
                                            <div class="media-body">
                                                <p class="text-muted font-weight-medium">Bid Amount</p>
                                                <h4 class="mb-0">{{$bid_amount}}</h4>
                                            </div>

                                            <div
                                                class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                                <span class="avatar-title rounded-circle bg-primary">
                                                    <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                </span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card mini-stats-wid">
                                    {{-- <a href="{{ route('user_management') }}"> --}}
                                        <div class="card-body">
                                            <div class="media">
                                                <div class="media-body">
                                                    <p class="text-muted font-weight-medium">Tottal winning</p>
                                                    <h4 class="mb-0">{{ $tottal_winning_amount }}</h4>
                                                </div>

                                                <div
                                                    class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                                    <span class="avatar-title">
                                                        <i class="bx bx-user font-size-24"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    {{-- </a> --}}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mini-stats-wid"> <a href="{{ route('game_name') }}">
                                        <div class="card-body">
                                            <div class="media">
                                                <div class="media-body">
                                                    <p class="text-muted font-weight-medium">Tottal Deposite</p>
                                                    <h4 class="mb-0">{{ $tottal_deposite_amount }}</h4>
                                                </div>

                                                <div
                                                    class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        <i class="bx bx-archive-in font-size-24"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mini-stats-wid">
                                    <div class="card-body">
                                        <div class="media">
                                            <div class="media-body">
                                                <p class="text-muted font-weight-medium">Tottal Withdrawal</p>
                                                <h4 class="mb-0">{{$totta_withdrawal_amount}}</h4>
                                            </div>

                                            <div
                                                class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                                <span class="avatar-title rounded-circle bg-primary">
                                                    <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                </span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Total Bids On Single Ank Of Date 05 Sep 2024</h4>
                                <form id="searchBidFrm" name="searchBidFrm" method="post">
                                    <div class="row">
                                        <div class="form-group col-md-5">
                                            <label>Game Name</label>
                                            <select id="bid_game_name" name="bid_game_name" class="form-control">
                                                <option value='0'>-Select Game Name-</option>

                                                @foreach ($market_data as $market)
                                                    @php
                                                        // Create a DateTime object from the AM/PM time format
                                                        $time = new DateTime($market->open_time);
                                                        $times = new DateTime($market->close_time);

                                                        // Format the time to 24-hour format (e.g., '14:30')
                                                        $formattedTime = $time->format('g:i A');
                                                        $formattedTimes = $times->format('g:i A');
                                                    @endphp

                                                    <option value="{{ $market->id }}">{{ $market->market_name }}
                                                        ({{ $formattedTime }}-{{ $formattedTimes }})
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label>Market Time</label>
                                            <select id="market_status" name="market_status" class="form-control">
                                                <option value='0'>-Select Market Time-</option>
                                                <option value="1">Open Market</option>
                                                <option value="2">Close Market</option>

                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block" id="searchBtn"
                                                name="searchBtn">Get</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div id="search">
                                    <div class="row-2_5 tot_bit_boxs">
                                        <div class="col-md-2_5">
                                            <div class="card border card_0">
                                                <div class="card-header bg-transparent card_0">
                                                    <h5 class="my-0 text-primary">Total Bids <span id="bid0">0</span>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <h2 id="total0">0</h2>
                                                    <h5 class="card-title mt-0">Total Bid Amount</h5>

                                                </div>
                                                <div class="card-footer-ank">Ank <span>0</span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2_5">
                                            <div class="card border card_1">
                                                <div class="card-header bg-transparent card_1">
                                                    <h5 class="my-0 text-primary">Total Bids <span id="bid1">0</span>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <h2 id="total1">0</h2>
                                                    <h5 class="card-title mt-0">Total Bid Amount</h5>

                                                </div>
                                                <div class="card-footer-ank">Ank <span>1</span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2_5">
                                            <div class="card border card_2">
                                                <div class="card-header bg-transparent card_2">
                                                    <h5 class="my-0 text-primary">Total Bids <span id="bid2">0</span>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <h2 id="total2">0</h2>
                                                    <h5 class="card-title mt-0">Total Bid Amount</h5>

                                                </div>
                                                <div class="card-footer-ank">Ank <span>2</span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2_5">
                                            <div class="card border card_3">
                                                <div class="card-header bg-transparent card_3">
                                                    <h5 class="my-0 text-primary">Total Bids <span id="bid3">0</span>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <h2 id="total3">0</h2>
                                                    <h5 class="card-title mt-0">Total Bid Amount</h5>

                                                </div>
                                                <div class="card-footer-ank">Ank <span>3</span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2_5">
                                            <div class="card border card_4">
                                                <div class="card-header bg-transparent card_4">
                                                    <h5 class="my-0 text-primary">Total Bids <span id="bid4">0</span>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <h2 id="total4">0</h2>
                                                    <h5 class="card-title mt-0">Total Bid Amount</h5>

                                                </div>
                                                <div class="card-footer-ank">Ank <span>4</span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2_5">
                                            <div class="card border card_5">
                                                <div class="card-header bg-transparent card_5">
                                                    <h5 class="my-0 text-primary">Total Bids <span id="bid5">0</span>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <h2 id="total5">0</h2>
                                                    <h5 class="card-title mt-0">Total Bid Amount</h5>

                                                </div>
                                                <div class="card-footer-ank">Ank <span>5</span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2_5">
                                            <div class="card border card_6">
                                                <div class="card-header bg-transparent card_6">
                                                    <h5 class="my-0 text-primary">Total Bids <span id="bid6">0</span>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <h2 id="total6">0</h2>
                                                    <h5 class="card-title mt-0">Total Bid Amount</h5>

                                                </div>
                                                <div class="card-footer-ank">Ank <span>6</span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2_5">
                                            <div class="card border card_7">
                                                <div class="card-header bg-transparent card_7">
                                                    <h5 class="my-0 text-primary">Total Bids <span id="bid7">0</span>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <h2 id="total7">0</h2>
                                                    <h5 class="card-title mt-0">Total Bid Amount</h5>

                                                </div>
                                                <div class="card-footer-ank">Ank <span>7</span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2_5">
                                            <div class="card border card_8">
                                                <div class="card-header bg-transparent card_8">
                                                    <h5 class="my-0 text-primary">Total Bids <span id="bid8">0</span>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <h2 id="total8">0</h2>
                                                    <h5 class="card-title mt-0">Total Bid Amount</h5>

                                                </div>
                                                <div class="card-footer-ank">Ank <span>8</span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2_5">
                                            <div class="card border card_9">
                                                <div class="card-header bg-transparent card_9">
                                                    <h5 class="my-0 text-primary">Total Bids <span id="bid9">0</span>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <h2 id="total9">0</h2>
                                                    <h5 class="card-title mt-0">Total Bid Amount</h5>

                                                </div>
                                                <div class="card-footer-ank">Ank <span>9</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>

            </div>


            <!-- <div class="row">

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Fund Request Auto Deposit History</h4>
                            <div class="dt-ext table-responsive demo-gallery">
                                <table class="table table-striped table-bordered " id="autoFundRequestList">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User Name</th>
                                            <th>Amount</th>
                                            <th>Request No.</th>
                                            <th>Txn Id</th>
                                            <th>Reject Remark</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="msg"></div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>

    @section('script')

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#bid_amt').text('Please select a game');

                $('#game_name').change(function() {
                    var marketId = $(this).val();

                    if (marketId !== '0') {
                        $.ajax({
                            url: '/get-market-bid-amount', // Adjust the URL if necessary
                            type: 'POST',
                            data: {
                                market_id: marketId, // This should match your controller's expectation
                                _token: '{{ csrf_token() }}' // Include CSRF token
                            },
                            success: function(response) {
                                $('#bid_amt').text(response.total_bid_amount);
                                console.log(response);
                            },
                            error: function() {
                                $('#bid_amt').text('Error fetching bid amount');
                            }
                        });
                    } else {
                        $('#bid_amt').text('Please select a market');
                    }
                });
            });
        </script>


    @endsection

@endsection
