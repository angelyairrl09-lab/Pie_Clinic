
@extends('layouts.app')

@section('title', 'Editar seguimiento')

@section('content')

<h3 class="fw-bold mb-4">Editar seguimiento</h3>

<div class="card pc-card">
    <div class="card-body">

        <form
            method="POST"
            action="{{ route('seguimientos.update', $seguimiento) }}"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

            @include('seguimientos._form')
        </form>

        @if($seguimiento->fotografias->isNotEmpty())

            <hr class="my-4">

            <h5 class="fw-semibold mb-3">
                Fotografías existentes
            </h5>

            <div class="row g-3">

                @foreach($seguimiento->fotografias as $foto)

                    <div class="col-6 col-md-3">

                        <img
                            class="img-fluid rounded"
                            src="{{ asset('storage/' . $foto->ruta) }}"
                            alt="Fotografía del seguimiento"
                        >

                        <form
                            method="POST"
                            action="{{ route('seguimientos.fotografias.destroy', [$seguimiento, $foto]) }}"
                            class="mt-2"
                            onsubmit="return confirm('¿Eliminar esta fotografía?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-sm btn-outline-danger"
                            >
                                Eliminar foto
                            </button>
                        </form>

                    </div>

                @endforeach

            </div>

        @endif

    </div>
</div>

@endsection
