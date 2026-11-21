
@extends('layouts.app')

@section('title', 'Editar diagnóstico')

@section('content')

<h3 class="fw-bold mb-4">Editar diagnóstico</h3>

<form method="POST"
      action="{{ route('diagnosticos.update', $diagnostico) }}"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    @include('diagnosticos.form')

</form>

@endsection
