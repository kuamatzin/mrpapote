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

        <!-- Scripts -->
        <script>
            window.App = {!! json_encode([
                'user' => Auth::user(),
                'signedIn' => Auth::check()
            ]) !!};
        </script>

        @yield('head-section')
    </head>
    <body>
        @include('nav')
        <div class="columns content-admin">
            <aside class="column is-2 aside hero is-fullheight is-hidden-mobile">
                <div>
                    <div class="main">
                        <a href="/" class="item {{$active == 'orders' ? 'active' : '' }}"><span class="icon"><i class="fa fa-home"></i></span><span class="name">Ordenes</span></a>
                        <a href="/admin" class="item {{$active == 'admin' ? 'active' : '' }}">
                            <span class="icon"><i class="fa fa-map-marker"></i></span>
                            <span class="name">Administrador</span>
                        </a>
                        <a href="/expenses" class="item {{$active == 'expenses' ? 'active' : '' }}"><span class="icon"><i class="fa fa-th-list"></i></span><span class="name">Gastos</span></a>
                        <a href="/statistics?start_date=5&end_date=8" class="item {{$active == 'stats' ? 'active' : '' }}"><span class="icon"><i class="fa fa-folder-o"></i></span><span class="name">Estadísticas</span></a>
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
                        <strong>Mi Changarro</strong> by <a target="_blank" href="http://inovuz.com"> Inovuz</a>
                    </p>
                </div>
            </div>
        </footer>
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        @yield('scripts')
    </body>
</html>