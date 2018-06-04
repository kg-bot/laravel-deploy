<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 1:24 AM
 */
class CreateDeploySourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'deploy_sources', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->timestamps();

            $table->string( 'source' );
            $table->boolean( 'active' )->default( true );
            $table->text( 'token' )->unique();
            $table->string( 'name' );
            $table->string( 'script_source' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'deploy_sources' );
    }
}