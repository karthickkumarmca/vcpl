<aside class="main-sidebar elevation-4 sidebar-dark-success" style="padding-top: 50px;">
	<section class="sidebar">
		<ul class="sidebar-menu" data-widget="tree">
			@if(config("roles.".Session::get('user_role').".dashboard"))
			<li class="{{ Request::is('dashboard') ? 'active' : '' }}">
				<a href="{!! url('dashboard') !!}">
					<i class="fa fa-dashboard"></i> <span>Dashboard </span>
				</a>
			</li>
			@endif
			@if(config("roles.".Session::get('user_role').".hallmark_management"))
			<li class="{{ Request::is('hallmark-list') ? 'active' : '' }} {{ Request::is('create-hallmark') ? 'active' : '' }} {{ Request::is('edit-hallmark/*') ? 'active' : '' }} {{ Request::is('view-hallmark/*') ? 'active' : '' }}">
				<a href="{!! url(route('hallmark-list')) !!}">
					<i class="fa fa-calendar"></i> <span>Hallmark Management</span>
				</a>
			</li>
			@endif
			@if(config("roles.".Session::get('user_role').".chain_management"))
			<li class="{{ Request::is('chain-list') ? 'active' : '' }} {{ Request::is('create-chain') ? 'active' : '' }} {{ Request::is('edit-chain/*') ? 'active' : '' }} {{ Request::is('view-chain/*') ? 'active' : '' }}">
				<a href="{!! url(route('chain-list')) !!}">
					<i class="fa fa-calendar"></i> <span>Chain Management</span>
				</a>
			</li>
			@endif
			@if(config("roles.".Session::get('user_role').".user_managements"))
			<li class="{{ Request::is('user-list') ? 'active' : '' }} {{ Request::is('create-user') ? 'active' : '' }} {{ Request::is('edit-user/*') ? 'active' : '' }} {{ Request::is('view-user/*') ? 'active' : '' }} {{ Request::is('change-password/*') ? 'active' : '' }}">
				<a href="{!! url(route('user-list')) !!}">
					<i class="fa fa-user"></i> <span>Users </span>
				</a>
			</li>
			@endif
			@if(config("roles.".Session::get('user_role').".customer_management"))
			<li class="{{ Request::is('customer-list') ? 'active' : '' }} {{ Request::is('create-customer') ? 'active' : '' }} {{ Request::is('edit-customer/*') ? 'active' : '' }} {{ Request::is('view-customer/*') ? 'active' : '' }}">
				<a href="{!! url(route('customer-list')) !!}">
					<i class="fa fa-users"></i> <span>Customers </span>
				</a>
			</li>
			@endif
			@if(config("roles.".Session::get('user_role').".stock_management"))
			<!-- <li class="{{ Request::is('stock-list') ? 'active' : '' }} {{ Request::is('create-stock') ? 'active' : '' }} {{ Request::is('edit-stock/*') ? 'active' : '' }} {{ Request::is('view-stock/*') ? 'active' : '' }}">
				<a href="{!! url(route('stock-list')) !!}">
					<i class="fa fa-stack-exchange"></i> <span>Stock Order </span>
				</a>
			</li> -->
			@endif
			<li>
				<a title="Logout" data-toggle="control-sidebar" href="#" onclick="event.preventDefault();
				swal('Are you sure want to logout?','','',{
					buttons:{						
						cancel : 'Cancel',
						confirm : {text:'Confirm',className:'btn-success'}
					}	
				})
				.then((value) => {
					if(value){
						document.getElementById('logout-form-slide').submit();
					}
				});
				">
				<i class="fa fa-sign-out fa-fw"></i> Logout
				</a>
				<form id="logout-form-slide" action="{{ route('logout') }}" method="POST" style="display: none;">
					@csrf
				</form>
			</li>
		</ul>
	</section>
</aside>