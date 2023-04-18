@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Customer <small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('customer-list'))}}">Customer management</a></li>
		<li>Edit Customer</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('customer/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Customer</h3>
					</div>

					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" placeholder="Enter Name" name="name" value="{{old('name') ? old('name') : $customer->name}}" data-rule="admin" onkeypress="return ((event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || event.charCode == 8 || event.charCode == 32);" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('name'))
								<div class="error">{{ $errors->first('name') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Email <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate allow_characters" placeholder="Enter Email" name="email" value="{{old('email') ? old('email') : $customer->email}}" data-rule="admin" />
								<input type="hidden" name="old_email" id="old_email" value="{{ $customer->email }}">
								<span class="validation_error"></span>
								@if($errors->has('email'))
								<div class="error">{{ $errors->first('email') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Phone Number <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate number_restrict" placeholder="Enter Phone Number" name="phone" value="{{old('phone') ? old('phone') : $customer->phonenumber}}" data-rule="admin" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57));" maxlength="10"/>
								<input type="hidden" name="old_phone" id="old_phone" value="{{ $customer->phonenumber }}">
								<span class="validation_error"></span>
								@if($errors->has('phone'))
								<div class="error">{{ $errors->first('phone') }}</div>
								@endif
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="form-group has-feedback @if ($errors->has('g-recaptcha-response')) has-error @endif col-md-12" id="recaptcha-boxs" style="max-width:100%;height: 0px !important;">
								<div id="capcha-elements"></div>
								<span class="validation_error"></span>
								@if($errors->has('g-recaptcha-response'))
								<div class="error">{{ $errors->first('g-recaptcha-response') }}</div>
								@endif
							</div>
						</div>
					</div>
					<div class="box-footer">
						<input type="hidden" name="edited_customer" id="edited_customer" value="1">
						<div class="pull-right">
							<button type="submit" id="customer-submit" class="btn btn-success">
								<strong>Save</strong>
							</button>
							<a href="{!! url(route('customer-list')) !!}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="customer_id" value="{!! $customer->uuid !!}" />
			</form>
		</div>
	</div>
</section>
@endsection
@section('after-scripts-end')
<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script type="text/javascript">
	var siteCaptchaKey = "{{config('general_settings.site_captcha_key')}}";
	var onloadCallback = function() {
		grecaptcha.render('capcha-elements', {
			'sitekey': siteCaptchaKey
		});
	};
</script>

@include('customers.script')
@stop