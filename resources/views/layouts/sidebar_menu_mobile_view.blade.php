
<li class="{{ Request::is('appview/cement-movement/*') ? 'active' : '' }}">
	<a href="{!! url(route('create-cement-movement')) !!}">
		<i class="fa fa-building"></i> <span>CEMENT MOVEMENT </span>
	</a>
</li>

<li class="{{ Request::is('appview/labour-movement/*') ? 'active' : '' }}">
	<a href="{!! url(route('create-labour-movement')) !!}">
		<i class="fa fa-building-o"></i> <span>LABOURS</span>
	</a>
</li>

<li class="{{ Request::is('appview/centering-movement/*') ? 'active' : '' }}">
	<a href="{!! url(route('create-centering-movement')) !!}">
		<i class="fa fa-hospital-o"></i> <span>CENTERING MATERIALS </span>
	</a>
</li>

<li class="{{ Request::is('appview/tools-movement/*') ? 'active' : '' }}">
	<a href="{!! url(route('create-tools-movement')) !!}">
		<i class="fa fa-bolt"></i> <span>TOOLS AND PLANTS </span>
	</a>
</li>
<li class="{{ Request::is('appview/workout-movement/*') ? 'active' : '' }}">
	<a href="{!! url(route('create-workout-movement')) !!}">
		<i class="fa fa-id-card-o"></i> <span>WORK OUT TURN AT SITE </span>
	</a>
</li>
<li class="{{ Request::is('appview/lorry-movement/*') ? 'active' : '' }}">
	<a href="{!! url(route('create-lorry-movement')) !!}">
		<i class="fa fa-truck"></i> <span>LORRY MOVEMENT </span>
	</a>
</li>
<li class="{{ Request::is('appview/shop-movement/*') ? 'active' : '' }}">
	<a href="{!! url(route('create-shop-movement')) !!}">
		<i class="fa fa-shopping-basket"></i> <span>SHOP MOVEMENT </span>
	</a>
</li>
<li class="{{ Request::is('appview/task-movement/*') ? 'active' : '' }}">
	<a href="{!! url(route('create-task-movement')) !!}">
		<i class="fa fa-tasks"></i> <span>ASSIGNED TASK LIST </span>
	</a>
</li>