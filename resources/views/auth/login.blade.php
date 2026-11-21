<x-guest-layout>
<h2 class="pc-auth-title">Iniciar sesión</h2>
@if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
<form method="POST" action="{{ route('login') }}">
@csrf
<div class="mb-3"><label for="email" class="form-label">Correo electrónico</label><input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" autocomplete="username" required autofocus>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><div class="mb-3"><label for="password" class="form-label">Contraseña</label><input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror"  autocomplete="current-password" required >@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember" id="remember_me"><label class="form-check-label" for="remember_me">Recordarme</label></div>
<button class="btn btn-pc w-100" type="submit">Iniciar sesión</button>
</form>
@if(Route::has('password.request'))<a class="pc-auth-link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>@endif
@if(Route::has('register'))<a class="pc-auth-link" href="{{ route('register') }}">Crear cuenta</a>@endif
</x-guest-layout>