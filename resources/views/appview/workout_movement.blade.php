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
						<h3 class="box-title">Work Out TURN AT SITE</h3>
					</div>

					<div class="box-body">
						

						<div class="col-md-12">
							<div class="form-group">
								<label>Subcontractor List <span class="text-danger"> *</span></label>
								<select name="subcontractor_id" class="form-control pos_validate" id="subcontractor_id">
									<option value="">Select Subcontractor List</option>
								</select>
								<span class="validation_error"></span>
								@if($errors->has('subcontractor_id'))
								<div class="error">{{ $errors->first('subcontractor_id') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Floor Name <span class="text-danger"> *</span></label>
								<select name="floor_id" class="form-control pos_validate" id="floor_id">
									<option value="">Select Floor Name</option>
								</select>
								<span class="validation_error"></span>
								@if($errors->has('floor_id'))
								<div class="error">{{ $errors->first('floor_id') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Work Group <span class="text-danger"> *</span></label>
								<select name="work_group" class="form-control pos_validate" id="work_group">
									<option value="">Select Work Group</option>
								</select>
								<span class="validation_error"></span>
								@if($errors->has('work_group'))
								<div class="error">{{ $errors->first('work_group') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Work Details <span class="text-danger"> *</span></label>
								<select name="work_details" class="form-control pos_validate" id="work_details">
									<option value="">Select Floor Name</option>
								</select>
								<span class="validation_error"></span>
								@if($errors->has('work_details'))
								<div class="error">{{ $errors->first('work_details') }}</div>
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
								<label>Unit <span class="text-danger"> *</span></label>
								<select name="unit_id" class="form-control pos_validate" id="unit_id">
									<option value="">Select Unit</option>
								</select>
								<span class="validation_error"></span>
								@if($errors->has('unit_id'))
								<div class="error">{{ $errors->first('unit_id') }}</div>
								@endif
							</div>
						</div>
						

						<div class="col-md-12">
							<div class="form-group">
								<label>Rate <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Rate" name="rate" value="" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('rate'))
								<div class="error">{{ $errors->first('rate') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Date <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Date" name="labour_date" value="{{ date('d-M-Y')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('labour_date'))
								<div class="error">{{ $errors->first('labour_date') }}</div>
								@endif
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
