@extends('layouts.admin')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="width: 100%; max-width: 500px;">
        <h4 class="mb-4 text-center">{{ __('Register') }}</h4>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <x-input-label for="name" :value="__('Name')" class="form-label" />
                <x-text-input id="name" type="text" name="name"
                    :value="old('name')" required autofocus autocomplete="name"
                    class="form-control" />
                <x-input-error :messages="$errors->get('name')" class="text-danger mt-1" />
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" class="form-label" />
                <x-text-input id="email" type="email" name="email"
                    :value="old('email')" required autocomplete="username"
                    class="form-control" />
                <x-input-error :messages="$errors->get('email')" class="text-danger mt-1" />
            </div>

            <!-- Password -->
            <div class="mb-3">
                <x-input-label for="password" :value="__('Password')" class="form-label" />
                <x-text-input id="password" type="password" name="password"
                    required autocomplete="new-password"
                    class="form-control" />
                <x-input-error :messages="$errors->get('password')" class="text-danger mt-1" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="form-label" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                    required autocomplete="new-password"
                    class="form-control" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger mt-1" />
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('login') }}" class="text-decoration-none text-primary">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="btn btn-primary">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection