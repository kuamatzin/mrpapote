<div class="column is-8 is-offset-2">
    <div class="login-form">
        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <p class="control has-icon has-icon-right">
                <input class="input email-input" type="email" name="email" placeholder="carlos@gmail.com" required autofocus value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <span class="help is-danger" v-if="errors.get('name')">{{ $errors->first('email') }}</span>
                @endif
            </p>
            <br>
            <p class="control has-icon has-icon-right">
                <input class="input password-input" type="password" name="password" placeholder="contraseña" required>
                @if ($errors->has('password'))
                    <span class="help is-danger" v-if="errors.get('name')">{{ $errors->first('password') }}</span>
                @endif
            </p>
            <br>
            <button type="submit" class="button is-success is-outlined is-medium is-fullwidth">Iniciar Sesión</button>
        </form>
        <br>
        <a  href="/redirect?provider=facebook">
            <button class="button is-info is-outlined is-medium is-fullwidth">Iniciar Sesión con&nbsp;<i class="fa fa-facebook" aria-hidden="true"></i></button>
        </a>
        <br>
        <a href="/redirect?provider=google">
            <button class="button is-danger is-outlined is-medium is-fullwidth">Iniciar Sesión con&nbsp;<i class="fa fa-google" aria-hidden="true"></i></button>
        </a>
    </div>
    <div class="section forgot-password">
        <p class="has-text-centered">
            <a id="register_button">No tengo cuenta. <span>Registrarse</span></a>
            <a href="#">Olvidé mi contraseña</a>
            <a href="#">¿Ayuda?</a>
        </p>
    </div>
</div>