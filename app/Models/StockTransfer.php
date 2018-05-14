<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class StockTransfer extends Model
{

    protected $fillable = ['from_warehouse_id','to_warehouse_id', 'product_id','qty', 'description', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('stock_transfer');

        parent::__construct($attributes);
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function warehouseFrom()
    {
        return $this->belongsTo('App\Models\Warehouse', 'from_warehouse_id', 'id');
    }

    public function warehouseTo()
    {
        return $this->belongsTo('App\Models\Warehouse', 'to_warehouse_id', 'id');
    }

}
