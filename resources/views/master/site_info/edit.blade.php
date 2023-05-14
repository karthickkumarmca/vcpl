@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Site Info<small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('site-info-list'))}}">Site Info Details</a></li>
		<li>Edit Site Info</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/site-info/store')}}">
				@csrf
				<div class="box box-primary">

					<div class="box-header with-border">
						<h3 class="box-title">Edit Site Info</h3>
					</div>

					<div class="box-body">

						<div class="col-md-12">
							<div class="form-group">
								<label>Site Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" placeholder="Enter Site Name" name="site_name" value="{{old('site_name') ? old('site_name') : $site_info_details->site_name}}" minlength="1" maxlength="100"/>
								<span class="validation_error"></span>
								@if($errors->has('site_name'))
								<div class="error">{{ $errors->first('site_name') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Site Location <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Site Name" name="site_location" value="{{old('site_location') ? old('site_location') : $site_info_details->site_location }}" data-rule="admin" minlength="1" maxlength="100"/>
								<span class="validation_error"></span>
								@if($errors->has('site_location'))
								<div class="error">{{ $errors->first('site_location') }}</div>
								@endif
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
								<label>Select Site Incharge <span class="text-danger"> *</span></label>
								<select name="site_engineer_id" class="form-control pos_validate" id="site_engineer_id">
									<option value="">Select Site Incharge</option>
									@isset($site_engineer)
										@foreach($site_engineer as $s)
											@if(old('site_engineer_id') != "")
												@if(old('site_engineer_id') == $s['id'])
													<option value="{{$s['id']}}" selected>{{$s['name']}}</option>
												@else
													<option value="{{$s['id']}}">{{$s['name']}}</option>
												@endif
											@else
												@isset($site_info_details->site_engineer_id)
													@if($site_info_details->site_engineer_id == $s['id'])
														<option value="{{$s['id']}}" selected>{{$s['name']}}</option>
													@else
														<option value="{{$s['id']}}">{{$s['name']}}</option>
													@endif
												@else
													<option value="{{$s['id']}}">{{$s['name']}}</option>
												@endisset
											@endif
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
								<select name="sub_contractor_id" class="form-control pos_validate" id="sub_contractor_id">
									<option value="">Select Sub Contractor </option>

									@isset($sub_contractor)
										@foreach($sub_contractor as $sub)
											@if(old('category_id') != "")
												@if(old('category_id') == $sub['id'])
													<option value="{{$sub['id']}}" selected>{{$sub['name']}}</option>
												@else
													<option value="{{$sub['id']}}">{{$sub['name']}}</option>
												@endif
											@else
												@isset($site_info_details->sub_contractor_id)
													@if($site_info_details->sub_contractor_id == $sub['id'])
														<option value="{{$sub['id']}}" selected>{{$sub['name']}}</option>
													@else
														<option value="{{$sub['id']}}">{{$sub['name']}}</option>
													@endif
												@else
													<option value="{{$sub['id']}}">{{$sub['name']}}</option>
												@endisset
											@endif
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('sub_contractor_id'))
								<div class="error">{{ $errors->first('sub_contractor_id') }}</div>
								@endif
							</div>
						</div>

						{{-- <div class="col-md-12">
							<div class="form-group">
								<label>Select Store keeper</label>
								<select name="store_keeper_id" class="form-control pos_validate" id="store_keeper_id">
									<option value="">Select Store Keeper</option>
									@isset($store_keeper)
										@foreach($store_keeper as $sub)
											@if ($site_info_details->store_keeper_id != "")
												@if($site_info_details->store_keeper_id == $sub['id'])
													<option value="{{$sub['id']}}" selected>{{$sub['name']}}</option>
												
												@endif
											@else
											<option value="{{$sub['id']}}" >{{$sub['name']}}</option>
											@endif
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('store_keeper_id'))
								<div class="error">{{ $errors->first('store_keeper_id') }}</div>
								@endif
							</div>
						</div> --}}
					
					</div>
					<div class="box-footer">
						<input type="hidden" name="edited_site_info" id="edited_site_info" value="1">
						<div class="pull-right">
							<button type="submit" id="site-info-submit" class="btn btn-success">
								<strong>Save</strong>
							</button>
							<a href="{{url(route('site-info-list'))}}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="site_info_id" value="{!! $site_info_details->uuid !!}" />
			</form>
		</div>
	</div>
</section>
@endsection
@section('after-scripts-end')
<!--<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>!-->


@include('master.site_info.script')
@stop