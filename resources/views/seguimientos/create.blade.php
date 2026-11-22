@extends('layouts.app')
@section('title', 'Nuevo seguimiento')
@section('content')
<h3 class="fw-bold mb-4">Nuevo seguimiento</h3>
<div class="card pc-card">
    <div class="card-body">
        <form method="POST" action="{{ route('seguimientos.store') }}" enctype="multipart/form-data">
            @include('seguimientos._form')

        </form></div></div>
@endsection
