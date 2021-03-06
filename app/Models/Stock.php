<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Stock extends Model
{

    protected $fillable = ['product_id', 'warehouse_id','mutual_balance', 'actual_balance', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('stock');

        parent::__construct($attributes);
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function warehouse()
    {
        return $this->hasMany('App\Models\Warehouse_id', 'warehouse_id', 'id');
    }
}
