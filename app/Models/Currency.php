<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Currency extends Model
{

    protected $fillable = ['name', 'code', 'symbol','rate', 'is_default', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('currencies');

        parent::__construct($attributes);
    }

    public function priceLists()
    {
        return $this->hasMany('App\Models\PriceLists', 'currency_id', 'id');
    }
}
