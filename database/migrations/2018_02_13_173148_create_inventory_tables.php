<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInventoryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create('stock', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->default(0);
            $table->integer('warehouse_id')->default(0);
            $table->decimal('mutual_balance',12,2)->nullable();
            $table->decimal('actual_balance',12,2)->nullable();
            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
        });

        Schema::connection($connection)->create('bincard', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->default(0);
            $table->integer('warehouse_id')->default(0);
            $table->string('transaction_description')->nullable();
            $table->decimal('credit',12,2)->nullable();
            $table->decimal('debit',12,2)->nullable();
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

        Schema::connection($connection)->dropIfExists('stock');
        Schema::connection($connection)->dropIfExists('bincard');
    }
}
