@extends('layouts.main')
@section('content')
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Roles <small>management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('roles-list'))}}">Roles management</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Roles Details</h3>
				</div>
				<div class="box-body">
					@if($roles->status)
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
									<label>Roles Name</label>
								</th>
								<td>{!! $roles->role_name !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Master</label>
								</th>
								<td>
									@if(in_array('roles_management',$roles->master)) Roles <br> @endif
							    @if(in_array('units_management',$roles->master)) Units <br> @endif
							    @if(in_array('staffgroups_management',$roles->master)) Staff Group <br> @endif
							    @if(in_array('staff_details_management',$roles->master)) Staff Details <br> @endif
							    @if(in_array('site_info_management',$roles->master)) Site Info <br> @endif
							    @if(in_array('client_site_management',$roles->master)) Client Info <br> @endif
							    @if(in_array('architect_site_management',$roles->master)) Architect Info <br> @endif
							    @if(in_array('categories_management',$roles->master)) Product Categories <br> @endif
							    @if(in_array('sub_categories_management',$roles->master)) Sub Product Categories <br> @endif
							    @if(in_array('product_details_management',$roles->master)) Product Details <br> @endif
							    @if(in_array('labour_categories_management',$roles->master)) Labour Categories <br> @endif
							    @if(in_array('labour_wages_management',$roles->master)) Labour Wages <br> @endif


							    @if(in_array('centering_materials_management',$roles->master)) centering Materials <br> @endif
							    @if(in_array('lorry_materials_management',$roles->master)) Lorry Materials <br> @endif
							    @if(in_array('shop_materials_management',$roles->master)) Shop Materials <br> @endif
							    @if(in_array('toolsplants_materials_management',$roles->master)) Toold and Plant Materials <br> @endif
							    @if(in_array('vehicle_materials_management',$roles->master)) Vehicle Materials <br> @endif

							    @if(in_array('ownership_management',$roles->master)) Ownership <br> @endif
							    @if(in_array('property_name_management',$roles->master)) Property Name <br> @endif
							    @if(in_array('property_categories_management',$roles->master)) property Categories <br> @endif
								</td>
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
								<td>{!! $roles->created_at !!}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="box-footer">
					<div class="pull-right">
						<a href="{!! url('roles-list') !!}" class="btn btn-default">
							<strong>Back</strong>
						</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>
@endsection

