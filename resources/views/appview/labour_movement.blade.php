@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('appview/labour-movement/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">LABOUR</h3>
					</div>

					<div class="box-body">
						

						<div class="col-md-12">
							<div class="form-group">
								<label>Subcontractor List <span class="text-danger"> *</span></label>
								<select name="subcontractor_id" class="form-control pos_validate" id="subcontractor_id">
									<option value="">Select Subcontractor List</option>
									@isset($subcontractors)
										@foreach($subcontractors as $s)
										<option value="{{$s['id']}}">{{$s['name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('subcontractor_id'))
								<div class="error">{{ $errors->first('subcontractor_id') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Shift <span class="text-danger"> *</span></label>
								<select name="shift_id" class="form-control pos_validate" id="shift_id">
									<option value="">Select Shift</option>
									<option value="1">Day Shift</option>
									<option value="2">Night Shift</option>
								</select>
								<span class="validation_error"></span>
								@if($errors->has('shift_id'))
								<div class="error">{{ $errors->first('shift_id') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Date <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Date" name="labour_date" value="{{ date('Y-m-d')}}" data-rule="admin" id="labour_date" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('labour_date'))
								<div class="error">{{ $errors->first('labour_date') }}</div>
								@endif
							</div>
						</div>


						<div class="col-md-12">
							<div class="form-group">
								<label>Labour Category <span class="text-danger"> *</span></label>
								<div class="table-responsive">
        							<table class="table table-bordered table-striped custom_datatable" id="categories-list-datatable">
	        							<thead>
	        								<tr><th>S.no</th><th>Labour Category</th><th>No of Labour</th></tr>
	        							</thead>
	        							<tbody>
											@isset($labour_categories)
												@foreach($labour_categories as $k=>$l)
												<tr>
		        									<td>{{$k+1}}</td>
		        									<td>{{$l['category_name']}}</td>
		        									<td><span ><input type="text" name="number_of_labour[{{$l['id']}}]" value=""></span></td>
	        									</tr>
												@endforeach
											@endisset
	        								
									        
	    								</tbody>
    								</table>
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

	$('#labour_date').datepicker({
	    startDate : start,
	    endDate   : end
	// update "toDate" defaults whenever "fromDate" changes
	}).on('changeDate', function(){
	    // set the "toDate" start to not be later than "fromDate" ends:
	    $('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 

	$(document).ready(function () {

	    $("#admin-form").validate({
	    	ignore: [],
	        rules: {
	            "group_name": {
	                required: true,
	                minlength: 1,
	                maxlength: 100,
	                normalizer:function( value ) {
	               		return $.trim(value);
	                },
	            },
	        },
	        messages: {
	            "group_name": {
	                required: "Please enter a group name",
	                minlength: "Enter group name minimum 1 character",
	                maxlength: "Enter group name maximum 100 character",
	            },
	        },
	        submitHandler: function (form) {
	           form.submit();
	        }
	    });

	});

</script>

    @stop
