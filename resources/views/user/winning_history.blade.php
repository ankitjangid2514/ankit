@extends('user.layouts.main')

@section('title', 'Winning History')

@section('content')

<style>
    .card {
        margin-bottom: 20px;
        background: linear-gradient(135deg, #2f3b42, #1d1f22);
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card-body {
        color: #fff;
        padding: 20px;
    }

    .card-title {
        text-align: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 15px;
        color: #ffdd33;
    }

    .card-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .card-content .info p strong,
    .card-content .bid-info p strong {
        color: #ff6f61;
    }

    .card-content .info p span,
    .card-content .bid-info p span {
        color: #ffffff;
    }
</style>

<div class="container">
    <div class="row" style="background: var(--primary); padding: 10px; border-radius:10px;">
        <div class="col-12">
            <span style="color: var(--white); font-family: auto; font-size: 18px;">Winning History</span>
        </div>
    </div>

    <!-- Form to filter winnings by date -->
    <form method="POST" action="{{ route('winning_history_list') }}">
        @csrf
        <div class="row mt-3">
            <div class="col-md-4">
                <label for="date" class="form-label text-white">Select Date</label>
                <input type="date" id="date" name="date" class="form-control" value="{{ old('date', session('selectedDate', now()->toDateString())) }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary mt-4" style="width: 100%;">Filter</button>
            </div>
        </div>
    </form>
    
    @if (session('winnings') && count(session('winnings')))
        <div class="row mt-3">
            @foreach (session('winnings') as $winning)
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $winning->market_name }}</h5>
                            <div class="card-content">
                                <div class="info">

                                    <p><strong>Session:</strong> <span>{{ $winning->session ?? '-' }}</span></p>
                                    <p><strong>Date:</strong> <span>{{ $winning->date }}</span></p>
                                    <p><strong>Game Type:</strong> <span>{{ $winning->gtype }}</span></p>
                                </div>
                                <div class="bid-info">
                                    <p><strong>Winning Amount:</strong> <span>₹{{ $winning->amount }}</span></p>
                                    <p><strong>Bid Point:</strong> <span>{{ $winning->bid_point }}</span></p>
                                    <p><strong>Bid Amount:</strong> <span>{{ $winning->bid_amount }}</span></p>
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
                <p style="color: #fff; font-size: 16px;" class="text-center">No winnings found for the selected date.</p>
            </div>
        </div>
    @endif
    
</div>

@endsection
