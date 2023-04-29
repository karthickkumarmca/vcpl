<style type="text/css">
/*.skin-red .sidebar-menu>li>.treeview-menu {
    margin: 0 1px;
    background: #fff;
    background-color:#fff
}*/
</style>
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
			<li class="treeview-nav treeview @if(str_contains(url()->current(),'master/')) active @endif">
				<a href="#">
				    <span>MASTERS</span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">
			<li class="treeview-nav treeview">
				<a href="#">
				    <span>STAFF</span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">

					@if(config("roles.".Session::get('user_role').".staffgroups_management"))
						<li class="{{ Request::is('staffgroups-list') ? 'active' : '' }} {{ Request::is('create-staffgroups') ? 'active' : '' }} {{ Request::is('edit-staffgroups/*') ? 'active' : '' }} {{ Request::is('view-staffgroups/*') ? 'active' : '' }}">
						<a href="{!! url(route('staffgroups-list')) !!}">
							<span>STAFF GROUP</span>
						</a>
						</li>
					@endif
					@if(config("roles.".Session::get('user_role').".staff_details_management"))
					<li class="{{ Request::is('staff-details-list') ? 'active' : '' }} {{ Request::is('create-staff-details') ? 'active' : '' }} {{ Request::is('edit-staff-details/*') ? 'active' : '' }} {{ Request::is('view-staff-details/*') ? 'active' : '' }}">
						<a href="{!! url(route('staff-details-list')) !!}">
							<span>STAFF DETAILS</span>
						</a>
						</li>
					@endif
			    </ul>
			</li>
			<li class="treeview-nav treeview 
			@if(str_contains(url()->current(),'client-info-list')) active @endif
			@if(str_contains(url()->current(),'client-site')) active @endif
			" >
				<a href="#">
				     <span>SITE </span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">

					<li class="">
						<a href="javascript::">
							<span>SITE INFO</span>
						</a>
					</li>
					

					@if(config("roles.".Session::get('user_role').".client_site_management"))
					<li class="{{ Request::is('master/client-site/*') ? 'active' : '' }}">
						<a href="{!! url(route('client-info-list')) !!}">
							<span>CLIENT INFO </span>
						</a>
					</li>
					@endif

					@if(config("roles.".Session::get('user_role').".architect_site_management"))
					<li class="{{ Request::is('master/architect-site/*') ? 'active' : '' }}">
						<a href="{!! url(route('architect-site-list')) !!}">
							<span>ARCHITECT INFO </span>
						</a>
					</li>
					@endif
					

			    </ul>


			</li>

			@if(config("roles.".Session::get('user_role').".units_management"))
			<li class="{{ Request::is('units-list') ? 'active' : '' }} {{ Request::is('create-units') ? 'active' : '' }} {{ Request::is('edit-hallmark/*') ? 'active' : '' }} {{ Request::is('view-hallmark/*') ? 'active' : '' }}">
				<a href="{!! url(route('units-list')) !!}">
					<span>UNITS MANAGEMENT</span>
				</a>
			</li>
			@endif

			

			@if(config("roles.".Session::get('user_role').".roles_management"))
				<li class="{{ Request::is('roles-list') ? 'active' : '' }} {{ Request::is('create-roles') ? 'active' : '' }} {{ Request::is('edit-roles/*') ? 'active' : '' }} {{ Request::is('view-roles/*') ? 'active' : '' }}">
				<a href="{!! url(route('roles-list')) !!}">
					<span>ROLES MANAGEMENT</span>
				</a>
				</li>
			@endif

      		@if(config("roles.".Session::get('user_role').".clients_management"))
				<li class="{{ Request::is('clients-list') ? 'active' : '' }} {{ Request::is('create-clients') ? 'active' : '' }} {{ Request::is('edit-clients/*') ? 'active' : '' }} {{ Request::is('view-clients/*') ? 'active' : '' }}">
				<a href="{!! url(route('clients-list')) !!}">
					<span>CLIENTS MANAGEMENT</span>
				</a>
			</li>
			@endif
        	<!--  <li class="treeview-nav treeview  @if(str_contains(url()->current(),'sub-categories')) active @endif   @if(str_contains(url()->current(),'product-details')) active @endif">

				<a href="#">
				    <span>MASTER SETTINGS</span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">
					
				    @if(config("roles.".Session::get('user_role').".roles_management"))
						<li class="{{ Request::is('roles-list') ? 'active' : '' }} {{ Request::is('create-roles') ? 'active' : '' }} {{ Request::is('edit-roles/*') ? 'active' : '' }} {{ Request::is('view-roles/*') ? 'active' : '' }}">
						<a href="{!! url(route('roles-list')) !!}">
							<span>ROLES MANAGEMENT</span>
						</a>
						</li>
					@endif
	          		@if(config("roles.".Session::get('user_role').".clients_management"))
						<li class="{{ Request::is('clients-list') ? 'active' : '' }} {{ Request::is('create-clients') ? 'active' : '' }} {{ Request::is('edit-clients/*') ? 'active' : '' }} {{ Request::is('view-clients/*') ? 'active' : '' }}">
						<a href="{!! url(route('clients-list')) !!}">
							<span>CLIENTS MANAGEMENT</span>
						</a>
					</li>
					@endif

			    </ul>


			</li>  -->
			<li class="treeview-nav treeview  
			@if(str_contains(url()->current(),'categories/list')) active @endif
			@if(str_contains(url()->current(),'categories/edit')) active @endif
			@if(str_contains(url()->current(),'categories/view')) active @endif
			   @if(str_contains(url()->current(),'sub-categories')) active @endif   @if(str_contains(url()->current(),'product-details')) active @endif">
				<a href="#">
				    <span>PRODUCT SETTINGS</span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">
					@if(config("roles.".Session::get('user_role').".categories_management"))
					<li class="{{ Request::is('master/categories/*') ? 'active' : '' }}">
						<a href="{!! url(route('categories-list')) !!}">
							<span>CATEGORIES</span>
						</a>
					</li>
					@endif

					@if(config("roles.".Session::get('user_role').".sub_categories_management"))
					<li class="{{ Request::is('master/sub-categories/*') ? 'active' : '' }}">
						<a href="{!! url(route('sub-categories-list')) !!}">
							<span>SUB CATEGORIES</span>
						</a>
					</li>
					@endif

					@if(config("roles.".Session::get('user_role').".product_details_management"))
					<li class="{{ Request::is('master/product-details-list/*') ? 'active' : '' }}">
						<a href="{!! url(route('product-details-list')) !!}">
							<span>PRODUCT DETAILS</span>
						</a>
					</li>
					@endif
					
					<!-- <li class="">
						<a href="javascript::">
							<span>RENTAL RATE/ NAME CHANGE</span>
						</a>
					</li> -->


			    </ul>


			</li>

			<li class="treeview-nav treeview @if(str_contains(url()->current(),'labour-categories')) active @endif">
				<a href="#">
				    <span>LABOURS</span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">

					@if(config("roles.".Session::get('user_role').".labour_categories_management"))
					<li class="{{ Request::is('master/labour-categories/*') ? 'active' : '' }}">
						<a href="{!! url(route('labour-categories-list')) !!}">
							<span>CATEGORIES</span>
						</a>
					</li>
					@endif
					<li class="">
						<a href="javascript::">
							 <span>LABOUR DAILY WAGES</span>
						</a>
					</li>
			    </ul>
			</li>

			<li class="treeview-nav treeview  
			@if(str_contains(url()->current(),'centering-materials')) active @endif
			@if(str_contains(url()->current(),'lorry-materials')) active @endif
			@if(str_contains(url()->current(),'shop-materials')) active @endif
			@if(str_contains(url()->current(),'toolsplants-materials')) active @endif
			@if(str_contains(url()->current(),'vehicle-materials')) active @endif
			">
				<a href="#">
				    <span>MATERIALS</span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">
					@if(config("roles.".Session::get('user_role').".centering_materials_management"))
					<li class="{{ Request::is('master/centering-materials/*') ? 'active' : '' }}">
						<a href="{!! url(route('centering-materials-list')) !!}">
							<span>CENTERING MATERIALS</span>
						</a>
					</li>
					@endif

					@if(config("roles.".Session::get('user_role').".lorry_materials_management"))
					<li class="{{ Request::is('master/lorry-materials/*') ? 'active' : '' }}">
						<a href="{!! url(route('lorry-materials-list')) !!}">
							<span>LORRY MATERIALS</span>
						</a>
					</li>
					@endif
					
					@if(config("roles.".Session::get('user_role').".shop_materials_management"))
					<li class="{{ Request::is('master/shop-materials/*') ? 'active' : '' }}">
						<a href="{!! url(route('shop-materials-list')) !!}">
							<span>SHOP MATERIALS</span>
						</a>
					</li>
					@endif

					@if(config("roles.".Session::get('user_role').".toolsplants_materials_management"))
					<li class="{{ Request::is('master/toolsplants-materials/*') ? 'active' : '' }}">
						<a href="{!! url(route('toolsplants-materials-list')) !!}">
							<span>TOOLS AND PLANTS</span>
						</a>
					</li>
					@endif

					@if(config("roles.".Session::get('user_role').".vehicle_materials_management"))
					<li class="{{ Request::is('master/vehicle-materials/*') ? 'active' : '' }}">
						<a href="{!! url(route('vehicle-materials-list')) !!}">
							<span>VEHICLE</span>
						</a>
					</li>
					@endif
					
				

			    </ul>


			</li>

			<li class="treeview-nav treeview  
				@if(str_contains(url()->current(),'property-categories')) active @endif 
				@if(str_contains(url()->current(),'ownership')) active @endif 
				@if(str_contains(url()->current(),'property-name')) active @endif 

				">
				<a href="#">
				    <span>PROPERTY</span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">

					@if(config("roles.".Session::get('user_role').".ownership_management"))
					<li class="{{ Request::is('master/ownership/*') ? 'active' : '' }}">
						<a href="{!! url(route('ownership-list')) !!}">
							<span>OWNERSHIP</span>
						</a>
					</li>
					@endif
					@if(config("roles.".Session::get('user_role').".property_categories_management"))
					<li class="{{ Request::is('master/property-categories/*') ? 'active' : '' }}">
						<a href="{!! url(route('property-categories-list')) !!}">
							<span>CATEGORIES</span>
						</a>
					</li>
					@endif

					@if(config("roles.".Session::get('user_role').".property_name_management"))
					<li class="{{ Request::is('master/property-name/*') ? 'active' : '' }}">
						<a href="{!! url(route('property-name-list')) !!}">
							<span>PROPERTY NAME</span>
						</a>
					</li>
					@endif

			    </ul>


			</li>
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