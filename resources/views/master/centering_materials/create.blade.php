@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">Centering materials <small>Management</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('centering-materials-list'))}}">Centering materials management</a></li>
		<li>Create Centering materials</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/centering-materials/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Create Centering materials</h3>
					</div>

					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Select Material Name <span class="text-danger"> *</span></label>
								<select name="category_id" class="form-control pos_validate" id="category_id">
									<option value="">Select category</option>
									@isset($categories)
										@foreach($categories as $category)
										<option value="{{$category['id']}}">{{$category['product_name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('category_id'))
								<div class="error">{{ $errors->first('category_id') }}</div>
								@endif
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Unit Rate <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Name" name="rate_unit" value="{{old('rate_unit')}}" data-rule="admin" minlength="1" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('rate_unit'))
								<div class="error">{{ $errors->first('rate_unit') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Select Unit <span class="text-danger"> *</span></label>
								<select name="units_id" class="form-control pos_validate" id="units_id">
									<option value="">Select Unit</option>
									@isset($units)
										@foreach($units as $unit)
										<option value="{{$unit['id']}}">{{$unit['unit_name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('units_id'))
								<div class="error">{{ $errors->first('units_id') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>From Date <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Select date" id="from_date" name="from_date" value="{{old('from_date')}}" data-rule="admin" minlength="1" maxlength="128" readonly/>
								<span class="validation_error"></span>
								@if($errors->has('rate_unit'))
								<div class="error">{{ $errors->first('from_date') }}</div>
								@endif
							</div>
						</div>


						<div class="col-md-12">
							<div class="form-group">
								<label>To Date <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Select date" id="to_date" name="to_date" value="{{old('to_date')}}" data-rule="admin" minlength="1" maxlength="128" readonly/>
								<span class="validation_error"></span>
								@if($errors->has('rate_unit'))
								<div class="error">{{ $errors->first('to_date') }}</div>
								@endif
							</div>
						</div>
						
					</div>
					
					<div class="box-footer">
						<div class="pull-right">
							<button type="submit" id="categories-submit" class="btn btn-success">
								Save
							</button>
							<a href="{{url(route('centering-materials-list'))}}" class="btn btn-default">
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

    @include('master.property_name.script')
    <script type="text/javascript">
	// set default dates
	var start = new Date();
	// set end date to max one year period:
	var end = new Date(new Date().setYear(start.getFullYear()+1));

	$('#from_date').datepicker({
	    startDate : start,
	    endDate   : end
	// update "toDate" defaults whenever "fromDate" changes
	}).on('changeDate', function(){
	    // set the "toDate" start to not be later than "fromDate" ends:
	    $('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 

	$('#to_date').datepicker({
	    startDate : start,
	    endDate   : end
	// update "fromDate" defaults whenever "toDate" changes
	}).on('changeDate', function(){
	    // set the "fromDate" end to not be later than "toDate" starts:
	    $('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});

    </script>
    @stop
