    @extends('user.layouts.main')
    @section('title','how to Play')

    @section('content')

    <style>
        :root {
            --primary: #ff9f00;
            --white: #ffffff;
            --orange: #ff9f00;
        }
        .steps-icon {
            font-size: 20px;
            color: var(--orange);
        }
        .step-text {
            font-size: 16px;
        }
        .step-container {
            margin-bottom: 15px;
        }
        .step-title {
            background: var(--primary);
            padding: 10px;
            color: var(--white);
        }
        .step-wrapper {
            margin-top: 20px;
            margin-bottom: 30px;
        }
    </style>


<div class="container ">
    <div class="row step-title " style="border-radius: 10px;">
        <div class="col-12">
            <span>How to Play</span>
        </div>
    </div>
</div>

<div class="container step-wrapper">
    <div class="row step-container">
        <div class="col-auto d-flex align-items-center">
            <i class="fa fa-star steps-icon"></i>
        </div>
        <div class="col">
            <p class="step-text">Download our application from Google Play Store or from our official website.</p>
        </div>
    </div>

    <div class="row step-container">
        <div class="col-auto d-flex align-items-center">
            <i class="fa fa-star steps-icon"></i>
        </div>
        <div class="col">
            <p class="step-text">Register with your mobile number, email ID, and username on our platform.</p>
        </div>
    </div>

    <div class="row step-container">
        <div class="col-auto d-flex align-items-center">
            <i class="fa fa-star steps-icon"></i>
        </div>
        <div class="col">
            <p class="step-text">Login with the application using your mobile number and password.</p>
        </div>
    </div>

    <div class="row step-container">
        <div class="col-auto d-flex align-items-center">
            <i class="fa fa-star steps-icon"></i>
        </div>
        <div class="col">
            <p class="step-text">Select the game type, choose your favorite number, and start playing.</p>
        </div>
    </div>

    <div class="row step-container">
        <div class="col-auto d-flex align-items-center">
            <i class="fa fa-star steps-icon"></i>
        </div>
        <div class="col">
            <p class="step-text">Get a chance to win up to 10 Lac points!</p>
        </div>
    </div>
</div>



</body>
</html>
@endsection