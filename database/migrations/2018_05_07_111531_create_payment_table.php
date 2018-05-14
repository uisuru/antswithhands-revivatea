<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create('payment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id');
            $table->string('invoice_number');
            $table->string('payment_code')->nullable();
            $table->decimal('paid_amount',12,2)->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('branch_code')->nullable();
            $table->enum('status', array('ACTIVE','INACTIVE'));
            $table->timestamps();
        });

        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('invoice', function($table) {
            $table->decimal('invoice_balance_amount',12,2)->nullable();
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

        Schema::connection($connection)->dropIfExists('payment');

        Schema::table('invoice', function($table) {
            $table->dropColumn('invoice_balance_amount');
        });
    }
}
