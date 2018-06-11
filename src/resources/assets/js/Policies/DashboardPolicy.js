export default class DashboardPolicy {

    static access( user ) {

        return user.role === undefined || user.role === 'admin' || user.role === 'user';
    }

}