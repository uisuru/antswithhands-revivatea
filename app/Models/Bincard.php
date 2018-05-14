<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Bincard extends Model
{

    protected $fillable = ['product_id', 'warehouse_id','transaction_description', 'credit', 'debit','status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('bincard');

        parent::__construct($attributes);
    }

    public function product()
    {
        return $this->hasMany('App\Models\Product', 'product_id', 'id');
    }

    public function warehouse()
    {
        return $this->hasMany('App\Models\Warehouse', 'warehouse_id', 'id');
    }
}
