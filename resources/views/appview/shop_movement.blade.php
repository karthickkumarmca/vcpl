@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/vehicle-materials/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">SHOP MOVEMENT</h3>
					</div>

					<div class="box-body">
						

						<div class="col-md-12">
							<div id="accordion">
							  <div class="card">
							    <div class="card-header" id="headingOne">
							      <h5 class="mb-0">
							        <a class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							          RECEIVED ITEMS
							        </a>
							      </h5>
							    </div>

							    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
							      <div class="card-body">

							      	<div class="col-md-12">
										<div class="form-group">
											<label>Material <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Material" name="material" value="{{old('material')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('material'))
											<div class="error">{{ $errors->first('material') }}</div>
											@endif
										</div>
									</div>
							        <div class="col-md-12">
										<div class="form-group">
											<label>Supply score <span class="text-danger"> *</span></label>
											<select name="supply_score" class="form-control pos_validate" id="supply_score">
											</select>
											<span class="validation_error"></span>
											@if($errors->has('supply_score'))
											<div class="error">{{ $errors->first('supply_score') }}</div>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Bill Number <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Bill Number" name="bill_number" value="{{old('bill_number')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('bill_number'))
											<div class="error">{{ $errors->first('bill_number') }}</div>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Quantity <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Quantity" name="quantity" value="{{old('quantity')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('quantity'))
											<div class="error">{{ $errors->first('quantity') }}</div>
											@endif
										</div>
									</div>

									
									<div class="col-md-12">
										<div class="form-group">
											<label>Vechile Number <span class="text-danger"> *</span></label>
											<select name="vechile_number" class="form-control pos_validate" id="vechile_number">
											</select>
											<span class="validation_error"></span>
											@if($errors->has('vechile_number'))
											<div class="error">{{ $errors->first('vechile_number') }}</div>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group col-md-12">
											<label>In/Out Time <span class="text-danger"> *</span></label>
											<div class="row ">
												<input type="text" class="form-control pos_validate col-md-5" autocomplete="off" placeholder="Select Time" name="received_date" value="{{old('received_date')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<input type="text" class="form-control pos_validate col-md-6" autocomplete="off" placeholder="Select Time" name="received_date" value="{{old('received_date')}}" data-rule="admin" minlength="1" maxlength="128"/>
											</div>
											
											
										</div>
									</div>
							      </div>
							    </div>
							  </div>
							  <div class="card">
							    <div class="card-header" id="headingTwo">
							      <h5 class="mb-0">
							        <a class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
							         TRANSFERED TO WHOM
							        </a>
							      </h5>
							    </div>
							    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
							      <div class="card-body">
							        <div class="col-md-12">
										<div class="form-group">
											<label>Seril Number <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Seril Number" name="seril_number" value="{{old('seril_number')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('seril_number'))
											<div class="error">{{ $errors->first('seril_number') }}</div>
											@endif
										</div>
									</div>
									 <div class="col-md-12">
										<div class="form-group">
											<label>To Site <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter To site" name="to_site" value="{{old('to_site')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('to_site'))
											<div class="error">{{ $errors->first('to_site') }}</div>
											@endif
										</div>
									</div>
									 <div class="col-md-12">
										<div class="form-group">
											<label>Transfer slip number <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Transfer slip number" name="transfer_slip_number" value="{{old('Transfer slip number')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('Transfer slip number'))
											<div class="error">{{ $errors->first('Transfer slip number') }}</div>
											@endif
										</div>
									</div>
									
									<div class="col-md-12">
										<div class="form-group">
											<label>Vechile Number <span class="text-danger"> *</span></label>
											<select name="vechile_number" class="form-control pos_validate" id="vechile_number">
											</select>
											<span class="validation_error"></span>
											@if($errors->has('vechile_number'))
											<div class="error">{{ $errors->first('vechile_number') }}</div>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group col-md-12">
											<label>In/Out Time <span class="text-danger"> *</span></label>
											<div class="row ">
												<input type="text" class="form-control pos_validate col-md-5" autocomplete="off" placeholder="Select Time" name="received_date" value="{{old('received_date')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<input type="text" class="form-control pos_validate col-md-6" autocomplete="off" placeholder="Select Time" name="received_date" value="{{old('received_date')}}" data-rule="admin" minlength="1" maxlength="128"/>
											</div>
											
											
										</div>
									</div>

									<div class="col-md-12">
										<div class="form-group col-md-12">
											<div class="row ">
												<input type="text" class="form-control pos_validate col-md-6" autocomplete="off" placeholder="Materail" name="received_date" value="{{old('received_date')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<input type="text" class="form-control pos_validate col-md-5" autocomplete="off" placeholder="Quantity" name="received_date" value="{{old('received_date')}}" data-rule="admin" minlength="1" maxlength="128"/>

											<a href="javascript::" id="categories-submit col-md-1" class="btn btn-success">Add</a>
											</div>
											
											
										</div>
									</div>

									
							      </div>
							    </div>
							  </div>
							 
							</div>
						</div>

					
						
					</div>
					<div class="box-footer">
						<div class="pull-right">
							<button type="submit" id="categories-submit" class="btn btn-success">
								Save
							</button>
							<a href="{{url(route('vehicle-materials-list'))}}" class="btn btn-default">
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
<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>

    @include('master.vehicle_materials.script')
     <script type="text/javascript">
	// set default dates
	var start = new Date();
	// set end date to max one year period:
	var end = new Date(new Date().setYear(start.getFullYear()+1));

	$('#insurance_date').datepicker({
	    startDate : start,
	    endDate   : end
	// update "toDate" defaults whenever "fromDate" changes
	}).on('changeDate', function(){
	    // set the "toDate" start to not be later than "fromDate" ends:
	    $('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 


    </script>
    @stop
