@extends('admin.layouts.main')
@section('title')
Starline Game Name Management
@endsection
@section('container')

<style>
    .popup {
        display: none;
        /* Hidden by default */
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }


    body {
        overflow: hidden;
        /* Prevent scrolling when popup is open */
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
            <div class="row">
                <!-- Zero Configuration  Starts-->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title d-flex align-items-center justify-content-between">Starline Game Name List <a class="btn btn-primary btn-sm btn-float" href="#addstarlinegameModal" role="button" data-toggle="modal">Add Game </a></h4>
                            <table class="table table-striped table-bordered" id="starlinegameList">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Game Name</th>
                                        <!-- <th>Game Name Hindi</th> -->
                                        <th>Open Time</th>
                                        <th>Close Time</th>
                                        <th>Status</th>
                                        <!-- <th>Market Status</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addstarlinegameModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal_right_side" role="document">
            <div class="modal-content col-12 col-md-5">
                <div class="modal-header">
                    <h5 class="modal-title">Add Game</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form class="theme-form" method="post" action="{{route('starline_market_insert')}}">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="game_id" value="">
                            <div class="form-group col-12">
                                <label for="game_name">Game Name</label>
                                <input type="text" name="game_name" id="game_name" class="form-control" placeholder="Enter Game Name" />
                            </div>

                            <!-- <div class="form-group col-12">
                        <label for="game_name_hindi">Game Name Hindi</label>
                        <input type="text" name="game_name_hindi" id="game_name_hindi" class="form-control" placeholder="Enter Game Name In Hindi"/>
                    </div> -->
                            <div class="row col-12">
                                <div class="form-group col-6">
                                    <label for="open_time">Open Time</label>
                                    <input name="open_time" id="open_time" class="form-control digits" type="time" value="">

                                </div>
                                <div class="form-group col-6">
                                    <label for="close_time">Close Time</label>
                                    <input name="close_time" id="close_time" class="form-control digits" type="time" value="">

                                </div>
                            </div>

                            <div class="form-group col-12">
                                <button type="submit" class="btn btn-primary waves-light m-t-10">Submit</button>
                                <button type="reset" class="btn btn-danger waves-light m-t-10">Reset</button>

                            </div>
                        </div>
                        <!-- <div class="form-group">
                    <div id="msg"></div>
                </div> -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updategameModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal_right_side" role="document">
            <div class="modal-content col-12 col-md-5">
                <div class="modal-header">
                    <h5 class="modal-title">Update Game</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body modal_update_game_body">
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="starlineoffdayModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal_right_side" role="document">
            <div class="modal-content col-12 col-xl-4">
                <div class="modal-header">
                    <h5 class="modal-title">Market Off Day</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body starline_modal_off_day">
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div id="editPopup" class="popup" style="display: none;">
        <form id="editGameForm" method="post" action="{{ route('edit_starline_market') }}">
            @csrf
            <input type="hidden" name="game_id" />
            <div class="form-group">
                <label for="game_name">Game Name</label>
                <input type="text" name="game_name" class="form-control" placeholder="Enter Game Name" />
            </div>
            <div class="form-group">
                <label for="open_time">Open Time</label>
                <input type="time" name="open_time" class="form-control" />
            </div>
            <div class="form-group">
                <label for="close_time">Close Time</label>
                <input type="time" name="close_time" class="form-control" />
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-secondary" id="cancelEdit">Cancel</button>
        </form>

    </div>

    @section('script')

    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize the DataTable
            var table = $('#starlinegameList').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('starline_game_data') }}",
                    type: 'POST',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}';
                        d.parent_id = $('#search').val(); // Include CSRF token for security
                    }
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'starline_name'
                    },
                    // { data: 'market_name_hindi' },
                    {
                        data: 'open_time',
                        render: function(data, type, row) {
                            // Assume data is in HH:MM:SS format (without any timezone adjustments)
                            let [hours, minutes, seconds] = data.split(':');

                            // Create a new Date object using arbitrary year, month, and day but local time
                            let openTime = new Date();
                            openTime.setHours(hours, minutes, seconds);

                            // Format it to 12-hour format with AM/PM using toLocaleString
                            return openTime.toLocaleString('en-US', {
                                hour: 'numeric',
                                minute: 'numeric',
                                hour12: true
                            });
                        }
                    },
                    {
                        data: 'close_time',
                        render: function(data, type, row) {
                            // Assume data is in HH:MM:SS format (without any timezone adjustments)
                            let [hours, minutes, seconds] = data.split(':');

                            // Create a new Date object using arbitrary year, month, and day but local time
                            let closeTime = new Date();
                            closeTime.setHours(hours, minutes, seconds);

                            // Format it to 12-hour format with AM/PM using toLocaleString
                            return closeTime.toLocaleString('en-US', {
                                hour: 'numeric',
                                minute: 'numeric',
                                hour12: true
                            });
                        }
                    },
                    {
                        data: 'market_status',
                        render: function(data, type, row) {
                            // Check the status and render the corresponding badge
                            if (data === 'active') {
                                // If the market status is 'active', show "inactive" with a red badge
                                return '<a class="reject-btn" data-id="' + row.id + '"><span class="badge badge-success">Active</span></a>';
                            } else if (data === 'inactive') {
                                // If the market status is 'inactive', show "active" with a green badge
                                return '<a class="accept-btn" data-id="' + row.id + '"><span class="badge badge-danger">Inactive</span></a>';
                            } else {
                                // Handle any other possible statuses, if any
                                return '<span class="badge badge-secondary">Unknown Status</span>';
                            }
                        }
                    },

                    {
                        render: function(data, type, row) {
                            console.log(row); // Debug the row data
                            return `
        <button class="btn btn-primary btn-edit" 
                data-id="${row.id}" 
                data-name="${row.starline_name}" 
                data-open-time="${row.open_time}" 
                data-close-time="${row.close_time}">
            Edit
        </button>
        <button class="btn btn-danger btn-delete" data-id="${row.id}">Delete</button>
    `;
                        }

                    }
                ]
            });

            // Change Market Status to Inactive
            $(document).on('click', '.reject-btn', function() {
                var id = $(this).data('id');
                // Handle approve action
                if (confirm('Are you sure you want to Inactive this market ?')) {
                    $.ajax({
                        url: "{{ route('inactive_starline_market') }}", // Your route to handle delete
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            table.ajax.reload(); // Reload the DataTable
                            alert('Market Inactive successfully.');
                        },
                        error: function() {
                            alert('Error occurred while INactive the Market.');
                        }
                    });
                }
            });

            // Change Market Status to Active
            $(document).on('click', '.accept-btn', function() {
                var id = $(this).data('id');
                // Handle approve action
                if (confirm('Are you sure you want to Active this market ?')) {
                    $.ajax({
                        url: "{{ route('active_starline_market') }}", // Your route to handle delete
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            table.ajax.reload(); // Reload the DataTable
                            alert('Market Active successfully.');
                        },
                        error: function() {
                            alert('Error occurred while Active the Market.');
                        }
                    });
                }
            });

            // Handle Edit button click
            $('#starlinegameList tbody').on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var openTime = $(this).data('open-time');
                var closeTime = $(this).data('close-time');

                // Populate form fields with data
                $('#editPopup input[name="game_id"]').val(id);
                $('#editPopup input[name="game_name"]').val(name);
                $('#editPopup input[name="open_time"]').val(openTime);
                $('#editPopup input[name="close_time"]').val(closeTime);

                // Show the popup
                $('#editPopup').show();
            });

            // Handle Cancel button click
            $('#cancelEdit').on('click', function() {
                $('#editPopup').hide();
                $('#editGameForm')[0].reset(); // Reset the form
            });


            $('#starlinegameList tbody').on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                // Handle approve action
                if (confirm('Are you sure you want to Delete this market ?')) {
                    $.ajax({
                        url: "{{ route('delete_starline_market') }}", // Your route to handle delete
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            table.ajax.reload(); // Reload the DataTable
                            alert('Market Deleted successfully.');
                        },
                        error: function() {
                            alert('Error occurred while Delete the Market.');
                        }
                    });
                }
            });
        });
    </script>


    @endsection

    @endsection