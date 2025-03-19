
<footer class="footer">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-6">
					<script>document.write(new Date().getFullYear())</script> ©Matka.
				</div>
				<div class="col-sm-6">
					<div class="text-sm-right d-none d-sm-block">

					</div>
				</div>
			</div>
		</div>
	</footer>
</div>	</div>


	<input type="hidden" id="base_url" value="https://realratanmatka.org/">
	<input type="hidden" id="admin" value="realratan-admin">

	<div id="snackbar"></div>
	<div id="snackbar-info"></div>
	<div id="snackbar-error"></div>
	<div id="snackbar-success"></div>

	<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-lg">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-8 text-right">
						<p>Are you sure you want to logout? If you logout then your session is terminated.</p>
					</div>
					<div class="col-md-4 text-right">
						<button class="btn btn-info waves-effect waves-light" data-dismiss="modal">Cancel</button>
						<a href="logout.php" class="btn btn-info waves-effect waves-light">Logout</a>

					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>

	<div class="modal fade" id="deleteConfirmOpenResutlt" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-md">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p>Are you sure you want to delete this result?</p>
					</div>
					<div class="col-md-12">
						<button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
						<input type="hidden" name="delete_game_id" id="delete_game_id" value="">
						<button onclick="OpenDeleteResultData();" id="openDecBtn1" class="btn btn-success waves-effect waves-light">Yes</button>

					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>


	<div class="modal fade" id="deleteConfirmOpenStarlineResutlt" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-md">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p>Are you sure you want to delete this result?</p>
					</div>
					<div class="col-md-12">
						<button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
						<input type="hidden" name="delete_starline_game_id" id="delete_starline_game_id">
						<button onclick="OpenDeleteStarlineResultData();" id="openDecBtn1" class="btn btn-success waves-effect waves-light">Yes</button>

					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>


	<div class="modal fade" id="deleteConfirmOpenGalidisswarResutlt" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-md">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p>Are you sure you want to delete this result?</p>
					</div>
					<div class="col-md-12">
						<button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
						<input type="hidden" name="delete_gali_game_id" id="delete_gali_game_id">
						<button onclick="OpenDeleteGalidisswarResultData();" id="openDecBtn1" class="btn btn-success waves-effect waves-light">Yes</button>

					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>


	<div class="modal fade" id="deleteConfirmCloseResutlt" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-md">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p>Are you sure you want to delete this result?</p>
					</div>
					<div class="col-md-12">
						<input type="hidden" name="delete_close_game_id" id="delete_close_game_id" value="">
						<button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
						<button onclick="closeDeleteResultData();" id="closeDecBtn1" class="btn btn-success waves-effect waves-light">Yes</button>

					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>




	<div class="modal fade" id="fundRequestAcceptModal" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-md">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p>Are you sure you want to accept this fund request?</p>
					</div>
					<div class="col-md-12">
						<button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
						<button onclick="accept_request(this.value)" id="accept_request_id" class="btn btn-success waves-effect waves-light">Yes</button>

					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>


	<div class="modal fade" id="winnerListModal" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-lg">
		<div class="modal-content">
		<div class="modal-header">
        <h5 class="modal-title">Winner List</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h5>Total Bid Amount : <b><span id="total_bid"></span></b></h5>
						<h5>Total Winning Amount : <b><span id="total_winneing_amt"></span></b></h5>

						<div class="dt-ext table-responsive" style="max-height: 400px;overflow-y: scroll;">

							<table class="table table-striped table-bordered">

								<thead>

									<tr>

										<th>#</th>
										<th>User Name</th>
										<th>Bid Points</th>
										<th>Winning Amount</th>
										<th>Type</th>
										<th>Bid TX ID</th>

									</tr>

								</thead>

								<tbody id="winner_result_data">

								</tbody>
							</table>
						</div>
					</div>

				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>

	<div class="modal fade" id="fundRequestRejectModal" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-md">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p>Are you sure you want to reject this fund request?</p>
					</div>
					<div class="col-md-12">
						<button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
						<button onclick="reject_request(this.value)" id="reject_request_id" class="btn btn-danger waves-effect waves-light">Yes</button>

					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
	<div class="modal fade" id="fundRequestAutoRejectModal" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-md">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p>Are you sure you want to reject this fund request?</p>
					</div>
					<div class="form-group col-md-12">
						<label>Remark</label>
						<input type="text" name="reject_auto_remark" id="reject_auto_remark" class="form-control" placeholder="Enter Remark"/>
					</div>
					<div class="col-md-12">
						<button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
						<button onclick="reject_auto_request(this.value)" id="reject_auto_request_id" class="btn btn-danger waves-effect waves-light">Yes</button>

					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>



	<div class="modal fade" id="autoDeleteModal" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-md">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p>Are you sure you want to delete this fund request?</p>
					</div>
					<div class="col-md-12">
						<button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
						<button onclick="delete_auto_request(this.value)" id="delete_auto_id" class="btn btn-danger waves-effect waves-light">Yes</button>

					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>

	<div class="modal fade" id="fundRequestAutoAcceptModal" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-md">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p>Are you sure you want to accept this fund request?</p>
					</div>
					<div class="col-md-12">
						<button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
						<button onclick="accept_auto_request(this.value)" id="accept_auto_request_id" class="btn btn-success waves-effect waves-light">Yes</button>

					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>

	<div class="modal fade" id="autoDeleteModal" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-md">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p>Are you sure you want to delete this fund request?</p>
					</div>
					<div class="col-md-12">
						<button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
						<button onclick="delete_auto_request(this.value)" id="delete_auto_id" class="btn btn-danger waves-effect waves-light">Yes</button>

					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>


<div id="viewWithdrawRequest" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title mt-0" id="myLargeModalLabel">Withdraw Request Detail</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body viewWithdrawRequestBody">



			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<div id="requestApproveModel" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Approve Withdraw Request</h5><button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
		<form class="theme-form"  id="withdrawapproveFrm" method="post" enctype="multipart/formdata">
			<div class="form-group user_info">

			</div>
			<div class="form-group">
				<label for="">Payment Receipt Image<span class="Img_ext">(Allow Only.jpeg,.jpg,.png)</span></label>
				<input class="form-control" name="file" id="file" type="file" onchange="return validateImageExtensionOther(this.value,1)"/>
			</div>
			<div class="form-group">
				<label>Remark</label>
				<input type="text" name="remark" id="remark" class="form-control" placeholder="Enter Remark"/>
			</div>
		  <input type="hidden" name="withdraw_req_id" id="withdraw_req_id" value="">
		  <div class="form-group">
		  <button type="submit" class="btn btn-info btn-sm m-t-10" id="submitBtn" name="submitBtn">Submit</button>
		  </div>
		  <div class="form-group m-b-0">
			 <div id="alert_msg_manager"></div>
		  </div>
	   </form>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>


<div id="requestRejectModel" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reject Withdraw Request</h5><button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
		<form class="theme-form"  id="withdrawRejectFrm" method="post" enctype="multipart/formdata">
			<div class="form-group">
				<label>Remark</label>
				<input type="text" name="remark" id="remark" class="form-control" placeholder="Enter Remark"/>
			</div>
		  <input type="hidden" name="withdraw_req_id" id="r_withdraw_req_id" value="">
		  <div class="form-group">
		  <button type="submit" class="btn btn-info btn-sm m-t-10" id="submitBtnReject" name="submitBtnReject">Submit</button>
		  </div>
		  <div class="form-group m-b-0">
			 <div id="alert_msg"></div>
		  </div>
	   </form>
      </div>
    </div>
  </div>
</div>



<div class="modal fade " id="open-img-modal" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
        <button type="button" class="close" style="text-align:right" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <img class="my_image"/>
      </div>
	</div>
  </div>
</div>

	<script src="{{asset('adminassets/libs/jquery/jquery.min.js')}}"></script>

	<script src="{{asset('adminassets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
	<script src="{{asset('adminassets/libs/metismenu/metisMenu.min.js')}}"></script>
	<script src="{{asset('adminassets/libs/simplebar/simplebar.min.js')}}"></script>
	<script src="{{asset('adminassets/libs/simplebar/simplebar.min.js')}}"></script>
	<script src="{{asset('adminassets/libs/simplebar/simplebar.min.js')}}"></script>
	<script src="{{asset('adminassets/libs/node-waves/waves.min.js')}}"></script>
	<script src="{{asset('adminassets/libs/select2/js/select2.min.js')}}"></script>
	<script src="{{asset('adminassets/js/pages/form-advanced.init.js')}}"></script>
	<!-- Required datatable js -->
        <script src="{{asset('adminassets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('adminassets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <!-- Buttons examples -->
        <script src="{{asset('adminassets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('adminassets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
        <script src="{{asset('adminassets/libs/jszip/jszip.min.js')}}"></script>
        <script src="{{asset('adminassets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
        <script src="{{asset('adminassets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
        <script src="{{asset('adminassets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
        <script src="{{asset('adminassets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
        <script src="{{asset('adminassets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>

        <!-- Responsive examples -->
        <script src="{{asset('adminassets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('adminassets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

        <!-- Datatable init js -->
        <script src="{{asset('adminassets/js/pages/datatables.init.js')}}"></script>
	<!-- App js -->
	<script src="{{asset('adminassets/js/app.js')}}"></script>
	<script src="{{asset('adminassets/js/customjs.js?v=4003')}}"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    @yield('script')

  </body>

</html>
