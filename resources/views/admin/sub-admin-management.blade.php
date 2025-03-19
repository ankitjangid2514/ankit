@extends('admin.layouts.main')
@section('title')
Sub Admin Managment
@endsection
@section('container')

    <div class="main-content">	<div class="page-content">
        <div class="container-fluid">
            <div class="row">
            <!-- Zero Configuration  Starts-->	
                <div class="col-sm-12">
                    <div class="card">
                    <div class="card-body">
                    <h4 class="card-title d-flex align-items-center justify-content-between">Sub Admin List			  <a class="btn btn-primary btn-sm btn-float m-b-10" href="#addSubAdminModal" role="button" data-toggle="modal">Add Sub Admin</a></h4>
                        
                        <table class="table table-striped table-bordered" id="subAdminList">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>User Name</th>
                                <th>Admin Email</th>
                                <th>Creation Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addSubAdminModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal_right_side" role="document">
            <div class="modal-content col-12 col-md-5">
            <div class="modal-header">
                <h5 class="modal-title">Add SubAdmin</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="theme-form" id="subAdminFrm" name="subAdminFrm" method="post" action="{{route('sub_admin_insert')}}">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="admin_id" value="">
                        <div class="form-group col-12">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="text" name="sub_admin_name" id="sub_admin_name" class="form-control" placeholder="Enter Sub Admin Name"/>
                        </div>
                        
                        <div class="form-group col-12">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="number" name="number" id="number" class="form-control" placeholder="Enter Admin Number"/>
                        </div>
                        
                        <div class="form-group col-12">
                            <label for="exampleInputEmail1">User Name</label>
                            <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter Sub Admin Name"/>
                        </div>
                        
                        <div class="form-group col-12">
                            <label for="exampleInputEmail1">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password"/>
                        </div>
                        
                        
                        <div class="form-group col-12">
                            <button type="submit" class="btn btn-primary waves-light m-t-10" id="submitBtn" name="submitBtn">Submit</button>
                            <button type="reset" class="btn btn-danger waves-light m-t-10">Reset</button>
                        
                        </div>
                    </div>
                    <div class="form-group">
                        <div id="msg"></div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal_right_side" role="document">
            <div class="modal-content col-12 col-md-5">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body batch_body">
            
            </div>
            </div>
        </div>
    </div>	
	
@endsection