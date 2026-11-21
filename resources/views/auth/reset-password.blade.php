<x-guest-layout>
<h2 class="pc-auth-title">Restablecer contraseña</h2>
@if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
<form method="POST" action="{{ route('password.store') }}">
@csrf
<input type="hidden" name="token" value="{{ $request->route('token') }}"><div class="mb-3"><label for="email" class="form-label">Correo electrónico</label><input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $request->email) }}" autocomplete="username" required autofocus>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><div class="mb-3"><label for="password" class="form-label">Nueva contraseña</label><input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror"  autocomplete="new-password" required >@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div><div class="mb-3"><label for="password_confirmation" class="form-label">Confirmar contraseña</label><input id="password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror"  autocomplete="new-password" required >@error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<button class="btn btn-pc w-100" type="submit">Guardar contraseña</button>
</form>

</x-guest-layout>