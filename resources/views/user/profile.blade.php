@extends('user.layouts.main')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
	<div class="row" style="background:#ffc827;padding: 10px;">
		<div class="col-12">
			<span style="color:#000;">My Profile</span>
		</div>
	</div>
</div>

<div class="container" style="margin-top: 30px; margin-bottom: 30px;">
	<form method="post" id="game_form">
		<div class="row">

			<div class="col-12">
				<div class="form-group">
				    <label class="form-label" for="from_date" style="color:#ffc827;">Name</label>
				    <input type="text" class="form-control" name="username" id="username" value="{{ $userData->name }}" placeholder="Enter Name" required="required" readonly />
				</div>
			</div>

			<div class="col-12">
				<div class="form-group">
				    <label class="form-label" for="from_date" style="color:#ffc827;">Phone Number</label>
				    <input type="text" class="form-control" name="point_value" id="point_value" placeholder="Phone Number" required="required" value="{{ $userData->number }}" readonly />
				</div>
			</div>

		</div>
	</form>
</div>

<!--===============================================================================================-->
<script src="{{ url('vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->

<script>
	function openNav() {
		localStorage.setItem("menubar", "1");
		document.getElementById("mySidenav").style.width = "250px";
	}

	function closeNav() {
		localStorage.setItem("menubar", "0");
		document.getElementById("mySidenav").style.width = "0";
	}
</script>
@endsection
