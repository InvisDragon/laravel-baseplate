@extends('baseplate::baseplate.layout')

@section('body')
    <div class="security-options inner-panel">
        <h1>{{ __('Super user checkpoint') }}</h1>

        <p>
            {{ __('You need to re-authenticate yourself in order to be able to continue with this action.') }}
        </p>

        @if(Session::has('security-message'))
            <div class="success-message">
                {{ Session::get('security-message') }}
            </div>
        @endif

        <x-baseplate::validation-errors class="mb-4" :errors="$errors" />
        <form method="post">
            @csrf

            <input type="hidden" id="auth_method" name="auth_method" value="password" />

            @foreach(InvisibleDragon\LaravelBaseplate\Auth\AuthMethod::get_methods() as $method)
                {!! $method::get_auth_html() !!}
            @endforeach

            <button class="primary">{{ __('Continue') }}</button>

        </form>

    </div>
@endsection
