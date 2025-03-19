@extends('admin.layouts.main')
@section('title')
    Customer Sell Report
@endsection
@section('container')

    <div class="main-content">	<div class="page-content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12 col-xl-12 col-md-12">

                    <div class="row">

                        <div class="col-sm-12">

                            <div class="card">

                                <div class="card-body">
                                    <h4 class="card-title">Customer Sell Report</h4>
                                    <form id="customerSellFrm" name="customerSellFrm" method="post">

                                    <div class="row">
                                                                            <div class="form-group col-md-2">

                                            <label>Date</label>

                                            <div class="date-picker">

                                                <div class="input-group">

                                                <input class="form-control digits" type="date"  value="2024-09-05" name="start_date" id="start_date" max="2024-09-05" >

                                                </div>

                                            </div>

                                        </div>



                                        <div class="form-group col-md-3">
                                            <label>Game Name</label>
                                            <select id="game_name" name="game_name" class="form-control">
                                                <option value=''>-Select Game Name-</option>
                                                @foreach ($data as $market)

                                                <option value="{{$market->id}}">{{$market->market_name}}</option>

                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="form-group col-md-3">
                                            <label>Game Type</label>
                                            <select  id="game_type" name="game_type" class="form-control" onchange="getSession(this.value);">
                                                <option value='0'>All</option>
                                                @foreach ($gtype as $list)

                                                    <option value="{{$list->id}}">{{$list->gtype}}</option>
                                                @endforeach


                                            </select>
                                        </div>


                                        <div class="form-group col-md-2 session_get">
                                            <label>Session</label>
                                            <select id="market_status" name="market_status" class="form-control">
                                                <option value=''>-Select Session-</option>
                                                    <option value="Open">Open</option>
                                                    <option value="Close">Close</option>

                                            </select>
                                        </div>


                                        <div class="form-group col-md-2">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block" id="submitBtn" name="submitBtn">Submit</button>
                                        </div>

                                    </div>

                                        <div class="form-group">

                                            <div id="msg"></div>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <div class="container-fluid">
            <div class="row">
            <!-- Zero Configuration  Starts-->
                <div class="col-sm-12">
                    <div class="card">
                    <div class="card-body">

                        <div class="mytable">
                        </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('script')
        {{-- For Getting The Today Date --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date();
                const year = today.getFullYear();
                const month = ('0' + (today.getMonth() + 1)).slice(-2); // Adding 1 because months are 0-indexed
                const day = ('0' + today.getDate()).slice(-2); // Ensure two digits for day

                const formattedDate = `${year}-${month}-${day}`;
                const dateInput = document.getElementById('start_date');

                // Set the max attribute to today's date
                dateInput.setAttribute('max', formattedDate);

                // Set the value to today's date
                dateInput.value = formattedDate;
            });
        </script>
    @endsection

@endsection
