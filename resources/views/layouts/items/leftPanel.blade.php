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
                         <li class="active" @click="menu={{$menu->data_menu['id']}}">
                            <a class="btn-link" href="#"> <p class="text-primary"><i class="menu-icon {{$menu->data_menu['icon']}}"></i> {{$menu->data_menu['name']}}</p></a>
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