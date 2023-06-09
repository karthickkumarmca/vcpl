@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">Ownership <small>Management</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('ownership-list'))}}">Ownership management</a></li>
		<li>Create Ownership</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/ownership/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Create Ownership</h3>
					</div>

					

						<div class="col-md-12">
						<div class="form-group">
								<label>Select Owner Name <span class="text-danger"> *</span></label>
								<select name="staff_id" class="form-control pos_validate" id="staff_id">
									<option value="">Select Owner Name</option>
									@isset($Staffdetails)
										@foreach($Staffdetails as $cat)
										<option value="{{$cat['id']}}">{{$cat['name']}}</option>
										@endforeach
									@endisset
								</select>
								<span class="validation_error"></span>
								@if($errors->has('staff_id'))
								<div class="error">{{ $errors->first('staff_id') }}</div>
								@endif
							</div>
							</div>
						


						<div class="col-md-12">
							<div class="form-group">
								<label>Position <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter position" name="position" value="{{old('position')}}" data-rule="admin" minlength="3" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('position'))
								<div class="error">{{ $errors->first('position') }}</div>
								@endif
							</div>
						</div>
						

						
					</div>
					<div class="box-footer">
						<div class="pull-right">
							<button type="submit" id="categories-submit" class="btn btn-success">
								Save
							</button>
							<a href="{{url(route('ownership-list'))}}" class="btn btn-default">
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

    @include('master.ownership.script')
    @stop
