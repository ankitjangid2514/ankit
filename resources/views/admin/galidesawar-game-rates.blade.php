@extends('admin.layouts.main')
@section('title')
GaliDesawar Game Rates Managment
@endsection
@section('container')

    <div class="main-content">	<div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-lg-8 mr-auto ml-auto">
                    <div class="row">
                        <div class="col-sm-12 col-12 ">
                            <div class="card">
                                <div class="card-body">
                                <h5 class="card-title">Add Games Rate</h5>
                                    <form class="theme-form mega-form" method="post" action="{{route('desawar_game_rates_insert')}}">
                                        @csrf
                                        {{-- <input type="hidden" name="game_rate_id" value="1"> --}}
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="col-form-label">Left Digit Value 1</label>
                                            <input class="form-control" type="number" name="left_digit_bid" id="left_digit_bid" value="{{$data->left_digit_bid}}" placeholder="Enter Left Digit Bid Value">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="col-form-label">Left Digit Value 2</label>
                                            <input class="form-control" type="number" name="left_digit_win" id="left_digit_win" value="{{$data->left_digit_win}}" placeholder="Enter Left Digit Win Value">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-form-label">Right Digit Value 1</label>
                                            <input class="form-control" type="number" name="right_digit_bid" id="right_digit_bid" value="{{$data->right_digit_bid}}" placeholder="Enter Right Digit Bid Value">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="col-form-label">Right Digit Value 2</label>
                                            <input class="form-control" type="number" name="right_digit_win" id="right_digit_win" value="{{$data->right_digit_win}}" placeholder="Enter Right Digit Win Value">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="col-form-label">Jodi Digit Value 1</label>
                                            <input class="form-control" type="number" name="jodi_digit_bid" id="jodi_digit_bid" value="{{$data->jodi_digit_bid}}" placeholder="Enter Jodi Digit Bid Value">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="col-form-label">Jodi Digit Value 2</label>
                                            <input class="form-control" type="number" name="jodi_digit_win" id="jodi_digit_win" value="{{$data->jodi_digit_win}}" placeholder="Enter Jodi Digit Win Value">
                                        </div>


                                    </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary waves-light m-t-10">Submit</button>
                                        </div>
                                        <div class="form-group">
                                            <div id="error"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
