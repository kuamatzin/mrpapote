<div class="column is-8 is-offset-2">
  <div class="login-form">
    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
      {{ csrf_field() }}
      <p class="control has-icon has-icon-right">
        <input class="input email-input" type="text" name="name" placeholder="Nombre" required autofocus value="{{ old('name') }}">
        <span class="icon user">
          <i class="fa fa-user"></i>
        </span>
        @if ($errors->has('name'))
            <span class="help is-danger" v-if="errors.get('name')">{{ $errors->first('name') }}</span>
        @endif
      </p>
      <br>
      <p class="control has-icon has-icon-right">
       <input id="email" type="email" class="input email-input" name="email_register" value="{{ old('email_register') }}" required placeholder="Email">
        <span class="icon user">
          <i class="fa fa-user"></i>
        </span>
        @if ($errors->has('email_register'))
            <span class="help is-danger" v-if="errors.get('name')">{{ $errors->first('email_register') }}</span>
        @endif
      </p>
      <br>
      <p class="control has-icon has-icon-right">
        <input class="input password-input" type="password" name="passwordregister" placeholder="Contraseña" required>
        <span class="icon user">
          <i class="fa fa-lock"></i>
        </span>
        @if ($errors->has('passwordregister'))
            <span class="help is-danger" v-if="errors.get('name')">{{ $errors->first('passwordregister') }}</span>
        @endif
      </p>
      <br>
      <p class="control has-icon has-icon-right">
        <input class="input password-input" type="password" name="passwordregister_confirmation" placeholder="Confirmar contraseña" required>
        <span class="icon user">
          <i class="fa fa-lock"></i>
        </span>
      </p>
      <p class="control login">
        <button type="submit" class="button is-success is-outlined is-large is-fullwidth">Registrarse</button>
      </p>
    </form>
    <p class="control login">
      <a  href="/redirect?provider=facebook">
        <button class="button is-info is-outlined is-large is-fullwidth">Registrarse con&nbsp;<i class="fa fa-facebook" aria-hidden="true"></i></button>
      </a>
    </p>
    <p class="control login">
      <a href="/redirect?provider=google">
        <button class="button is-danger is-outlined is-large is-fullwidth">Registrarse con&nbsp;<i class="fa fa-google" aria-hidden="true"></i></button>
      </a>
    </p>
  </div>
  <div class="section forgot-password">
    <p class="has-text-centered">
      <a id="login_button">Ya tengo cuenta. <span>Iniciar Sesión</span></a>
      <a href="#">Olvidé mi contraseña</a>
      <a href="#">¿Ayuda?</a>
    </p>
  </div>
</div>