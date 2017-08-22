<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Changarro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/login.css">
  </head>
  <body>
    <div class="login-wrapper columns">
      <div class="column is-8 is-hidden-mobile hero-banner">
        <section class="hero is-fullheight is-dark">
          <div class="hero-body">
            <div class="container section">
              <div class="has-text-right">
                <h1 class="title is-1">Iniciar Sesión</h1>
              </div>
            </div>
          </div>
          <div class="hero-footer">
          </div>
        </section>
      </div>
      <div class="column is-4">
        <section class="hero is-fullheight">
          <div class="hero-heading">
            <div class="section has-text-centered">
              <img src="http://bulma.io/images/bulma-logo.png" alt="Bulma logo" width="150px">
            </div>
          </div>
          <div class="hero-body">
            <div class="container">
              <div class="columns">
                <div class="column is-8 is-offset-2">
                  <div class="login-form">
                  <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                  {{ csrf_field() }}
                    <p class="control has-icon has-icon-right">
                      <input class="input email-input" type="email" name="email" placeholder="carlos@gmail.com" required autofocus value="{{ old('email') }}">
                      <span class="icon user">
                        <i class="fa fa-user"></i>
                      </span>
                    </p>
                    <br>
                    <p class="control has-icon has-icon-right">
                      <input class="input password-input" type="password" name="password" placeholder="contraseña" required>
                      <span class="icon user">
                        <i class="fa fa-lock"></i>
                      </span>
                    </p>
                    <p class="control login">
                      <button type="submit" class="button is-success is-outlined is-large is-fullwidth">Iniciar Sesión</button>
                    </p>
                    </form>
                    <p class="control login">
                      <a  href="/redirect">
                          <button class="button is-info is-outlined is-large is-fullwidth"><i class="fa fa-facebook" aria-hidden="true"></i></button>
                      </a>
                    </p>
                    <p class="control login">
                        <a href="">
                            <button class="button is-danger is-outlined is-large is-fullwidth"><i class="fa fa-google" aria-hidden="true"></i></button>
                        </a>              
                    </p>
                  </div>
                  <div class="section forgot-password">
                    <p class="has-text-centered">
                      <a href="#">Olvidé mi contraseña</a>
                      <a href="#">¿Ayuda?</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </body>
</html>