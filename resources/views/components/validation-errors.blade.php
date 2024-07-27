@props(['errors'])

@if ($errors->any())
<div class="alert alert-danger" {{ $attributes }}>
    <p><strong>
        {{ __('Whoops! Something went wrong.') }}
    </strong></p>

    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
