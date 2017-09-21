<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Changarro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="/css/login.css">
  </head>
  <body>
    <div id="app"></div>
    <div class="login-wrapper columns">
      <div class="column is-8 is-hidden-mobile hero-banner">
        <section class="hero is-fullheight is-dark">
          <div class="hero-body">
            <div class="container section">
              <div class="has-text-right">
              </div>
            </div>
          </div>
          <div class="hero-footer">
          </div>
        </section>
      </div>
      <div class="column is-4">
        <section class="hero is-fullheight" style="padding-top: 70px">
            <div class="columns">
                <div class="column is-4 is-offset-4"><img src="/img/logo.png">
                </div>
            </div>
          <div class="hero-body">
            <div class="container">
              <div class="columns">
                @if (old('name'))
                  <div  id="login" style="display: none; -webkit-animation-duration: .70s;">
                    @include('auth.loginForm')
                  </div>
                  <div id="register" style="-webkit-animation-duration: .65s;">
                    @include('auth.registerForm')
                  </div>
                @else
                  <div  id="login" style="-webkit-animation-duration: .70s;">
                    @include('auth.loginForm')
                  </div>
                  <div id="register" style="display: none; -webkit-animation-duration: .65s;">
                    @include('auth.registerForm')
                  </div>
                @endif
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
      $('#register_button').click(function(event) {
        $( "#login" ).animateCss('bounceOutLeft').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
          $('#login').hide();
          $('#register').show().animateCss('bounceInRight');
        });
      });
      $('#login_button').click(function(event) {
        $( "#register" ).animateCss('bounceOutLeft').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
          $('#register').hide();
          $('#login').show().animateCss('bounceInRight');
        });
      });
    </script>
  </body>
</html>