@extends('layouts.main')
@section('content')
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		client Info <small>management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('client-info-list'))}}">client Info management</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">client Info Details</h3>
				</div>
				<div class="box-body">
					@if($client_site->status)
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
									<label>Vehicle Name</label>
								</th>
								<td>{!! $client_site->client_name !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Site Name</label>
								</th>
								<td>@isset($siteinfo)
										@foreach($siteinfo as $siteinf)
											@isset($client_site->site_id)
													@if($client_site->site_id == $siteinf['id'])
													{{$siteinf['site_name']}}
												@endif
											@endisset
										@endforeach
									@endisset</td>
							</tr>

							<tr>
								<th class="grey_header">
									<label>Cader</label>
								</th>
								<td>{!! $client_site->cader !!}</td>
							</tr>

							<tr>
								<th class="grey_header">
									<label>Mobile Number</label>
								</th>
								<td>{!! $client_site->mobile_number !!}</td>
							</tr>

							<tr>
								<th class="grey_header">
									<label>Email</label>
								</th>
								<td>{!! $client_site->email_id !!}</td>
							</tr>

							<tr>
								<th class="grey_header">
									<label>Address</label>
								</th>
								<td>{!! $client_site->address !!}</td>
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
								<td>{!! $client_site->created_at !!}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="box-footer">
					<div class="pull-right">
						<a href="{{url(route('client-info-list'))}}" class="btn btn-default">
							<strong>Back</strong>
						</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>
@endsection