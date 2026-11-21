<x-guest-layout>
<h2 class="pc-auth-title">Crear cuenta</h2>
@if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
<form method="POST" action="{{ route('register') }}">
@csrf
<div class="mb-3"><label for="name" class="form-label">Nombre completo</label><input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" autocomplete="name" required autofocus>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><div class="mb-3"><label for="email" class="form-label">Correo electrónico</label><input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" autocomplete="username" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><div class="mb-3"><label for="password" class="form-label">Nueva contraseña</label><input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror"  autocomplete="new-password" required >@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><div class="mb-3"><label for="password_confirmation" class="form-label">Confirmar contraseña</label><input id="password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror"  autocomplete="new-password" required >@error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<button class="btn btn-pc w-100" type="submit">Registrarse</button>
</form>
<a class="pc-auth-link" href="{{ route('login') }}">Volver al inicio de sesión</a>
</x-guest-layout>