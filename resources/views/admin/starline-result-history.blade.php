@extends('admin.layouts.main')
@section('title')
Starline Result History
@endsection
@section('container')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Game Result History</h4>
                                <div class="dt-ext table-responsive">
                                    <table id="starlineGameResultHistory" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Game Name</th>
                                                <th>Result Date</th>
                                                <th>Declare Date</th>
                                                <th>Digit</th>
                                                <th>Panna</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div id="msg"></div>
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
                var table = $('#starlineGameResultHistory').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('starline_result_history_list') }}',
                        type: 'get',
                        data: function(d) {
                            d._token = '{{ csrf_token() }}'; // Include CSRF token for security
                        }
                    },
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'starline_name'
                        },
                        {
                            data: 'result_date'
                        },
                        {
                            data: 'created_at'
                        },
                        {
                            data: 'digit'
                        },
                        {
                            data: 'panna'
                        },

                    ]
                });
            });
        </script>

    @endsection

@endsection
