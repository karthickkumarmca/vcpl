@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			@if(Session::has('message'))
			    <div class="alert alert-{{Session::get('class')}} alert-dismissible fade show w-50 ml-auto alert-custom"
			        role="alert">
			        {{ Session::get('message') }}
			        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			            <span aria-hidden="true">&times;</span>
			        </button>
			    </div>
			@endif
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('appview/cement-movement/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">CEMENT MOVEMENT</h3>
					</div>

					<div class="box-body">
						
						<div class="col-md-12">
							<div class="form-group">
								<label>Opening Balance <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="EnterOpening Balance" name="opening_balance" value="{{$stock}}" data-rule="admin" minlength="1" maxlength="128" readonly/>
								
							</div>
						</div>
						<input type="hidden" value="3" name="selected_tab" id="selected_tab">
						<div class="col-md-12">
							<div id="accordion">
							  <div class="card">

							  	<div class="card-header" id="headingthree">
							      <h5 class="mb-0">
							        <a class="btn btn-link tab3" data-toggle="collapse" data-target="#collapsethree" aria-expanded="true" aria-controls="collapsethree">
							         ISSUED TO SITE
							        </a>
							      </h5>
							    </div>
							    <div id="collapsethree" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
							      <div class="card-body">

							      	<div class="col-md-12">
										<div class="form-group">
											<label>BAGS <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate number_restrict" autocomplete="off" placeholder="Enter Quantity" name="bags" value="{{old('bags')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('bags'))
											<div class="error">{{ $errors->first('bags') }}</div>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>PURPOSE <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter PURPOSE " name="purpose" value="{{old('purpose')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('purpose'))
											<div class="error">{{ $errors->first('purpose') }}</div>
											@endif
										</div>
									</div>
									
							      </div>
							    </div>
							  </div>


							    <div class="card-header" id="headingOne">
							      <h5 class="mb-0">
							        <a class="btn btn-link tab1" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							          RECEIVED ITEMS
							        </a>
							      </h5>
							    </div>

							    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
							      <div class="card-body">
									<div class="col-md-12">
										<div class="form-group">
											<label>BILL NUMBER <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Bill Number" name="bill_number" value="{{old('bill_number')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('bill_number'))
											<div class="error">{{ $errors->first('bill_number') }}</div>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>QUANTITY <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate number_restrict" autocomplete="off" placeholder="Enter Quantity" name="rquantity" value="{{old('rquantity')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('rquantity'))
											<div class="error">{{ $errors->first('rquantity') }}</div>
											@endif
										</div>
									</div>
									
									<div class="col-md-12">
										<div class="form-group">
											<label>GRAND AND BRAND <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="ENTER GRAND AND BRAND" name="grand_and_brand" value="{{old('grand_and_brand')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('grand_and_brand'))
											<div class="error">{{ $errors->first('grand_and_brand') }}</div>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>VEHICLE NAME <span class="text-danger"> *</span></label>
											<select name="vehicle_id" class="form-control pos_validate" id="vehicle_id">
												<option value="">SELECT VEHICLE NAME</option>
												@isset($Vehicle_materials)
													@foreach($Vehicle_materials as $dfg)
													<option value="{{$dfg['id']}}">{{$dfg['vehicle_name']}}</option>
													@endforeach
												@endisset
												
											</select>
											<span class="validation_error"></span>
											@if($errors->has('vehicle_id'))
											<div class="error">{{ $errors->first('vehicle_id') }}</div>
											@endif
										</div>
									</div>
							      </div>
							    </div>
							  </div>
							  <div class="card">
							    <div class="card-header" id="headingTwo">
							      <h5 class="mb-0">
							        <a class="btn btn-link collapsed tab2" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
							         TRANSFERED TO WHOM
							        </a>
							      </h5>
							    </div>
							    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
							      <div class="card-body">
							      	<div class="col-md-12">
									<div class="form-group">
										<label>SITE NAME <span class="text-danger"> *</span></label>
										<select name="site_id" class="form-control pos_validate" id="site_id">
											<option value="">Select Site Name</option>
											@isset($siteinfo)
												@foreach($siteinfo as $siteinf)
												<option value="{{$siteinf['id']}}">{{$siteinf['site_name']}}</option>
												@endforeach
											@endisset
											
										</select>
										<span class="validation_error"></span>
										@if($errors->has('site_id'))
										<div class="error">{{ $errors->first('site_id') }}</div>
										@endif
									</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>QUANTITY <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate number_restrict" autocomplete="off" placeholder="Enter Quantity" name="quantity" value="{{old('quantity')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('quantity'))
											<div class="error">{{ $errors->first('quantity') }}</div>
											@endif
										</div>
									</div>
										
									 <div class="col-md-12">
										<div class="form-group">
											<label>TRANSFER SLIP NUMBER <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="Enter Transfer slip number" name="transfer_slip_number" value="{{old('Transfer slip number')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('Transfer slip number'))
											<div class="error">{{ $errors->first('Transfer slip number') }}</div>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>VECHILE NUMBER <span class="text-danger"> *</span></label>
											<select name="vechile_number" class="form-control pos_validate" id="vechile_number">
												<option value="">SELECT VEHICLE NAME</option>
												@isset($Vehicle_materials)
													@foreach($Vehicle_materials as $dfg)
													<option value="{{$dfg['id']}}">{{$dfg['vehicle_name']}}</option>
													@endforeach
												@endisset
											</select>
											<span class="validation_error"></span>
											@if($errors->has('vechile_number'))
											<div class="error">{{ $errors->first('vechile_number') }}</div>
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
							<a href="{{url(route('vehicle-materials-list'))}}" class="btn btn-default">
								Back
							</a>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">TRANSCATION HISOTY</h3>
                </div>
                <div class="box-body">
                    <div class="datatable_list form-inline" id="pos-custom-datatable"></div>
                </div>
            </div>
        </div>
    </div>
	</div>
	
</div>
</section>
@endsection
@section('after-scripts-end')
<script src="{{asset('js/custom/appview.js')}}"></script>
<script type="text/javascript">

        /**
         * DataTable Properties
         */
         var table_properties = {
            'name': 'subcategories-list',
            'columns': [
            {
                "name" : "date_created",
                "label": "Date",
                "badge": {
                    "display" : 0
                },
                "sort": {
                    "display" : 0,
                    "field" : "date_created"
                },
                "search": {
                    "display" : 0,
                    "type"    : "input"
                }
            },
             {
                "name" : "quantity",
                "label": "Quantity",
                "badge": {
                    "display" : 0
                },
                "sort": {
                    "display" : 0,
                    "field" : "quantity"
                },
               "search": {
                    "display" : 0,
                    "type"    : "input"
                }
            },
             {
                "name" : "type",
                "label": "Type",
                "badge": {
                    "display" : 0
                },
                "sort": {
                    "display" : 0,
                    "field" : "type"
                },
                "search": {
                    "display" : 0,
                    "type"    : "input"
                }
            },
             
            ],
            'api_url': 'create',
            'data_key': 'records',
            'daterange_picker': {
                'display' : false,
                'default_days': 29
            },
            'action_button' : {
                'display': false,
                'action': [
               

                    ]
                },
                "offset" : [
                10,
                25,
                50
                ],
                'pos_container' : 'pos-custom-datatable',
                'property_key' : 'admin_properties'
            };

        /**
         * Initiate DataTable
         */
         dataTable.initiateDatatable(table_properties);

        $('#subcategories-list-search').hide();

    </script>
@include('master.architect_site.script')
@stop


  
