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

      <p class="card-text">Here you can view last deployment log, all deployment logs. Also you can enable or disable
                           quick deploy.</p>

      <template slot="footer">

        <b-btn variant="outline-success"
               class="float-left"
               v-b-modal.changeQuickDeployModal>
          Edit quick deploy per client
        </b-btn>

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
             id="lastLogModal"
             @hidden="closeLastLog">

      <template v-if="last_log !== null"
                slot="modal-title">

        <h4>Log - {{ last_log.date }} -

          <span :class="last_log.level_class">{{ last_log.level }}</span>
        </h4>
      </template>

      <b-textarea disabled
                  v-model="last_log.message"
                  v-if="last_log !== null">
      </b-textarea>
    </b-modal>

    <b-modal ref="changeQuickDeployModal"
             id="changeQuickDeployModal"
             size="xs"
             hide-footer
             :title="trans.get('laravel-deploy.settings.deployments.quick_deploy.modal.title')">

      <b-form @submit.prevent="changeQuickDeploy()">

        <b-form-group :label="trans.get('laravel-deploy.settings.deployments.quick_deploy.modal.labels.client')">

          <b-select :options="clients"
                    :text-field="'name'"
                    v-model="quick_deploy_client"
                    :value-field="'id'">

          </b-select>
        </b-form-group>

        <b-form-group>

          <b-btn variant="primary"
                 type="submit">Change
          </b-btn>
        </b-form-group>

      </b-form>
    </b-modal>
  </div>
</template>

<script>
    export default {
        name:    'settings-deployments',
        data() {

            return {

                urls:                {

                    deploy_now:          window.Laravel.urls.ajax.settings.deployments.deploy_now,
                    change_quick_deploy: window.Laravel.urls.ajax.clients.auto_deploy,
                    index:               window.Laravel.urls.ajax.settings.index,
                    last_log:            window.Laravel.urls.ajax.settings.last_log,
                },
                deploy_client:       null,
                quick_deploy_client: null,
                settings:            {},
                clients:             [],
                last_log:            null,
            };
        },
        methods: {

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
            changeQuickDeploy() {

                let url = this.urls.change_quick_deploy.replace( 0, this.quick_deploy_client );
                this.$http.post( url ).then( response => {

                    this.$toasted.show( this.trans.get( 'laravel-deploy.settings.deployments.http.change_quick_deploy.success' ), {

                        duration: 3000,
                        type:     'info',
                    } );

                    this.quick_deploy_client = null;
                    this.$refs.changeQuickDeployModal.hide();
                }, response => {

                    this.$toasted.show( this.trans.get( 'laravel-deploy.settings.deployments.http.change_quick_deploy.error' ), {

                        duration: 3000,
                        type:     'error',
                    } );
                } );
            },
            init() {

                this.$http.get( this.urls.index ).then( response => {

                    this.settings = response.data.settings;
                    this.clients = response.data.clients;
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

                    response.data.log.date = new Date( response.data.log.date.date ).toDateString();

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

            this.init();
        }
    };
</script>

<style scoped>

</style>