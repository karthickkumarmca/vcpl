@extends('layouts.main')
@section('content')
<style>
	.error{color:#f00;    margin-bottom: 0px;}
</style>
<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Message Header<small>Management</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{url(route('home'))}}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{url(route('message-header-list'))}}">Message Header</a></li>
		<li>Edit Header Message</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-sm-12">
			<form id="admin-form" method="post" enctype="multipart/form-data" action="{{URL::to('master/message-header/store')}}">
				@csrf
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Product</h3>
					</div>
					<div class="box-body">
					

					
						<div class="col-md-12">
							<div class="form-group">
								<label>Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" placeholder="Enter Name" name="name" value="{{old('name') ? old('name') : $message_header->name}}" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('name'))
								<div class="error">{{ $errors->first('name') }}</div>
								@endif
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label>Description <span class="text-danger"> *</span></label>
								<input type="text" class="form-control pos_validate" placeholder="Enter Description" name="description" value="{{old('description') ? old('description') : $message_header->description}}" maxlength="128"/>
								<span class="validation_error"></span>
								@if($errors->has('description'))
								<div class="error">{{ $errors->first('description') }}</div>
								@endif
							</div>
						</div>
					
					</div>
					<div class="box-footer">
						<input type="hidden" name="edited_categories" id="edited_categories" value="1">
						<div class="pull-right">
							<button type="submit" id="categories-submit" class="btn btn-success">
								<strong>Save</strong>
							</button>
							<a href="{{url(route('message-header-list'))}}" class="btn btn-default">
								<strong>Back</strong>
							</a>
						</div>
					</div>
				</div>
				<input type="hidden" name="message_header_id" value="{!! $message_header->uuid !!}" />
			</form>
		</div>
	</div>
</section>
@endsection
@section('after-scripts-end')
<script src="{{asset('js/custom/formValidation.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('plugins/jquery-validation/additional-methods.min.js')}}"></script>


@include('master.message_header.script')
@stop