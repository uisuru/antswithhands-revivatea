<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Warehouse extends Model
{

    protected $fillable = ['name', 'address','city', 'phone_number', 'contact_person', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('warehouse');

        parent::__construct($attributes);
    }

    public function bincard()
    {
        return $this->belongsTo('App\Models\Bincard', 'warehouse_id', 'id');
    }

    public function stock()
    {
        return $this->belongsTo('App\Models\Stock', 'warehouse_id', 'id');
    }

    public function stockTransferFrom()
    {
        return $this->hasMany('App\Models\StockTransfer', 'from_warehouse_id', 'id');
    }

    public function stockTransferTo()
    {
        return $this->hasMany('App\Models\StockTransfer', 'to_warehouse_id', 'id');
    }
}
