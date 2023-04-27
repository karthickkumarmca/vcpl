@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Vehicle materials <small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('vehicle-materials-list'))}}">Vehicle materials management</a></li>
		<li>Edit Vehicle materials</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/vehicle-materials/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Vehicle materials</h3>
					</div>
					<div class="box-body">
					
					
						<div class="col-md-12">
							<div class="form-group">
								<label>Vehicle Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" placeholder="Enter Name" name="vehicle_name" value="{{old('vehicle_name') ? old('vehicle_name') : $centering_materials->vehicle_name}}" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('vehicle_name'))
								<div class="error">{{ $errors->first('vehicle_name') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Company <span class="text-danger"> *</span></label>
								<select name="is_company" class="form-control pos_validate" id="is_company">
									<option value="0" @if($centering_materials->is_company==0) selected @endif>No</option>
									<option value="1" @if($centering_materials->is_company==1) selected @endif>Yes</option>
									
								</select>
								<span class="validation_error"></span>
								@if($errors->has('is_company'))
								<div class="error">{{ $errors->first('is_company') }}</div>
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
							<a href="{{url(route('vehicle-materials-list'))}}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="centering_vehicle_id" value="{!! $centering_materials->uuid !!}" />
			</form>
		</div>
	</div>
</section>
@endsection
@section('after-scripts-end')
<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>


@include('master.property_name.script')
@stop