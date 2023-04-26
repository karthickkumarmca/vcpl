@extends('layouts.main')
@section('content')
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Lorry materials <small>management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('toolsplants-materials-list'))}}">Lorry materials management</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Lorry materials Details</h3>
				</div>
				<div class="box-body">
					@if($centering_materials->status)
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
											@isset($centering_materials->property_material_id)
													@if($centering_materials->property_material_id == $category['id'])
													{{$category['product_name']}}
												@endif
											@endisset
										@endforeach
									@endisset</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Unit Name</label>
								</th>
								<td>@isset($units)
										@foreach($units as $category)
											@isset($centering_materials->units_id)
													@if($centering_materials->units_id == $category['id'])
													{{$category['unit_name']}}
												@endif
											@endisset
										@endforeach
									@endisset</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Rate Unit</label>
								</th>
								<td>{!! $centering_materials->rate_unit !!}</td>
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
								<td>{!! $centering_materials->created_at !!}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="box-footer">
					<div class="pull-right">
						<a href="{{url(route('toolsplants-materials-list'))}}" class="btn btn-default">
							<strong>Back</strong>
						</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>
@endsection