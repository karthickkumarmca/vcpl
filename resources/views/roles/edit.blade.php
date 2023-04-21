@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Roles <small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('roles-list'))}}">Roles management</a></li>
		<li>Edit Roles</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('roles/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Roles</h3>
					</div>

					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Role Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" placeholder="Enter Role" name="role_name" value="{{old('role_name') ? old('role_name') : $roles->role_name}}" data-rule="admin" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('role_name'))
								<div class="error">{{ $errors->first('role_name') }}</div>
								@endif
							</div>
						</div>
					
					</div>
					<div class="box-footer">
						<input type="hidden" name="edited_Roles" id="edited_Roles" value="1">
						<div class="pull-right">
							<button type="submit" id="roles-submit" class="btn btn-success">
								<strong>Save</strong>
							</button>
							<a href="{!! url(route('roles-list')) !!}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="roles_id" value="{!! $roles->uuid !!}" />
			</form>
		</div>
	</div>
</section>
@endsection
@section('after-scripts-end')
<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>


@include('roles.script')
@stop