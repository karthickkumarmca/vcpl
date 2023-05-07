@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">Architect Info <small>Management</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('architect-site-list'))}}">Architect Info management</a></li>
		<li>Create Architect Info</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/architect-site/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Create Architect Info</h3>
					</div>

					<div class="box-body">
						
						<div class="col-md-12">
							<div class="form-group">
								<label>Architect Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter architect name" name="architect_name" value="{{old('architect_name')}}" data-rule="admin" minlength="1" maxlength="100"/>
								<span class="validation_error"></span>
								@if($errors->has('architect_name'))
								<div class="error">{{ $errors->first('architect_name') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Site Name <span class="text-danger"> *</span></label>
								<select name="site_id" class="form-control pos_validate" id="site_id">
									<option value="">Select Site Name</option>
									@isset($siteinfo)
										@foreach($siteinfo as $siteinf)
										<option value="{{$siteinf['id']}}">{{$siteinf['site_name']}}</option>
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
							<div class="form-group">
								<label>Cader <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Cader" name="cader" value="{{old('cader')}}" data-rule="admin" minlength="1" maxlength="100"/>
								<span class="validation_error"></span>
								@if($errors->has('cader'))
								<div class="error">{{ $errors->first('cader') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Mobile Number <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate number_restrict" autocomplete="off" placeholder="Enter Moble Number" name="mobile_number" value="{{old('mobile_number')}}" data-rule="admin" minlength="1" maxlength="10"/>
								<span class="validation_error"></span>
								@if($errors->has('mobile_number'))
								<div class="error">{{ $errors->first('mobile_number') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Email Id <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate email" autocomplete="off" placeholder="Enter Email id" name="email_id" value="{{old('email_id')}}" data-rule="admin" minlength="1" maxlength="100"/>
								<span class="validation_error"></span>
								@if($errors->has('email_id'))
								<div class="error">{{ $errors->first('email_id') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Address <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Address" name="address" value="{{old('address')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('address'))
								<div class="error">{{ $errors->first('address') }}</div>
								@endif
							</div>
						</div>

						
					</div>
					<div class="box-footer">
						<div class="pull-right">
							<button type="submit" id="categories-submit" class="btn btn-success">
								Save
							</button>
							<a href="{{url(route('architect-site-list'))}}" class="btn btn-default">
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
<!--<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>!-->

    @include('master.architect_site.script')
    @stop
