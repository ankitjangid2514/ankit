@extends('admin.layouts.main')
@section('title')
Change Password
@endsection
@section('container')
<style>
	.fade-out {
		opacity: 0;
		transition: opacity 1s ease-out;
	}
</style>

<div class="main-content">
	<div class="page-content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-6 col-md-6 mr-auto ml-auto">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title">Change Password</h4>
							@if (session('status'))
							<div id="popup-alert" class="alert alert-success alert-dismissible fade show" role="alert">
								{{ session('status') }}
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							@endif

							@if ($errors->any())
							<div id="popup-alert" class="alert alert-danger alert-dismissible fade show" role="alert">
								@foreach ($errors->all() as $error)
								<div>{{ $error }}</div>
								@endforeach
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							@endif

							<form action="{{ route('admin_password') }}" method="POST">
								@csrf
								<div class="form-group">
									<label for="oldpass">Old Password</label>
									<input type="password" name="oldpass" id="oldpass" class="form-control" placeholder="Enter Old Password">
								</div>

								<div class="form-group">
									<label for="newpass">New Password</label>
									<input type="password" name="newpass" id="newpass" placeholder="Enter New Password" class="form-control" required>
								</div>

								<div class="form-group">
									<label for="retypepass">Confirm New Password</label>
									<input type="password" name="retypepass" id="retypepass" class="form-control" placeholder="Enter Confirm Password" required>
								</div>

								<button type="submit" class="btn btn-primary">Update Password</button>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	@section('script')

		<script>
			document.addEventListener('DOMContentLoaded', function() {
				const alert = document.getElementById('popup-alert');
				if (alert) {
					// Set the timer to hide the alert after 5 seconds (5000 milliseconds)
					setTimeout(() => {
						alert.classList.add('fade-out'); // Optionally add a fade-out effect
						alert.addEventListener('transitionend', () => alert.remove()); // Remove from DOM after fading
					}, 5000); // 5 seconds
				}
			});
		</script>

	@endsection

	@endsection