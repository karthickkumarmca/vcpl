@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">Rental Agreement<small>Management</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('rental-agreement-list'))}}">Rental Agreement</a></li>
		<li>Create Rental Agreement</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/rental-agreement/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Create Rental Agreement</h3>
					</div>

					<div class="box-body">

						<div class="row col-md-12">
							<div class="form-group col-md-4">
								<label>Select property Name <span class="text-danger"> *</span></label>
								<select name="property_id" class="form-control pos_validate" id="property_id">
									<option value="">Select property</option>
									@isset($Property_name)
										@foreach($Property_name as $cat)
										<option value="{{$cat['id']}}">{{$cat['property_name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('property_id'))
								<div class="error">{{ $errors->first('property_id') }}</div>
								@endif
							</div>

							<div class="form-group  col-md-4">
								<label>Property Category</label>
								<input type="text" class="form-control pos_validate " autocomplete="off" value="Property Category" data-rule="admin" minlength="1" id="category_name" maxlength="128" readonly/>
								<span class="validation_error"></span>
								
							</div>
							<div class="form-group  col-md-4">
								<label>Owner Name</label>
								<input type="text" class="form-control pos_validate " id="ownership_name" autocomplete="off" value="Owner Name" data-rule="admin" minlength="1" maxlength="128" readonly/>
								<span class="validation_error"></span>
								
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="form-group">
								<label>Tenant Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate " autocomplete="off" placeholder="Enter Tenant Name" name="tenant_name" value="{{old('tenant_name')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('tenant_name'))
								<div class="error">{{ $errors->first('tenant_name') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Rent Start Date <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Select Rent Start Date" id="rent_start_date" name="rent_start_date" value="{{old('rent_start_date')}}" data-rule="admin" minlength="1" maxlength="128" readonly/>
								<span class="validation_error"></span>
								@if($errors->has('rent_start_date'))
								<div class="error">{{ $errors->first('rent_start_date') }}</div>
								@endif
							</div>
						</div>


						<div class="col-md-12">
							<div class="form-group">
								<label>Rent End Date <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Select Rent End Date" id="rent_end_date" name="rent_end_date" value="{{old('rent_end_date')}}" data-rule="admin" minlength="1" maxlength="128" readonly/>
								<span class="validation_error"></span>
								@if($errors->has('rent_end_date'))
								<div class="error">{{ $errors->first('rent_end_date') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Rental Area(Sq.ft) <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate number_restrict " autocomplete="off" placeholder="Enter Rental Area" name="rental_area" value="{{old('rental_area')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('rental_area'))
								<div class="error">{{ $errors->first('rental_area') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Rental Amount <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate number_restrict " autocomplete="off" placeholder="Enter Rental Amount" name="rental_amount" value="{{old('rental_amount')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('rental_amount'))
								<div class="error">{{ $errors->first('rental_amount') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Maintainance charge(per Month) <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate number_restrict" autocomplete="off" placeholder="Enter Maintainance charge" name="maintainance_charge" value="{{old('maintainance_charge')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('maintainance_charge'))
								<div class="error">{{ $errors->first('maintainance_charge') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Next Increment <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate number_restrict" autocomplete="off" placeholder="Enter Next Increment" name="next_increment" value="{{old('next_increment')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('next_increment'))
								<div class="error">{{ $errors->first('next_increment') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Aadhar Number</label>
								<input type="text" class="form-control pos_validate " autocomplete="off" placeholder="Enter Aadhar Number" name="aadhar_number" value="{{old('aadhar_number')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('aadhar_number'))
								<div class="error">{{ $errors->first('aadhar_number') }}</div>
								@endif
							</div>
						</div>


						<div class="col-md-12">
							<div class="form-group">
								<label>Pan Number</label>
								<input type="text" class="form-control pos_validate " autocomplete="off" placeholder="Enter Pan Number" name="pan_number" value="{{old('pan_number')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('pan_number'))
								<div class="error">{{ $errors->first('pan_number') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>GST IN</label>
								<input type="text" class="form-control pos_validate " autocomplete="off" placeholder="Enter GST IN" name="gst_in" value="{{old('gst_in')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('gst_in'))
								<div class="error">{{ $errors->first('gst_in') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Contact Person  <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate " autocomplete="off" placeholder="Enter Contact Person" name="contact_person_name" value="{{old('contact_person_name')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('contact_person_name'))
								<div class="error">{{ $errors->first('contact_person_name') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Contact Person Mobile Number  <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate number_restrict" autocomplete="off" placeholder="Enter Contact Person Mobile Number" name="contact_person_mobile_number" value="{{old('contact_person_mobile_number')}}" data-rule="admin" minlength="1" maxlength="15"/>
								<span class="validation_error"></span>
								@if($errors->has('contact_person_mobile_number'))
								<div class="error">{{ $errors->first('contact_person_mobile_number') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Alternative Contact Person </label>
								<input type="text" class="form-control pos_validate " autocomplete="off" placeholder="Enter Contact Person" name="alternative_contact_person_name" value="{{old('alternative_contact_person_name')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('alternative_contact_person_name'))
								<div class="error">{{ $errors->first('alternative_contact_person_name') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Alternative Mobile Number  <span class="text-danger"> *</span> </label>
								<input type="text" class="form-control pos_validate number_restrict" autocomplete="off" placeholder="Enter Contact Person Mobile Number" name="alternative_contact_person_mobile_number" value="{{old('alternative_contact_person_mobile_number')}}" data-rule="admin" minlength="1" maxlength="15"/>
								<span class="validation_error"></span>
								@if($errors->has('alternative_contact_person_mobile_number'))
								<div class="error">{{ $errors->first('alternative_contact_person_mobile_number') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Present Rental Rate  <span class="text-danger"> *</span> </label>
								<input type="text" class="form-control pos_validate number_restrict" autocomplete="off" placeholder="Enter Present Rental Rate" name="present_rental_rate" value="{{old('present_rental_rate')}}" data-rule="admin" minlength="1" maxlength="15"/>
								<span class="validation_error"></span>
								@if($errors->has('present_rental_rate'))
								<div class="error">{{ $errors->first('present_rental_rate') }}</div>
								@endif
							</div>
						</div>


						<div class="col-md-12">
							<div class="form-group">
								<label>Advance Paid  <span class="text-danger"> *</span> </label>
								<input type="text" class="form-control pos_validate number_restrict" autocomplete="off" placeholder="Enter Advance Paid" name="advance_paid" value="{{old('advance_paid')}}" data-rule="admin" minlength="1" maxlength="15"/>
								<span class="validation_error"></span>
								@if($errors->has('advance_paid'))
								<div class="error">{{ $errors->first('advance_paid') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Payment Mode <span class="text-danger"> *</span></label>
								<select name="payment_mode" class="form-control pos_validate" id="payment_mode">
									<option value="">Select Payment Mode</option>
									<option value="1">Wire Transfer</option>
									<option value="2">Cash</option>
									<option value="3">Online</option>
								</select>
								<span class="validation_error"></span>
								@if($errors->has('payment_mode'))
								<div class="error">{{ $errors->first('payment_mode') }}</div>
								@endif
							</div>
						</div>

						


						
					</div>
					<div class="box-footer">
						<div class="pull-right">
							<button type="submit" id="categories-submit" class="btn btn-success">
								Save
							</button>
							<a href="{{url(route('rental-agreement-list'))}}" class="btn btn-default">
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

    @include('master.rental_agreement.script')
      <script type="text/javascript">
	// set default dates
	var start = new Date();
	// set end date to max one year period:
	var end = new Date(new Date().setYear(start.getFullYear()+1));

	$('#rent_start_date').datepicker({
	    startDate : start,
	    endDate   : end
	// update "toDate" defaults whenever "fromDate" changes
	}).on('changeDate', function(){
	    // set the "toDate" start to not be later than "fromDate" ends:
	    $('#rent_end_date').datepicker('setStartDate', new Date($(this).val()));
	}); 

	$('#rent_end_date').datepicker({
	    startDate : start,
	    endDate   : end
	// update "fromDate" defaults whenever "toDate" changes
	}).on('changeDate', function(){
	    // set the "fromDate" end to not be later than "toDate" starts:
	    $('#rent_start_date').datepicker('setEndDate', new Date($(this).val()));
	});

    </script>
    @stop
