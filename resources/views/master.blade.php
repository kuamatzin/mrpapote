<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Mr. Papote</title>
        <meta name="google-site-verification" content="-OXRYs7Zlt6Gr-P_brFm43ZLazAt1wz_TTZ_gqBjtM8" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- Scripts -->
        <script>
            window.App = {!! json_encode([
                'user' => Auth::user(),
                'signedIn' => Auth::check(),
                'stripeKey' => config('services.stripe.key')
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
                        <a href="/mysales" class="item {{$active == 'orders' ? 'active' : '' }}"><span class="icon"><i class="fa fa-list-ol"></i></span><span class="name">Ordenes</span></a>
                        <a href="/admin" class="item {{$active == 'admin' ? 'active' : '' }}">
                            <span class="icon"><i class="fa fa-user-circle-o"></i></span>
                            <span class="name">Admin</span>
                        </a>
                        <a href="/expenses" class="item {{$active == 'expenses' ? 'active' : '' }}">
                            <span class="icon"><i class="fa fa-money"></i></span>
                            <span class="name">Gastos</span>
                        </a>
                        <a href="/statistics" class="item {{$active == 'stats' ? 'active' : '' }}">
                            <span class="icon"><i class="fa fa-line-chart"></i></span>
                            <span class="name">Estadísticas</span>
                        </a>
                        <a href="/account" class="item {{$active == 'account' ? 'active' : '' }}">
                            <span class="icon"><i class="fa fa-user"></i></span>
                            <span class="name">Cuenta</span>
                        </a>
                        <a href="/subscription" class="item {{$active == 'subscription' ? 'active' : '' }}">
                            <span class="icon"><i class="fa-dot-circle-o"></i></span>
                            <span class="name">Subscripción</span>
                        </a>
                        <a href="/invoices" class="item {{$active == 'subscription' ? 'active' : '' }}">
                            <span class="icon"><i class="fa fa-file-text-o"></i></span>
                            <span class="name">Comprobantes</span>
                        </a>
                        <a href="/card" class="item {{$active == 'card' ? 'active' : '' }}">
                            <span class="icon"><i class="fa fa-credit-card"></i></span>
                            <span class="name">Método de Pago</span>
                        </a>
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
        <script src="https://checkout.stripe.com/checkout.js"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        @yield('scripts')
    </body>
</html>