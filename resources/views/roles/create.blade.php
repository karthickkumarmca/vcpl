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
	<h1 class="col-lg-6 no-padding">Roles <small>Management</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('roles-list'))}}">Roles management</a></li>
		<li>Create Roles</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('roles/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Create Roles</h3>
					</div>

					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Roles <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Roles" name="role_name" value="{{old('role_name')}}" data-rule="admin" minlength="1" maxlength="100"/>
								<span class="validation_error"></span>
								@if($errors->has('role_name'))
								<div class="error">{{ $errors->first('role_name') }}</div>
								@endif
							</div>
						</div>
					</div>
					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Master Modules <span class="text-danger"> *</span></label>
								<!--<select data-placeholder="Please choose roles list" class="form-control" multiple="multiple" id="master" name="master[]">
							    <option value="roles_management">Roles</option>
							    <option value="units_management">Units</option>
							    <option value="staffgroups_management">Staff Group</option>
							    <option value="staff_details_management">Staff Details</option>
							    <option value="site_info_management">Site Info</option>
							    <option value="client_site_management">Client Info</option>
							    <option value="architect_site_management">Architect Info</option>
							    <option value="categories_management">Product Categories</option>
							    <option value="sub_categories_management">Sub Product Categories</option>

							    <option value="product_details_management">Product Details</option>
							    <option value="labour_categories_management">Labour Categories</option>
							    <option value="labour_wages_management">Labour Wages</option>
							    
							    <option value="centering_materials_management">centering Materials</option>
							    <option value="lorry_materials_management">Lorry Materials</option>
							    <option value="shop_materials_management">Shop Materials</option>
							    <option value="toolsplants_materials_management">Toold and Plant Materials</option>
							    <option value="vehicle_materials_management">Vehicle Materials</option>

							    <option value="ownership_management">Ownership</option>
							    <option value="property_name_management">Property Name</option>
							    <option value="property_categories_management">property Categories</option>
							    <option value="product_rental_management">Product Rental</option>
							    
							    <option value="message_header_management">Message Header</option>
							   
							  </select> !-->


							  	<table>
									<tr>
										<td><input type="checkbox" name="master[]" id="master" value="roles_management"> Roles</td>
										<td><input type="checkbox" name="master[]" id="master" value="units_management"> Units</td>
										<td><input type="checkbox" name="master[]" id="master" value="staffgroups_management"> Staff Group</td>
									</tr>
									<tr>	
										<td><input type="checkbox" name="master[]" id="master" value="staff_details_management"> Staff Details</td>
										<td><input type="checkbox" name="master[]" id="master" value="site_info_management"> Site Info</td>
										<td><input type="checkbox" name="master[]" id="master" value="client_site_management"> Client Info</td>
									</tr>
									<tr>	
										<td><input type="checkbox" name="master[]" id="master" value="architect_site_management"> Architect Info</td>
										<td><input type="checkbox" name="master[]" id="master" value="categories_management"> Product Categories</td>
										<td><input type="checkbox" name="master[]" id="master" value="sub_categories_management"> Sub Product Categories</td>
									</tr>
									<tr>
										<td><input type="checkbox" name="master[]" id="master" value="product_details_management"> Product Details</td>
											<td><input type="checkbox" name="master[]" id="master" value="labour_categories_management"> Labour Categories</td>
											<td><input type="checkbox" name="master[]" id="master" value="labour_wages_management"> Labour Wages</td>
									</tr>
									<tr>
											<td><input type="checkbox" name="master[]" id="master" value="centering_materials_management"> centering Materials</td>
											<td><input type="checkbox" name="master[]" id="master" value="lorry_materials_management"> Lorry Materials</td>
											<td><input type="checkbox" name="master[]" id="master" value="shop_materials_management"> Shop Materials</td>
									</tr>
									<tr>		
											<td><input type="checkbox" name="master[]" id="master" value="toolsplants_materials_management"> Toold and Plant Materials</td>
											<td><input type="checkbox" name="master[]" id="master" value="vehicle_materials_management"> Vehicle Materials</td>

											<td><input type="checkbox" name="master[]" id="master" value="ownership_management"> Ownership</td>
									</tr>
									<tr>		
											<td><input type="checkbox" name="master[]" id="master" value="property_name_management"> Property Name</td>
											<td><input type="checkbox" name="master[]" id="master" value="property_categories_management"> property Categories</td>
											<td><input type="checkbox" name="master[]" id="master" value="product_rental_management"> Product Rental</td>
									</tr>
									<tr>
											<td><input type="checkbox" name="master[]" id="master" value="rental_agreement_management"> Rental Agreement</td>
											<td><input type="checkbox" name="master[]" id="master" value="message_header_management"> Message Header</td>
									</tr>
								</table>
							   @if($errors->has('master'))
								<div class="error">{{ $errors->first('master') }}</div>
								@endif
							</div>
							<label id="master-error" class="error" for="master"></label>
							<label id="master[]-error" class="error" for="master[]"></label>
						</div>

						
					</div>

					<div class="box-footer">
						<div class="pull-right">
							<button type="submit" id="Roles-submit" class="btn btn-success">
								Save
							</button>
							<a href="{{url(route('roles-list'))}}" class="btn btn-default">
								Back
							</a>
						</div>
					</div>
				</div>
			</form>
		</div>
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
