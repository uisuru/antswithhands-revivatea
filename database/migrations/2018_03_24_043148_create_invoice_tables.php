<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoiceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create('invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->text('invoice_id');
            $table->integer('customer_id');
            $table->date('invoice_date');
            $table->decimal('gross_amount',12,2);
            $table->decimal('discount',12,2);
            $table->decimal('tax_amount',12,2);
            $table->decimal('net_amount',12,2);
            $table->integer('free_items');
            $table->integer('sales_rep');
            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
        });

        Schema::connection($connection)->create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->text('invoice_id');
            $table->integer('item_id');
            $table->integer('qty');
            $table->decimal('unit_price',12,2);
            $table->decimal('total_price',12,2);
            $table->decimal('discount_rate',12,2);
            $table->decimal('discount_amount',12,2);
            $table->decimal('net_price',12,2);
            $table->integer('package_id');
            $table->integer('free_items');
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

        Schema::connection($connection)->dropIfExists('invoice');
        Schema::connection($connection)->dropIfExists('invoice_items');
    }
}
