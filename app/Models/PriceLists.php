<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class PriceLists extends Model
{

    protected $fillable = ['name', 'code', 'currency_id','rate', 'is_default', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('price_lists');

        parent::__construct($attributes);
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id', 'id');
    }

    public function company()
    {
        return $this->hasMany('App\Models\Company', 'default_price_list_id', 'id');
    }
}
