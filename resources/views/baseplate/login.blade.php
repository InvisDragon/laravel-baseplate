@extends('baseplate::baseplate.layout')

@section('body')
    <div class="bg-row">
        <div class="login-panel">
            <div class="login-content-panel">
                <h1 class="text-center">
                    @if(config('baseplate.logo_image'))
                        <img class="login-logo" src="{{ config('baseplate.logo_image') }}" alt="{{ config('app.name') }}" title="{{ config('app.name') }}" />
                    @else
                        {{ config('app.name') }}
                    @endif
                    <span class="small">{{ config('baseplate.login_intro') ?? __('Log in to') }}</span>
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
                <form class="challenge_form" method="post">
                    @csrf
                    <label for="email">{{ __('Email Address') }}</label>
                    <input type="text" id="email" name="email" autocomplete="username webauthn" />

                    <input type="hidden" id="auth_method" name="auth_method" value="password" />
                    @foreach(InvisibleDragon\LaravelBaseplate\Auth\AuthMethod::get_methods() as $method)
                        {!! $method::get_auth_html() !!}
                    @endforeach

                    {!! InvisibleDragon\LaravelBaseplate\Challenge\ChallengeMethod::get_method()::output_scripts() !!}
                    <button class="primary">{{ __('Login') }}</button>

                    @if(config('baseplate.allow_forgot_password'))
                        <a href="{{ route('password.request') }}" class="forgot-password bottom-link">
                            {{ __('Forgot Password')  }}
                        </a>
                    @endif

                    @if(config('baseplate.login_register_link'))
                        <a href="{{ config('baseplate.login_register_link') }}" class="bottom-link">
                            {{ config('baseplate.login_register_link_text') }}
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
