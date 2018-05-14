<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConfigTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create('currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 190)->unique();
            $table->string('code', 60);
            $table->string('symbol', 60);
            $table->double('rate',10,2);
            $table->enum('is_default', array('Yes','No'));
            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
        });

        Schema::connection($connection)->create('price_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 190)->unique();
            $table->string('code', 60);
            $table->integer('currency_id', false);
            $table->double('rate',10,2);
            $table->enum('is_default', array('Yes','No'));
            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
        });

        Schema::connection($connection)->create('tax_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 190)->unique();
            $table->string('code', 60);
            $table->double('rate',10,2);
            $table->enum('is_default', array('Yes','No'));
            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
        });

        Schema::connection($connection)->create('payment_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 190)->unique();
            $table->string('due_in_days', 60);
            $table->enum('is_default', array('Yes','No'));
            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
        });

        Schema::connection($connection)->create('payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 190)->unique();
            $table->enum('is_default', array('Yes','No'));
            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
        });

        Schema::connection($connection)->create('stock_adjustment_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reason', 190)->unique();
            $table->enum('is_default', array('Yes','No'));
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

        Schema::connection($connection)->dropIfExists('currencies');
        Schema::connection($connection)->dropIfExists('price_lists');
        Schema::connection($connection)->dropIfExists('tax_types');
        Schema::connection($connection)->dropIfExists('payment_terms');
        Schema::connection($connection)->dropIfExists('payment_methods');
        Schema::connection($connection)->dropIfExists('stock_adjustment_reasons');
    }
}
