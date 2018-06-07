import Gate from './Gate';
import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import VueResource from 'vue-resource';
import VueRouter from 'vue-router';
import Toasted from 'vue-toasted';
import Icon from 'vue-awesome/components/Icon';
import 'vue-awesome/icons';
// Translations
import Lang from 'lang.js';
import messages from './messages';
// Navigation components
import NavigationComponent from './components/NavigationComponent.vue';
import Navbar from './components/Navbar';
// Admin components
import AdminNavigationComponent from './components/Admin/AdminNavigationComponent';
import AdminPanel from './components/Admin/AdminPanel';
import AdminCountryTable from './components/Admin/Cards/Country/Table';
import AdminUsers from './components/Admin/Cards/AdminUsers';
import AdminLogsTable from './components/Admin/Cards/Log/Table';

const default_locale = window.default_language;
const fallback_locale = window.fallback_locale;

window._ = require( 'lodash' );
window.changeCase = require( 'change-case' );
require( 'datejs' );

const routes = [

    // Admin routes
    { path: '/admin/panel', component: AdminPanel, name: 'admin-panel' },
    { path: '/admin/countries', component: AdminCountryTable, name: 'admin-countries-table' },
    { path: '/admin/users', component: AdminUsers, name: 'admin-users-table' },
    { path: '/admin/logs', component: AdminLogsTable, name: 'admin-logs-table' },
];

Vue.use( VueResource );
Vue.use( BootstrapVue );
Vue.use( VueRouter );
Vue.component( 'icon', Icon );
Vue.use( Toasted );

Vue.prototype.$eventHub = new Vue(); // Global event bus
Vue.prototype.$gate = new Gate( window.user );
VueRouter.prototype.$gate = new Gate( window.user );
Vue.prototype.trans = new Lang( { messages, locale: default_locale, fallback: fallback_locale } );

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

let token = document.head.querySelector( 'meta[name="csrf-token"]' ).content;

Vue.http.headers.common[ 'X-CSRF-TOKEN' ] = token;
Vue.http.headers.common[ 'Accept' ] = 'application/json';

const router = new VueRouter( {
    routes // short for `routes: routes`
} );

Vue.http.interceptors.push( function ( request ) {

    let self = this;

    // return response callback
    return function ( response ) {

        if ( response.status === 401 ) {

            self.$toasted.show( 'You have been logged out due to inactivity, will be now redirected to login.', {

                type:     'danger',
                duration: 2000,
            } );

            window.setTimeout( function () {

                window.location.replace( '/' );
            }, 3000 );

        }

    };
} );
router.beforeEach( ( to, from, next ) => {

    //console.log( to );
    let self = router;

    if ( to.path !== '/' && to.name !== null && self.$gate.can( window.changeCase.snakeCase( to.name ), 'router', to ) ) {

        next();

    } else {

        return false;
    }

} );

const app = new Vue( {
    el:         '#app',
    components: {

        'navigation-component':       NavigationComponent,
        'admin-navigation-component': AdminNavigationComponent,
        'navbar':                     Navbar,
    },
    router,

} );
