@extends('layouts.main')
@section('content')
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Labour wages <small>management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('labour-wages-list'))}}">Labour wages management</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Labour wages Details</h3>
				</div>
				<div class="box-body">
					@if($labour_wages->status)
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
								<td>@isset($siteinfo)
										@foreach($siteinfo as $siteinf)
											@isset($labour_wages->site_id)
													@if($labour_wages->site_id == $siteinf['id'])
													{{$siteinf['site_name']}}
												@endif
											@endisset
										@endforeach
									@endisset</td>
							</tr>

							<tr>
								<th class="grey_header">
									<label>Sub contractor Name</label>
								</th>
								<td>@isset($staffdetails)
										@foreach($staffdetails as $staffdetail)
											@isset($labour_wages->sub_contractor_id)
												@if($labour_wages->sub_contractor_id == $staffdetail['id'])
													{{$staffdetail['name']}}
												@endif
											@endisset
										@endforeach
									@endisset</td>
							</tr>

							<tr>
								<th class="grey_header">
									<label>Category Name</label>
								</th>
								<td>@isset($categories)
										@foreach($categories as $category)
											@isset($labour_wages->labour_category_id)
												@if($labour_wages->labour_category_id == $category['id'])
													{{$category['category_name']}}
												@endif
											@endisset
										@endforeach
									@endisset</td>
							</tr>

							<tr>
								<th class="grey_header">
									<label>Labour Wages of a particular subcontractor for a new site</label>
								</th>
								<td>{!! $labour_wages->rate !!}</td>
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
								<td>{!! $labour_wages->created_at !!}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="box-footer">
					<div class="pull-right">
						<a href="{{url(route('labour-wages-list'))}}" class="btn btn-default">
							<strong>Back</strong>
						</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>
@endsection