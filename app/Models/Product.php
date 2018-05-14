<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Product extends Model
{

    protected $fillable = ['product_name', 'sku','manage_stock_level', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('product');

        parent::__construct($attributes);
    }

    public function suppliers()
    {
        return $this->belongsTo('App\Models\Suppliers', 'supplier_id', 'id');
    }

    public function productTypes()
    {
        return $this->belongsTo('App\Models\ProductTypes', 'product_type_id', 'id');
    }

    public function brands()
    {
        return $this->belongsTo('App\Models\Brands', 'brand_id', 'id');
    }

    public function categories()
    {
        return $this->belongsTo('App\Models\Categories', 'category_id', 'id');
    }

    public function bincard()
    {
        return $this->belongsTo('App\Models\Bincard', 'product_id', 'id');
    }

    public function stock()
    {
        return $this->hasMany('App\Models\Stock', 'product_id', 'id');
    }

    public function grnitems()
    {
        return $this->hasMany('App\Models\GrnItems', 'product_id', 'id');
    }
}
