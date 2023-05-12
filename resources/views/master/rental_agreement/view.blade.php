@extends('layouts.main')
@section('content')
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Rental Agreement <small>management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('rental-agreement-list'))}}">Rental Agreement</a></li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Rental Agreement</h3>
				</div>
				<div class="box-body">
					@if($rental_agreement->status)
					@php
					$status = "Active";
					$status_bg="bg-green";
					@endphp
					@else
					@php
					$status = "In-Active";
					$status_bg="bg-red";
					@endphp
					@endif
					<div class="table-responsive">
						<table class="table table-bordered">
							
							<tr>
								<th class="grey_header">
									<label>Property Name</label>
								</th>
								<td>{!! $rental_agreement->property_name !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Property Category Name</label>
								</th>
								<td>{!! $rental_agreement->category_name !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Ownership Name</label>
								</th>
								<td>{!! $rental_agreement->ownership_name !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Tenant Name</label>
								</th>
								<td>{!! $rental_agreement->tenant_name !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Rent Start Date</label>
								</th>
								<td>{!! $rental_agreement->rent_start_date !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Rent End Date</label>
								</th>
								<td>{!! $rental_agreement->rent_end_date !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Rent</label>
								</th>
								<td>{!! $rental_agreement->rent_start_date !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Rental Area(Sq.ft)</label>
								</th>
								<td>{!! $rental_agreement->rental_area !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Rental Amount</label>
								</th>
								<td>{!! $rental_agreement->rental_amount !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Maintainance charge(per Month) </label>
								</th>
								<td>{!! $rental_agreement->maintainance_charge !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Next Increment</label>
								</th>
								<td>{!! $rental_agreement->next_increment !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Aadhar Number</label>
								</th>
								<td>{!! $rental_agreement->aadhar_number !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Pan Number</label>
								</th>
								<td>{!! $rental_agreement->pan_number !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>GST IN</label>
								</th>
								<td>{!! $rental_agreement->gst_in !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Contact Person</label>
								</th>
								<td>{!! $rental_agreement->contact_person_name !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Contact Person Mobile Number</label>
								</th>
								<td>{!! $rental_agreement->contact_person_mobile_number !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Alternative Contact Person</label>
								</th>
								<td>{!! $rental_agreement->alternative_contact_person_name !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Alternative Mobile Number</label>
								</th>
								<td>{!! $rental_agreement->alternative_contact_person_mobile_number !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Present Rental Rate</label>
								</th>
								<td>{!! $rental_agreement->present_rental_rate !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Advance Paid </label>
								</th>
								<td>{!! $rental_agreement->advance_paid !!}</td>
							</tr>
							<tr>
								<th class="grey_header">
									<label>Payment Mode</label>
								</th>
								<td>@if($rental_agreement->payment_mode==1)
					Wire Transfer
					@elseif($rental_agreement->payment_mode==1)
					Cash
					@else
					Online
					
					@endif</td>
							</tr>

							@if($rental_agreement->aadhar_proof!='')
							<tr>
								<th class="grey_header">
									<label>Adhaar Image </label>
								</th>
								<td><a href="{{$rental_agreement->aadhar_proof}}" target="_blank">Click to view the uploaded image</a></td>
							</tr>
							@endif

							@if($rental_agreement->pan_proof!='')
							<tr>
								<th class="grey_header">
									<label>Pan Image </label>
								</th>
								<td><a href="{{$rental_agreement->pan_proof}}" target="_blank">Click to view the uploaded image</a></td>
							</tr>
							@endif
							
							<tr>
								<th class="grey_header">
									<label>Status</label>
								</th>
								<td><span class="badge {!! $status_bg !!}"> {!! $status !!}</span></td>
							</tr>
							
							<tr>
								<th class="grey_header">
									<label>Created At</label>
								</th>
								<td>{!! $rental_agreement->created_at !!}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="box-footer">
					<div class="pull-right">
						<a href="{{url(route('rental-agreement-list'))}}" class="btn btn-default">
							<strong>Back</strong>
						</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>
@endsection