<x-guest-layout>
<h2 class="pc-auth-title">Recuperar contraseña</h2>
@if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
<form method="POST" action="{{ route('password.email') }}">
@csrf
<p class="text-secondary">Ingresa tu correo para recibir un enlace de recuperación.</p><div class="mb-3"><label for="email" class="form-label">Correo electrónico</label><input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" autocomplete="username" required autofocus>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<button class="btn btn-pc w-100" type="submit">Enviar enlace</button>
</form>
<a class="pc-auth-link" href="{{ route('login') }}">Volver al inicio de sesión</a>
</x-guest-layout>