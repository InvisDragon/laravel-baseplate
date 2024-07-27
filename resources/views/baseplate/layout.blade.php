<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0" />

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="/vendor/baseplate/css/baseplate.css" />

    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}" />
</head>
<body>

    <div class="bg-row">
        <div class="login-panel">
            <div class="login-content-panel">
                <h1 class="text-center">
                    <span class="small">{{ __('Log in to') }}</span>
                    {{ config('app.name') }}
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

</body>
</html>
