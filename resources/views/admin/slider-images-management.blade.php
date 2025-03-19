@extends('admin.layouts.main')
@section('title')
Slider Image Management
@endsection
@section('container')

<div class="main-content">	<div class="page-content">
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
			  <div class="card-body">
			  <h4 class="card-title d-flex align-items-center justify-content-between">Slider Image Management			 <a class="btn btn-primary btn-sm btn-float m-b-10" href="#addSliderImageModal" role="button" data-toggle="modal">Add Slider Image</a></h4>
				  <table id="imagesList" class="table table-striped table-bordered">
					<thead>
					  <tr>
						<th>#</th>
						<th>Slider Image</th>
						<th>Display Order</th>
						<th>Creation Date</th>
						<th>Status</th>
						<th>Action</th>
					  </tr>
					</thead>
					</table>

					<div id="msg"></div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="addSliderImageModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal_right_side" role="document">
	<div class="modal-content col-12 col-md-5">
	  <div class="modal-header">
		<h5 class="modal-title">Add Slider Image</h5>
		<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  </div>
	  <div class="modal-body">
		<form class="theme-form" enctype="multipart/form-data" method="post" action="{{route('slider_image_insert')}}">
            @csrf
			<div class="row">

				<div class="form-group col-12">
					<label for="">Slider Image<span class="Img_ext">(Allow Only.jpeg,.jpg,.png)</span></label>
					<input class="form-control" name="image" id="file" type="file" onchange="return validateImageExtensionOther(this.value,1)"/>
				</div>

				<div class="form-group col-12">
					<label for="exampleInputEmail1">Display Order</label>
					<input type="number" name="display_order" id="display_order" class="form-control" placeholder="Enter Image Display Order"/>
				</div>

				<div class="form-group col-12">
					<button type="submit" class="btn btn-primary waves-light m-t-10" id="submitBtn" name="submitBtn">Submit</button>
					<button type="reset" class="btn btn-danger waves-light m-t-10">Reset</button>
				</div>
			</div>
			<div class="form-group">
				<div id="error"></div>
			</div>
		</form>
	</div>
	</div>
  </div>
</div>

<div class="modal fade" id="imageDeleteModal" tabindex="-1" role="dialog" aria-hidden="true" >
	  <div class="modal-dialog modal-frame modal-top modal-md">
		<div class="modal-content">
		  <div class="modal-body">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<p>Are you sure you want to delete this?</p>
					</div>
					<div class="col-md-12">
						<button class="btn btn-info waves-effect waves-light" data-dismiss="modal">No</button>
						<button onclick="delete_this(this.value)" id="delete_id" class="btn btn-danger waves-effect waves-light">Yes</button>

					</div>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>

    @section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize the DataTable
            var table = $('#imagesList').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('slider_data') }}",
                    type: 'post',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                        d.result_pik_date = $('#result_pik_date').val(); // Add selected date as filter
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'image', render: function(data, type, row) {
                        return `<img src="${data}" style="width:50px; height:50px;"/>`;
                    }},
                    { data: 'display_order' },
                    { data: 'created_at' },
                    { 
                        data: 'status', 
                        render: function(data) {
                            // Check the status and set the class accordingly
                            let statusClass = data === 'active' ? 'text-success' : 'text-danger';
                            return '<span class="' + statusClass + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'status', // Specifies the data source for this column
                        className: 'text-center', // Optional: center-align the action column
                        render: function(data, type, row) {
                                let buttons = '';

                            if (row.status === 'inactive') {
                                buttons = `
                                    <a href="javascript:void(0);" class="btn btn-success btn-sm approve-btn" data-id="${row.id}">
                                        <i class="fa-solid fa-check"></i> Active
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </a>
                                `;
                            } else if (row.status === 'active') {
                                buttons = `
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm reject-btn" data-id="${row.id}">
                                        <i class="fa-solid fa-ban"></i> InActive
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </a>
                                `;
                            } else {
                                buttons = `<span>No Actions Available</span>`;
                            }

                            return buttons;
                        }
                    }
                    
                ]
            });

            $(document).on('click', '.approve-btn', function() {
                var id = $(this).data('id');
                // Handle approve action
                if (confirm('Are you sure you want to active this image ?')) {
                    $.ajax({
                        url: "{{ route('slider_image_approve') }}", // Your route to handle delete
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            table.ajax.reload(); // Reload the DataTable
                            alert('Slider Image has been Active successfully.');
                        },
                        error: function() {
                            alert('Error occurred while Active the Slider Image.');
                        }
                    });
                }
            });

            $(document).on('click', '.reject-btn', function() {
                var id = $(this).data('id');
                // Handle approve action
                if (confirm('Are you sure you want to inactive this image?')) {
                    $.ajax({
                        url: "{{ route('slider_image_reject') }}", // Your route to handle delete
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            table.ajax.reload(); // Reload the DataTable
                            alert('Slider Image has been InActive successfully.');
                        },
                        error: function() {
                            alert('Error occurred while Inactive the Slider Image.');
                        }
                    });
                }
            });

            $('#imagesList').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                console.log(id);
                // Implement your delete functionality here
                if (confirm('Are you sure you want to delete this record?')) {
                    $.ajax({
                        url: "{{ route('delete_slider_image') }}", // Your route to handle delete
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            table.ajax.reload(); // Reload the DataTable
                            alert('Record deleted successfully.');
                        },
                        error: function() {
                            alert('Error occurred while deleting the record.');
                        }
                    });
                }
            });


            
        });
    </script>
    @endsection

    @endsection
