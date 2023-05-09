@extends('layouts.main')
@section('content')
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Property name <small>management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('property-name-list'))}}">Property name management</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Property name Details</h3>
				</div>
				<div class="box-body">
					@if($property_name->status)
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
											@isset($property_name->property_category_id)
												@if($property_name->property_category_id == $category['id'])
													{{$category['category_name']}}
												@endif
											@endisset
										@endforeach
									@endisset</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Ownership Name {{$property_name->ownership_id}}</label>
								</th>
								<td>@isset($ownership)
										@foreach($ownership as $owner)
											@isset($property_name->ownership_id)
												@if($property_name->ownership_id == $owner['id'])
													{{$owner['ownership_name']}}
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
								<td>{!! $property_name->created_at !!}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="box-footer">
					<div class="pull-right">
						<a href="{{url(route('property-name-list'))}}" class="btn btn-default">
							<strong>Back</strong>
						</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>
@endsection