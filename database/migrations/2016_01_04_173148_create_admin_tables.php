<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create(config('admin.database.users_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 190)->unique();
            $table->string('password', 60);
            $table->string('name', 255);
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();;
            $table->string('email', 255)->nullable();;
            $table->string('telephone', 255)->nullable();;
            $table->string('avatar')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('admin.database.roles_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50);
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('admin.database.permissions_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50);
            $table->string('http_method')->nullable();
            $table->text('http_path')->nullable();
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('admin.database.menu_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 50);
            $table->string('icon', 50);
            $table->string('uri', 50)->nullable();

            $table->timestamps();
        });

        Schema::connection($connection)->create(config('admin.database.role_users_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('user_id');
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('admin.database.role_permissions_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('admin.database.user_permissions_table'), function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('permission_id');
            $table->index(['user_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('admin.database.role_menu_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('menu_id');
            $table->index(['role_id', 'menu_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create(config('admin.database.operation_log_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('path');
            $table->string('method', 10);
            $table->string('ip', 15);
            $table->text('input');
            $table->index('user_id');
            $table->timestamps();
        });
        DB::table('admin_users')->insert(//Insert Admin info
            array(
                ['id' => '1',
                    'username' => 'admin',
                    'password' => bcrypt('admin'),
                    'name' => 'I.D. Udawattha',
                    'first_name' => 'Isuru',
                    'last_name' => 'Dilshan',
                    'email' => 'idudawattha@gmail.com',
                    'telephone' => '0716003239',
                    'avatar' => null,
                    'created_at' => date_create()],

                ['id' => '2',
                    'username' => 'Reviva.admin',
                    'password' => bcrypt('admin'),
                    'name' => 'Reviva Admin',
                    'first_name' => 'Reviva',
                    'last_name' => 'Admin',
                    'email' => 'admin@reviva.lk',
                    'telephone' => '0712345678',
                    'avatar' => null,
                    'created_at' => date_create()],

            )
        );
        DB::table('admin_menu')->insert(//Insert Admin Menu
            array(
                        ['id' => '1',
                            'parent_id' => '0',
                            'order' => '1',
                            'title' => 'Index',
                            'icon' => 'fa-bar-chart',
                            'uri' => '/',
                            'created_at' => date_create()],

                        ['id' => '2',
                            'parent_id' => '0',
                            'order' => '2',
                            'title' => 'Admin',
                            'icon' => 'fa-tasks',
                            'uri' => null,
                            'created_at' => date_create()],

                        ['id' => '3',
                            'parent_id' => '2',
                            'order' => '3',
                            'title' => 'Users',
                            'icon' => 'fa-users',
                            'uri' => 'auth/users',
                            'created_at' => date_create()],

                        ['id' => '4',
                            'parent_id' => '2',
                            'order' => '4',
                            'title' => 'Roles',
                            'icon' => 'fa-user',
                            'uri' => 'auth/roles',
                            'created_at' => date_create()],

                        ['id' => '5',
                            'parent_id' => '2',
                            'order' => '5',
                            'title' => 'Permission',
                            'icon' => 'fa-ban',
                            'uri' => 'auth/permissions',
                            'created_at' => date_create()],

                        ['id' => '6',
                            'parent_id' => '2',
                            'order' => '6',
                            'title' => 'Menu',
                            'icon' => 'fa-bars',
                            'uri' => 'auth/menu',
                            'created_at' => date_create()],

                        ['id' => '7',
                            'parent_id' => '2',
                            'order' => '7',
                            'title' => 'Operation log',
                            'icon' => 'fa-history',
                            'uri' => 'auth/logs',
                            'created_at' => date_create()],

                        ['id' => '8',
                            'parent_id' => '0',
                            'order' => '8',
                            'title' => 'System',
                            'icon' => 'fa-toggle-on',
                            'uri' => null,
                            'created_at' => date_create()],

                        ['id' => '9',
                            'parent_id' => '15',
                            'order' => '10',
                            'title' => 'Currency',
                            'icon' => 'fa-dollar',
                            'uri' => 'auth/currency',
                            'created_at' => date_create()],

                        ['id' => '10',
                            'parent_id' => '15',
                            'order' => '11',
                            'title' => 'Price List',
                            'icon' => 'fa-list',
                            'uri' => 'auth/pricelists',
                            'created_at' => date_create()],

                        ['id' => '11',
                            'parent_id' => '15',
                            'order' => '12',
                            'title' => 'Tax',
                            'icon' => 'fa-percent',
                            'uri' => 'auth/tax',
                            'created_at' => date_create()],

                        ['id' => '12',
                            'parent_id' => '15',
                            'order' => '13',
                            'title' => 'Payment Terms',
                            'icon' => 'fa-align-justify',
                            'uri' => 'auth/paymentterms',
                            'created_at' => date_create()],

                        ['id' => '13',
                            'parent_id' => '15',
                            'order' => '14',
                            'title' => 'Payment Methods',
                            'icon' => 'fa-bars',
                            'uri' => 'auth/paymentmethods',
                            'created_at' => date_create()],

                        ['id' => '14',
                            'parent_id' => '15',
                            'order' => '15',
                            'title' => 'Stock Adj. Reasons',
                            'icon' => 'fa-bars',
                            'uri' => 'auth/stockadjustmentreasons',
                            'created_at' => date_create()],

                        ['id' => '15',
                            'parent_id' => '8',
                            'order' => '9',
                            'title' => 'Configuration',
                            'icon' => 'fa-bars',
                            'uri' => null,
                            'created_at' => date_create()],

                        ['id' => '16',
                            'parent_id' => '15',
                            'order' => '0',
                            'title' => 'Customer',
                            'icon' => 'fa-home',
                            'uri' => 'auth/company',
                            'created_at' => date_create()],

                        ['id' => '17',
                            'parent_id' => '15',
                            'order' => '0',
                            'title' => 'Warehouse',
                            'icon' => 'fa-stop-circle',
                            'uri' => 'auth/warehouse',
                            'created_at' => date_create()],

                        ['id' => '18',
                            'parent_id' => '15',
                            'order' => '0',
                            'title' => 'Suppliers',
                            'icon' => 'fa-bars',
                            'uri' => 'auth/suppliers',
                            'created_at' => date_create()],

                        ['id' => '19',
                            'parent_id' => '8',
                            'order' => '0',
                            'title' => 'Product Configuration',
                            'icon' => 'fa-bars',
                            'uri' => null,
                            'created_at' => date_create()],

                        ['id' => '20',
                            'parent_id' => '19',
                            'order' => '0',
                            'title' => 'Product Types',
                            'icon' => 'fa-bars',
                            'uri' => 'auth/producttypes',
                            'created_at' => date_create()],

                        ['id' => '21',
                            'parent_id' => '19',
                            'order' => '0',
                            'title' => 'Brands',
                            'icon' => 'fa-bars',
                            'uri' => 'auth/brands',
                            'created_at' => date_create()],

                        ['id' => '22',
                            'parent_id' => '19',
                            'order' => '0',
                            'title' => 'Category',
                            'icon' => 'fa-bars',
                            'uri' => 'auth/category',
                            'created_at' => date_create()],

                        ['id' => '23',
                            'parent_id' => '19',
                            'order' => '0',
                            'title' => 'Products',
                            'icon' => 'fa-bars',
                            'uri' => 'auth/products',
                            'created_at' => date_create()],

                        ['id' => '24',
                            'parent_id' => '0',
                            'order' => '0',
                            'title' => 'Inventory',
                            'icon' => 'fa-cubes',
                            'uri' => null,
                            'created_at' => date_create()],

                        ['id' => '25',
                            'parent_id' => '24',
                            'order' => '0',
                            'title' => 'Manage GRN',
                            'icon' => 'fa-bars',
                            'uri' => 'auth/grn',
                            'created_at' => date_create()],

                        ['id' => '26',
                            'parent_id' => '24',
                            'order' => '0',
                            'title' => 'Stock Transfer',
                            'icon' => 'fa-bars',
                            'uri' => 'auth/stock_transfer',
                            'created_at' => date_create()],

                        ['id' => '27',
                            'parent_id' => '0',
                            'order' => '0',
                            'title' => 'Reports',
                            'icon' => 'fa-bars',
                            'uri' => null,
                            'created_at' => date_create()],

                        ['id' => '28',
                            'parent_id' => '27',
                            'order' => '0',
                            'title' => 'Bin Card',
                            'icon' => 'fa-bars',
                            'uri' => 'auth/bincard',
                            'created_at' => date_create()],

                        ['id' => '29',
                            'parent_id' => '27',
                            'order' => '0',
                            'title' => 'Stock',
                            'icon' => 'fa-bars',
                            'uri' => 'auth/stock',
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
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->dropIfExists(config('admin.database.users_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.roles_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.permissions_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.menu_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.user_permissions_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.role_users_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.role_permissions_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.role_menu_table'));
        Schema::connection($connection)->dropIfExists(config('admin.database.operation_log_table'));
    }
}
