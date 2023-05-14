@extends('layouts.main')
@section('content')
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Site Info <small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('site-info-list'))}}">Site Info Details</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Site Info Details</h3>
				</div>
				<div class="box-body">
					@if($data->status)
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
									<label>Site Name</label>
								</th>
								<td>{!! $data->site_name !!}</td>
							</tr>

							<tr>
								<th class="grey_header">
									<label>Site Location</label>
								</th>
								<td>{!! $data->site_location !!}</td>
							</tr>

							<tr>
								<th class="grey_header">
									<label>Site Incharge Name</label>
								</th>
								<td>{!! $data->site_engineer_name !!}</td>
							</tr>

							<tr>
								<th class="grey_header">
									<label>Sub Contractor Name</label>
								</th>
								<td>{!! $data->sub_contractor_name !!}</td>
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
								<td>{!! $data->date_created !!}</td>
							</tr>

						</table>
					</div>
				</div>
				<div class="box-footer">
					<div class="pull-right">
						<a href="{{url(route('site-info-list'))}}" class="btn btn-default">
							<strong>Back</strong>
						</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>
@endsection