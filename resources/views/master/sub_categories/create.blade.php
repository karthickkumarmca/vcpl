@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">Sub Categories <small>Management</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('categories-list'))}}">Sub Categories management</a></li>
		<li>Create Sub Categories</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/sub-categories/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Create Sub Categories</h3>
					</div>

					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Select category <span class="text-danger"> *</span></label>
								<select name="category_id" class="form-control pos_validate" id="category_id">
									<option value="">Select category</option>
									@isset($categories)
										@foreach($categories as $category)
										<option value="{{$category['id']}}">{{$category['category_name']}}</option>
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
								<label>Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Name" name="sub_category_name" value="{{old('sub_category_name')}}" data-rule="admin" minlength="3" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('sub_category_name'))
								<div class="error">{{ $errors->first('sub_category_name') }}</div>
								@endif
							</div>
						</div>
						
					</div>
					<div class="box-footer">
						<div class="pull-right">
							<button type="submit" id="categories-submit" class="btn btn-success">
								Save
							</button>
							<a href="{{url(route('sub-categories-list'))}}" class="btn btn-default">
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

    @include('master.sub_categories.script')
    @stop
