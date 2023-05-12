@extends('layouts.main')
@section('content')
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		ProductDetails <small>management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('product-rental-list'))}}">Product Rental</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Product Rental</h3>
				</div>
				<div class="box-body">
					@if($product_rental->status)
					@php
					$status = "Active";
					$status_bg="bg-green";
					@endphp
					@else
					@php
					$status = "In-Active";
					$status_bg="bg-red";
					@endphp
					@endif
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th class="grey_header">
									<label>Category Name</label>
								</th>
								<td>@isset($categories)
										@foreach($categories as $category)
											@isset($product_rental->category_id)
												@if($product_rental->category_id == $category['id'])
													{{$category['category_name']}}
												@endif
											@endisset
										@endforeach
									@endisset</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Product Name</label>
								</th>
								<td>@isset($Productdetails)
										@foreach($Productdetails as $category)
											@isset($product_rental->product_details_id)
												@if($product_rental->product_details_id == $category['id'])
													{{$category['product_name']}}
												@endif
											@endisset
										@endforeach
									@endisset</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Product Rent</label>
								</th>
								<td>{!! $product_rental->rent_unit !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Product Unit</label>
								</th>
								<td>@isset($units)
										@foreach($units as $unit)
											@isset($product_rental->unit_id)
												@if($product_rental->unit_id == $unit['id'])
													{{$unit['unit_name']}}
												@endif
											@endisset
										@endforeach
									@endisset</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Status</label>
								</th>
								<td><span class="badge {!! $status_bg !!}"> {!! $status !!}</span></td>
							</tr>
							
							<tr>
								<th class="grey_header">
									<label>Created At</label>
								</th>
								<td>{!! $product_rental->created_at !!}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="box-footer">
					<div class="pull-right">
						<a href="{{url(route('product-rental-list'))}}" class="btn btn-default">
							<strong>Back</strong>
						</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>
@endsection