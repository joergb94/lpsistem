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
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar sesion') }}</a>  
                    @if (Route::has('register'))
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Registrarse') }}</a>    
                    @endif
                </div>
            </div>

        @else
            <div class="col-sm-7">
                <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                <div class="header-left">
                                    <a class="nav-link text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            <i class="fa fa-power-off"></i> {{ __('Salir') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                </div>
            </div>

            <div class="col-sm-5">
                <div class="user-area dropdown float-right">
        
                </div>
                <div id ="coins-user" class="user-area dropdown float-right">
                     @if($dm['type_user'] == 5)
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