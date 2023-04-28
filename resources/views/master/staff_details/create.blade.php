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
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('staff-details/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Staff details</h3>
					</div>
					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>User Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter User Name" name="user_name" value="{{old('user_name')}}" data-rule="admin" onkeypress="return ((event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || event.charCode == 8 || event.charCode == 32);" minlength="3" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('user_name'))
								<div class="error">{{ $errors->first('user_name') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Password <span class="text-danger"> *</span></label>
								<input type="password" class="form-control pos_validate allow_characters" placeholder="Enter Password" name="password" value="{{old('password')}}" data-rule="admin" id="user-password" autocomplete="new-password" />
								<span class="validation_error"></span>
								@if($errors->has('password'))
								<div class="error">{{ $errors->first('password') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Confirm Password <span class="text-danger"> *</span></label>
								@csrf
								<input type="password" class="form-control pos_validate allow_characters" placeholder="Enter Confirm Password" name="confirm_password" value="{{old('confirm_password')}}" data-rule="admin" autocomplete="new-password" />
								<span class="validation_error"></span>
								@if($errors->has('confirm_password'))
								<div class="error">{{ $errors->first('confirm_password') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Full Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Full Name" name="full_name" value="{{old('full_name')}}" data-rule="admin" onkeypress="return ((event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || event.charCode == 8 || event.charCode == 32);" minlength="3" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('full_name'))
								<div class="error">{{ $errors->first('full_name') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Email <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate allow_characters" placeholder="Enter Email" name="email" value="{{old('email')}}" data-rule="admin" />
								<span class="validation_error"></span>
								@if($errors->has('email'))
								<div class="error">{{ $errors->first('email') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Select User Group <span class="text-danger"> *</span></label>
								<select name="user_group_name" class="form-control pos_validate" id="user_group_name">
									<option value="">Select Select User Group</option>
									@isset($Staffgroups)
										@foreach($Staffgroups as $s)
										<option value="{{$s['id']}}">{{$s['group_name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('user_group_name'))
								<div class="error">{{ $errors->first('user_group_name') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Select Site Name <span class="text-danger"> *</span></label>
								<select name="site_name" class="form-control pos_validate" id="site_name">
									<option value="">Select Site Name</option>
									@isset($Staffgroups)
										@foreach($Staffgroups as $s)
										<option value="{{$s['id']}}">{{$s['group_name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('site_name'))
								<div class="error">{{ $errors->first('site_name') }}</div>
								@endif
							</div>
						</div>
						

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
							<div class="custom-control custom-checkbox">
    							<input type="checkbox" class="custom-control-input" id="sub_contractor" name="sub_contractor">
    							<label class="custom-control-label" for="customCheck">Sub Contractor</label>
    							<span class="validation_error"></span>
								@if($errors->has('sub_contractor'))
								<div class="error">{{ $errors->first('sub_contractor') }}</div>
								@endif
  							</div>
							
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Select Role Name <span class="text-danger"> *</span></label>
								<select name="role_name" class="form-control pos_validate" id="role_name">
									<option value="">Select Role Name</option>
									@isset($Roles)
										@foreach($Roles as $r)
										<option value="{{$r['id']}}">{{$r['role_name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('role_name'))
								<div class="error">{{ $errors->first('role_name') }}</div>
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
