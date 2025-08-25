
@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">👤 Mi Perfil</h5>
                    <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm">Editar</a>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre</label>
                        <div class="form-control-plaintext">{{ auth()->user()->name }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Correo electrónico</label>
                        <div class="form-control-plaintext">{{ auth()->user()->email }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Fecha de registro</label>
                        <div class="form-control-plaintext">{{ auth()->user()->created_at->format('d/m/Y') }}</div>
                    </div>

                    <hr>

                    <div class="text-end">
                        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar tu cuenta?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Eliminar cuenta</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection