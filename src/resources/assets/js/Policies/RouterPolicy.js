export default class RouterPolicy {

    static customers_table( user ) {

        return user.role === 'user';
    }

    static customer_groups( user ) {

        return user.role === 'user';
    }

    static customer_details( user, route ) {

        return user.role === 'user' || user.id === route.params.id;
    }

    static inventory( user, route ) {

        return user.role === 'user';
    }
}