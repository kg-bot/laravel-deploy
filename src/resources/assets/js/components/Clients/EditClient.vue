<template>
  <b-modal id="editClient"
           ref="editClient"
           hide-footer
           lazy
           :title="trans.get('laravel-deploy.clients.edit.title')"
           size="lg">

    <b-form @submit.prevent="onSubmit"
            v-if="client !== null">

      <b-form-group :label="trans.get('laravel-deploy.clients.edit.labels.name')">

        <b-input type="text"
                 :placeholder="trans.get('laravel-deploy.clients.edit.placeholders.name')"
                 v-model="client.name">

        </b-input>
      </b-form-group>

      <b-form-group :label="trans.get('laravel-deploy.clients.edit.labels.source')">

        <b-input type="text"
                 :placeholder="trans.get('laravel-deploy.clients.edit.placeholders.source')"
                 v-model="client.source">

        </b-input>
      </b-form-group>

      <b-form-group :label="trans.get('laravel-deploy.clients.edit.labels.script')">

        <b-input type="text"
                 :placeholder="trans.get('laravel-deploy.clients.edit.placeholders.script')"
                 v-model="client.script_source">

        </b-input>
      </b-form-group>

      <b-form-group :label="trans.get('laravel-deploy.clients.edit.labels.token')">

        <b-input type="text"
                 :placeholder="trans.get('laravel-deploy.clients.edit.placeholders.token')"
                 v-model="client.token">

        </b-input>
      </b-form-group>

      <b-form-group :label="trans.get('laravel-deploy.clients.edit.labels.auto_deploy')">

        <b-checkbox v-model="client.auto_deploy"></b-checkbox>
      </b-form-group>

      <b-form-group>

        <b-btn type="submit"
               variant="success">Edit
        </b-btn>
      </b-form-group>
    </b-form>
  </b-modal>
</template>

<script>
    export default {
        name:    'edit-client',
        props:   {

            update:         {

                default: window.Laravel.urls.ajax.clients.update,
                type:    String,
            },
            initial_client: {

                default: {},
                type:    Object,
            },
        },
        data() {

            return {

                client: null,
            };
        },
        methods: {

            onSubmit() {

                let self = this;
                this.$http.put( this.update, this.client ).then( response => {

                    self.$parent.$emit( 'client-updated', response.data.client );
                    self.$refs.editClient.hide();
                } );
            },
        },
        watch:   {

            initial_client( newClient ) {

                this.client = Object.assign( {}, newClient );
            }
        }
    };
</script>

<style scoped>

</style>