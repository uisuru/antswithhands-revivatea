<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create('suppliers', function (Blueprint $table) {
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

        Schema::connection($connection)->create('product_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 190)->unique();
            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
        });

        Schema::connection($connection)->create('brands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 190)->unique();
            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
        });

        Schema::connection($connection)->create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 190)->unique();
            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
        });

        Schema::connection($connection)->create('product', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name');
            $table->string('sku');
            $table->text('description')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('product_type_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->decimal('cost', 12,2)->nullable();
            $table->decimal('buying_price', 12,2)->nullable();
            $table->decimal('wholesale_price', 12,2)->nullable();
            $table->decimal('retail_price', 12,2)->nullable();
            $table->enum('manage_stock_level', array('YES','NO'));
            $table->decimal('stock_on_hand', 12,2)->nullable();
            $table->decimal('opening_stock', 12,2)->nullable();
            $table->decimal('re_order_level', 12,2)->nullable();
            $table->integer('category_id')->nullable();
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

        Schema::connection($connection)->dropIfExists('suppliers');
        Schema::connection($connection)->dropIfExists('product_types');
        Schema::connection($connection)->dropIfExists('brands');
        Schema::connection($connection)->dropIfExists('categories');
        Schema::connection($connection)->dropIfExists('product');
    }
}
