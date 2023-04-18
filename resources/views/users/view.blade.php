@extends('layouts.main')
@section('content')
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		User <small>management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('user-list'))}}">User management</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">User Details</h3>
				</div>
				<div class="box-body">
					@if($user->status)
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
									<label>Name</label>
								</th>
								<td>{!! $user->name !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Email</label>
								</th>
								<td>{!! $user->email !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Phone Number</label>
								</th>
								<td>{!! $user->phonenumber !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Status</label>
								</th>
								<td><span class="badge {!! $status_bg !!}"> {!! $status !!}</span></td>
							</tr>
							{{--<tr>
								<th class="grey_header">
									<label>Created By</label>
								</th>
								<td>{!! $user->CreatedBy['name'] !!}</td>
							</tr>--}}
							<tr>
								<th class="grey_header">
									<label>Created At</label>
								</th>
								<td>{!! $user->created_at !!}</td>
							</tr>
							<!-- <tr>
								<th class="grey_header">
									<label>User Image</label>
								</th>
								<td>@isset($user->user_image)
									@if($user->user_image != "")
									<img src="{{env('AWS_URL').$user->user_image}}" style="width:200px">
									@endif
								@endisset</td>
							</tr> -->

						</table>
					</div>
				</div>
				<div class="box-footer">
					<div class="pull-right">
						<a href="{!! url('user-list') !!}" class="btn btn-default">
							<strong>Back</strong>
						</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>
@endsection