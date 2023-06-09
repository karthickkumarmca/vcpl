@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">Staff Details<small>Management</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('staff-details-list'))}}">Staff Details</a></li>
		<li>Staff Details</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/staff-details/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Staff details</h3>
					</div>
					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Name" name="name" value="{{old('name')}}" data-rule="admin" onkeypress="return ((event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || event.charCode == 8 || event.charCode == 32);" minlength="1" maxlength="100"/>
								<span class="validation_error"></span>
								@if($errors->has('name'))
								<div class="error">{{ $errors->first('name') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Employee Code <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Employee Code" name="user_name" value="{{old('user_name')}}" data-rule="admin" onkeypress="" minlength="1" maxlength="10" style="text-transform:uppercase;" />
								<span class="validation_error"></span>
								@if($errors->has('user_name'))
								<div class="error">{{ $errors->first('user_name') }}</div>
								@endif
							</div>
							<a data-toggle="collapse" data-target="#username" style="cursor: pointer;color:red">Example Format : VCPL001 </a>	
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Password <span class="text-danger"> *</span></label>
								<input type="password" class="form-control pos_validate allow_characters" placeholder="Enter Password" name="password" value="{{old('password')}}" data-rule="admin" id="password" autocomplete="new-password" minlength="1" maxlength="100" />
								<span class="validation_error"></span>
								@if($errors->has('password'))
								<div class="error">{{ $errors->first('password') }}</div>
								@endif
							</div>
							<a data-toggle="collapse" data-target="#demo" style="cursor: pointer;color:red">Passowrd intructions</a>	
							<div id="demo" class="collapse">
								<li>Must be at least 10 characters in length </li>
								<li>Must contain at least one lowercase letter</li>
								<li>Must contain at least one uppercase letter </li>
								<li>Must contain at least one digit </li>
								<li>Must contain a special character</li> <br>
							</div>
						</div>
						{{-- <div class="col-md-12">
							<div class="form-group">
								<label>Email <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate allow_characters" placeholder="Enter Email" name="email" value="{{old('email')}}" data-rule="admin" />
								<span class="validation_error"></span>
								@if($errors->has('email'))
								<div class="error">{{ $errors->first('email') }}</div>
								@endif
							</div>
						</div> --}}
						<div class="col-md-12">
							<div class="form-group">
								<label>Mobile Number <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate number_restrict" placeholder="Enter mobile number" name="phone_number" value="{{old('phone_number')}}" data-rule="admin" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57));" minlength="7" maxlength="10"/>
								<span class="validation_error"></span>
								@if($errors->has('phone_number'))
								<div class="error">{{ $errors->first('phone_number') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Select User Group <span class="text-danger"> *</span></label>
								<select name="user_groups_id" class="form-control pos_validate" id="user_groups_id" data-rule="admin">
									<option value="">Select Select User Group</option>
									@isset($Staffgroups)
										@foreach($Staffgroups as $s)
										<option value="{{$s['id']}}">{{$s['group_name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('user_groups_id'))
								<div class="error">{{ $errors->first('user_groups_id') }}</div>
								@endif
							</div>
						</div>
						{{-- <div class="col-md-12">
							<div class="form-group">
								<label>Select Site Name </label>
								<select name="site_id" class="form-control pos_validate" id="site_id" data-rule="admin">
									<option value="">Select Site Name</option>
									@isset($Siteinfo)
										@foreach($Siteinfo as $s)
										<option value="{{$s['id']}}">{{$s['site_name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('site_id'))
								<div class="error">{{ $errors->first('site_id') }}</div>
								@endif
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="custom-control custom-checkbox">
    							<input type="checkbox" class="custom-control-input" value="1" id="sub_contractor" name="sub_contractor" data-rule="admin">
    							<label class="custom-control-label" for="sub_contractor">Sub Contractor</label>
    							<span class="validation_error"></span>
								@if($errors->has('sub_contractor'))
								<div class="error">{{ $errors->first('sub_contractor') }}</div>
								@endif
  							</div>
						</div> --}}
						<div class="col-md-12">
							<div class="form-group">
								<label>Select Role Name <span class="text-danger"> *</span></label>
								<select name="role_id" class="form-control pos_validate" id="role_id" data-rule="admin">
									<option value="">Select Role Name</option>
									@isset($Roles)
										@foreach($Roles as $r)
										<option value="{{$r['id']}}">{{$r['role_name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('role_id'))
								<div class="error">{{ $errors->first('role_id') }}</div>
								@endif
							</div>
						</div>					
					</div>
					<div class="box-footer">
						<div class="pull-right">
							<button type="submit" id="staff-details-submit" class="btn btn-success">
								Save
							</button>
							<a href="{{url(route('staff-details-list'))}}" class="btn btn-default">
								Back
							</a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</section>
@endsection
@section('after-scripts-end')
<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>

@include('master.staff_details.script')
@stop
