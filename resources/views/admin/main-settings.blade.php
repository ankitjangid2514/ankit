@extends('admin.layouts.main')
@section('title')
    Settings
@endsection
@section('container')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row row_col">

                    {{-- <div class="col-sm-12 col-xl-6">
                        <div class="card h100p">
                            <div class="card-body">
                                <h4 class="card-title">Add App Link</h4>
                                <form class="theme-form mega-form" id="appLinkFrm" name="appLinkFrm" method="post">
                                    <input type="hidden" name="id" value="1">
                                    <div class="form-group">
                                        <label class="col-form-label">App Link</label>
                                        <input class="form-control" type="text" name="app_link" id="app_link"
                                            value="https://realratanmatka.org/app/Real_Ratan_Matka.apk"
                                            placeholder="Enter APP Link">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Share Message</label>
                                        <textarea class="form-control" name="content" rows="4" id="content">Hi, I found this Awesome very new matka app.. to play online matka and result chart. I am inviting you to join this application. Many features like Auto Deposit, Fast Withdrawals, Referral System are there to increase user experience.</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Referral Share Message</label>
                                        <textarea class="form-control" name="share_referral_content" rows="4" id="share_referral_content">I found new Matka App to play online matka and result chart. I am inviting you to join this application. Many features like Auto Deposit, Fast Withdrawals, Referral System are there
                                        to increase user experience.</textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary waves-light m-t-10"
                                            id="submitMobileBtn">Submit</button>
                                    </div>
                                    <div class="form-group">
                                        <div id="error_msg"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> --}}

                    {{-- <div class="col-sm-12 col-xl-6">
                        <div class="card h100p">
                            <div class="card-body">
                                <h4 class="card-title">Add UPI ID</h4>
                                <form class="theme-form mega-form" id="adminUPIFrm" name="adminUPIFrm" method="post">
                                    <input type="hidden" name="account_id" value="1">


                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label class="col-form-label">Google UPI Payment Id</label>
                                            <input class="form-control" type="text" name="google_upi_payment_id"
                                                id="google_upi_payment_id" value="vyapar.167407021855@hdfcbank"
                                                placeholder="Enter google upi payment id">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label class="col-form-label">Phone Pe UPI Payment Id</label>
                                            <input class="form-control" type="text" name="phonepay_upi_payment_id"
                                                id="phonepay_upi_payment_id" value="7597085464@ybl"
                                                placeholder="Enter phonepay upi payment id">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label class="col-form-label">Other UPI Payment Id</label>
                                            <input class="form-control" type="text" name="upi_payment_id"
                                                id="upi_payment_id" value="vyapar.167407021855@hdfcbank"
                                                placeholder="Enter upi payment id">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary waves-light m-t-10" id="upiSubmitBtn"
                                            name="upiSubmitBtn">Submit</button>
                                    </div>
                                    <div class="form-group">
                                        <div id="error_upi"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> --}}

                    {{-- <div class="col-sm-12 col-xl-6">
                        <div class="card h100p">
                            <div class="card-body">
                                <h4 class="card-title">App Maintainence</h4>
                                <form class="theme-form mega-form" id="appMaintainenceFrm" name="appMaintainenceFrm"
                                    method="post">
                                    <input type="hidden" name="value_id" value="1">
                                    <div class="form-group">
                                        <label class="col-form-label">Share Message</label>
                                        <textarea class="form-control" name="app_maintainence_msg" rows="4" id="app_maintainence_msg">Our app is under maintenance. We will back to you very soon.</textarea>
                                    </div>
                                    <div class="form-group col-6" style="margin-top:30px;">
                                        <div class="media">
                                            <div class="custom-control custom-switch mb-3" dir="ltr">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="maintainence_msg_status" name="maintainence_msg_status"
                                                    value="1">
                                                <label class="custom-control-label" for="maintainence_msg_status">Show Msg
                                                    (ON/OFF)</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary waves-light m-t-10"
                                            id="submitBtnAppMaintainece" name="submitBtnAppMaintainece">Submit</button>
                                    </div>
                                    <div class="form-group">
                                        <div id="error_maintainence"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>




            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-xl-12">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Add Value's</h4>

                                        <form class="theme-form mega-form" name="adminvaluesettingFrm" method="post"
                                            action="{{ route('set_amount') }}">
                                            @csrf
                                            <input type="hidden" name="value_id" value="1">
                                            <!-- Hidden value_id input -->
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">Minimum Deposit</label>
                                                    <input class="form-control" type="number" min=0 name="min_deposite"
                                                        id="min_deposite" value="{{ $set_amount->min_deposite }}"
                                                        placeholder="Enter Min. Deposit Amount">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">Maximum Deposit</label>
                                                    <input class="form-control" type="number" min=0 name="max_deposite"
                                                        id="max_deposite" value="{{ $set_amount->max_deposite }}"
                                                        placeholder="Enter Max Deposit Amount">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">Minimum Withdrawal</label>
                                                    <input class="form-control" type="number" min=0
                                                        name="min_withdrawal" id="min_withdrawal"
                                                        value="{{ $set_amount->min_withdrawal }}"
                                                        placeholder="Enter Min Withdrawal Amount">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">Maximum Withdrawal</label>
                                                    <input class="form-control" type="number" min=0
                                                        name="max_withdrawal" id="max_withdrawal"
                                                        value="{{ $set_amount->max_withdrawal }}"
                                                        placeholder="Enter Max Withdrawal Amount">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">Minimum Bid Amount</label>
                                                    <input class="form-control" type="number" min=0 name="min_bid_amt"
                                                        id="min_bid_amt" value="{{ $set_amount->min_bid_amount }}"
                                                        placeholder="Enter Min Bid Amount">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">Maximum Bid Amount</label>
                                                    <input class="form-control" type="number" min=0 name="max_bid_amt"
                                                        id="max_bid_amt" value="{{ $set_amount->max_bid_amount }}"
                                                        placeholder="Enter Max Bid Amount">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">Welcome Bonus</label>
                                                    <input class="form-control" type="number" min=0 name="welcome_bonus"
                                                        id="welcome_bonus" value="{{ $set_amount->welcome_bonus }}"
                                                        placeholder="Enter Welcome Bonus Amount">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary waves-light m-t-10"
                                                    id="submitValueBtn" name="submitValueBtn">Submit</button>
                                            </div>
                                            <div class="form-group">
                                                <div id="alert"></div>
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
                    <div class="col-sm-12 col-xl-12">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Add Marchant id</h4>

                                        <form class="theme-form mega-form" name="adminvaluesettingFrm" method="post"
                                            action="{{ route('updateMarchantId') }}">
                                            @csrf
                                            <input type="hidden" name="value_id" value="1">
                                            <!-- Hidden value_id input -->
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">Marchant Id </label>
                                                    <input class="form-control" type="text" name="marchant_Id"
                                                        id="marchant_Id" value="{{ $admin->marchant_Id }}"
                                                        placeholder="Enter Welcome marchant Id">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary waves-light m-t-10"
                                                    id="submitValueBtn" name="submitValueBtn">Submit</button>
                                            </div>



                                            <div class="form-group">
                                                <div id="alert"></div>
                                            </div>
                                        </form>



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Display success or error message -->
            @if (session('status') == 'success')
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @elseif (session('status') == 'error')
                <div class="alert alert-danger">
                    {{ session('message') }}
                </div>
            @endif

            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-xl-12">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Add Home Text</h4>

                                        <form class="theme-form mega-form" name="adminvaluesettingFrm" method="post"
                                            action="{{ route('updateHometext') }}">
                                            @csrf
                                            <input type="hidden" name="value_id" value="1">
                                            <!-- Hidden value_id input -->
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">Home text </label>
                                                    <input class="form-control" type="text" name="home_text"
                                                        id="home_text" value="{{ $admin->home_text }}"
                                                        placeholder="Enter Welcome marchant Id">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary waves-light m-t-10"
                                                    id="submitValueBtn" name="submitValueBtn">Submit</button>
                                            </div>



                                            <div class="form-group">
                                                <div id="alert"></div>
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
                    <div class="col-sm-12 col-xl-12">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Add Privacy Policy</h4>

                                        <form class="theme-form mega-form" name="adminvaluesettingFrm" method="post"
                                            action="{{ route('UpdateprivacyPolicy') }}">
                                            @csrf
                                            <input type="hidden" name="value_id" value="1">
                                            <!-- Hidden value_id input -->
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">Privacy Policy </label>
                                                    <input class="form-control" type="text" name="privacyPolicy"
                                                        id="privacyPolicy" value="{{ $admin->privacyPolicy }}"
                                                        placeholder="Enter Welcome privacy policy ">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary waves-light m-t-10"
                                                    id="submitValueBtn" name="submitValueBtn">Submit</button>
                                            </div>



                                            <div class="form-group">
                                                <div id="alert"></div>
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
                    <div class="col-sm-12 col-xl-12">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Add Wallet Page Text (Desawar)</h4>

                                        <form class="theme-form mega-form" name="adminvaluesettingFrm" method="post"
                                            action="{{ route('addWalletPageText') }}">
                                            @csrf
                                            <input type="hidden" name="value_id" value="1">
                                            <!-- Hidden value_id input -->
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">First line text </label>
                                                    <input class="form-control" type="text" name="textFirst"
                                                        id="textFirst" value="{{ $desawar_text->first }}"
                                                        placeholder="Enter Welcome privacy policy ">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">Second line text </label>
                                                    <input class="form-control" type="text" name="textSecond"
                                                        id="textSecond" value="{{ $desawar_text->second }}"
                                                        placeholder="Enter Welcome privacy policy ">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">Third line text </label>
                                                    <input class="form-control" type="text" name="textThird"
                                                        id="textThird" value="{{ $desawar_text->third }}"
                                                        placeholder="Enter Welcome privacy policy ">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label class="col-form-label">Video link </label>
                                                    <input class="form-control" type="text" name="video_link"
                                                        id="video_link" value="{{ $desawar_text->video_link }}"
                                                        placeholder="Enter Welcome privacy policy ">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary waves-light m-t-10"
                                                    id="submitValueBtn" name="submitValueBtn">Submit</button>
                                            </div>



                                            <div class="form-group">
                                                <div id="alert"></div>
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


        <div class="modal fade" id="upiUpdateModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-frame modal-top modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">UPI Update</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="theme-form mega-form" id="UPIOTPConfirmFrm" name="UPIOTPConfirmFrm" method="post">
                            <input type="hidden" name="account_id" value="1">
                            <input type="hidden" name="upi_id" id="upi_id" value="">
                            <input type="hidden" name="up_google_upi_payment_id" id="up_google_upi_payment_id"
                                value="">
                            <input type="hidden" name="up_phonepay_upi_payment_id" id="up_phonepay_upi_payment_id"
                                value="">
                            <div class="form-group">
                                <h6 id="otp_number">OTP Sent To :- </h6>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">OTP Code</label>
                                <input class="form-control" type="text" name="otp_code" id="otp_code"
                                    value="" placeholder="Enter OTP">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary waves-light m-t-10" id="otpSubmitBtn"
                                    name="otpSubmitBtn">Submit</button>
                            </div>
                            <div class="form-group">
                                <div id="error_upi_otp"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @section('script')
    @endsection
@endsection
