@extends('admin.layouts.main')
@section('title')
Users Query's
@endsection
@section('container')

    <div class="main-content">	<div class="page-content">
        <div class="container-fluid">
            <div class="row">
            <!-- Zero Configuration  Starts-->
                <div class="col-sm-12">
                    <div class="card">
                    <div class="card-body">
                    <h4 class="card-title d-flex align-items-center justify-content-between">Users Query List</h4>
                        <table class="table table-striped table-bordered" id="userQueryList">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Query</th>
                                <th>Date</th>
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

@endsection
