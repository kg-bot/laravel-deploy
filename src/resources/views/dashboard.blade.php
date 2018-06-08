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
  <link href="{{ asset('assets/vendor/laravel-deploy/css/app.css') }}"
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
    @if(Gate::allows('access-dashboard', $user ))
      <b-col xs="12"
             md="3"
             lg="2">

        <div id="navigation-vue">

          @if(!$user->isAdmin() )
            <navigation-component></navigation-component>
          @else
            <admin-navigation-component></admin-navigation-component>
          @endif
        </div>
      </b-col>
    @endif

    <b-col xs="12"
           md="9"
           lg="10">

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
                search: "{{ route('ajax.search') }}",
                admin:  {

                    users:     {

                        list:   "{{ route('admin.users.list') }}",
                        store:  "{{ route('admin.users.store') }}",
                        update: "{{ route('admin.users.update', 0) }}",
                        delete: "{{ route('admin.users.delete', 0) }}",
                    },
                    countries: {

                        fetch:   "{{ route('ajax.admin.countries.fetch') }}",
                        destroy: "{{ route('ajax.admin.countries.destroy', 0) }}",
                        approve: "{{ route('ajax.admin.countries.approve', 0) }}",
                    },
                    logs:      {

                        fetch: "{{ route('ajax.admin.logs.fetch') }}",
                    },
                }
            },
            logout: "{{ route('logout') }}",
            admin:  {

                panel: "{{ route('admin.panel') }}",
            }
        }
    };
    window.app_name = "{{ config('app.name') }}";
    window.default_locale = "{{ config('app.lang') }}";
    window.fallback_locale = "{{ config('app.fallback_locale') }}";

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