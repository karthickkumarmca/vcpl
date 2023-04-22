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
			@if(config("roles.".Session::get('user_role').".units_management"))
			<li class="{{ Request::is('units-list') ? 'active' : '' }} {{ Request::is('create-units') ? 'active' : '' }} {{ Request::is('edit-hallmark/*') ? 'active' : '' }} {{ Request::is('view-hallmark/*') ? 'active' : '' }}">
				<a href="{!! url(route('units-list')) !!}">
					<i class="fa fa-calendar"></i> <span>Units Management</span>
				</a>
			</li>
			@endif
			@if(config("roles.".Session::get('user_role').".categories_management"))
			<li class="{{ Request::is('categories-list') ? 'active' : '' }} {{ Request::is('create-categories') ? 'active' : '' }} {{ Request::is('edit-categories/*') ? 'active' : '' }} {{ Request::is('view-categories/*') ? 'active' : '' }}">
				<a href="{!! url(route('categories-list')) !!}">
					<i class="fa fa-calendar"></i> <span>Categories Management</span>
				</a>
			</li>
			@endif

			@if(config("roles.".Session::get('user_role').".sub_categories_management"))
			<li class="{{ Request::is('sub-categories-list') ? 'active' : '' }} {{ Request::is('create-sub-categories') ? 'active' : '' }} {{ Request::is('edit-sub-categories/*') ? 'active' : '' }} {{ Request::is('view-sub-categories/*') ? 'active' : '' }}">
				<a href="{!! url(route('sub-categories-list')) !!}">
					<i class="fa fa-calendar"></i> <span>Sub Categories Management</span>
				</a>
			</li>
			@endif

			@if(config("roles.".Session::get('user_role').".user_management"))
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
        	<li class="active treeview-nav treeview menu-open">
				<a href="#">
				    <i class="fa fa-universal-access"></i> <span>Master Settings</span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">
				    @if(config("roles.".Session::get('user_role').".roles_management"))
						<li class="{{ Request::is('roles-list') ? 'active' : '' }} {{ Request::is('create-roles') ? 'active' : '' }} {{ Request::is('edit-roles/*') ? 'active' : '' }} {{ Request::is('view-roles/*') ? 'active' : '' }}">
						<a href="{!! url(route('roles-list')) !!}">
							<i class="fa fa-circle-o"></i> <span>Roles Management</span>
						</a>
						</li>
					@endif
	          		@if(config("roles.".Session::get('user_role').".clients_management"))
						<li class="{{ Request::is('clients-list') ? 'active' : '' }} {{ Request::is('create-clients') ? 'active' : '' }} {{ Request::is('edit-clients/*') ? 'active' : '' }} {{ Request::is('view-clients/*') ? 'active' : '' }}">
						<a href="{!! url(route('clients-list')) !!}">
							<i class="fa fa-circle-o"></i> <span>Clients Management</span>
						</a>
					</li>
					@endif
			    </ul>
			</li>
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