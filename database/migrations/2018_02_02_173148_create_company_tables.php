<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create('company', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name', 190)->unique();
            $table->string('logo', 255)->nullable();
            $table->string('company_code', 60);
            $table->string('tax_number', 100)->nullable();
            $table->string('phone_number', 20);
            $table->string('fax_number', 20)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('email_address', 255)->nullable();
            $table->text('description')->nullable();
            $table->integer('default_price_list_id');
            $table->integer('default_tax_type_id');
            $table->integer('default_payment_term_id');
            $table->integer('default_payment_method_id');
            $table->double('discount_rate');
            $table->double('minimum_order_value',12,2);
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('suburb')->nullable();
            $table->string('city');
            $table->string('post_code')->nullable();
            $table->string('location_latitude')->nullable();
            $table->string('location_longitude')->nullable();
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

        Schema::connection($connection)->dropIfExists('company');
    }
}
