@extends('baseplate::baseplate.layout')

@section('body')
    <div class="security-options inner-panel">
        <h1>{{ __('Security Options') }}</h1>

        <h2>{{ __('Change my login password') }}</h2>

        <x-baseplate::validation-errors class="mb-4" :errors="$errors" />

        <form method="post">

            @csrf

            <label for="password">{{ __('Current Password') }}</label>
            <input required type="password" name="current_password" />

            <label for="password">{{ __('New Password') }}</label>
            <input required type="password" name="new_password" />

            <label for="password">{{ __('New Password (again)') }}</label>
            <input required type="password" name="new_password_confirm" />

            <p>
                {{ __('For best security, your password should be a phrase such as "JohnLikesToBuyChocolateAtTheShop"') }}
            </p>

            <button type="submit" class="primary">{{ __('Change Password') }}</button>

        </form>

    </div>
@endsection
