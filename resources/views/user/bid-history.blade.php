@extends('user.layouts.main')

@section('title', 'Bid History')

@section('content')

<style>
    .card-body {
        color: #fff;
        padding: 20px;
    }

    .card {
        margin-bottom: 20px;
        background: linear-gradient(135deg, #2f3b42, #1d1f22); /* Gradient background */
        border-radius: 12px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition for hover effects */
    }

    .card:hover {
        transform: translateY(-5px); /* Lift the card on hover */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Stronger shadow on hover */
    }

    .card-title {
        text-align: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 15px;
        color: #ffdd33; /* Title color */
    }

    .card-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .card-content .info {
        flex: 1;
    }

    .card-content .info p {
        font-size: 1rem;
        margin-bottom: 5px;
    }

    .card-content .info p strong {
        color: #ff6f61; /* Static text color */
    }

    .card-content .info p span {
        color: #ffffff; /* Dynamic text color */
    }

    .bid-info {
        flex: 1;
        text-align: right;
    }

    .bid-info p {
        font-size: 1rem;
        margin-bottom: 5px;
    }

    .bid-info p strong {
        color: #ff6f61; /* Static text color */
    }

    .bid-info p span {
        color: #ffffff; /* Dynamic text color */
    }

    .info p .nowrap-text {
        white-space: nowrap; /* Prevent wrapping */
        overflow: hidden; /* Hide overflowing text */
        text-overflow: ellipsis; /* Add ellipsis for overflowed text */
    }

    .card-text {
        font-size: 1rem;
        margin-bottom: 8px;
    }
</style>

<div class="container">
    <div class="row" style="background: var(--primary); padding: 10px; border-radius:10px; margin-left:1px; margin-right:1px; ">
        <div class="col-12">
            <span style="color: var(--white); font-family: auto; font-size: 18px;">Bid History</span>
        </div>
    </div>

    <!-- Form to filter bids by date -->
    <form method="POST" action="{{ route('bid_history_list') }}">
        @csrf
        <div class="row mt-3">
            <div class="col-md-4">
                <label for="date" class="form-label text-white">Select Date</label>
                <input type="date" id="date" name="date" class="form-control" value="{{ old('date', session('selected_date')) }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary mt-4" style="width: 100%;">Filter</button>
            </div>
        </div>
    </form>

    @if (session('bids') && count(session('bids')))
        <div class="row mt-3">
            <!-- Loop through bids and display each in a card -->
            @foreach (session('bids') as $bid)
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Market Name at the Top -->
                            <h5 class="card-title">{{ $bid['market_name'] }}</h5>
                            
                            <div class="card-content">
                                <!-- Left: Session, Date, and Game Type -->
                                <div class="info">
                                    <p><strong>Session:</strong> <span>{{ $bid['session'] ?? '-' }}</span></p>
                                    <p><strong>Date:</strong> <span>{{ $bid['date'] }}</span></p>
                                    <p><strong>Game:</strong> <span class="">{{ $bid['game'] }}</span></p>
                                </div>

                                <!-- Right: Bid Number and Amount -->
                                <div class="bid-info">
                                    <p><strong>Bid Number:</strong> <span>{{ $bid['bid_number'] }}</span></p>
                                    <p><strong>Amount:</strong> <span>₹{{ $bid['amount'] }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row mt-3">
            <div class="col-12">
                <p style="color: #fff; font-size: 16px;" class="text-center">No bids found for the selected date.</p>
            </div>
        </div>
    @endif
</div>

@endsection
