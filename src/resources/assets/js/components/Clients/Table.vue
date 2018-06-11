<template>

  <div>

    <b-table :fields="fields"
             :items="clients"
             hover
             stripped
             caption-top
             :caption="'Clients - ' + clients.length"
             responsive>

      <template slot="active"
                slot-scope="data">

        <span v-if="data.item.active"
              @click.stop="changeActive(false, data.item)"
              class="text-success clickable"><icon name="check"></icon></span>

        <span v-else
              @click.stop="changeActive(true, data.item)"
              class="text-danger clickable"><icon name="times"></icon></span>
      </template>

      <template slot="auto_deploy"
                slot-scope="data">

        <span v-if="data.item.auto_deploy"
              @click.stop="changeAutoDeploy(false, data.item)"
              class="text-success clickable"><icon name="check"></icon></span>

        <span v-else
              @click.stop="changeAutoDeploy(true, data.item)"
              class="text-danger clickable"><icon name="times"></icon></span>
      </template>

      <template slot="actions"
                slot-scope="data">

        <b-btn @click.prevent="onEdit(data.item, data.index)"
               v-b-modal.editClient
               variant="info">Edit
        </b-btn>

        <b-btn @click.prevent="onDelete(data.item, data.index)"
               variant="danger">Delete
        </b-btn>
      </template>

      <template slot="table-caption">

        <h2 class="text-info">
          <b>Clients - {{ clients.length }}</b>
          <b-btn variant="info"
                 class="float-right"
                 v-b-modal.newClient>
            <icon class="d-sm-none"
                  name="plus-circle"></icon>
            <span class="d-none d-sm-inline">New Client</span>
          </b-btn>
        </h2>
      </template>

    </b-table>

    <new-client :store="urls.store"></new-client>

    <edit-client :initial_client="edit_client"
                 :update="update_url"></edit-client>
  </div>

</template>

<script>
    import NewClient from './NewClient';
    import EditClient from './EditClient';

    export default {
        components: {
            EditClient,
            NewClient
        },
        name:       'clients-table',
        data() {

            return {

                fields:      [

                    { key: 'id', label: '#', sortable: true },
                    { key: 'name', label: 'Name', sortable: true },
                    { key: 'source', label: 'Source', sortable: true },
                    { key: 'token', label: 'Token', sortable: true },
                    { key: 'active', label: 'Active', sortable: true },
                    { key: 'auto_deploy', label: 'Auto Deploy', sortable: true },
                    { key: 'actions', label: 'Actions', sortable: false },
                ],
                clients:     [],
                urls:        {

                    index:       window.Laravel.urls.ajax.clients.index,
                    store:       window.Laravel.urls.ajax.clients.store,
                    update:      window.Laravel.urls.ajax.clients.update,
                    destroy:     window.Laravel.urls.ajax.clients.destroy,
                    status:      window.Laravel.urls.ajax.clients.status,
                    auto_deploy: window.Laravel.urls.ajax.clients.auto_deploy,
                },
                edit_client: null,
                edit_index:  null,
                update_url:  null,
            };
        },
        methods:    {

            getClients() {

                let self = this;
                this.$http.get( this.urls.index ).then( response => {

                    self.clients = response.data.clients;

                }, response => {

                    _.each( response.data.errors, function ( error ) {

                        self.$toasted.show( error, {

                            type:     'error',
                            duration: 3000,
                        } );
                    } );
                } );
            },
            changeActive( active, item ) {

                let self = this;
                let url = this.urls.status.replace( 0, item.id );

                this.$http.post( url ).then( response => {

                    item.active = !item.active;
                }, response => {

                    self.$toasted.show( 'Can\'t change client status.', {

                        duration: 3000,
                        type:     'error',
                    } );
                } );
            },
            changeAutoDeploy( deploy, item ) {

                let self = this;
                let url = this.urls.auto_deploy.replace( 0, item.id );

                this.$http.post( url ).then( response => {

                    item.auto_deploy = !item.auto_deploy;
                }, response => {

                    self.$toasted.show( 'Can\'t change client auto deploy.', {

                        duration: 3000,
                        type:     'error',
                    } );
                } );
            },
            onEdit( item, index ) {

                this.edit_client = item;
                this.edit_index = index;
                this.update_url = this.urls.update.replace( 0, item.id );
            },
            onDelete( item, index ) {

                let self = this;
                let url = this.urls.destroy.replace( 0, item.id );

                this.$http.delete( url ).then( response => {

                    self.$toasted.show( 'Client deleted.', {

                        duration: 3000,
                        type:     'success',
                    } );

                    self.clients.splice( index, 1 );
                }, response => {

                    self.$toasted.show( 'Can\'t delete this client.', {

                        duration: 3000,
                        type:     'error',
                    } );
                } );
            }
        },
        mounted() {

            this.getClients();
            this.$on( 'client-created', function ( client ) {

                this.$toasted.show( 'New client created.', {

                    duration: 3000,
                    type:     'success',
                } );

                this.clients.push( client );
            } );
            this.$on( 'client-updated', function ( client ) {

                this.$toasted.show( 'Client updated.', {

                    duration: 3000,
                    type:     'success',
                } );

                this.clients.splice( this.edit_index, 1, client );
            } );
            this.$on( 'client-edit-closed', function () {

                this.edit_client = null;
                this.edit_index = null;
            } );
        },
    };
</script>

<style scoped>

</style>