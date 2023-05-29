@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="lorry-form" method="post" enctype="multipart/form-data" action="{{URL::to('appview/lorry-movement/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">LORRY MOVEMENT</h3>
					</div>
					<div class="box-body">
						<div class="col-md-12">
							<div id="accordion">
							  <div class="card">
							    <div class="card-header" id="headingOne">
							      <h5 class="mb-0">
							        <a class="btn btn-link" data-toggle="collapse"  aria-expanded="true" aria-controls="collapseOne">
							          LORRY MATERIALS:
							        </a>
							      </h5>
							    </div>

							    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
							      <div class="card-body">
							      	@if(session()->has('message'))
							      		@if(session()->has('class')=='success')
											<div class="alert alert-success appview_notification_alert" role="alert">
												<strong>Success : </strong> {{ session()->get('message') }}.
												<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">&times;</span>
												</button>
											</div>
										@else
										   	<div class="alert alert-danger appview_notification_alert" role="alert">
												<strong>Error : </strong> {{ session()->get('message') }}.
												<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">&times;</span>
												</button>
											</div>
									    @endif
									@endif
							      	<div class="col-md-12">
										<div class="form-group">
											<label>Material <span class="text-danger"> *</span></label>
											<select name="material_id" class="form-control pos_validate" id="material_id" >
												<option value="">Select material</option>
												@isset($productdetails)
													@foreach($productdetails as $s)
													<option value="{{$s['id']}}">{{$s['product_name']}}</option>
													@endforeach
												@endisset
											</select>
											<span class="validation_error"></span>
											@if($errors->has('material_id'))
											<div class="error">{{ $errors->first('material_id') }}</div>
											@endif
										</div>
									</div>
							        <div class="col-md-12">
										<div class="form-group">
											<label>Supply scope <span class="text-danger"> *</span></label>
											<select name="supply_score" class="form-control pos_validate" id="supply_score">
												<option value="">Select</option>
												<option value="1">VCPL</option>
												<option value="2">SUPPLIER</option>
												<option value="3">CLIENT</option>
											</select>
											<span class="validation_error"></span>
											@if($errors->has('supply_score'))
											<div class="error">{{ $errors->first('supply_score') }}</div>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Delivery chellan no<span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Delivery Chellan Number" name="delivery_chellan_number" value="{{old('delivery_chellan_number')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('delivery_chellan_number'))
											<div class="error">{{ $errors->first('delivery_chellan_number') }}</div>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Quantity <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Quantity" name="quantity" value="{{old('quantity')}}" data-rule="admin" minlength="1" maxlength="128" onkeypress="return isNumberKey(event)"/>
											<span class="validation_error"></span>
											@if($errors->has('quantity'))
											<div class="error">{{ $errors->first('quantity') }}</div>
											@endif
										</div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<label>Unit <span class="text-danger"> *</span></label>
											{{-- <input type="text" name="unit" class="form-control pos_validate" autocomplete="off" placeholder="Enter Unit" value="{{old('unit')}}" data-rule="admin" minlength="1" maxlength="128"> --}}
												<select name="unit" class="form-control pos_validate" id="unit" >
												<option value="">Select units</option>
												@isset($units)
													@foreach($units as $u)
													<option value="{{$u['id']}}">{{$u['unit_name']}}</option>
													@endforeach
												@endisset
											</select>
											<span class="validation_error"></span>
											@if($errors->has('unit'))
											<div class="error">{{ $errors->first('unit') }}</div>
											@endif
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
							<a href="{{url(route('create-lorry-movement'))}}" class="btn btn-default">
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
<script src="{{asset('js/lorry_script.js')}}"></script>

@stop
