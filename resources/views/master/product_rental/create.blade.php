@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">Product Rental<small>Management</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('product-rental-list'))}}">Product Rental</a></li>
		<li>Create Product</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/product-rental/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Create Product</h3>
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
								<label>Select Product <span class="text-danger"> *</span></label>
								<select name="product_details_id" class="form-control pos_validate" id="product_details_id">
									<option value="">Select Product</option>
								</select>
								<span class="validation_error"></span>
								@if($errors->has('product_details_id'))
								<div class="error">{{ $errors->first('product_details_id') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Rent / Unit <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate number_restrict" autocomplete="off" placeholder="Enter Rent/Unit" name="rent_unit" value="{{old('rent_unit')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('rent_unit'))
								<div class="error">{{ $errors->first('rent_unit') }}</div>
								@endif
							</div>
						</div>
						
					</div>
					<div class="box-footer">
						<div class="pull-right">
							<button type="submit" id="categories-submit" class="btn btn-success">
								Save
							</button>
							<a href="{{url(route('product-rental-list'))}}" class="btn btn-default">
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

    @include('master.product_rental.script')
    @stop
