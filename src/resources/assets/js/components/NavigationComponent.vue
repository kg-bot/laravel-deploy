<template>
  <div>
    <b-form @submit.prevent="search()"
            class="mb-2">

      <b-input-group class="mb-2">

        <b-input v-model="query"
                 type="text"
                 :placeholder="trans.get('laravel-deploy.search.placeholder')"></b-input>

        <b-input-group-text slot="append"
                            style="cursor: pointer;">
          <strong @click.prevent="onClear">X</strong>
        </b-input-group-text>
      </b-input-group>

      <b-card v-if="searching.results > 0"
              flush
              header="Customers"
              no-body>

        <b-list-group>

          <b-list-group-item v-for="result in searching.results"
                             :key="result.id"
                             to="#">
            {{ result.name }}
          </b-list-group-item>
        </b-list-group>
      </b-card>

    </b-form>

    <b-list-group>

      <b-card>

        <router-link :to="{ name: 'example' }"
                     size="sm">{{ trans.get('navigation.example')}}
        </router-link>
      </b-card>

    </b-list-group>
  </div>
</template>

<script>
    export default {
        name:    'navigation-component',
        data() {

            return {

                query:     '',
                searching: {

                    url:     window.Laravel.urls.ajax.search,
                    results: [],
                }
            };
        },
        methods: {

            search() {

                let self = this;
                this.$http.get( this.searching.url, {

                    params: {

                        query: this.query,
                    },
                } ).then( response => {

                    this.searching.results = response.data.results;
                }, response => {

                    _.each( response.data.errors, function ( error ) {

                        self.$toasted.show( error, {

                            type:     'danger',
                            duration: 3000,
                        } );
                    } );
                } );
            },
            onClear() {

                this.searching.results.customers = [];
                this.searching.results.customer_contacts = [];
                this.query = '';
            }
        }
    };
</script>

<style scoped>

</style>