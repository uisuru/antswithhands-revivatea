<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWarehouseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create('warehouse', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 190)->unique();
            $table->string('address');
            $table->string('city');
            $table->string('post_code')->nullable();
            $table->string('phone_number', 20);
            $table->string('email_address', 255)->nullable();
            $table->string('contact_person');

            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
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

        Schema::connection($connection)->dropIfExists('warehouse');
    }
}
