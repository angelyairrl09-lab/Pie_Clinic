
@extends('layouts.app')

@section('title', 'Nuevo diagnóstico')

@section('content')

<h3 class="fw-bold mb-4">Nuevo diagnóstico</h3>

<form method="POST"
      action="{{ route('diagnosticos.store') }}"
      enctype="multipart/form-data">

    @csrf

    @include('diagnosticos.form')

</form>

@endsection
