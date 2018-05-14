<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class ProductTypes extends Model
{

    protected $fillable = ['type', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('product_types');

        parent::__construct($attributes);
    }

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'product_type_id', 'id');
    }
}
