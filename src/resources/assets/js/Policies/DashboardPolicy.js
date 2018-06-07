export default class DashboardPolicy {

    static access( user ) {

        return user.role === 'admin' || user.role === 'user';
    }

}