@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
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
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Roles" name="role_name" value="{{old('role_name')}}" data-rule="admin" minlength="3" maxlength="128"/>
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
								<select data-placeholder="Begin typing a name to filter..." multiple class="form-control chosen-select" name="master[]">
							    <option value=""></option>
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
							   
							  </select>
							</div>
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
<!--<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>  !-->
<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script> 
<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/> 
    @include('roles.script')
    <script type="text/javascript">
/*$(".chosen-select").chosen({
  no_results_text: "Oops, nothing found!"
})*/
    </script>
    @stop
