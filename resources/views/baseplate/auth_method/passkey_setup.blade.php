@extends('baseplate::baseplate.layout')

@section('body')
    <div class="security-options inner-panel">
        <h1>{{ __('Passkey Setup') }}</h1>

        <button id="createPasskey" style="display:none" class="primary">{{ __('Continue') }}</button>

        <x-baseplate::validation-errors class="mb-4" :errors="$errors" />
        <form method="post" id="passkey" data-passkey-config="{{ json_encode($credentialOptions) }}">
            @csrf

            <input name="passkey" type="hidden" id="passkeyValue" />

        </form>

        <script type="text/javascript" src="/vendor/baseplate/js/passkey-setup.js?v=2"></script>

    </div>
@endsection
