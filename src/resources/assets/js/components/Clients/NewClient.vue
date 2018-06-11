<template>
  <b-modal id="newClient"
           ref="newClient"
           hide-footer
           :title="trans.get('laravel-deploy.clients.new.title')"
           size="lg">

    <b-form @submit.prevent="onSubmit">

      <b-form-group :label="trans.get('laravel-deploy.clients.new.labels.name')">

        <b-input type="text"
                 :placeholder="trans.get('laravel-deploy.clients.new.placeholders.name')"
                 v-model="form.name">

        </b-input>
      </b-form-group>

      <b-form-group :label="trans.get('laravel-deploy.clients.new.labels.source')">

        <b-input type="text"
                 :placeholder="trans.get('laravel-deploy.clients.new.placeholders.source')"
                 v-model="form.source">

        </b-input>
      </b-form-group>

      <b-form-group :label="trans.get('laravel-deploy.clients.new.labels.script')">

        <b-input type="text"
                 :placeholder="trans.get('laravel-deploy.clients.new.placeholders.script')"
                 v-model="form.script_source">

        </b-input>
      </b-form-group>

      <b-form-group :label="trans.get('laravel-deploy.clients.new.labels.token')">

        <b-input type="text"
                 :placeholder="trans.get('laravel-deploy.clients.new.placeholders.token')"
                 v-model="form.token">

        </b-input>
      </b-form-group>

      <b-form-group :label="trans.get('laravel-deploy.clients.new.labels.auto_deploy')">

        <b-checkbox v-model="form.auto_deploy"></b-checkbox>
      </b-form-group>

      <b-form-group>

        <b-btn type="submit"
               variant="info">Save
        </b-btn>
      </b-form-group>
    </b-form>
  </b-modal>
</template>

<script>
    export default {
        name:    'new-client',
        props:   {

            store: {

                default: window.Laravel.urls.ajax.clients.store,
                type:    String,
            }
        },
        data() {

            return {

                form: {},
            };
        },
        methods: {

            onSubmit() {

                let self = this;
                this.$http.post( this.store, this.form ).then( response => {

                    self.$parent.$emit( 'client-created', response.data.client );
                    self.form = {};
                    self.$refs.newClient.hide();
                } );
            }
        }
    };
</script>

<style scoped>

</style>