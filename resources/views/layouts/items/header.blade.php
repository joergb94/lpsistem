<header id="header" class="header">

    <div class="header-menu">
        @guest
            <div class="col-sm-7">
                <div class="header-left">
                    <h3>Logo</h3>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="btn-group float-right">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>  
                    @if (Route::has('register'))
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>    
                    @endif
                </div>
            </div>

        @else
            <div class="col-sm-7">
                <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                <div class="header-left">
                     <!--<div class="dropdown for-notification">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bell"></i>
                            <span class="count bg-danger">5</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="notification">
                            <p class="red">You have 3 Notification</p>
                            <a class="dropdown-item media bg-flat-color-1" href="#">
                            <i class="fa fa-check"></i>
                            <p>Server #1 overloaded.</p>
                        </a>
                            <a class="dropdown-item media bg-flat-color-4" href="#">
                            <i class="fa fa-info"></i>
                            <p>Server #2 overloaded.</p>
                        </a>
                            <a class="dropdown-item media bg-flat-color-5" href="#">
                            <i class="fa fa-warning"></i>
                            <p>Server #3 overloaded.</p>
                        </a>
                        </div>
                    </div>

                    <div class="dropdown for-message">
                        <button class="btn btn-secondary dropdown-toggle" type="button"
                            id="message"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti-email"></i>
                            <span class="count bg-primary">9</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="message">
                            <p class="red">You have 4 Mails</p>
                            <a class="dropdown-item media bg-flat-color-1" href="#">
                            <span class="photo media-left"><img alt="avatar" src="images/avatar/1.jpg"></span>
                            <span class="message media-body">
                                <span class="name float-left">Jonathan Smith</span>
                                <span class="time float-right">Just now</span>
                                    <p>Hello, this is an example msg</p>
                            </span>
                        </a>
                            <a class="dropdown-item media bg-flat-color-4" href="#">
                            <span class="photo media-left"><img alt="avatar" src="images/avatar/2.jpg"></span>
                            <span class="message media-body">
                                <span class="name float-left">Jack Sanders</span>
                                <span class="time float-right">5 minutes ago</span>
                                    <p>Lorem ipsum dolor sit amet, consectetur</p>
                            </span>
                        </a>
                            <a class="dropdown-item media bg-flat-color-5" href="#">
                            <span class="photo media-left"><img alt="avatar" src="images/avatar/3.jpg"></span>
                            <span class="message media-body">
                                <span class="name float-left">Cheryl Wheeler</span>
                                <span class="time float-right">10 minutes ago</span>
                                    <p>Hello, this is an example msg</p>
                            </span>
                        </a>
                            <a class="dropdown-item media bg-flat-color-3" href="#">
                            <span class="photo media-left"><img alt="avatar" src="images/avatar/4.jpg"></span>
                            <span class="message media-body">
                                <span class="name float-left">Rachel Santos</span>
                                <span class="time float-right">15 minutes ago</span>
                                    <p>Lorem ipsum dolor sit amet, consectetur</p>
                            </span>
                        </a>
                        </div>
                    </div>-->
                </div>
            </div>

            <div class="col-sm-5">
                <div class="user-area dropdown float-right">
                        <!--<a class="nav-link" href="#"><i class="fa fa-user"></i> My Profile</a>

                        <a class="nav-link" href="#"><i class="fa fa-user"></i> Notifications <span class="count">13</span></a>-->
      
                        <a class="nav-link" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            <i class="fa fa-power-off"></i> {{ __('Salir') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
        
                </div>
                <div id ="coins-user" class="user-area dropdown float-right">
                     @if($dm['type_user'] == 4)
                            <a class="nav-link {{($dm['coins']['coins'] > 0)? 'text-success' : 'text-danger' }}" href="#">
                                <i class="fa fa-money"></i> 
                                 $<span id="coins-data">{{$dm['coins']['coins']}}</span>
                            </a>
                    @endif
                </div>
            </div>          
        @endguest
    </div>

</header>