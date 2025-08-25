@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">✏️ Editar Perfil</h5>
                </div>

                <div class="card-body">
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success">
                            Perfil actualizado correctamente ✅
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nombre</label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Correo electrónico</label>
                            <input type="email" name="email" id="email" class="form-control"
                                   value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Guardar cambios</button>
                            <a href="{{ route('profile') }}" class="btn btn-secondary ms-2">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection