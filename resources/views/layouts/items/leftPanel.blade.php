<aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="./"><img src="images/logo.png" alt="Logo"></a>
                <a class="navbar-brand hidden" href="./"><img src="images/logo2.png" alt="Logo"></a>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                   
                    @forelse($dm['data_menu'] as $menu)
                         <li class="active">
                            <a class="btn-link" href="{{$menu->data_menu['link']}}"><i class="menu-icon {{$menu->data_menu['icon']}}"></i> {{$menu->data_menu['name']}}</a>
                         </li>
                        @empty
                        <li>
                            Sin Accessos
                        </li>
                        @endforelse
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>