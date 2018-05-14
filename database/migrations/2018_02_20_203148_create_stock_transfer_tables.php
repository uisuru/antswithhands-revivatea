<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockTransferTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create('stock_transfer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_warehouse_id')->default(0);
            $table->integer('to_warehouse_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->decimal('qty', 12, 2);
            $table->text('description')->nullable();
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

        Schema::connection($connection)->dropIfExists('stock_transfer');
    }
}
