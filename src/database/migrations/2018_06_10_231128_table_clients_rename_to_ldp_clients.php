<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableClientsRenameToLdpClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'clients', function ( Blueprint $table ) {

            $table->rename( 'ldp_clients' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'ldp_clients', function ( Blueprint $table ) {

            $table->rename( 'clients' );
        } );
    }
}
