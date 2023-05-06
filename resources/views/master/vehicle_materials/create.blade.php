@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">Vehicle materials <small>Management</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('vehicle-materials-list'))}}">Vehicle materials management</a></li>
		<li>Create Vehicle materials</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/vehicle-materials/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Create Vehicle materials</h3>
					</div>

					<div class="box-body">
						
						<div class="col-md-12">
							<div class="form-group">
								<label>Vehicle Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Vehicle Name" name="vehicle_name" value="{{old('vehicle_name')}}" data-rule="admin" minlength="1" maxlength="128"/>
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
									<option value="0">No</option>
									<option value="1">Yes</option>
									
								</select>
								<span class="validation_error"></span>
								@if($errors->has('is_company'))
								<div class="error">{{ $errors->first('is_company') }}</div>
								@endif
							</div>
						</div>

					
						
					</div>
					<div class="box-footer">
						<div class="pull-right">
							<button type="submit" id="categories-submit" class="btn btn-success">
								Save
							</button>
							<a href="{{url(route('vehicle-materials-list'))}}" class="btn btn-default">
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

    @include('master.vehicle_materials.script')
    @stop
