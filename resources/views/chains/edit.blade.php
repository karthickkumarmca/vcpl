@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Chain <small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('chain-list'))}}">Chain Management</a></li>
		<li>Edit Chain</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('chain/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Chain</h3>
					</div>

					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Chain Type <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" placeholder="Enter Chain Type" name="chain" value="{{old('chain') ? old('chain') : $chain->chain}}" data-rule="admin" maxlength="128"/>
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
											@if(old('chain') != "")
												@if(old('chain') == $hallmark['id'])
													<option value="{{$hallmark['id']}}" selected>{{$hallmark['rate']}}</option>
												@else
													<option value="{{$hallmark['id']}}">{{$hallmark['rate']}}</option>
												@endif
											@else
												@isset($chain->hallmark_id)
													@if($chain->hallmark_id == $hallmark['id'])
														<option value="{{$hallmark['id']}}" selected>{{$hallmark['rate']}}</option>
													@else
														<option value="{{$hallmark['id']}}">{{$hallmark['rate']}}</option>
													@endif
												@else
													<option value="{{$hallmark['id']}}">{{$hallmark['rate']}}</option>
												@endisset
											@endif
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
						<input type="hidden" name="edited_chain" id="edited_chain" value="1">
						<div class="pull-right">
							<button type="submit" id="chain-submit" class="btn btn-success">
								<strong>Save</strong>
							</button>
							<a href="{!! url(route('chain-list')) !!}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="chain_id" value="{!! $chain->uuid !!}" />
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

@include('chains.script')
@stop