<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 1:24 AM.
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
        Schema::create('deploy_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('source');
            $table->boolean('active')->default(true);
            $table->string('token', 512)->unique();
            $table->string('name');
            $table->string('script_source');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deploy_sources');
    }
}
