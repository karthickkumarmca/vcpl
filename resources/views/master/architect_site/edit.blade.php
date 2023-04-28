@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Architect Info <small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('architect-site-list'))}}">Architect Info management</a></li>
		<li>Edit Architect Info</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/architect-site/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Architect Info</h3>
					</div>
					<div class="box-body">
					
					
						<div class="col-md-12">
							<div class="form-group">
								<label>Vehicle Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" placeholder="Enter Name" name="architect_name" value="{{old('architect_name') ? old('architect_name') : $architect_site->architect_name}}" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('architect_name'))
								<div class="error">{{ $errors->first('architect_name') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Company <span class="text-danger"> *</span></label>
								<select name="is_company" class="form-control pos_validate" id="is_company">
									<option value="0" @if($architect_site->is_company==0) selected @endif>No</option>
									<option value="1" @if($architect_site->is_company==1) selected @endif>Yes</option>
									
								</select>
								<span class="validation_error"></span>
								@if($errors->has('is_company'))
								<div class="error">{{ $errors->first('is_company') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Cader <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Cader" name="cader" value="{{old('cader') ? old('cader') : $architect_site->cader}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('cader'))
								<div class="error">{{ $errors->first('cader') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Mobile Number <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Moble Number" name="mobile_number" value="{{old('mobile_number') ? old('mobile_number') : $architect_site->mobile_number}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('mobile_number'))
								<div class="error">{{ $errors->first('mobile_number') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Email Id <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Email id" name="email_id" value="{{old('email_id') ? old('email_id') : $architect_site->email_id}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('email_id'))
								<div class="error">{{ $errors->first('email_id') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Address <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Address" name="address" value="{{old('address') ? old('address') : $architect_site->address}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('address'))
								<div class="error">{{ $errors->first('address') }}</div>
								@endif
							</div>
						</div>
						
					
					</div>
					<div class="box-footer">
						<input type="hidden" name="edited_categories" id="edited_categories" value="1">
						<div class="pull-right">
							<button type="submit" id="categories-submit" class="btn btn-success">
								<strong>Save</strong>
							</button>
							<a href="{{url(route('architect-site-list'))}}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="architect_name_id" value="{!! $architect_site->uuid !!}" />
			</form>
		</div>
	</div>
</section>
@endsection
@section('after-scripts-end')
<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>


@include('master.architect_site.script')
@stop