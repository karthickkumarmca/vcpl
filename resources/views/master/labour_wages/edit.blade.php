@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Labour wages <small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('labour-wages-list'))}}">Labour wages management</a></li>
		<li>Edit Labour wages</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/labour-wages/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Labour wages</h3>
					</div>
					<div class="box-body">
					
						<div class="col-md-12">
							<div class="form-group">
								<label>Site Name <span class="text-danger"> *</span></label>
								<select name="site_id" class="form-control pos_validate" id="site_id">
									<option value="">Select Site Name</option>
									@isset($siteinfo)
										@foreach($siteinfo as $siteinf)
											@if(old('site_id') != "")
												@if(old('site_id') == $siteinf['id'])
													<option value="{{$siteinf['id']}}" selected>{{$siteinf['site_name']}}</option>
												@else
													<option value="{{$siteinf['id']}}">{{$siteinf['site_name']}}</option>
												@endif
											@else
												@isset($labour_wages->site_id)
													@if($labour_wages->site_id == $siteinf['id'])
														<option value="{{$siteinf['id']}}" selected>{{$siteinf['site_name']}}</option>
													@else
														<option value="{{$siteinf['id']}}">{{$siteinf['site_name']}}</option>
													@endif
												@else
													<option value="{{$siteinf['id']}}">{{$siteinf['site_name']}}</option>
												@endisset
											@endif
										@endforeach
									@endisset
									
								</select>
								<span class="validation_error"></span>
								@if($errors->has('is_company'))
								<div class="error">{{ $errors->first('is_company') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Sub contractor Name <span class="text-danger"> *</span></label>
								<select name="sub_contractor_id" class="form-control pos_validate" id="sub_contractor_id">
									<option value="">Sub contractor Name</option>
									@isset($staffdetails)
										@foreach($staffdetails as $staffdetail)
											@if(old('sub_contractor_id') != "")
												@if(old('sub_contractor_id') == $staffdetail['id'])
													<option value="{{$staffdetail['id']}}" selected>{{$staffdetail['name']}}</option>
												@else
													<option value="{{$staffdetail['id']}}">{{$staffdetail['name']}}</option>
												@endif
											@else
												@isset($labour_wages->sub_contractor_id)
													@if($labour_wages->sub_contractor_id == $staffdetail['id'])
														<option value="{{$staffdetail['id']}}" selected>{{$staffdetail['category_name']}}</option>
													@else
														<option value="{{$staffdetail['id']}}">{{$staffdetail['name']}}</option>
													@endif
												@else
													<option value="{{$staffdetail['id']}}">{{$staffdetail['name']}}</option>
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


						<div class="col-md-12">
							<div class="form-group">
								<label>Select Category <span class="text-danger"> *</span></label>
								<select name="labour_category_id" class="form-control pos_validate" id="labour_category_id">
									<option value="">Select Category</option>
									@isset($categories)
										@foreach($categories as $category)
											@if(old('labour_category_id') != "")
												@if(old('labour_category_id') == $category['id'])
													<option value="{{$category['id']}}" selected>{{$category['category_name']}}</option>
												@else
													<option value="{{$category['id']}}">{{$category['category_name']}}</option>
												@endif
											@else
												@isset($labour_wages->labour_category_id)
													@if($labour_wages->labour_category_id == $category['id'])
														<option value="{{$category['id']}}" selected>{{$category['category_name']}}</option>
													@else
														<option value="{{$category['id']}}">{{$category['category_name']}}</option>
													@endif
												@else
													<option value="{{$category['id']}}">{{$category['category_name']}}</option>
												@endisset
											@endif
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('labour_category_id'))
								<div class="error">{{ $errors->first('labour_category_id') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label> Labour Wages of a particular subcontractor for a new site <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Labour Wages of a particular subcontractor for a new site " name="rate" value="{{old('rate') ? old('rate') : $labour_wages->rate}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('rate'))
								<div class="error">{{ $errors->first('rate') }}</div>
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
							<a href="{{url(route('labour-wages-list'))}}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="client_name_id" value="{!! $labour_wages->uuid !!}" />
			</form>
		</div>
	</div>
</section>
@endsection
@section('after-scripts-end')
<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>


@include('master.labour_wages.script')
@stop