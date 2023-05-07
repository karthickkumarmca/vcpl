@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Staff Groups <small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('roles-list'))}}">Staff Groups management</a></li>
		<li>Edit Staff Groups</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/staffgroups/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Staff Groups</h3>
					</div>

					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Staff Group Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" placeholder="Enter Staff Group Name" name="group_name" value="{{old('group_name') ? old('group_name') : $staffgroups->group_name}}" data-rule="admin" minlength="1" maxlength="100"/>
								<span class="validation_error"></span>
								@if($errors->has('group_name'))
								<div class="error">{{ $errors->first('group_name') }}</div>
								@endif
							</div>
						</div>
					
					</div>
					<div class="box-footer">
						<input type="hidden" name="edited_staffgroups" id="edited_Roles" value="1">
						<div class="pull-right">
							<button type="submit" id="staff_groups_submit" class="btn btn-success">
								<strong>Save</strong>
							</button>
							<a href="{!! url(route('staffgroups-list')) !!}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="staff_groups_id" value="{!! $staffgroups->uuid !!}" />
			</form>
		</div>
	</div>
</section>
@endsection
@section('after-scripts-end')
<!--<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>!-->


@include('master.staffgroups.script')
@stop