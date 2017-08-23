<div class="column is-8 is-offset-2">
  <div class="login-form">
    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
      {{ csrf_field() }}
      <p class="control has-icon has-icon-right">
        <input class="input email-input" type="email" name="email" placeholder="carlos@gmail.com" required autofocus value="{{ old('email') }}">
        <span class="icon user">
          <i class="fa fa-user"></i>
        </span>
        @if ($errors->has('email'))
            <span class="help is-danger" v-if="errors.get('name')">{{ $errors->first('email') }}</span>
        @endif
      </p>
      <br>
      <p class="control has-icon has-icon-right">
        <input class="input password-input" type="password" name="password" placeholder="contraseña" required>
        <span class="icon user">
          <i class="fa fa-lock"></i>
        </span>
        @if ($errors->has('password'))
          <span class="help is-danger" v-if="errors.get('name')">{{ $errors->first('password') }}</span>
        @endif
      </p>
      <p class="control login">
        <button type="submit" class="button is-success is-outlined is-large is-fullwidth">Iniciar Sesión</button>
      </p>
    </form>
    <p class="control login">
      <a  href="/redirect?provider=facebook">
        <button class="button is-info is-outlined is-large is-fullwidth">Iniciar Sesión con&nbsp;<i class="fa fa-facebook" aria-hidden="true"></i></button>
      </a>
    </p>
    <p class="control login">
      <a href="/redirect?provider=google">
        <button class="button is-danger is-outlined is-large is-fullwidth">Iniciar Sesión con&nbsp;<i class="fa fa-google" aria-hidden="true"></i></button>
      </a>
    </p>
  </div>
  <div class="section forgot-password">
    <p class="has-text-centered">
      <a id="register_button">No tengo cuenta. <span>Registrarse</span></a>
      <a href="#">Olvidé mi contraseña</a>
      <a href="#">¿Ayuda?</a>
    </p>
  </div>
</div>