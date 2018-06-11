<template>
  <b-navbar toggleable="md"
            type="dark"
            variant="secondary">

    <b-navbar-toggle
            target="nav_collapse"></b-navbar-toggle>

    <b-navbar-brand href="/">{{ app_name }} - Deploy
    </b-navbar-brand>

    <b-collapse is-nav
                id="nav_collapse">

      <b-navbar-nav>

        <b-nav-item :to="{name: 'clients' }">{{ trans.get('laravel-deploy.navbar.clients')}}
        </b-nav-item>

        <b-nav-item :to="{name: 'settings' }">{{ trans.get('laravel-deploy.navbar.settings')}}
        </b-nav-item>
      </b-navbar-nav>

      <!-- Right aligned nav items -->
      <b-navbar-nav class="ml-auto">

        <b-nav-item-dropdown right>
          <!-- Using button-content slot -->
          <template slot="button-content">
            <em>{{ user.name }}</em>
          </template>
          <b-dropdown-item href="#"
                           @click.prevent="onLogout">{{ trans.get('auth.logout') }}
          </b-dropdown-item>
        </b-nav-item-dropdown>
      </b-navbar-nav>

    </b-collapse>
  </b-navbar>

</template>

<script>
    export default {
        name:    'navbar',
        data() {

            return {

                logout:   window.Laravel.urls.logout,
                user:     window.user,
                app_name: window.app_name,
            };
        },
        methods: {

            onLogout() {

                this.$http.post( this.logout ).then( response => {

                    window.location.replace( '/' );
                }, response => {

                    this.$toasted.show( 'Can\'t log you out right now.', {

                        duration: 3000,
                        type:     'error',
                    } );
                } );
            },
        }
    };
</script>

<style scoped>

</style>