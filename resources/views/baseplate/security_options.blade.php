@extends('baseplate::baseplate.layout')

@section('body')
    <div class="security-options inner-panel">
        <h1>{{ __('Security Options') }}</h1>

        @if(Session::has('security-message'))
            <div class="success-message">
                {{ Session::get('security-message') }}
            </div>
        @endif

        <h2>{{ __('Login Methods') }}</h2>

        <p>
            {{ __('These are the methods you can use to log into the system such as using a password.') }}
        </p>

        <table class="security-options-table">
            @if(auth()->user()->getAuthPassword())
                <tr>
                    <th>
                        {{ __('Using a password') }}
                    </th>
                    <td>
                        <a href="{{ route('security-options-change-password') }}">
                            {{ __('Change') }}
                        </a>
                    </td>
                </tr>
            @endif
        </table>

        <h2>{{ __('Logout') }}</h2>

        <a class="logout-button danger-button" href="{{ route('logout') }}" target="_top">
            {{ __('Logout') }}
        </a>

    </div>
@endsection
