<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('company', function (Blueprint $table) {
            $table->string('company_code')->nullable()->change();
            $table->string('phone_number')->nullable()->change();
            $table->string('default_price_list_id')->nullable()->change();
            $table->string('default_tax_type_id')->nullable()->change();
            $table->string('default_payment_term_id')->nullable()->change();
            $table->string('default_payment_method_id')->nullable()->change();
            $table->string('discount_rate')->nullable()->change();
            $table->string('minimum_order_value')->nullable()->change();
            $table->string('address_line1')->nullable()->change();
            $table->string('city')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company', function (Blueprint $table) {
            //
        });
    }
}
