@extends('baseplate::baseplate.layout')

@section('body')
    <div class="bg-row">
        <div class="login-panel">
            <div class="login-content-panel">
                <h1 class="text-center">
                    <span class="small">{{ config('baseplate.login_intro') ?? __('Log in to') }}</span>
                    @if(config('baseplate.logo_image'))
                        <img class="login-logo" src="{{ config('baseplate.logo_image') }}" alt="{{ config('app.name') }}" title="{{ config('app.name') }}" />
                    @else
                        {{ config('app.name') }}
                    @endif
                </h1>
                @auth
                    <p>
                        {{ __('You are already logged in. You can choose to log in as a different') }}
                    </p>
                @endauth
                <x-baseplate::validation-errors class="mb-4" :errors="$errors" />
                <form method="post">
                    @csrf
                    <label for="email">{{ __('Email Address') }}</label>
                    <input type="text" name="email" />

                    <label for="password">{{ __('Password') }}</label>
                    <input type="password" name="password" />

                    <button class="primary">{{ __('Login') }}</button>
                </form>
            </div>
        </div>
        <div class="bg-panel">
            <div class="bg-content-panel">
                <!-- TOOD: Have some exciting things here -->
            </div>
        </div>
    </div>
@endsection
