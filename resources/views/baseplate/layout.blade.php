<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0" />

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="/vendor/baseplate/css/baseplate.css" />

    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}" />

    <style>
        @if(config('baseplate.bg_image'))
        .bg-panel { background-image: url('{{ config('baseplate.bg_image') }}'); }
        @endif
        {{ config('baseplate.login_css') }}
    </style>

</head>
<body>

    @yield('body')

<script type="text/javascript">
    (function(){
        let height = 0;
        function sendPostMessage() {
            if (height !== document.body.offsetHeight) {
                height = document.body.offsetHeight + 50;
                window.parent.postMessage({
                    frameHeight: height
                }, '*');
            }
        }
        window.addEventListener('resize', sendPostMessage);
        sendPostMessage();
    })();
</script>

</body>
</html>
