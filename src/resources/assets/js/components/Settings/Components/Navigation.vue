<template>

  <b-nav tabs
         vertical>
    <b-nav-item :to="{name: 'settings-deployments', props: { settings, clients } }">Deployments</b-nav-item>
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