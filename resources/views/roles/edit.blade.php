@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
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
								<input type="text" class="form-control pos_validate" placeholder="Enter Role" name="role_name" value="{{old('role_name') ? old('role_name') : $roles->role_name}}" data-rule="admin" maxlength="128"/>
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
								<select data-placeholder="Begin typing a name to filter..." multiple class="form-control chosen-select" name="master[]">
							    <option value=""></option>
							    <option value="roles_management" @if(in_array('roles_management',$roles->master)) selected @endif >Roles</option>
							    <option value="units_management"  @if(in_array('units_management',$roles->master)) selected @endif>Units</option>
							    <option value="staffgroups_management"  @if(in_array('staffgroups_management',$roles->master)) selected @endif>Staff Group</option>
							    <option value="staff_details_management"  @if(in_array('staff_details_management',$roles->master)) selected @endif>Staff Details</option>
							    <option value="site_info_management"  @if(in_array('site_info_management',$roles->master)) selected @endif>Site Info</option>
							    <option value="client_site_management"  @if(in_array('client_site_management',$roles->master)) selected @endif>Client Info</option>
							    <option value="architect_site_management"  @if(in_array('architect_site_management',$roles->master)) selected @endif>Architect Info</option>
							    <option value="categories_management"  @if(in_array('categories_management',$roles->master)) selected @endif>Product Categories</option>
							    <option value="sub_categories_management"  @if(in_array('sub_categories_management',$roles->master)) selected @endif>Sub Product Categories</option>

							    <option value="product_details_management"  @if(in_array('product_details_management',$roles->master)) selected @endif>Product Details</option>
							    <option value="labour_categories_management"  @if(in_array('labour_categories_management',$roles->master)) selected @endif>Labour Categories</option>
							    <option value="labour_wages_management"  @if(in_array('labour_wages_management',$roles->master)) selected @endif>Labour Wages</option>


							     <option value="centering_materials_management"  @if(in_array('centering_materials_management',$roles->master)) selected @endif>centering Materials</option>
							    <option value="lorry_materials_management"  @if(in_array('lorry_materials_management',$roles->master)) selected @endif>Lorry Materials</option>
							    <option value="shop_materials_management"  @if(in_array('shop_materials_management',$roles->master)) selected @endif>Shop Materials</option>
							    <option value="toolsplants_materials_management"  @if(in_array('toolsplants_materials_management',$roles->master)) selected @endif>Toold and Plant Materials</option>
							    <option value="vehicle_materials_management"  @if(in_array('vehicle_materials_management',$roles->master)) selected @endif>Vehicle Materials</option>

							    <option value="ownership_management"  @if(in_array('ownership_management',$roles->master)) selected @endif>Ownership</option>
							    <option value="property_name_management"  @if(in_array('property_name_management',$roles->master)) selected @endif>Property Name</option>
							    <option value="property_categories_management"  @if(in_array('property_categories_management',$roles->master)) selected @endif>property Categories</option>
							   
							  </select>
							</div>
						</div>
						
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
<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>

 @include('roles.script')
    <script type="text/javascript">
$(".chosen-select").chosen({
  no_results_text: "Oops, nothing found!"
})
    </script>
    @stop