<x-guest-layout>
<h2 class="pc-auth-title">Confirmar contraseña</h2>
@if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
<form method="POST" action="{{ route('password.confirm') }}">
@csrf
<p class="text-secondary">Confirma tu contraseña para continuar.</p><div class="mb-3"><label for="password" class="form-label">Contraseña</label><input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror"  autocomplete="current-password" required >@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<button class="btn btn-pc w-100" type="submit">Confirmar</button>
</form>

</x-guest-layout>