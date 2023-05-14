@extends('layouts.main')
@section('content')
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Staff Details <small>management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('product-details-list'))}}">Staff Details</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Staff Details</h3>
				</div>
				<div class="box-body">
					@if($Staffdetails->status)
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
								<th class="grey_header"><label>Name</label></th>
								<td>{!! $Staffdetails->name !!}</td>
							</tr>
							<tr>
								<th class="grey_header"><label>Employee Code</label></th>
								<td>{!! $Staffdetails->user_name !!}</td>
							</tr>
							<tr>
								<th class="grey_header"><label>Email</label></th>
								<td>{!! $Staffdetails->email !!}</td>
							</tr>
							<tr>
								<th class="grey_header"><label>Phone number</label></th>
								<td>{!! $Staffdetails->phone_number !!}</td>
							</tr>
							<tr>
								<th class="grey_header"><label>Group name</label></th>
								<td>{!! $Staffdetails->group_name !!}</td>
							</tr>
							<tr>
								<th class="grey_header"><label>Role name</label></th>
								<td>{!! $Staffdetails->role_name !!}</td>
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
								<td>{!! $Staffdetails->date_created !!}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="box-footer">
					<div class="pull-right">
						<a href="{{url(route('staff-details-list'))}}" class="btn btn-default">
							<strong>Back</strong>
						</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>
@endsection