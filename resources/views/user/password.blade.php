@extends('user.layouts.main')
@section('title','Update Password')

@section('content')

    <div class="container-fluid">
        <div class="row" style="background: var(--primary);padding: 10px;">
            <div class="col-12">
                <span style="color:var(--white);">Password</span>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 30px;">

        <!-- Display Success Message -->
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <!-- Display Validation Errors -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" id="game_form" action="update_password">
            @csrf
            <div class="row">

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="opass">Old Password</label>
                        <input type="password" class="form-control" name="opass" id="opass" autocomplete="off"
                            placeholder="Enter Old Password" required />
                        @error('opass')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="npass">New Password</label>
                        <input type="password" class="form-control" name="npass" id="npass"
                            autocomplete="off" placeholder="Enter New Password" required />
                        @error('npass')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="cpass">Confirm Password</label>
                        <input type="password" class="form-control" name="cpass" id="cpass" autocomplete="off"
                            placeholder="Enter Confirm Password" required />
                        @error('cpass')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" id="submit" name="submit" class="btn"
                        style="color:#ffc827;width:100%;padding-top:12px;padding-bottom:12px;margin-top:50px;background:#500143;">
                        Update Password
                    </button>
                </div>

            </div>
        </form>
    </div>

@endsection
