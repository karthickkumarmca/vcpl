<header class="main-header">
    <style>
        .skin-red .sidebar-menu>li.active>a {
            border-left-color: #28a745;
        }

        .sidebar-menu>li>a:hover {
            background-color: #0078bf !important;
        }

        .skin-red .main-header .navbar .sidebar-toggle:hover {
            background-color: #000000;
        }
    </style>

    <a href="{{url('/')}}" class="logo" style="height: 100px;padding: 0 5px!important;background-color: #fff;">
        <img src="{{asset('images/logonew.png')}}" alt="Logo" style="height:100px;max-width:100%;">
       
    </a>
    <nav class="navbar navbar-expand-md fixed-top flex-md-nowrap p-0" style="background-color: #0078bf;">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="collapse navbar-collapse">
            <div class="navbar-nav mr-auto" style="margin-right:7%">
            </div>
            <ul class="navbar-nav mt-2 mt-md-0 nav"  style="margin-right:7%">

                <li class="nav-item dropdown">
<a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Hi, Welcome , <?php echo Session::get('username')?>    </a>
<ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
<li><a href="{{ url('change-user-password') }}"  style="margin-left:7%">Change Password</a></li>
<li> <a  style="margin-left:7%" title="Logout" data-toggle="control-sidebar" href="#" onclick="event.preventDefault();
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
                     Logout   <i class="fa fa-sign-out fa-fw"></i>
                    </a></li>
</ul>
</li>
                
                <li>
                   
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