<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGrnTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create('grn', function (Blueprint $table) {
            $table->increments('id');
            $table->string('grn_number')->nullable();
            $table->text('description')->nullable();
            $table->integer('warehouse_id')->default(0);
            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
        });

        Schema::connection($connection)->create('grn_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('grn_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('warehouse_id')->default(0);
            $table->decimal('qty',12,2)->nullable();
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

        Schema::connection($connection)->dropIfExists('grn');
        Schema::connection($connection)->dropIfExists('grn_items');
    }
}
