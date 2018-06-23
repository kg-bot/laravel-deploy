<template>

  <div>

    <b-card border-variant="light"
            header-tag="header"
            class="text-center">

      <template slot="header">

        <h2 class="text-left">{{ trans.get('laravel-deploy.settings.deployments.scripts.card.header')}}</h2>
      </template>

      <p class="card-text">{{ trans.get('laravel-deploy.settings.deployments.scripts.card.text')}}</p>

      <b-row>

        <b-col cols="3">

          <b-form-group :label="trans.get('laravel-deploy.settings.deployments.scripts.card.select_client_label')">

            <b-select :options="clients"
                      :value-field="'id'"
                      :text-field="'name'"
                      v-model="selected_client"
                      @input="onClientChange">

            </b-select>
          </b-form-group>
        </b-col>
      </b-row>

    </b-card>

    <b-modal ref="clientScriptModal"
             :title="trans.get('laravel-deploy.settings.deployments.scripts.client_script_modal.title')"
             size="lg"
             @hidden="closeClientScript"
             hide-footer>

      <b-form @submit.prevent="onScriptEdit">

        <b-form-group :label="trans.get('laravel-deploy.settings.deployments.scripts.client_script_modal.form.labels.script_input')">

          <b-textarea style="background-color: #212121; color: #ffffff"
                      v-model="client_script_source"></b-textarea>
        </b-form-group>

        <b-form-group>

          <b-btn type="submit"
                 variant="primary">
            {{ trans.get('laravel-deploy.settings.deployments.scripts.client_script_modal.form.submit_button')}}
          </b-btn>
        </b-form-group>
      </b-form>

    </b-modal>
  </div>
</template>

<script>
    export default {
        name:    'deployment-scripts',
        props:   {

            clients: {

                default: () => {

                    return [];
                },
                type:    Array,
            },
        },
        data() {

            return {

                selected_client:      null,
                client_script_source: null,
                urls:                 {

                    fetch: window.Laravel.urls.ajax.settings.deployments.scripts.fetch,
                    save:  window.Laravel.urls.ajax.settings.deployments.scripts.save,
                },
            };
        },
        methods: {

            onClientChange( client ) {

                this.$http.get( this.urls.fetch.replace( 0, client ) ).then( response => {

                    this.client_script_source = response.data.content;

                }, response => {

                    this.$toasted.show( response.data.message, {

                        duration: 3000,
                        type:     'error',
                    } );
                } );

                this.selected_client = client;
                this.$refs.clientScriptModal.show();
            },
            onScriptEdit() {

                this.$http.post( this.urls.save.replace( 0, this.selected_client ), { content: this.client_script_source } ).then( response => {

                    this.$toasted.show( 'Deploy script successfully updated.' );
                    this.client_script_source = null;
                    this.$refs.clientScriptModal.hide();

                }, response => {

                    this.$toasted.show( response.data.message, {

                        duration: 3000,
                        type:     'error',
                    } );
                } );
            },
            closeClientScript() {

                this.client_script_source = null;
            }
        }
    };
</script>

<style scoped>

</style>