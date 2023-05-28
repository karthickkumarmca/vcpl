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
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('appview/tools-movement/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">TOOLS AND PLANT</h3>
					</div>

					<div class="box-body">
						<div class="col-md-12">
							<div class="form-group">
								<label>Balance <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" autocomplete="off" placeholder="EnterOpening Balance" name="opening_balance" value="{{$stock}}" data-rule="admin" minlength="1" maxlength="128" readonly/>
								
							</div>
						</div>
						<div class="col-md-12">
							<div id="accordion">
							  <div class="card">
							    <div class="card-header" id="headingOne">
							      <h5 class="mb-0">
							        <a class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							          TRANSFERED TO WHOM
							        </a>
							      </h5>
							    </div>

							    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
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
							       <div class="col-md-12">
										<div class="form-group">
											<label>QUANTITY <span class="text-danger"> *</span></label>
											<input type="text" class="form-control pos_validate number_restrict" autocomplete="off" placeholder="Enter Quantity" name="quantity" value="{{old('rquantity')}}" data-rule="admin" minlength="1" maxlength="128"/>
											<span class="validation_error"></span>
											@if($errors->has('rquantity'))
											<div class="error">{{ $errors->first('rquantity') }}</div>
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