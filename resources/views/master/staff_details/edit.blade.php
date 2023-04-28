@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Product Details<small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('product-details-list'))}}">Product Details</a></li>
		<li>Edit Product</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/product-details/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Product</h3>
					</div>
					<div class="box-body">
					<div class="col-md-12">
							<div class="form-group">
								<label>Select Category <span class="text-danger"> *</span></label>
								<select name="category_id" class="form-control pos_validate" id="category_id">
									<option value="">Select Category</option>
									@isset($categories)
										@foreach($categories as $category)
											@if(old('category_id') != "")
												@if(old('category_id') == $category['id'])
													<option value="{{$category['id']}}" selected>{{$category['category_name']}}</option>
												@else
													<option value="{{$category['id']}}">{{$category['category_name']}}</option>
												@endif
											@else
												@isset($product_details->category_id)
													@if($product_details->category_id == $category['id'])
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
								@if($errors->has('category_id'))
								<div class="error">{{ $errors->first('category_id') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Select Sub category <span class="text-danger"> *</span></label>
								<select name="subcategory_id" class="form-control pos_validate" id="subcategory_id">
									@isset($sub_categories)
										@foreach($sub_categories as $sub)
											@if ($product_details->subcategory_id != "")
												@if($product_details->subcategory_id == $sub['id'])
													<option value="{{$sub['id']}}" selected>{{$sub['sub_category_name']}}</option>
												@endif
											@endif
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('subcategory_id'))
								<div class="error">{{ $errors->first('subcategory_id') }}</div>
								@endif
							</div>
						</div>
					
						<div class="col-md-12">
							<div class="form-group">
								<label>Product Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" placeholder="Enter Name" name="product_name" value="{{old('product_name') ? old('product_name') : $product_details->product_name}}" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('product_name'))
								<div class="error">{{ $errors->first('product_name') }}</div>
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
							<a href="{{url(route('product-details-list'))}}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="product_details_id" value="{!! $product_details->uuid !!}" />
			</form>
		</div>
	</div>
</section>
@endsection
@section('after-scripts-end')
<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>


@include('master.product_details.script')
@stop