<template>

  <b-nav tabs
         vertical>
    <b-nav-item v-if="clients.length > 0"
                :to="{name: 'settings-deployments' }">Deployments
    </b-nav-item>
  </b-nav>
</template>

<script>
    export default {
        name:  'settings-navigation',
        props: {

            settings: {

                default: {},
                type:    Object,
            },
            clients:  {

                default: [],
                type:    Array,
            },
        },
    };
</script>

<style scoped>

</style>