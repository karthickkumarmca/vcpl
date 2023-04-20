<header class="main-header">
    <style>
        .skin-red .sidebar-menu>li.active>a {
            border-left-color: #007847;
        }

        .sidebar-menu>li>a:hover {
            background-color: #007847 !important;
        }

        .skin-red .main-header .navbar .sidebar-toggle:hover {
            background-color: #000000;
        }
    </style>

    <a href="{{url('/')}}" class="logo" style="height: 50px;padding: 0 5px!important;background-color: #fff;">
        <img src="{{asset('images/logonew.png')}}" alt="Logo" style="max-width:100%;">
       
    </a>
    <nav class="navbar navbar-expand-md fixed-top flex-md-nowrap p-0" style="background-color: #007847;">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="collapse navbar-collapse">
            <div class="navbar-nav mr-auto">
            </div>
            <ul class="navbar-nav mt-2 mt-md-0 nav">
                <!-- Notifications -->
                <!-- <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle header-dropdown-toggle top" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-danger" id="notification-count">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Notifications</li>
                        <li>
                            <ul class="menu" id="notification-list">
                            </ul>
                        </li>
                        <li class="footer"><a href="{{ url('quarantine-alerts') }}">View all</a></li>
                    </ul>
                </li> -->

                <li class="user user-menu">
                    <a href="{{ url('change-user-password') }}">Change Password</a>
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
                            document.getElementById('logout-form').submit();
                        }
                    });
                    ">
                        <i class="fa fa-sign-out fa-fw"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    {{--<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Company name</a>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="#">Sign out</a>
        </li>
    </ul>
</nav>--}}


</header>
<script src="{{ URL('js/jquery.min.js') }}"></script>