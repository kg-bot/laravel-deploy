/**
 * Here you should import all policies that you create and want to use through your application
 */
import DashboardPolicy from './Policies/DashboardPolicy';
import RouterPolicy from './Policies/RouterPolicy';


export default class Gate {
    constructor( user ) {

        this.user = user;

        this.policies = {
            dashboard: DashboardPolicy,
            router:    RouterPolicy,
        };
    }

    before() {
        return this.user.role === undefined || this.user.role === 'admin';
    }

    can( action, type, model = null ) {
        if ( this.before() ) {
            return true;
        }

        if ( this.policies[ type ][ action ] !== undefined ) {

            return this.policies[ type ][ action ]( this.user, model );
        }

        return true;


    }

    cant( action, type, model = null ) {
        return !this.can( action, type, model );
    }
}