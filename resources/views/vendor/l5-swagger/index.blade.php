<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Source+Code+Pro:300,600|Titillium+Web:400,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ l5_swagger_asset($documentation, 'swagger-ui.css') }}" >
    <link rel="icon" type="image/png" href='{{asset("/images/fav-icon.png")}}' sizes="16x16" />
    <style>
        html
        {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *,
        *:before,
        *:after
        {
            box-sizing: inherit;
        }

        body {
            margin:0;
            background: #fafafa;
        }
        .swagger-ui .topbar {
            background: #4267B2;
        }

        .topbar-wrapper img {
            content:url('{{asset("/images/logo.png")}}');
        }
        .topbar-wrapper span:after {
            content: 'mylogo';
            color: #fff;
            visibility: visible;
            display: block;
            position: absolute;
            padding: 5px;
            top: 2px;
        }
        .swagger-ui .topbar .download-url-wrapper .download-url-button {
            background-color: #red !important;
        }

        .swagger-ui .topbar .download-url-wrapper input[type=text] {
            border-color: #red !important;
        }
    </style>
</head>

<body>


<div id="swagger-ui"></div>

<script src="{{ l5_swagger_asset($documentation, 'swagger-ui-bundle.js') }}"> </script>
<script src="{{ l5_swagger_asset($documentation, 'swagger-ui-standalone-preset.js') }}"> </script>
<script>
    window.onload = function() {
        // Build a system
        const ui = SwaggerUIBundle({
            dom_id: '#swagger-ui',

            url: "{!! $urlToDocs !!}",
            operationsSorter: {!! isset($operationsSorter) ? '"' . $operationsSorter . '"' : 'null' !!},
            configUrl: {!! isset($configUrl) ? '"' . $configUrl . '"' : 'null' !!},
            validatorUrl: {!! isset($validatorUrl) ? '"' . $validatorUrl . '"' : 'null' !!},
            oauth2RedirectUrl: "{{ route('l5-swagger.'.$documentation.'.oauth2_callback', [], $useAbsolutePath) }}",

            requestInterceptor: function(request) {
                request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                return request;
            },

            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],

            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],

            layout: "StandaloneLayout",

            persistAuthorization: {!! config('l5-swagger.defaults.persist_authorization') ? 'true' : 'false' !!},
        })

        window.ui = ui

    }

</script>
</body>

</html>
