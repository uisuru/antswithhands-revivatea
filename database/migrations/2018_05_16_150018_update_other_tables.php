<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOtherTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('bincard')->insert(//Insert into bincard table
            array(
                ['id' => '1',
                    'product_id' => '1',
                    'warehouse_id' => '1',
                    'transaction_description' => 'Desc',
                    'credit' => '10.00',
                    'debit' => '0.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '2',
                    'product_id' => '1',
                    'warehouse_id' => '1',
                    'transaction_description' => 'Desc',
                    'credit' => '10.00',
                    'debit' => '0.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '3',
                    'product_id' => '2',
                    'warehouse_id' => '1',
                    'transaction_description' => 'Desc',
                    'credit' => '100.00',
                    'debit' => '0.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '4',
                    'product_id' => '1',
                    'warehouse_id' => '1',
                    'transaction_description' => 'Desc',
                    'credit' => '100.00',
                    'debit' => '0.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '5',
                    'product_id' => '2',
                    'warehouse_id' => '1',
                    'transaction_description' => 'Desc',
                    'credit' => '50.00',
                    'debit' => '0.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '6',
                    'product_id' => '1',
                    'warehouse_id' => '2',
                    'transaction_description' => 'Stock Received - 2018-02-20',
                    'credit' => '10.00',
                    'debit' => '0.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '7',
                    'product_id' => '1',
                    'warehouse_id' => '2',
                    'transaction_description' => 'Stock Received - 2018-02-20',
                    'credit' => '10.00',
                    'debit' => '0.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '8',
                    'product_id' => '1',
                    'warehouse_id' => '2',
                    'transaction_description' => 'Stock Received - 2018-02-20',
                    'credit' => '10.00',
                    'debit' => '0.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '9',
                    'product_id' => '2',
                    'warehouse_id' => '1',
                    'transaction_description' => 'Desc 12',
                    'credit' => '0.00',
                    'debit' => '2.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '10',
                    'product_id' => '2',
                    'warehouse_id' => '2',
                    'transaction_description' => 'Desc 12',
                    'credit' => '2.00',
                    'debit' => '0.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '11',
                    'product_id' => '1',
                    'warehouse_id' => '1',
                    'transaction_description' => 'Desc12',
                    'credit' => '0.00',
                    'debit' => '20.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '12',
                    'product_id' => '1',
                    'warehouse_id' => '2',
                    'transaction_description' => 'Desc12',
                    'credit' => '20.00',
                    'debit' => '0.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '13',
                    'product_id' => '1',
                    'warehouse_id' => '1',
                    'transaction_description' => '',
                    'credit' => '0.00',
                    'debit' => '2.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '14',
                    'product_id' => '1',
                    'warehouse_id' => '2',
                    'transaction_description' => '',
                    'credit' => '2.00',
                    'debit' => '0.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '15',
                    'product_id' => '1',
                    'warehouse_id' => '1',
                    'transaction_description' => '',
                    'credit' => '0.00',
                    'debit' => '2.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '16',
                    'product_id' => '1',
                    'warehouse_id' => '2',
                    'transaction_description' => '',
                    'credit' => '2.00',
                    'debit' => '0.00',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

            )
        );
        DB::table('brands')->insert(//Insert into brands table
            array(
                ['id' => '1',
                    'type' => 'Brand',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],
            )
        );
        DB::table('categories')->insert(//Insert into categories table
            array(
                ['id' => '1',
                    'parent_id' => '0',
                    'order' => '1',
                    'title' => 'Root',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '2',
                    'parent_id' => '1',
                    'order' => '2',
                    'title' => 'Main Category',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '3',
                    'parent_id' => '2',
                    'order' => '3',
                    'title' => 'Sub Category MC 1',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],

                ['id' => '4',
                    'parent_id' => '1',
                    'order' => '4',
                    'title' => 'Main Category 2',
                    'status' => 'ACTIVE',
                    'created_at' => date_create()],
            )
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
