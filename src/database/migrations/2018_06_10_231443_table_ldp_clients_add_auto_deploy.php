<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableLdpClientsAddAutoDeploy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ldp_clients', function (Blueprint $table) {
            $table->boolean('auto_deploy')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ldp_clients', function (Blueprint $table) {
            $table->dropColumn('auto_deploy');
        });
    }
}
