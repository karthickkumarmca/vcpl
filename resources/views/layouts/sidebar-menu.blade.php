<?php 
$rolesAccess = Session::get('role_access');
?>
<aside class="main-sidebar elevation-4 sidebar-dark-success" style="padding-top: 50px;">
	<section class="sidebar">
		<ul class="sidebar-menu" data-widget="tree">
			
			<li class="{{ Request::is('dashboard') ? 'active' : '' }}">
				<a href="{!! url('dashboard') !!}">
					<i class="fa fa-dashboard"></i> <span>Dashboard </span>
				</a>
			</li>
			<li class="treeview-nav treeview @if(str_contains(url()->current(),'master/') || Request::is('roles-list') || Request::is('create-roles') || str_contains(url()->current(),'edit-roles') || str_contains(url()->current(),'view-roles') || Request::is('units-list') || str_contains(url()->current(),'view-units') || str_contains(url()->current(),'edit-units') || Request::is('create-units') || str_contains(url()->current(),'change-staff-password/') ) active @endif">
				<a href="#">
				    <span>MASTERS</span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">

			@if($rolesAccess['roles_management']==1)
				<li class="{{ Request::is('roles-list') ? 'active' : '' }} {{ Request::is('create-roles') ? 'active' : '' }} {{ Request::is('edit-roles/*') ? 'active' : '' }} {{ Request::is('view-roles/*') ? 'active' : '' }}">
				<a href="{!! url(route('roles-list')) !!}">
					<span>ROLES MANAGEMENT</span>
				</a>
				</li>
			@endif

			@if($rolesAccess['staffgroups_management']==1 
			|| $rolesAccess['staff_details_management']==1
			)
			<li class="treeview-nav treeview @if(str_contains(url()->current(),'master/staffgroups') || str_contains(url()->current(),'master/staff-details') || str_contains(url()->current(),'change-staff-password/') ) active @endif ">
				<a href="#">
				    <span>STAFF</span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">

					@if($rolesAccess['staffgroups_management']==1)
						<li class="{{ Request::is('master/staffgroups/list') ? 'active' : '' }} {{ Request::is('master/staffgroups/create') ? 'active' : '' }} {{ Request::is('master/staffgroups/edit/*') ? 'active' : '' }} {{ Request::is('master/staffgroups/view/*') ? 'active' : '' }}">
						<a href="{!! url(route('staffgroups-list')) !!}">
							<span>STAFF GROUP</span>
						</a>
						</li>
					@endif
					@if($rolesAccess['staff_details_management']==1)
					<li class="{{ Request::is('master/staff-details/list') ? 'active' : '' }} {{ Request::is('master/staff-details/create') ? 'active' : '' }} {{ Request::is('master/staff-details/edit/*') ? 'active' : '' }} {{ Request::is('staff-details.view/*') ? 'active' : '' }} {{ Request::is('change-staff-password/*') ? 'active' : '' }}">
						<a href="{!! url('master/staff-details/list') !!}">
							<span>STAFF DETAILS</span>
						</a>
						</li>
					@endif
			    </ul>
			</li>
			@endif

			@if($rolesAccess['site_info_management']==1 
			|| $rolesAccess['client_site_management']==1
			|| $rolesAccess['architect_site_management']==1
			)
			<li class="treeview-nav treeview 
			@if(str_contains(url()->current(),'architect-site')) active @endif
			@if(str_contains(url()->current(),'site-info')) active @endif
			@if(str_contains(url()->current(),'client-site')) active @endif
			" >
				<a href="#">
				     <span>SITE </span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">


					@if($rolesAccess['site_info_management']==1)
					<li class="{{ Request::is('master/site-info/*') ? 'active' : '' }}">
						<a href="{!! url(route('site-info-list')) !!}">
							<span>SITE INFO</span>
						</a>
					</li>
					@endif

					@if($rolesAccess['client_site_management']==1)
					<li class="{{ Request::is('master/client-site/*') ? 'active' : '' }}">
						<a href="{!! url(route('client-info-list')) !!}">
							<span>CLIENT INFO </span>
						</a>
					</li>
					@endif

					@if($rolesAccess['architect_site_management']==1)
					<li class="{{ Request::is('master/architect-site/*') ? 'active' : '' }}">
						<a href="{!! url(route('architect-site-list')) !!}">
							<span>ARCHITECT INFO </span>
						</a>
					</li>
					@endif
					

			    </ul>


			</li>
			@endif

			@if($rolesAccess['units_management']==1)
			<li class="{{ Request::is('units-list') ? 'active' : '' }} {{ Request::is('create-units') ? 'active' : '' }} {{ Request::is('edit-units/*') ? 'active' : '' }} {{ Request::is('view-units/*') ? 'active' : '' }}">
				<a href="{!! url(route('units-list')) !!}">
					<span>UNITS MANAGEMENT</span>
				</a>
			</li>
			@endif

			
        	@if($rolesAccess['categories_management']==1 
			|| $rolesAccess['sub_categories_management']==1
			|| $rolesAccess['product_details_management']==1
			)
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
					@if($rolesAccess['categories_management']==1)
					<li class="{{ Request::is('master/categories/*') ? 'active' : '' }}">
						<a href="{!! url(route('categories-list')) !!}">
							<span>CATEGORIES</span>
						</a>
					</li>
					@endif

					@if($rolesAccess['sub_categories_management']==1)
					<li class="{{ Request::is('master/sub-categories/*') ? 'active' : '' }}">
						<a href="{!! url(route('sub-categories-list')) !!}">
							<span>SUB CATEGORIES</span>
						</a>
					</li>
					@endif

					@if($rolesAccess['product_details_management']==1)
					<li class="{{ Request::is('master/product-details/*') ? 'active' : '' }}">
						<a href="{!! url(route('product-details-list')) !!}">
							<span>PRODUCT DETAILS</span>
						</a>
					</li>
					@endif
					
			    </ul>


			</li>
			@endif

			@if($rolesAccess['labour_categories_management']==1 || $rolesAccess['labour_wages_management']==1)
			<li class="treeview-nav treeview 
			@if(str_contains(url()->current(),'labour-categories')) active @endif
			@if(str_contains(url()->current(),'labour-wages')) active @endif">
				<a href="#">
				    <span>LABOURS</span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">

					@if($rolesAccess['labour_categories_management']==1)
					<li class="{{ Request::is('master/labour-categories/*') ? 'active' : '' }}">
						<a href="{!! url(route('labour-categories-list')) !!}">
							<span>CATEGORIES</span>
						</a>
					</li>
					@endif
					@if($rolesAccess['labour_wages_management']==1)
					<li class="{{ Request::is('master/labour-wages/*') ? 'active' : '' }}">
						<a href="{!! url(route('labour-wages-list')) !!}">
							<span>LABOUR DAILY WAGES </span>
						</a>
					</li>
					@endif
					
			    </ul>
			</li>
			@endif

			@if($rolesAccess['centering_materials_management']==1 
			|| $rolesAccess['lorry_materials_management']==1
			|| $rolesAccess['shop_materials_management']==1
			|| $rolesAccess['toolsplants_materials_management']==1
			|| $rolesAccess['vehicle_materials_management']==1
			)

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
					@if($rolesAccess['centering_materials_management']==1)
					<li class="{{ Request::is('master/centering-materials/*') ? 'active' : '' }}">
						<a href="{!! url(route('centering-materials-list')) !!}">
							<span>CENTERING MATERIALS</span>
						</a>
					</li>
					@endif

					@if($rolesAccess['lorry_materials_management']==1)
					<li class="{{ Request::is('master/lorry-materials/*') ? 'active' : '' }}">
						<a href="{!! url(route('lorry-materials-list')) !!}">
							<span>LORRY MATERIALS</span>
						</a>
					</li>
					@endif
					
					@if($rolesAccess['shop_materials_management']==1)
					<li class="{{ Request::is('master/shop-materials/*') ? 'active' : '' }}">
						<a href="{!! url(route('shop-materials-list')) !!}">
							<span>SHOP MATERIALS</span>
						</a>
					</li>
					@endif

					@if($rolesAccess['toolsplants_materials_management']==1)
					<li class="{{ Request::is('master/toolsplants-materials/*') ? 'active' : '' }}">
						<a href="{!! url(route('toolsplants-materials-list')) !!}">
							<span>TOOLS AND PLANTS</span>
						</a>
					</li>
					@endif

					@if($rolesAccess['vehicle_materials_management']==1)
					<li class="{{ Request::is('master/vehicle-materials/*') ? 'active' : '' }}">
						<a href="{!! url(route('vehicle-materials-list')) !!}">
							<span>VEHICLE</span>
						</a>
					</li>
					@endif
					
				

			    </ul>


			</li>

			@endif

			@if($rolesAccess['ownership_management']==1 
			|| $rolesAccess['property_categories_management']==1
			|| $rolesAccess['property_name_management']==1
			)

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

					@if($rolesAccess['ownership_management']==1)
					<li class="{{ Request::is('master/ownership/*') ? 'active' : '' }}">
						<a href="{!! url(route('ownership-list')) !!}">
							<span>OWNERSHIP</span>
						</a>
					</li>
					@endif
					@if($rolesAccess['property_categories_management']==1)
					<li class="{{ Request::is('master/property-categories/*') ? 'active' : '' }}">
						<a href="{!! url(route('property-categories-list')) !!}">
							<span>CATEGORIES</span>
						</a>
					</li>
					@endif

					@if($rolesAccess['property_name_management']==1)
					<li class="{{ Request::is('master/property-name/*') ? 'active' : '' }}">
						<a href="{!! url(route('property-name-list')) !!}">
							<span>PROPERTY NAME</span>
						</a>
					</li>
					@endif

			    </ul>


			</li>
			@endif


			@if($rolesAccess['product_rental_management']==1 
			)

			<li class="treeview-nav treeview  
				@if(str_contains(url()->current(),'product-rental')) active @endif 

				">
				<a href="#">
				    <span>RENTAL RATE</span>
				    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
				</a>
				<ul class="treeview-menu">

					@if($rolesAccess['ownership_management']==1)
					<li class="{{ Request::is('master/product-rental/*') ? 'active' : '' }}">
						<a href="{!! url(route('product-rental-list')) !!}">
							<span>PRODUCT RENTAL</span>
						</a>
					</li>
					@endif
					

			    </ul>


			</li>
			@endif


			 </ul>


			</li>
			@include('layouts.sidebar-menu-transactions')
			
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