@extends('baseplate::baseplate.layout')

@section('body')
    <div class="bg-row">
        <div class="login-panel">
            <div class="login-content-panel">
                <h1 class="text-center">
                    <span class="small">{{ __('Forgot Password') }}</span>
                    @if(config('baseplate.logo_image'))
                        <img class="login-logo" src="{{ config('baseplate.logo_image') }}" alt="{{ config('app.name') }}" title="{{ config('app.name') }}" />
                    @else
                        {{ config('app.name') }}
                    @endif
                </h1>

                <x-baseplate::validation-errors class="mb-4" :errors="$errors" />
                <form method="post">
                    @csrf
                    <label for="email">{{ __('Email Address') }}</label>
                    <input required type="text" name="email" />

                    <button class="primary">{{ __('Submit') }}</button>
                </form>
            </div>
        </div>
        <div class="bg-panel">
            <div class="bg-content-panel">
            </div>
        </div>
    </div>
@endsection
