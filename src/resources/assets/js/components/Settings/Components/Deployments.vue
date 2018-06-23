<template>

  <div>

    <b-card border-variant="light"
            header-tag="header"
            class="text-center">

      <template slot="header">

        <h2 class="text-left">Deployments
          <b-btn variant="info"
                 class="float-right"
                 v-b-modal.deployNowClient>Deploy now
          </b-btn>
        </h2>
      </template>

      <p class="card-text">Here you can view last deployment log, all deployment logs, manually deploy for each
                           client, etc.</p>

      <template slot="footer">

        <b-btn variant="link"
               @click="lastLog()"
               class="float-right">View last deployment log
        </b-btn>
      </template>
    </b-card>

    <b-modal ref="deployNowClient"
             :title="trans.get('laravel-deploy.settings.deployments.deploy_now.modal.title')"
             id="deployNowClient"
             size="xs"
             hide-footer>

      <b-form @submit.prevent="deployNow()">

        <b-form-group :label="trans.get('laravel-deploy.settings.deployments.deploy_now.modal.labels.client')">

          <b-select :options="clients"
                    :text-field="'name'"
                    v-model="deploy_client"
                    :value-field="'id'">

          </b-select>
        </b-form-group>

        <b-form-group>

          <b-btn variant="primary"
                 type="submit">Deploy
          </b-btn>
        </b-form-group>

      </b-form>

    </b-modal>

    <b-modal ref="lastLogModal"
             ok-only
             ok-title="Close"
             ok-variant="secondary"
             id="lastLogModal"
             size="lg"
             @hidden="closeLastLog">

      <template v-if="last_log !== null"
                slot="modal-title">

        <h4>Log - {{ last_log.date }} -

          <span :class="last_log.level_class">{{ last_log.level }}</span>
        </h4>
      </template>

      <pre v-if="last_log !== null"
           v-html="last_log.message"
           style="padding: 30px 10px; white-space: pre-wrap; background-color: #e0e0e0;"></pre>
    </b-modal>

    <!-- Deployment scripts section -->
    <deployment-scripts class="mt-5"
                        :clients="clients"></deployment-scripts>
  </div>
</template>

<script>
    import DeploymentScripts from './DeploymentScripts';

    export default {
        components: { DeploymentScripts },
        name:       'settings-deployments',
        data() {

            return {

                urls:          {

                    deploy_now:          window.Laravel.urls.ajax.settings.deployments.deploy_now,
                    change_quick_deploy: window.Laravel.urls.ajax.clients.auto_deploy,
                    index:               window.Laravel.urls.ajax.settings.index,
                    last_log:            window.Laravel.urls.ajax.settings.last_log,
                },
                deploy_client: null,
                settings:      {},
                clients:       [],
                last_log:      null,
            };
        },
        methods:    {

            deployNow() {

                let url = this.urls.deploy_now.replace( 0, this.deploy_client );
                this.$http.post( url, { client: this.deploy_client } ).then( response => {

                    this.$toasted.show( this.trans.get( 'laravel-deploy.settings.deployments.http.deploy_now.success' ), {

                        duration: 3000,
                        type:     'info',
                    } );

                    this.deploy_client = null;
                    this.$refs.deployNowClient.hide();
                }, response => {

                    this.$toasted.show( this.trans.get( 'laravel-deploy.settings.deployments.http.deploy_now.error' ), {

                        duration: 3000,
                        type:     'error',
                    } );

                    this.deploy_client = null;
                    this.$refs.deployNowClient.hide();
                } );
            },
            init() {

                this.$http.get( this.urls.index ).then( response => {

                    this.clients = response.data.clients;
                    this.settings = response.data.settings;
                } );
            },
            lastLog() {

                let self = this;
                const error_class = [
                    'ERROR',
                    'CRITICAL',
                    'ALERT',
                    'EMERGENCY'
                ];
                this.$http.get( this.urls.last_log ).then( response => {

                    response.data.log.date = new Date( response.data.log.date.date ).format( 'Y-m-d H:s:i' );

                    let level = response.data.log.level;
                    if ( error_class.includes( level ) ) {

                        response.data.log.level_class = 'text-danger';

                    } else if ( level === 'DEBUG' ) {

                        response.data.log.level_class = 'text-black';

                    } else if ( level === 'INFO' ) {

                        response.data.log.level_class = 'text-info';

                    } else if ( level === 'NOTICE' ) {

                        response.data.log.level_class = 'text-success';

                    } else if ( level === 'WARNING' ) {

                        response.data.log.level_class = 'text-warning';
                    }

                    this.last_log = response.data.log;
                    self.$refs.lastLogModal.show();
                } );
            },
            closeLastLog() {

                this.last_log = null;
            }
        },
        mounted() {

            console.log( this.trans.get( 'laravel-deploy.navbar.clients' ) );
            this.init();
        }
    };
</script>

<style scoped>

</style>