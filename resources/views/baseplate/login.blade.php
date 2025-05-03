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

                @if(Session::has('security-message'))
                    <div class="success-message">
                        {{ Session::get('security-message') }}
                    </div>
                @endif

                <x-baseplate::validation-errors class="mb-4" :errors="$errors" />
                <form method="post">
                    @csrf
                    <label for="email">{{ __('Email Address') }}</label>
                    <input type="text" name="email" autocomplete="username webauthn" />

                    <input type="hidden" id="auth_method" name="auth_method" value="password" />
                    @foreach(InvisibleDragon\LaravelBaseplate\Auth\AuthMethod::get_methods() as $method)
                        {!! $method::get_auth_html() !!}
                    @endforeach

                    <button class="primary">{{ __('Login') }}</button>

                    @if(config('baseplate.allow_forgot_password'))
                        <a href="{{ route('password.request') }}" class="forgot-password text-muted">
                            {{ __('Forgot Password')  }}
                        </a>
                    @endif
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
