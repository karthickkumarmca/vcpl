@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Staff Details<small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('product-details-list'))}}">Staff Details</a></li>
		<li>Edit Staff</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/staff-details/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Staff</h3>
					</div>

					<div class="box-body">

					<div class="col-md-12">
							<div class="form-group">
								<label>Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Full Name" name="name" value="{{old('name') ? old('name') : $details->name}}" data-rule="admin" onkeypress="return ((event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || event.charCode == 8 || event.charCode == 32);" minlength="3" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('name'))
								<div class="error">{{ $errors->first('name') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>User Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter User Name" name="user_name" value="{{old('user_name')? old('user_name') : $details->user_name}}" data-rule="admin" onkeypress="return ((event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || event.charCode == 8 || event.charCode == 32);" minlength="3" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('user_name'))
								<div class="error">{{ $errors->first('user_name') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Email <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate allow_characters" placeholder="Enter Email" name="email" value="{{old('email')? old('email') : $details->email}}" data-rule="admin" />
								<span class="validation_error"></span>
								@if($errors->has('email'))
								<div class="error">{{ $errors->first('email') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Mobile Number <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate number_restrict" placeholder="Enter mobile number" name="phone_number" value="{{old('phone_number')? old('phone_number') : $details->phone_number}}" data-rule="admin" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57));" minlength="7" maxlength="10"/>
								<span class="validation_error"></span>
								@if($errors->has('phone_number'))
								<div class="error">{{ $errors->first('phone_number') }}</div>
								@endif
							</div>
						</div>	
					<div class="col-md-12">
							<div class="form-group">
								<label>Select User Group<span class="text-danger"> *</span></label>
								<select name="user_groups_id" class="form-control pos_validate" id="user_groups_id">
									<option value="">Select User Group</option>
									@isset($staff_groups)
										@foreach($staff_groups as $s)
											@if(old('user_groups_ids') != "")
												@if(old('user_groups_ids') == $s['id'])
													<option value="{{$s['id']}}" selected>{{$s['group_name']}}</option>
												@else
													<option value="{{$s['id']}}">{{$s['group_name']}}</option>
												@endif
											@else
												@isset($details->user_groups_ids)
													@if($details->user_groups_ids == $s['id'])
														<option value="{{$s['id']}}" selected>{{$s['group_name']}}</option>
													@else
														<option value="{{$s['id']}}">{{$s['group_name']}}</option>
													@endif
												@else
													<option value="{{$s['id']}}">{{$s['group_name']}}</option>
												@endisset
											@endif
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('user_groups_id'))
								<div class="error">{{ $errors->first('user_groups_id') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Select Site Name <span class="text-danger"> *</span></label>
								<select name="site_id" class="form-control pos_validate" id="site_id">
									<option value="">Select Site Name</option>
									@isset($site_info)
										@foreach($site_info as $sub)
											@if ($details->site_ids != "")
												@if($details->site_ids == $sub['id'])
													<option value="{{$sub['id']}}" selected>{{$sub['site_name']}}</option>
												@endif
											@endif
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
									@if($details->sub_contractor)
									<input type="checkbox" class="custom-control-input" value="1" checked="checked" id="sub_contractor" name="sub_contractor" data-rule="admin">
									@else
									<input type="checkbox" class="custom-control-input" value="1" id="sub_contractor" name="sub_contractor" data-rule="admin">
									@endif
    							<label class="custom-control-label" for="sub_contractor">Sub Contractor</label>
    							<span class="validation_error"></span>
								@if($errors->has('sub_contractor'))
								<div class="error">{{ $errors->first('sub_contractor') }}</div>
								@endif
  							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Select Role Name <span class="text-danger"> *</span></label>
								<select name="role_id" class="form-control pos_validate" id="role_id">
									<option value="">Select Role Name</option>
									@isset($roles)
										@foreach($roles as $r)
											@if ($details->role_ids != "")
												@if($details->role_ids == $r['id'])
													<option value="{{$r['id']}}" selected>{{$r['role_name']}}</option>
												@else
													<option value="{{$s['id']}}">{{$r['role_name']}}</option>
												@endif
											@endif
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
						<input type="hidden" name="edited_staff_details" id="edited_staff_details" value="1">
						<div class="pull-right">
							<button type="submit" id="staff-details-submit" class="btn btn-success">
								<strong>Save</strong>
							</button>
							<a href="{{url(route('staff-details-list'))}}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="password" value="{!! $details->uuid !!}" />
				<input type="hidden" name="staff_details_id" value="{!! $details->uuid !!}" />
			</form>
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