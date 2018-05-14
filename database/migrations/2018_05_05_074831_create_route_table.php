<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create('route', function (Blueprint $table) {
            $table->increments('id');
            $table->string('route_name')->nullable();
            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
        });

        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('company', function($table) {
            $table->integer('route_id')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->dropIfExists('route');

        Schema::table('company', function($table) {
            $table->dropColumn('route_id');
        });
    }
}
