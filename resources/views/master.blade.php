<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Mr. Papote</title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        @include('nav')
        <div class="columns">
            <aside class="column is-2 aside hero is-fullheight is-hidden-mobile">
                <div>
                    <div class="main">
                        <div class="title">Main</div>
                        <a href="/" class="item {{$active == 'orders' ? 'active' : '' }}"><span class="icon"><i class="fa fa-home"></i></span><span class="name">Ordenes</span></a>
                        <a href="/admin" class="item {{$active == 'admin' ? 'active' : '' }}">
                            <span class="icon"><i class="fa fa-map-marker"></i></span>
                            <span class="name">Administrador</span>
                        </a>
                        <a href="#" class="item"><span class="icon"><i class="fa fa-th-list"></i></span><span class="name">Gastos</span></a>
                        <a href="#" class="item"><span class="icon"><i class="fa fa-folder-o"></i></span><span class="name">Historial</span></a>
                    </div>
                </div>
            </aside>
            <div class="content column is-10">
                <div class="columns" id="app">
                    @yield('content')
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <div class="has-text-centered">
                    <p>
                        <strong>Bulma</strong> by <a href="http://jgthms.com">Jeremy Thomas</a>. The source code is licensed
                        <a href="http://opensource.org/licenses/mit-license.php">MIT</a>. The website content
                        is licensed <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/">CC ANS 4.0</a>.
                    </p>
                    <p>
                        <a class="icon" href="https://github.com/jgthms/bulma">
                            <i class="fa fa-github"></i>
                        </a>
                    </p>
                </div>
            </div>
        </footer>
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        @yield('scripts')
    </body>
</html>