@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Tools and Plants materials <small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('toolsplants-materials-list'))}}">Tools and Plants materials management</a></li>
		<li>Edit Tools and Plants materials</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/toolsplants-materials/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Tools and Plants materials</h3>
					</div>
					<div class="box-body">
					<div class="col-md-12">
							<div class="form-group">
								<label>Select Material Name<span class="text-danger"> *</span></label>
								<select name="category_id" class="form-control pos_validate" id="category_id">
									<option value="">Select Material Name</option>
									@isset($categories)
										@foreach($categories as $category)
												@isset($centering_materials->property_material_id)
													@if($centering_materials->property_material_id == $category['id'])
														<option value="{{$category['id']}}" selected>{{$category['product_name']}}</option>
													@else
														<option value="{{$category['id']}}">{{$category['product_name']}}</option>
													@endif
												@else
													<option value="{{$category['id']}}">{{$category['product_name']}}</option>
												@endisset
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('category_id'))
								<div class="error">{{ $errors->first('category_id') }}</div>
								@endif
							</div>
						</div>
					
						<div class="col-md-12">
							<div class="form-group">
								<label>Rate Units <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" placeholder="Enter Name" name="rate_unit" value="{{old('rate_unit') ? old('rate_unit') : $centering_materials->rate_unit}}" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('rate_unit'))
								<div class="error">{{ $errors->first('rate_unit') }}</div>
								@endif
							</div>
						</div>

<div class="col-md-12">
							<div class="form-group">
								<label>Select Units <span class="text-danger"> *</span></label>
								<select name="units_id" class="form-control pos_validate" id="units_id">
									<option value="">Select Units</option>
									@isset($units)
										@foreach($units as $owner)
											@if(old('ownership_id') != "")
												@if(old('units_id') == $owner['id'])
													<option value="{{$owner['id']}}" selected>{{$owner['unit_name']}}</option>
												@else
													<option value="{{$owner['id']}}">{{$owner['unit_name']}}</option>
												@endif
											@else
												@isset($centering_materials->units_id)
													@if($centering_materials->units_id == $owner['id'])
														<option value="{{$owner['id']}}" selected>{{$owner['unit_name']}}</option>
													@else
														<option value="{{$owner['id']}}">{{$owner['unit_name']}}</option>
													@endif
												@else
													<option value="{{$owner['id']}}">{{$owner['unit_name']}}</option>
												@endisset
											@endif
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('units_id'))
								<div class="error">{{ $errors->first('units_id') }}</div>
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
							<a href="{{url(route('toolsplants-materials-list'))}}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="centering_materials_id" value="{!! $centering_materials->uuid !!}" />
			</form>
		</div>
	</div>
</section>
@endsection
@section('after-scripts-end')
<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>


@include('master.toolsplants_materials.script')
@stop