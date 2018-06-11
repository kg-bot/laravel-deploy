<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible"
        content="IE=edge">
  <meta name="viewport"
        content="width=device-width, initial-scale=1">
  <meta name="csrf-token"
        content="{{ csrf_token() }}">

  <title>{{ config('laravel-deploy.front.title') }}</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600"
        rel="stylesheet"
        type="text/css">

  <!-- Default Styles -->
  <link href="{{ asset('assets/vendor/laravel-deploy/css/laravel-deploy.css') }}"
        rel="stylesheet">

  <style>

    .clickable {

      cursor : pointer;
    }

    .full-width {
      width : 100%;
    }
  </style>

  <!-- Styles Stack -->
  @stack('styles')
</head>
<body>

<b-container fluid
             id="app">

  <navbar></navbar>


  <b-row class="mt-2">

    <b-col>

      <router-view></router-view>
    </b-col>

  </b-row>
</b-container>

<!-- Scripts -->
<script>

    window.Laravel = {

        token: document.querySelector( 'meta[name=csrf-token]' ).content,

        urls: {

            ajax:   {

                clients:  {

                    index:       "{{ route('laravel-deploy.ajax.clients.index') }}",
                    store:       "{{ route('laravel-deploy.ajax.clients.store') }}",
                    update:      "{{ route('laravel-deploy.ajax.clients.update', 0) }}",
                    destroy:     "{{ route('laravel-deploy.ajax.clients.destroy', 0) }}",
                    status:      "{{ route('laravel-deploy.ajax.clients.status', 0) }}",
                    auto_deploy: "{{ route('laravel-deploy.ajax.clients.auto_deploy', 0) }}",
                },
                settings: {

                    last_log:    "{{ route('laravel-deploy.ajax.settings.last_log') }}",
                    index:       "{{ route('laravel-deploy.ajax.settings.index') }}",
                    deployments: {

                        deploy_now: "{{ route('laravel-deploy.ajax.settings.deployments.deploy_now', 0) }}",
                    },
                },
            },
            logout: "{{ route('logout') }}",
        }
    };
    window.app_name = "{{ config('app.name') }}";
    window.default_locale = "{{ config('app.locale') }}";
    window.fallback_locale = "{{ config('app.fallback_locale') }}";
    window.messages = @json($messages);

    @auth
        window.user = @json($user);
  @endauth
</script>
@stack('urls')

<script src="{{ asset('assets/vendor/laravel-deploy/js/manifest.js') }}"></script>
<script src="{{ asset('assets/vendor/laravel-deploy/js/vendor.js') }}"></script>
<script src="{{asset('assets/vendor/laravel-deploy/js/laravel-deploy.js')}}"></script>

<!-- Stack Scripts -->
@stack('scripts')
</body>
</html>