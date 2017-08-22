<nav class="nav is-dark has-shadow" id="top">
    <div class="container">
        <div class="nav-left">
            <a class="nav-item" href="/">
                Mr. Papote
            </a>
        </div>
        <span class="nav-toggle">
            <span></span>
            <span></span>
            <span></span>
        </span>
        <div class="nav-right nav-menu is-hidden-tablet">
            <a class="nav-item is-tab is-active">
                Dashboard
            </a>
            <a class="nav-item is-tab">
                Activity
            </a>
            <a class="nav-item is-tab">
                Timeline
            </a>
            <a class="nav-item is-tab">
                Folders
            </a>
        </div>
        <nav class="navbar">
            <div class="navbar-menu">
                <div class="navbar-end">
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">
                            {{Auth::user()->name}}
                        </a>
                        <div class="navbar-dropdown is-right">
                            <a class="navbar-item">
                                <i class="fa fa-cog" aria-hidden="true"></i> &nbsp;&nbsp;&nbsp;&nbsp; Preferencias
                            </a>
                            <a class="navbar-item" 
                                href="{{ route('logout') }}" 
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out" aria-hidden="true"></i>  &nbsp;&nbsp;&nbsp;&nbsp; Cerrar Sesión

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</nav>