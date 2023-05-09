@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">Site Info<small>Management</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('product-details-list'))}}">Site Info Details</a></li>
		<li>Create Site Info</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/site-info/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Create Site Info</h3>
					</div>

					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Site Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Site Name" name="site_name" value="{{old('site_name')}}" data-rule="admin" minlength="1" maxlength="100"/>
								<span class="validation_error"></span>
								@if($errors->has('site_name'))
								<div class="error">{{ $errors->first('site_name') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Site Location <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Site Location" name="site_location" value="{{old('site_location')}}" data-rule="admin" minlength="1" maxlength="100"/>
								<span class="validation_error"></span>
								@if($errors->has('site_location'))
								<div class="error">{{ $errors->first('site_location') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Select Site Engineer <span class="text-danger"> *</span></label>
								<select name="site_engineer_id" class="form-control pos_validate" id="site_engineer_id" data-rule="admin">
									<option value="">Select site engineer</option>
									@isset($Siteengineer)
										@foreach($Siteengineer as $s)
										<option value="{{$s['id']}}">{{$s['name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('site_engineer_id'))
								<div class="error">{{ $errors->first('site_engineer_id') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Select Sub Contractor <span class="text-danger"> *</span></label>
								<select name="sub_contractor_id" class="form-control pos_validate" id="sub_contractor_id" data-rule="admin">
									<option value="">Select Sub Contractor</option> 
									@isset($Subcontractor)
										@foreach($Subcontractor as $s)
										<option value="{{$s['id']}}">{{$s['name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('sub_contractor_id'))
								<div class="error">{{ $errors->first('sub_contractor_id') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Select Store Keeper </label>
								<select name="store_keeper_id" class="form-control pos_validate" id="store_keeper_id" data-rule="admin">
									<option value="">Select store keeper</option>
									@isset($Storekeeper)
										@foreach($Storekeeper as $s)
										<option value="{{$s['id']}}">{{$s['name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('store_keeper_id'))
								<div class="error">{{ $errors->first('store_keeper_id') }}</div>
								@endif
							</div>
						</div>
						
					</div>
					<div class="box-footer">
						<div class="pull-right">
							<button type="submit" id="site-info-submit" class="btn btn-success">
								Save
							</button>
							<a href="{{url(route('site-info-list'))}}" class="btn btn-default">
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
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script> !-->

    @include('master.site_info.script')
    @stop
