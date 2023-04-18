@extends('layouts.main')
@section('content')
<style>
	.elevation-2 {
		box-shadow: 0 3px 6px rgba(0, 0, 0, .16), 0 3px 6px rgba(0, 0, 0, .23) !important;
		border-radius: 50%;
	}
</style>

<section class="content-header">
	<h1 class="col-lg-6 no-padding">
		Dashboard
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Dashboard</li>
	</ol>
</section>
<!-- /.content-header -->
@if($show_dashboard_list == 1)
<section class="content">
	<div class="row mb-3">
		<div class="col-6">
			<div class="card card-widget widget-user">
				<div class="widget-user-header bg-success text-center">
					<h4 class="widget-user-desc">USER INFORMATION</h4>
				</div>
				<div class="widget-user-image">
					<img class="img-circle elevation-2" src="{{asset('images/default.jpg')}}" alt="User Avatar">
				</div>
				<div class="card-footer pt-5">
					<div class="row">
						<div class="col-sm-4 border-right">
							<div class="description-block">
								<h5 class="description-header">{{isset($user_count['users_count'])?$user_count['users_count']:"0"}}</h5>
								<span class="description-text"><b>Users</b></span>
							</div>
						</div>
						<div class="col-sm-4 border-right">
							<div class="description-block">
								<h5 class="description-header">{{isset($customers_count['customers_count'])?$customers_count['customers_count']:"0"}}</h5>
								<span class="description-text"><b>Customers</b></span>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="description-block">
								<h5 class="description-header">{{$user_count['total_count'] + $customers_count['total_count']}}</h5>
								<span class="description-text"><b>Total</b></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-sm-6 pr-0">
			<div class="card">
				<!-- <div class="card-header">Users</div> -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Users</h3>
					</div>
				</div>
				<div class="card-body" style="min-height: 380px">
					<div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
						<div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
							<div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
						</div>
						<div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
							<div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
						</div>
					</div> <canvas id="user-line" width="299" height="200" class="chartjs-render-monitor" style="display: block; width: 299px; height: 200px;"></canvas>
				</div>
			</div>
		</div>
	</div>
</section>
@endif
<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js'></script> -->
<script src="{{asset('js/Chart.bundle.min.js')}}"></script>
<script>
	$(document).ready(function() {
		var users_count = "{{$user_count['users_count']}}";
		var customers_count = "{{$customers_count['customers_count']}}";
		var total_user_count = "{{$user_count['total_count'] + $customers_count['total_count']}}";
		
		var ctx = $("#user-line");
		var myLineChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: ["Users", "Customers"],
				datasets: [{
					data: [users_count, customers_count],
					backgroundColor: ["#ff00b1", "#0ee0d6"]
				}]
			},
			options: {
				title: {
					display: true,
					text: 'Total Count ' + total_user_count
				}
			}
		});
	});
</script>
@endsection