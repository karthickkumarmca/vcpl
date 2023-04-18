@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">Chain <small>Management</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('chain-list'))}}">Chain Management</a></li>
		<li>Create Chain</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('chain/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Create Chain</h3>
					</div>

					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Chain Type <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Chain Type" name="chain" value="{{old('chain')}}" data-rule="admin" minlength="3" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('chain'))
								<div class="error">{{ $errors->first('chain') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Select Hallmark <span class="text-danger"> *</span></label>
								<select name="hallmark_id" class="form-control pos_validate" id="hallmark_id">
									<option value="">Select Hallmark</option>
									@isset($hallmarks)
										@foreach($hallmarks as $hallmark)
										<option value="{{$hallmark['id']}}">{{$hallmark['rate']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('hallmark_id'))
								<div class="error">{{ $errors->first('hallmark_id') }}</div>
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
						<div class="pull-right">
							<button type="submit" id="chain-submit" class="btn btn-success">
								Save
							</button>
							<a href="{{url(route('chain-list'))}}" class="btn btn-default">
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
    @include('chains.script')
    @stop
