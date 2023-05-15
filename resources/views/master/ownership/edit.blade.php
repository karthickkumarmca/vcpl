@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Labour Ownership <small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('ownership-list'))}}">Ownership management</a></li>
		<li>Edit Ownership</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/ownership/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Ownership</h3>
					</div>

					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Select Owner Name <span class="text-danger"> *</span></label>
								<select name="staff_id" class="form-control pos_validate" id="staff_id">
									<option value="">Select Owner Name</option>
									@isset($Staffdetails)
										@foreach($Staffdetails as $category)
											@if(old('staff_id') != "")
												@if(old('staff_id') == $category['id'])
													<option value="{{$category['id']}}" selected>{{$category['name']}}</option>
												@else
													<option value="{{$category['id']}}">{{$category['name']}}</option>
												@endif
											@else
												@isset($ownership->staff_id)
													@if($ownership->staff_id == $category['id'])
														<option value="{{$category['id']}}" selected>{{$category['name']}}</option>
													@else
														<option value="{{$category['id']}}">{{$category['name']}}</option>
													@endif
												@else
													<option value="{{$category['id']}}">{{$category['name']}}</option>
												@endisset
											@endif
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('staff_id'))
								<div class="error">{{ $errors->first('staff_id') }}</div>
								@endif
							</div>
						</div>


						<div class="col-md-12">
							<div class="form-group">
								<label>Position <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter position" name="position" value="{{old('position') ? old('position') : $ownership->position}}" data-rule="admin" minlength="3" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('position'))
								<div class="error">{{ $errors->first('position') }}</div>
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
							<a href="{!! url(route('ownership-list')) !!}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="ownership_id" value="{!! $ownership->uuid !!}" />
			</form>
		</div>
	</div>
</section>
@endsection
@section('after-scripts-end')
<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>


@include('master.ownership.script')
@stop