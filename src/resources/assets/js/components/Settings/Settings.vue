<template>

  <b-row>

    <b-col xs="12"
           md="3"
           lg="2">

      <div>

        <settings-navigation :settings="settings"
                             :clients="clients"></settings-navigation>
      </div>
    </b-col>

    <b-col xs="12"
           md="9"
           lg="10">

      <router-view></router-view>
    </b-col>
  </b-row>
</template>

<script>
    import SettingsNavigation from './Components/Navigation';

    export default {
        components: { SettingsNavigation },
        name:       'settings',
        data() {

            return {

                urls:     {

                    index: window.Laravel.urls.ajax.settings.index,
                },
                settings: null,
                clients:  [],
            };
        },
        methods:    {

            index() {

                this.$http.get( this.urls.index ).then( response => {

                    this.settings = response.data.settings;
                    this.clients = response.data.clients;
                } );
            }
        },
        mounted() {

            this.index();
        }
    };
</script>

<style scoped>

</style>