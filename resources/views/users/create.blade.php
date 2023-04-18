@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">User <small>Management</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('user-list'))}}">User management</a></li>
		<li>Create User</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('user/store')}}">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Create user</h3>
					</div>

					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Name" name="name" value="{{old('name')}}" data-rule="admin" onkeypress="return ((event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || event.charCode == 8 || event.charCode == 32);" minlength="3" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('name'))
								<div class="error">{{ $errors->first('name') }}</div>
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
								<label>Phone Number <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate number_restrict" placeholder="Enter phone number" name="phone" value="{{old('phone')}}" data-rule="admin" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57));" minlength="7" maxlength="10"/>
								<span class="validation_error"></span>
								@if($errors->has('phone'))
								<div class="error">{{ $errors->first('phone') }}</div>
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
						<!-- <div class="col-md-12">
							<div class="form-group">
								<label>User Image<span class="text-danger"> </span></label>
								<input type="file" class="form-control pos_validate" name="user_image" id="user_image" accept="image/jpg,image/jpeg,image/png,image/JPG,image/JPEG,image/PNG" data-rule="admin">
								<input type="hidden" name="selected_image" id="selected_image" value="">

								<span class="validation_error" id="user_image_error"></span>
								@if($errors->has('user_image'))
								<div class="error">{{ $errors->first('user_image') }}</div>
								@endif

								@if(session()->has('user_image_error'))
								<div class="error">{{ session('user_image_error') }}</div>
								@endif
							</div>
						</div> -->
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
						<div class="pull-right">
							<button type="submit" id="user-submit" class="btn btn-success">
								Save
							</button>
							<a href="{{url(route('user-list'))}}" class="btn btn-default">
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
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script type="text/javascript">
	var siteCaptchaKey = "{{config('general_settings.site_captcha_key')}}";
	var onloadCallback = function() {
		grecaptcha.render('capcha-elements', {
			'sitekey': siteCaptchaKey
		});
	};
</script>
<script>
	function scaleCaptcha() {
            // Width of the reCAPTCHA element, in pixels
            var reCaptchaWidth = 304;
            var reCaptchaheight = 78;

            // Get the containing element's width
            var containerWidth = $('#recaptcha-box').width();

            if (reCaptchaWidth != containerWidth) {
                // Calculate the scale
                var captchaScale = containerWidth / reCaptchaWidth;
                // Apply the transformation
                $('#capcha-element').css({
                	'transform': 'scale(0)'
                });
                $('#capcha-element').css({
                	'-webkit-transform': 'scale(' + captchaScale + ')'
                });
                $('#capcha-element').css({
                	'transform-origin': '0 0'
                });
                $('#capcha-element').css({
                	'-webkit-transform-origin': '0 0'
                });

                $('#recaptcha-box').height(reCaptchaheight * captchaScale);
            }
        }
        $(window).resize(function() {
        	scaleCaptcha();
        });
        scaleCaptcha();
    </script>
    @include('admin.script')
    @stop
