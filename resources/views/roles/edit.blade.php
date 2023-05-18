@extends('layouts.main')
@section('content')
<link rel="stylesheet" href="{{ URL('css/bootstrap-multiselect.css') }}">
<style>
	.error{color:#f00;    margin-bottom: 0px;}
	.multiselect-container {transform:translate3d(0px, 0px, 0px) !important;}
	 table {
        border-collapse: collapse;
    }
    table, th, td {
       
    }
    th, td {
        padding: 10px;
    }
    table.secondary caption {
        caption-side: bottom;
    }
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Roles <small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('roles-list'))}}">Roles management</a></li>
		<li>Edit Roles</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('roles/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Roles</h3>
					</div>

					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Role Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" placeholder="Enter Role" name="role_name" value="{{old('role_name') ? old('role_name') : $roles->role_name}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('role_name'))
								<div class="error">{{ $errors->first('role_name') }}</div>
								@endif
							</div>
						</div>
						<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Master Modules <span class="text-danger"> *</span></label>
								<!--<select data-placeholder="" id="master" multiple class="form-control " name="master[]"></select>!-->

								<table>
									<tr>
										<td><input type="checkbox" name="master[]" value="roles_management" @if(in_array('roles_management',$roles->master)) checked @endif> Roles</td>
										<td><input type="checkbox" name="master[]" value="units_management"  @if(in_array('units_management',$roles->master)) checked @endif> Units</td>
										<td><input type="checkbox" name="master[]" value="staffgroups_management"  @if(in_array('staffgroups_management',$roles->master)) checked @endif> Staff Group</td>
									</tr>
									<tr>
										<td><input type="checkbox" name="master[]" value="staff_details_management"  @if(in_array('staff_details_management',$roles->master)) checked @endif> Staff Details</td>
										<td><input type="checkbox" name="master[]" value="site_info_management"  @if(in_array('site_info_management',$roles->master)) checked @endif> Site Info</td>
										<td><input type="checkbox" name="master[]" value="client_site_management"  @if(in_array('client_site_management',$roles->master)) checked @endif> Client Info</td>
									</tr>
									<tr>
										<td><input type="checkbox" name="master[]" value="architect_site_management"  @if(in_array('architect_site_management',$roles->master)) checked @endif> Architect Info</td>
										<td><input type="checkbox" name="master[]" value="categories_management"  @if(in_array('categories_management',$roles->master)) checked @endif> Product Categories</td>
										<td><input type="checkbox" name="master[]" value="sub_categories_management"  @if(in_array('sub_categories_management',$roles->master)) checked @endif> Sub Product Categories</td>
									</tr>
									<tr>
										<td><input type="checkbox" name="master[]" value="product_details_management"  @if(in_array('product_details_management',$roles->master)) checked @endif> Product Details</td>
										<td><input type="checkbox" name="master[]" value="labour_categories_management"  @if(in_array('labour_categories_management',$roles->master)) checked @endif> Labour Categories</td>
										<td><input type="checkbox" name="master[]" value="labour_wages_management"  @if(in_array('labour_wages_management',$roles->master)) checked @endif> Labour Wages</td>
									</tr>
									<tr>
										<td><input type="checkbox" name="master[]" value="centering_materials_management"  @if(in_array('centering_materials_management',$roles->master)) checked @endif> centering Materials</td>
										<td><input type="checkbox" name="master[]" value="lorry_materials_management"  @if(in_array('lorry_materials_management',$roles->master)) checked @endif> Lorry Materials</td>
										<td><input type="checkbox" name="master[]" value="shop_materials_management"  @if(in_array('shop_materials_management',$roles->master)) checked @endif> Shop Materials</td>
									</tr>
									<tr>
										<td><input type="checkbox" name="master[]" value="toolsplants_materials_management"  @if(in_array('toolsplants_materials_management',$roles->master)) checked @endif> Toold and Plant Materials</td>
										<td><input type="checkbox" name="master[]" value="vehicle_materials_management"  @if(in_array('vehicle_materials_management',$roles->master)) checked @endif> Vehicle Materials</td>
										<td><input type="checkbox" name="master[]" value="ownership_management"  @if(in_array('ownership_management',$roles->master)) checked @endif> Ownership</td>
									</tr>
									<tr>
										<td><input type="checkbox" name="master[]" value="property_name_management"  @if(in_array('property_name_management',$roles->master)) checked @endif> Property Name</td>
										<td><input type="checkbox" name="master[]" value="property_categories_management"  @if(in_array('property_categories_management',$roles->master)) checked @endif> property Categories</td>
										<td><input type="checkbox" name="master[]" value="product_rental_management"  @if(in_array('product_rental_management',$roles->master)) checked @endif> Product Rental</td>
									</tr>
									<tr>
										<td><input type="checkbox" name="master[]" value="message_header_management"  @if(in_array('message_header_management',$roles->master)) checked @endif> Message Header</td>
										<td><input type="checkbox" name="master[]" value="rental_agreement_management" @if(in_array('rental_agreement_management',$roles->master)) checked @endif> Rental Agreement</td>
									</tr>
								</table>
							</div>
						</div>
						<label id="master-error" class="error" for="master"></label>
						<label id="master[]-error" class="error" for="master[]"></label>
					</div>
					
					</div>
					<div class="box-footer">
						<input type="hidden" name="edited_Roles" id="edited_Roles" value="1">
						<div class="pull-right">
							<button type="submit" id="roles-submit" class="btn btn-success">
								<strong>Save</strong>
							</button>
							<a href="{!! url(route('roles-list')) !!}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="roles_id" value="{!! $roles->uuid !!}" />
			</form>
		</div>
	</div>
</section>
@endsection
@section('after-scripts-end')

 @include('roles.script')
    <script type="text/javascript">
	    /*$('#master').multiselect({
	        enableFiltering: true,
			maxHeight: 400,
			dropUp: true,
	        includeSelectAllOption: true,
	        enableCaseInsensitiveFiltering: true,
	        filterPlaceholder: 'Please choose roles list',
	        buttonWidth: '800px',
	        dropRight: true,
	    });*/
    </script>
    @stop